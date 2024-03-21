<?php
    namespace Aleum\Utils;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    include_once __DIR__ . '/PHPMailer/src/Exception.php';
    include_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
    include_once __DIR__ . '/PHPMailer/src/SMTP.php';

    /*
        Example:
        $mail = new Mail(
            'admin', $__CONFIG['mail']['account'], $__CONFIG['mail']['password'],
            $__CONFIG['mail']['host'], $__CONFIG['mail']['port'], $__CONFIG['mail']['secure']
        );
    */
    class MAIL {
        public $mailer = null;
        public $auth = null;
        public $debug = null;
        public $stmp_host = null;
        public $stmp_port = null;
        public $mail_address = null;
        public $user_name = null;
        public $password = null;
        public $secure = null;

        function __construct($user_name, $mail_address, $password, $stmp_host = 'smtp.gmail.com', $stmp_port = 587, $secure = 'tls') {
            $this -> mailer = new PHPMailer(true);
            $this -> smtp_host = $stmp_host;
            $this -> smtp_port = $stmp_port;
            $this -> user_name = $user_name;
            $this -> mail_address = $mail_address;
            $this -> password = $password;
            $this -> auth = true;
            $this -> secure = $secure;
        }

        public function send(Array $to, bool $is_html, string $sub, string $body, Array $from = array()) {
            try {
                if (!isset($from[0], $from[1])) $from = Array($this -> mail_address, $this -> user_name);

                $mailer = $this -> mailer;
                $mailer -> SMTPDebug = 0;
                $mailer -> isSMTP();

                $mailer -> CharSet = "utf-8";
                $mailer -> Host = $this -> smtp_host;
                $mailer -> SMTPAuth = (bool)($this -> auth);
                $mailer -> Username = $this -> mail_address;
                $mailer -> Password = $this -> password;
                $mailer -> SMTPSecure = $this -> secure;
                $mailer -> Port = $this -> smtp_port;

                $mailer -> From = $from[0];
                $mailer -> FromName = $from[1];

                $mailer -> setFrom($from[0], $from[1]);
                $mailer -> addAddress($to[0], $to[1]);

                $mailer -> isHTML($is_html);
                $mailer -> Subject = $sub;
                $mailer -> Body = $body;

                $mailer -> send();

                return true;
            } catch (Exception $e) {
                return false;
            }
        }
    }
?>