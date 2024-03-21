<?php
    use Aleum\Utils\Load;
    use Aleum\Router\Router;

    $route = new Router($main = true);

    $route -> any("/", function() {
        echo "Hello, World!";
    });
?>