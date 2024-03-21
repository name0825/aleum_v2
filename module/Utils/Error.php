<?php
    namespace Aleum\Utils\Error;
    use Aleum\Utils\Load;

    include_once __DIR__."/Load.php";

    function get_error_info(string $code): string {
        $http_error_code = Array(
            // http status code
            "100" => "Continue",
            "101" => "Switching Protocol",
            "102" => "Processing",
            "103" => "Early Hints",
            "200" => "OK",
            "201" => "Created",
            "202" => "Accepted",
            "203" => "Non-Authoritative Information (en-US)",
            "204" => "No Content",
            "205" => "Reset Content",
            "206" => "Pratial Content",
            "207" => "Multi-Status",
            "208" => "Multi-Status",
            "226" => "IM Used",
            "300" => "Multiple Choice (en-US)",
            "301" => "Moved Permanently",
            "302" => "Found",
            "303" => "See Other (en-US)",
            "304" => "Not Modified",
            "305" => "Use Proxy",
            "306" => "unused",
            "307" => "Temporary Redirect",
            "308" => "Permanent Redirect (en-US)",
            "400" => "Bad Request",
            "401" => "Unauthorized",
            "402" => "Payment Required",
            "403" => "Forbidden",
            "404" => "Not Found",
            "405" => "Method Not Allowed",
            "406" => "Not Acceptable (en-US)",
            "407" => "Proxy Authentication Required (en-US)",
            "408" => "Request Timeout",
            "409" => "Conflict",
            "410" => "Gone (en-US)",
            "411" => "Length Required",
            "412" => "Precondition Failed (en-US)",
            "413" => "Payload Too Large",
            "414" => "URI Too Long (en-US)",
            "415" => "Unsupported Media Type (en-US)",
            "416" => "Requested Range Not Satisfiable",
            "417" => "Expectation Failed (en-US)",
            "418" => "I'm a teapot",
            "421" => "Misdirected Request",
            "422" => "Unprocessable Entity",
            "423" => "Locked",
            "424" => "Failed Dependency",
            "426" => "Upgrade Required (en-US)",
            "428" => "Precondition Required (en-US)",
            "429" => "Too Many Requests (en-US)",
            "431" => "Request Header Fields Too Large",
            "451" => "Unavailable For Legal Reasons (en-US)",
            "500" => "Internal Server Error",
            "501" => "Not Implemented",
            "502" => "Bad Gateway",
            "503" => "Service Unavailable",
            "504" => "Gateway Timeout",
            "505" => "HTTP Version Not Supported",
            "506" => "Variant Also Negotiates (en-US)",
            "507" => "Insufficient Storage (en-US)",
            "508" => "Loop Detected (en-US)",
            "510" => "Not Extended (en-US)",
            "511" => "Network Authentication Required (en-US)",
    
            // custom error code
            "R500" => "Server Error"
        );
        return $http_error_code[$code] ?? "Unknown Error";
    }

    function abort(mixed $code, string $msg = null): void {
        $error_code = strval($code);
        $error_msg = $msg ?? get_error_info($error_code);
        Load::load_page("error.html", array("error_code" => $error_code, "error_msg" => $error_msg));
        exit;
    }
?>