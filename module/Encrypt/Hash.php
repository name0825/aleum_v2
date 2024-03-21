<?php
    namespace Aleum\Encryption;
    use Exception;

    include_once __DIR__."/Hashids.php";

    class Hash {
        private $hashids;

        public function __construct() {
            $this -> hashids = array();
        }

        public function create_hashids(string $name, string $salt, int $min_length, string $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890") {
            $this -> hashids[$name] = new Hashids($salt, $min_length, $alphabet);
        }

        public function encode(string $name, ...$numbers) : string {
            if (!isset($this -> hashids[$name]))
                new Exception("Invalid name");
            return $this -> hashids[$name] -> encode($numbers);
        }

        public function encodeHex(string $name, string $hex) : string {
            if (!isset($this -> hashids[$name]))
                new Exception("Invalid name");
            return $this -> hashids[$name] -> encodeHex($hex);
        }

        public function decode(string $name, string $hash, int $get = 0) : array {
            if (!isset($this -> hashids[$name]))
                new Exception("Invalid name");
            $decode = $this -> hashids[$name] -> decode($hash);
            if (count($decode) == 0)
                return array();
            return $decode;
        }

        public function decodeHex(string $name, string $hash, int $get = 0) : string {
            if (!isset($this -> hashids[$name]))
                new Exception("Invalid name");
            $decode = $this -> hashids[$name] -> decodeHex($hash);
            return $decode;
        }
    }
?>