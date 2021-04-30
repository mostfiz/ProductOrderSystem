<?php
    namespace App\Infrastructure\Services;

    class DatabaseConnection {

        public $pdo = null;
        private $host = null;
        private $port = null;
        private $db_name   = null;
        private $username = null;
        private $password = null;

        // constructor with $db as database connection
        public function __construct(){
            
            $this->host = getenv('DB_HOST');
            $this->port = getenv('DB_PORT');
            $this->db_name   = getenv('DB_DATABASE');
            $this->username = getenv('DB_USERNAME');
            $this->password = getenv('DB_PASSWORD');
            // instantiate database and product object
            
            $db = $this->getConnection();
            $this->pdo = $db;
        }


        /**
        * COnnect the db
        *
        * @return $conn
        */
        private function getConnection(){

            $this->conn = null;

            try{
                $this->conn = new \PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
                $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            }catch(\PDOException $exception){
                echo "Connection error: " . $exception->getMessage();
            }

            return $this->conn;
        }
    }


