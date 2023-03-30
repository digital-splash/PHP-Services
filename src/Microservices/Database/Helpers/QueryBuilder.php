<?php
    namespace DigitalSplash\Database\Helpers;

    use DigitalSplash\Database\Models\DatabaseCredentials;
    use DigitalSplash\Helpers\Helper;


    class QueryBuilder {

        public static function insert(
            string $table,
            array $data,
            DatabaseCredentials $db
        ): string {
            $columns = array_keys($data);
            $values = array_values($data);
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            $columns = implode(", ", $columns);
            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            // Data binding
            $conn = new mysqli($db->host, $db->username, $db->password, $db->database);
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('s', count($data)), ...$values);
            return $sql;
        }

        public static function update(
            string $table,
            array $data,
            array $where,
            DatabaseCredentials $db
        ): string {
            $columns = array_keys($data);
            $values = array_values($data);
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            $columns = implode(", ", $columns);
            $sql = "UPDATE $table SET ($columns) VALUES ($placeholders) WHERE $where";
            // Data binding
            $conn = new mysqli($db->host, $db->username, $db->password, $db->database);
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('s', count($data)), ...$values);
            return $sql;
        }
        
        
    }