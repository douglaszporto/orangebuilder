<?php

namespace OrangeBuild\Controllers;

use \Exception as Exception;
use \OrangeBuild\View as View;

class ErrorHandlerCtrl{

	public function NotFound(){
		View::RenderRequest("404.tpl");
	}
}

?>