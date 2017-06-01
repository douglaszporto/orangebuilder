<?php

require_once dirname(__FILE__) . "/autoloader.php";

use \OrangeBuild\URL as URL;

$url = new URL($_GET["route"]);

$url->mapping("^admin/([a-zA-Z0-9\-]+)$", "AdminCtrl.Listing");
$url->mapping("^admin/novo/([a-zA-Z0-9\-]+)$", "AdminCtrl.Add");
$url->mapping("^admin/form/([a-zA-Z0-9\-]+)$", "AdminCtrl.FormAdd");

$url->forward();

?>