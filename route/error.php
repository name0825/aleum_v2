<?php
    use Aleum\Utils\Error;
    use Aleum\Router\Router;

    $route = new Router();
    $route -> any("*", function(mixed $code = null) {
        if ($code === null || $code === '' || $code === '/')
            $code = 'R500';
        Error\abort($code);
    });
?>