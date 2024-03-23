<?php
    namespace Aleum\Encryption;
    use Exception;

    include_once __DIR__."/Hashids.php";

    class Hash {
        private static $hashids = array();

        public static function create_hashids(string $name, string $salt, int $min_length, string $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890") {
            self::$hashids[$name] = new Hashids($salt, $min_length, $alphabet);
        }

        public static function encode(string $name, ...$numbers) : string {
            if (!isset(self::$hashids[$name]))
                new Exception("Invalid name");
            return self::$hashids[$name] -> encode($numbers);
        }

        public static function encodeHex(string $name, string $hex) : string {
            if (!isset(self::$hashids[$name]))
                new Exception("Invalid name");
            return self::$hashids[$name] -> encodeHex($hex);
        }

        public static function decode(string $name, string $hash, int $get = 0) : array {
            if (!isset(self::$hashids[$name]))
                new Exception("Invalid name");
            $decode = self::$hashids[$name] -> decode($hash);
            if (count($decode) == 0)
                return array();
            return $decode;
        }

        public static function decodeHex(string $name, string $hash, int $get = 0) : string {
            if (!isset(self::$hashids[$name]))
                new Exception("Invalid name");
            $decode = self::$hashids[$name] -> decodeHex($hash);
            return $decode;
        }
    }
?>