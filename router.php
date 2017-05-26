<?php

// php -S localhost:8000 router.php

if (preg_match('/(^statics|sitemap.xml$|robots.txt$)/', $_SERVER["REQUEST_URI"]))
    return false;

$_GET["route"] = $_SERVER["REQUEST_URI"];
include dirname(__FILE__) . "/routes.php";

?>