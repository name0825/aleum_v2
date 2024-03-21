<?php
    namespace Aleum\Utils;
    use Aleum;

    include_once __DIR__ ."/../Encrypt/utils.php";

    class FingerPrint {
        private static $accept; // VARIABLE, OPTIONAL
        private static $acceptLanguage; // CONSTANT
        private static $acceptEncoding; // CONSTANT
        private static $protocol; // CONSTANT
        private static $ip; // VARIABLE, OPTIONAL
        private static $ua; // VARIABLE
        private static $uaPlatform; // CONSTANT
        private static $browser; // CONSTANT
        private static $browserVersion; // VARIABLE
        private static $doNotTrack; // VARIABLE
        private static $gpc; // VARIABLE (Global Privacy Control)

        public static function init() {
            $headers = apache_request_headers();

            self::$accept = $_SERVER['HTTP_ACCEPT'] ?? 'none';
            self::$acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'none';
            self::$acceptEncoding = $headers['Accept-Encoding'] ?? $_SERVER['HTTP_ACCEPT_ENCODING'] ?? 'none';
            self::$protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'none';
            self::$ip = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'none';
            self::$ua = $_SERVER['HTTP_USER_AGENT'] ?? 'none';
            self::$uaPlatform = self::getPlatform();
            $browserInfo = self::getBrowserInfo();
            self::$browser = $browserInfo['name'];
            self::$browserVersion = $browserInfo['version'];

            self::$doNotTrack = $headers['Dnt'] ?? $_SERVER["HTTP_DNT"] ?? 'none';
            self::$doNotTrack = $headers['Sec-Gpc'] ?? 'none';
        }

        public static function getAccept(): string {
            return self::$accept;
        }

        public static function getUaPlatform(): string {
            return self::$uaPlatform;
        }

        public static function getBrowser(): string {
            return self::$browser;
        }

        public static function getBrowserVersion(): string {
            return self::$browserVersion;
        }

        public static function getIp(): string {
            return self::$ip;
        }

        public static function getFingerPrint(
            bool $encodeHex = false,
            bool $include_accept = false,
            bool $include_ip = false,
            bool $include_variable = false,
            bool $raw_output = false,
            string $algorithm = DEFAULT_HASH_ALGORITHM
        ): string {
            $data = '';
            $data .= self::$acceptLanguage;
            $data .= self::$acceptEncoding;
            $data .= self::$protocol;
            $data .= self::$uaPlatform;
            $data .= self::$browser;
            $data .= self::$doNotTrack;
            $data .= self::$gpc;

            if ($include_variable) {
                $data .= self::$ua;
                $data .= self::$browserVersion;
            }
            if ($include_accept)
                $data .= self::$accept;
            if ($include_ip)
                $data .= self::$ip;
            if ($encodeHex)
                $data = Aleum\Encryption\string2hex($data);

            return hash($algorithm, $data, $raw_output);
        }

        private static function getPlatform(): string {
            $platform = 'Unknown';
            if (preg_match('/Windows/i', self::$ua))
                $platform = 'Windows';
            else if (preg_match('/Android/i', self::$ua))
                $platform = 'Android';
            else if (preg_match('/iPhone/i', self::$ua))
                $platform = 'iPhone';
            else if (preg_match('/iPad/i', self::$ua))
                $platform = 'iPad';
            else if (preg_match('/Macintosh/i', self::$ua))
                $platform = 'Macintosh';
            else if (preg_match('/Linux/i', self::$ua))
                $platform = 'Linux';
            return $platform;
        }

        private static function getBrowserInfo(): array {
            $browser = array('name' => 'Unknown', 'version' => 'Unknown');
            if (preg_match('/MSIE/i', self::$ua) && !preg_match('/Opera/i', self::$ua)) {
                $browser['name'] = 'Internet Explorer';
                $browser['version'] = preg_match('/MSIE\/([^ ]+)/i', self::$ua, $matches) ? $matches[1] : 'Unknown';
            } else if (preg_match('/Firefox/i', self::$ua)) {
                $browser['name'] = 'Firefox';
                $browser['version'] = preg_match('/Firefox\/([^ ]+)/i', self::$ua, $matches) ? $matches[1] : 'Unknown';
            } else if (preg_match('/OPR/i', self::$ua)) {
                $browser['name'] = 'Opera';
                $browser['version'] = preg_match('/OPR\/([^ ]+)/i', self::$ua, $matches) ? $matches[1] : 'Unknown';
            } else if(preg_match('/SamsungBrowser/i', self::$ua)) {
                $browser['name'] = 'Samsung Internet';
                $browser['version'] = preg_match('/SamsungBrowser\/([^ ]+)/i', self::$ua, $matches) ? $matches[1] : 'Unknown';
            } else if (preg_match('/Edge?/i', self::$ua)) {
                $browser['name'] = 'Microsoft Edge';
                $browser['version'] = preg_match('/Edge?\/([^ ]+)/i', self::$ua, $matches) ? $matches[1] : 'Unknown';
            } else if (preg_match('/KAKAOTALK/i', self::$ua)) {
                $browser['name'] = 'KakaoTalk';
                $browser['version'] = preg_match('/KAKAOTALK ([^ ]+)/i', self::$ua, $matches) ? $matches[1] : 'Unknown';
            } else if (preg_match('/Naver/i', self::$ua)) {
                $browser['name'] = 'Naver';
                $browser['version'] = preg_match('/Naver\(.*?(\d+\.\d+\.\d+)[;\)]/i', self::$ua, $matches) ? $matches[1] : 'Unknown';
            } else if (preg_match('/Instagram/i', self::$ua)) {
                $browser['name'] = 'Instagram';
                $browser['version'] = preg_match('/Instagram (\d+\.\d+\.\d+)/i', self::$ua, $matches) ? $matches[1] : 'Unknown';
            } else if (preg_match('/Chrome/i', self::$ua)) {
                $browser['name'] = 'Google Chrome';
                $browser['version'] = preg_match('/Chrome\/([^ ]+)/i', self::$ua, $matches) ? $matches[1] : 'Unknown';
            } else if (preg_match('/CriOS/i', self::$ua)) {
                $browser['name'] = 'Google Chrome';
                $browser['version'] = preg_match('/CriOS\/([^ ]+)/i', self::$ua, $matches) ? $matches[1] : 'Unknown';
            } else if (preg_match('/Safari/i', self::$ua)) {
                $browser['name'] = 'Apple Safari';
                $browser['version'] = preg_match('/Safari\/([^ ]+)/i', self::$ua, $matches) ? $matches[1] : 'Unknown';
            } else if (preg_match('/Trident/i', self::$ua)) {
                $browser['name'] = 'Internet Explorer';
                $browser['version'] = preg_match('/rv:([^ ]+)/i', self::$ua, $matches) ? $matches[1] : 'Unknown';
            }
            return $browser;
        }
    }
?>