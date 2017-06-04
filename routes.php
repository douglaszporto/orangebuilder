<?php

require_once dirname(__FILE__) . "/autoloader.php";

use \OrangeBuild\URL as URL;

$url = new URL($_GET["route"]);

$url->mapping("^admin/([a-zA-Z0-9\-]+)$", "AdminCtrl.Listing");
/*$url->mapping("^admin/formulario/([a-zA-Z0-9\-]+)$", "AdminCtrl.FormAdd");
$url->mapping("^admin/editar/([a-zA-Z0-9\-]+)/([0-9]+)$", "AdminCtrl.FormEdit");
$url->mapping("^admin/novo/([a-zA-Z0-9\-]+)$", "AdminCtrl.Add");
$url->mapping("^admin/salvar/([a-zA-Z0-9\-]+)/([0-9]+)$", "AdminCtrl.Edit");
$url->mapping("^admin/remover/([a-zA-Z0-9\-]+)$", "AdminCtrl.Delete");*/
$url->mapping("^admin/([a-zA-Z0-9\-]+)/form(?:/?)([0-9]*?)$", "AdminCtrl.Forms");
$url->mapping("^admin/([a-zA-Z0-9\-]+)/([a-zA-Z0-9\-]+)$", "AdminCtrl.Operations");

$url->forward();

?>