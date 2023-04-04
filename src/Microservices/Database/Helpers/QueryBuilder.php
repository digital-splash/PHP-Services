<?php
    namespace DigitalSplash\Database\Helpers;

    use DigitalSplash\Database\Models\DatabaseCredentials;
    use DigitalSplash\Helpers\Helper;


    class QueryBuilder {

        //TODO add buildsql function 
        //TODO non static
        public function insert(
            string $table,
            array $data,
            DatabaseCredentials $db
        ): string {
            $columns = array_keys($data);
            $values = array_values($data);
            //TODO implode values directly
            $placeholders = implode(', ', $data);
            $columns = implode(", ", $columns);
            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            return $sql;
        }

        public function update(
            string $table,
            array $data,
            array $where,
            DatabaseCredentials $db
        ): string {
            $columns = array_keys($data);
            $values = array_values($data);
            $placeholders = implode(', ', $data);
            $columns = implode(", ", $columns);
            $sql = "UPDATE $table SET ($columns) VALUES ($placeholders) WHERE $where";
            return $sql;
        }

        public  function delete(
            string $table,
            array $where,
            DatabaseCredentials $db
        ): string {
            $sql = "DELETE FROM $table WHERE $where";
            return $sql;
        }

        // public static function select(
        //     string $table,
        //     array $columns,
        //     array $where,
        //     DatabaseCredentials $db
        // ): string {
        //     $columns = implode(", ", $columns);
        //     $sql = "SELECT $columns FROM $table WHERE $where";
        //     return $sql;
        // }



        // public static function selectAll(
        //     string $table,
        //     DatabaseCredentials $db
        // ): string {
        //     $sql = "SELECT * FROM $table";
        //     return $sql;
        // }

        // public static function selectAllWhere(
        //     string $table,
        //     array $where,
        //     DatabaseCredentials $db
        // ): string {
        //     $sql = "SELECT * FROM $table WHERE " . implode(" AND ", $where);
        //     return $sql;
        // }

        // public static function selectAllOrderBy(
        //     string $table,
        //     DatabaseCredentials $db,
        //     string $orderBy
        // ): string {
        //     $sql = "SELECT * FROM $table ORDER BY $orderBy";
        //     return $sql;
        // }

        // public static function selectAllWhereOrderBy(
        //     string $table,
        //     array $where,
        //     string $orderBy
        // ): string {
        //     $sql = "SELECT * FROM $table WHERE $where ORDER BY $orderBy";
        //     return $sql;
        // }


        // public static function selectAllJoin(
        //     string $table,
        //     array $columns,
        //     array $join,
        // ): string {
        //     $columns = implode(", ", $columns);
        //     $sql = "SELECT $columns FROM $table";
        //     foreach($join as $j){
        //         $sql .= " JOIN $j";
        //     }
        //     return $sql;
        // }

        // public static function selectAllJoinWhere(
        //     string $table,
        //     array $columns,
        //     array $join,
        //     array $where,
        //     DatabaseCredentials $db
        // ): string {
        //     $columns = implode(", ", $columns);
        //     $sql = "SELECT $columns FROM $table";
        //     foreach($join as $j){
        //         $sql .= " JOIN $j";
        //     }
        //     $sql .= " WHERE $where";
        //     return $sql;
        // }


        // public static function selectAllJoinWhereOrderBy(
        //     string $table,
        //     array $columns,
        //     array $join,
        //     array $where,
        //     array $orderBy,
        //     DatabaseCredentials $db
        // ): string {
        //     $columns = implode(", ", $columns);
        //     $sql = "SELECT $columns FROM $table";
        //     foreach($join as $j){
        //         $sql .= " JOIN $j";
        //     }
        //     $sql .= " WHERE $where";
        //     $sql .= " ORDER BY $orderBy";
        //     return $sql;
        // }

        // public static function selectAllJoinOrderBy(
        //     string $table,
        //     array $columns,
        //     array $join,
        //     array $orderBy,
        //     DatabaseCredentials $db
        // ): string {
        //     $columns = implode(", ", $columns);
        //     $sql = "SELECT $columns FROM $table";
        //     foreach($join as $j){
        //         $sql .= " JOIN $j";
        //     }
        //     $sql .= " ORDER BY $orderBy";
        //     return $sql;
        // }



        public function select(
            string $table,
            array $columns = [],
            array $where = [],
            array $join = [],
            string $orderBy = ""
        ): string {
            $sql = "SELECT ";
            if (empty($columns)) {
                $sql .= "*";
            } else {
                $sql .= implode(", ", $columns);
            }
    
            $sql .= " FROM $table";
    
            foreach ($join as $j) {
                $sql .= " JOIN $j";
            }
    
            if (!empty($where)) {
                $whereClause = implode(" AND ", $where);
                $sql .= " WHERE $whereClause";
            }
    
            if (!empty($orderBy)) {
                $sql .= " ORDER BY $orderBy";
            }
    
            return $sql;
        }
    
    }