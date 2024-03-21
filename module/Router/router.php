<?php
    namespace Aleum\Router;
    use Aleum\Utils\Error;
    use Exception;

    include_once __DIR__."/../Utils/Error.php";

    class Router {
        private $route;
        private $routes;

        private static $main_route = null;

        public function __construct(bool $main = false) {
            $this -> route = array(
                "GET" => array(),
                "POST" => array(),
                "PUT" => array(),
                "DELETE" => array(),
                "ANY" => array()
            );
            $this -> routes = array();

            if ($main === true || self::$main_route === null)
                self::$main_route = $this;
        }

        public static function get_main_route(): Router {
            return self::$main_route;
        }
        
        public static function get_router_path(string $file): string {
            return ALEUM_ROUTER_PATH.$file.".php";
        }

        public static function load_route(string $file): void {
            include ALEUM_ROUTER_PATH.$file;
        }

        public static function exec_main(string $path): void {
            self::$main_route -> exec($path);
        }

        private function __add(string $path, string $method, callable $callback): void {
            if (isset($this -> route[$method][$path])) {
                syslog(LOG_ERR, "Route already exists");
                throw new Exception("Route already exists");
            }
            $this -> route[$method][$path] = $callback;
        }

        public function get(string $path, callable $callback): void {
            $this -> __add($path, "GET", $callback);
        }

        public function post(string $path, callable $callback): void {
            $this -> __add($path, "POST", $callback);
        }

        public function put(string $path, callable $callback): void {
            $this -> __add($path, "PUT", $callback);
        }

        public function delete(string $path, callable $callback): void {
            $this -> __add($path, "DELETE", $callback);
        }

        public function any(string $path, callable $callback): void {
            $this -> __add($path, "ANY", $callback);
        }

        public function add_route(
            string $path, self $route,
            bool $duplicate = false, // If true, will overwrite existing route
            bool $duplicate_log = true // If true, will log if route already exists
        ): void {
            if (isset($this -> routes[$path]) && !$duplicate) {
                if ($duplicate_log)
                    syslog(LOG_ERR, "Route already exists");
                throw new Exception("Route already exists");
            }

            $this -> routes[$path] = $route;
        }

        public function exec(string $path): void {
            if ($path === "")
                $path = "/";

            $root_path = "/".(explode("/", $path)[1] ?? '');
            if (isset($this -> routes[$root_path])) {
                $path = "/".substr($path, strlen($root_path) + 1);
                $this -> routes[$root_path] -> exec($path);
                return;
            }

            $method = $_SERVER['REQUEST_METHOD'];
            if (isset($this -> route[$method][$path])) {
                $this -> route[$method][$path]();
                return;
            }

            if (isset($this -> route["ANY"][$path])) {
                $this -> route["ANY"][$path]();
                return;
            }

            if (isset($this -> route[$method]["*"])) {
                $this -> route[$method]["*"]($path);
                return;
            }

            if (isset($this -> route["ANY"]["*"])) {
                $this -> route["ANY"]["*"]($path);
                return;
            }

            Error\abort(404);
        }
    }
?>