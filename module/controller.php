<?php
    // include Encrypt module
    include_once __DIR__."/Encrypt/Miku.php";
    include_once __DIR__."/Encrypt/Hash.php";

    // include Utils module
    include_once __DIR__."/Utils/DB.php";
    include_once __DIR__."/Utils/Load.php";
    include_once __DIR__."/Utils/Mail.php";
    include_once __DIR__."/Utils/Error.php";
    include_once __DIR__."/Utils/FingerPrint.php";

    // include Router module
    define("ALEUM_ROUTER_PATH", realpath(__DIR__."/../route")."/");
    include_once __DIR__."/Router/router.php";

    // Process the path
    $path = $_SERVER["REQUEST_URI"];
    $path = explode("?", $path)[0];
    $path = explode("#", $path)[0];

    // Load Main Router
    Aleum\Router\Router::load_route("route.php");
    try {
        Aleum\Router\Router::get_main_route() -> add_route(
            "/error", Aleum\Utils\Load::require(Aleum\Router\Router::get_router_path("error"))['route'],
            $duplicate_log = false
        );
    } catch (Exception $e) {
    }
?>