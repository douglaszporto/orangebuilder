<?php

require_once dirname(__FILE__) . "/autoloader.php";

use \OrangeBuild\URL as URL;

$url = new URL($_GET["route"]);

$url->mapping("^admin/([a-zA-Z0-9\-]+)$", "AdminCtrl.Listing");
$url->mapping("^admin/novo/([a-zA-Z0-9\-]+)$", "AdminCtrl.Add");
$url->mapping("^admin/formulario/([a-zA-Z0-9\-]+)$", "AdminCtrl.FormAdd");
$url->mapping("^admin/salvar/([a-zA-Z0-9\-]+)/([0-9]+)$", "AdminCtrl.Edit");
$url->mapping("^admin/editar/([a-zA-Z0-9\-]+)/([0-9]+)$", "AdminCtrl.FormEdit");

$url->forward();

?>