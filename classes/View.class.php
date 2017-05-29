<?php

namespace OrangeBuild;

use \Exception as Exception;
use \Smarty as Smarty;

define('SMARTY_DIR', dirname(__FILE__).'/libs/smarty/');

if(!defined("VIEW_DIR"))
    define('VIEW_DIR', dirname(__FILE__).'/../views/');

require_once SMARTY_DIR . '/Smarty.class.php';

class View {

    static  $instance  = NULL;
	private $smarty    = NULL;
	private $debugging = false;


	public function __construct(){
		$this->smarty = new Smarty();

		$this->smarty->setTemplateDir(VIEW_DIR);
		$this->smarty->setCompileDir(SMARTY_DIR.'/compiled/');
		$this->smarty->setConfigDir(SMARTY_DIR.'/configs/');
		$this->smarty->setCacheDir(SMARTY_DIR.'/cache/');

		$this->smarty->left_delimiter = '{{';
		$this->smarty->right_delimiter = '}}';
	}

    static public function getInstance(){
        if (self::$instance == NULL)
			self::$instance = new View();
		return self::$instance;
    }


	public function debug(){
		$this->debugging = true;
	}


	public function define($var,$value){
		$this->smarty->assign($var,$value);
	}


	public function renderFile($file){
		if($this->debugging)
			$smarty->debugging = true;

		$this->smarty->assign('version',SHOP_VERSION);
		$this->smarty->assign('domain',SHOP_DOMAIN);
		$this->smarty->assign('request_path',trim($_SERVER["REQUEST_URI"],"/"));
		$this->smarty->assign('siteProduction',SHOP_PRODUCTION);

		$this->smarty->loadFilter('output','trimwhitespace');

		if(file_exists(VIEW_DIR.$file))
			$this->smarty->display($file);
		else
			$this->smarty->display('404.tpl');
	}


	public function ReturnFile($file){
		return $this->smarty->fetch($file);
	}

    static public function RenderRequest($file, $context = array()){
        $view = View::getInstance();
        $view->Render($file, 0, $context);
    }

    static public function RenderAjax($file, $context = array()){
        $view = View::getInstance();
        $view->Render($file, 1, $context);
    }

    static public function RenderDefault($file, $context = array()){
        $view = View::getInstance();
        $view->Render($file, 2, $context);
    }


	public function Render($file, $mode = 0, $context=array()){

		if($mode === 1 && (!isset($_SERVER["HTTP_X_REQUESTED_WITH"]) || $_SERVER["HTTP_X_REQUESTED_WITH"] != 'XMLHttpRequest'))
			return;

		if($mode === 2 && (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] == 'XMLHttpRequest'))
			return;

		set_error_handler('OrangeBuild\template_error_handler');

		try{
			foreach ($context as $k => $v) {
				$this->define($k,$v);
			}

			//$i18n = new I18n(SITE_LANG);
			//$smarty->Define('i18n',$i18n->getStrings());

			$this->RenderFile($file);
		} catch(\ErrorException $e) {
			echo "Ocorreu algum erro no template {$file}:<br/>" , $e->getMessage(), "<br/><br/>";
			foreach ($context as $k => $v)
				echo $k," => ",$v,"<br/>";
		}
	}
	

}

function template_error_handler($severity, $message, $filename, $lineno) {
	if (error_reporting() == 0)
		return;

	if (error_reporting() & $severity)
		throw new \ErrorException($message, 0, $severity, $filename, $lineno);
}

?>