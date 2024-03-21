<?php
    namespace Aleum\Encryption;
    use GMP;

    include_once __DIR__ . '/utils.php';

    class MIKU {
        private $n;
        private $pb_key;
        private $pv_key;
        private $key_size;

        public function __construct(int $key_size = 2048) {
            $this->key_size = $key_size;
        }

        public function key_generator(): bool {
            // 큰 소수 두 개를 무작위로 생성
            $p = $this->get_large_prime();
            $q = $this->get_large_prime();
            // n = p * q
            $this->n = gmp_mul($p, $q);
            // phi(n) = (p-1) * (q-1)
            $phi_n = gmp_mul(gmp_sub($p, 1), gmp_sub($q, 1));

            // e (공개키)를 무작위로 선택하기 위해 랜덤한 소수를 생성
            $e = $this->get_random_prime(gmp_min(gmp_init("50000000"), $phi_n));

            // d (개인키)
            $this->pv_key = gmp_invert($e, $phi_n);
            // 공개키
            $this->pb_key = $e;
            return TRUE;
        }

        public function get_pb_key(int $base = 16): string {
            return gmp_strval($this->pb_key, $base);
        }

        public function get_pv_key(int $base = 16): string {
            return gmp_strval($this->pv_key, $base);
        }

        public function get_n(int $base = 16): string {
            return gmp_strval($this->n, $base);
        }

        public function set_pb_key(string $pb_key, int $base = 16): bool {
            $this->pb_key = gmp_init($pb_key, $base);
            return TRUE;
        }

        public function set_pv_key(string $pv_key, int $base = 16): bool {
            $this->pv_key = gmp_init($pv_key, $base);
            return TRUE;
        }

        public function set_n(string $n, int $base = 16): bool {
            $this->n = gmp_init($n, $base);
            return TRUE;
        }

        public function encrypt(string $str, int $base = 16): array {
            if ($this->pb_key == null)
                return FALSE;

            $buffer = array();
            $len = strlen($str);

            for ($i = 0; $i < $len; $i++) {
                $encrypted = gmp_powm(gmp_init(ord($str[$i]), 10) + 5, $this->pb_key, $this->n);
                array_push($buffer, gmp_strval($encrypted, $base));
            }

            return $buffer;
        }

        private function decrypt_legacy(array $encrypted, int $base = 16): string {
            $decrypted = "";
            foreach ($encrypted as $char) {
                $decrypt = gmp_strval(gmp_powm(gmp_init($char, $base), $this->pv_key, $this->n) - gmp_init(5), 16);
                $cnt = strlen($decrypt);
                for ($i = 0; $i < $cnt; $i += 2)
                    $decrypted .= chr(hexdec(substr($decrypt, $i, 2)));
            }
            return base64_decode($decrypted);
        }

        public function decrypt(array $encrypted, int $base = 16): string {
            if ($this->pv_key == null)
                return FALSE;

            $decrypted = $this -> decrypt_legacy($encrypted, $base);
            //die($decrypted);
            $md5_checksum = substr($decrypted, -32);
            $md5_hash = md5(substr($decrypted, 0, 1), false);

            if ($md5_hash == $md5_checksum)
                return substr($decrypted, 0, -32);
            if (substr($decrypted, -5) == 'anime')
                return substr($decrypted, 0, -5);
            throw new \Exception("Integrity check failed");
        }

        // 무작위로 큰 소수 생성
        private function get_large_prime(): GMP {
            $random_number = gmp_random_bits($this->key_size);
            $prime = gmp_nextprime($random_number);
            return $prime;
        }

        // 무작위로 소수 생성 (1과 $max 사이의 소수)
        private function get_random_prime(GMP $max): GMP {
            do {
                $random_number = gmp_random_range(gmp_init(2), $max);
                $random_number = gmp_nextprime($random_number);
            } while (gmp_prob_prime($random_number) != 2);

            return $random_number;
        }
    }
?>
