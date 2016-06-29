<?php
class UbendoModuleTest extends Module 
{
	public function __construct()
	{
		$this->author = 'ubendo';
		$this->displayName = 'Modulo Base';
		$this->description = 'Estructura basica para el desarrollo de los modulos';	
		$this->name = 'ubendomoduletest';	
		$this->tab = 'front_office_features';
		$this->version = '0.1';
		$this->bootstrap = true;
		$this->ps_versions_compliancy = array('min' => '1.6','max' => '1.6.1.5');
		//$this->dependencies = array('paypal', 'blockcart');
		parent::__construct();
	}
	public function install()
	{
		// Call install parent method
		if( !parent::install() )
			return false;

		// Execute module install SQL statements
		//$sql_file = dirname(__FILE__).'/install/install.sql';
		//if( !$this->loadSQLFile($sql_file) )
		//	return false;

		// Register hooks
		if (!$this->registerHook('displayBackOfficeHeader'))
			return false;

		// All went well!
		return true;				
	}
	public function uninstall()
	{
		// Call uninstall parent method
		if( !parent::uninstall() )
			return false;

		// Execute module uninstall statements
		//$sql_file = dirname(__FILE__).'/install/uninstall.sql';
		//if( !$this->loadSQLFile($sql_file) )
		//	return false;

		// Delete configuration values
		
		// All went well!
		return true;
	}
	public function loadSQLFile($sql_file)
	{
		// Get install SQL file content
		$sql_content = file_get_contents($sql_file);

		// Replace prefix and store SQL command in array
		$sql_content = str_replace('PREFIX_', _DB_PREFIX_, $sql_content);
		$sql_requests = preg_split("/;\s*[\r\n]+/", $sql_content);

		// Execute each SQL statement
		$result = true;
		foreach($sql_requests as $request)
			if (!empty($request))
				$result &= Db::getInstance()->execute(trim($request));
		
		// Return result
		return $result;		
	}
	public function onClickOption($type, $href = false)
	{
		$confirm_reset = $this->l('Reseteando este modulo borraras todo los datos almacenados en la base de datos, estas seguro de resetearlo?');
		$reset_callback = "return ubendomoduletest_reset('".addslashes($confirm_reset)."');";
		
		$matchType = array(
			'reset' => $reset_callback,
			'delete' => "return confirm('Estas seguro de eliminar este modulo?')",
		);

		if (isset($matchType[$type]))
			return $matchType[$type];
		
		return '';
	}
	/* El siguiente metodo es un ejemplo para mejorar las url amigables de los controllers del modulo
	 *
	 *
	public function hookModuleRoutes()
	{
		return array(
			'module-ubendomodultest-comments' => array(
				'controller' => 'comments',
				'rule' => 'product-comments{/:module_action}{/:id_product}/page{/:page}',
				'keywords' => array(
					'id_product' => array('regexp' => '[\d]+', 'param' => 'id_product'),
					'page' => array('regexp' => '[\d]+', 'param' => 'page'),
					'module_action' => array('regexp' => '[\w]+', 'param' => 'module_action'),
					'product_rewrite' => array('regexp' => '[\w-_]+', 'param' => 'product_rewrite')
				),
				'params' => array('fc' => 'module', 'module' => 'ubendomodultest', 'controller' => 'comments')
			)
		);
	}*/			
	public function getContent()
	{

		return $this->display(__FILE__, 'getContent.tpl');
	}
	public function hookDisplayBackOfficeHeader($params)
	{
		// If we are not on section modules, we do not add JS file
		if (Tools::getValue('controller') != 'AdminModules')
			return '';

		// Assign module ubendomodultest base dir
		$this->context->smarty->assign('pc_base_dir', __PS_BASE_URI__.'modules/'.$this->name.'/');
		// Display template
		return $this->display(__FILE__, 'displayBackOfficeHeader.tpl');
	}		
}