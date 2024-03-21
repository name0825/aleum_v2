<?php
    namespace Aleum\Utils;

    class Load {
        private static $required = array();

        private static function is_required($path): bool {
            return isset(self::$required[$path]);
        }
    
        public static function require($path): array {
            include $path;
            return get_defined_vars();
        }

        public static function require_once($path): array {
            if (!self::is_required($path))
                self::$required[$path] = self::require($path);
            return self::$required[$path];
        }

        public static function load_page(string $file, array $bind = array()): void {
            extract($bind);
            include __DIR__."/../../static/".$file;
        }
    }
?>