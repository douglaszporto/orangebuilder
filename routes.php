<?php

require_once dirname(__FILE__) . "/autoloader.php";

use \OrangeBuild\URL as URL;

$url = new URL($_GET["route"]);

$url->mapping("^admin/([a-zA-Z0-9\-]+)$", "AdminCtrl.Listing");
$url->mapping("^admin/([a-zA-Z0-9\-]+)/form(?:/?)([0-9]*?)$", "AdminCtrl.Forms");
$url->mapping("^admin/([a-zA-Z0-9\-]+)/([a-zA-Z0-9\-]+)$", "AdminCtrl.Operations");

$url->forward();

?>