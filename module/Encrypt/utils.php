<?php
    namespace Aleum\Encryption;
    use GMP;

    function gmp_min(GMP $a, GMP $b): GMP {
        if (gmp_cmp($a, $b) < 0)
            return $a;
        else
            return $b;
    }

    function string2hex(string $data): string {
        $hex = '';
        for ($i=0; $i<strlen($data); $i++) {
            $ord = ord($data[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0'.$hexCode, -2);
        }
        return strToUpper($hex);
    }

    function hex2string(string $data): string {
        $str = '';
        for ($i=0; $i<strlen($data); $i+=2) {
            $str .= chr(hexdec(substr($data, $i, 2)));
        }
        return $str;
    }
?>