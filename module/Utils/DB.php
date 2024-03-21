<?php
    namespace Aleum\Utils;
    use mysqli;

    class DB {
        public $conn = null;
        public $result = null;

        function __construct(string $host = NULL, string $id = NULL, string $pass = NULL, string $name = NULL, string $port = '3306') {
            $this -> conn = new mysqli($host, $id, $pass, $name, $port);

            mysqli_set_charset($this -> conn, 'utf8');

            register_shutdown_function(function() {
                $this -> conn -> close();
                $this -> result = null;
                $this -> conn = null;
            });
        }

        public function query(string $query, ...$data) {
            $stmt = $this -> conn -> prepare($query);
            
            if (count($data) != 0) {
                $type = "";
                foreach ($data as $v) $type .= gettype($v)[0];
                $stmt -> bind_param($type, ...$data);
            }

            $stmt -> execute();
            $res = $stmt -> get_result();

            if ($res -> num_rows == 0) $this -> result = null;
            else {
                $this -> result = Array();
                while(($r = $res -> fetch_assoc()) !== null) $this -> result[] = $r;
            }

            $res -> close();
            $stmt -> close();
            #$this -> conn -> commit();

            return $this -> result;
        }

        public function insert(string $sql, ...$data) {
            $stmt = $this -> conn -> prepare($sql);
   
            if (count($data) != 0) {
                $type = "";
                foreach ($data as $v) $type .= gettype($v)[0];
                $stmt -> bind_param($type, ...$data);
            }

            $this -> result = $stmt -> execute();
            $stmt -> close();
            #$this -> conn -> commit();

            return $this -> result;
        }

        public function disconnect() {
            $this -> conn -> close();
        }
    }
?>