<?php
    namespace DigitalSplash\Database\Models;

    class DatabaseCredentials {

        private string $host     = "";
        private string $username = "";
        private string $password = "";
        private string $database = "";
        private string $port     = "";
        private string $charset  = "";

        public function setHost(
            string $host
        ): void {
            $this->host = $host;
        }

        public function getHost(): string {
            return $this->host;
        }

        public function setUsername(
            string $username
        ): void {
            $this->username = $username;
        }

        public function getUsername(): string {
            return $this->username;
        }

        public function setPassword(
            string $password
        ): void {
            $this->password = $password;
        }

        public function getPassword(): string {
            return $this->password;
        }

        public function setDatabase(
            string $database
        ): void {
            $this->database = $database;
        }

        public function getDatabase(): string {
            return $this->database;
        }

        public function setPort(
            string $port
        ): void {
            $this->port = $port;
        }

        public function getPort(): string {
            return $this->port;
        }

        public function setCharset(
            string $charset
        ): void {
            $this->charset = $charset;
        }

        public function getCharset(): string {
            return $this->charset;
        }

    }