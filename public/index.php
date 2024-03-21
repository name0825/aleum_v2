<?php
    ob_start();
    try {
        include __DIR__."/../module/controller.php";
        Aleum\Router\Router::exec_main($path);
        if (error_get_last() !== NULL)
            Aleum\Utils\Error\abort(500);
    } catch (Exception $e) {
        Aleum\Utils\Error\abort(500);
    }
    ob_end_flush();
?>