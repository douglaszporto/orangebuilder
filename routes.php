<?php

require_once dirname(__FILE__) . "/autoloader.php";

use \OrangeBuild\URL as URL;

$url = new URL($_GET["route"]);

$url->mapping("^admin/(.*?)$", "AdminCtrl.Listing");

$url->forward();

?>