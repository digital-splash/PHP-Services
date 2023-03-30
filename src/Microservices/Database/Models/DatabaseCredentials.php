<?php
    namespace DigitalSplash\Database\Models;

    class DatabaseCredentials {

        public string $host     = "";
        public string $username = "";
        public string $password = "";
        public string $database = "";
        public string $port     = "";
        public string $charset  = "";

        public function __construct(
            string $host,
            string $username,
            string $password,
            string $database,
            string $port,
            string $charset
        ) {
            $this->host     = $host;
            $this->username = $username;
            $this->password = $password;
            $this->database = $database;
            $this->port     = $port;
            $this->charset  = $charset;
        }


        public function GetDsn(): string {
            return 
                "mysql:host=" . $this->host . 
                ";dbname=" . $this->database . 
                ";port=" . $this->port . 
                ";charset=" . $this->charset;
        }
        
        
        public function GetUsername(): string {
            return $this->username;
        }


        public function GetPassword(): string {
            return $this->password;
        }


        public function GetDatabase(): string {
            return $this->database;
        }


        public function GetHost(): string {
            return $this->host;
        }


        public function GetPort(): string {
            return $this->port;
        }


        public function GetCharset(): string {
            return $this->charset;
        }

    }