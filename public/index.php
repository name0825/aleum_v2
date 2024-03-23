<?php
    ob_start();
    try {
        include __DIR__."/../module/controller.php";
        Aleum\Router\Router::exec_main($path);
        if (error_get_last() !== NULL) {
            ob_clean();
            error_log(error_get_last()["message"]);
            Aleum\Utils\Error\abort(500);
        }
    } catch (Exception $e) {
        ob_clean();
        error_log($e->getMessage());
        Aleum\Utils\Error\abort(500);
    }
    ob_end_flush();
?>