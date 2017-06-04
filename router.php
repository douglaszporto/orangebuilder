<?php

// php -S localhost:8000 router.php

if (preg_match('~(^[\\\/]{0,1}statics|sitemap.xml|robots.txt$)~i', $_SERVER["REQUEST_URI"]))
    return false;

if (isset($_SERVER['QUERY_STRING'])) {
    $_GET = [];
    parse_str($_SERVER['QUERY_STRING'], $_GET);
}

$url = explode("?", $_SERVER["REQUEST_URI"]);
$_GET["route"] = $url[0];
include dirname(__FILE__) . "/routes.php";

?>