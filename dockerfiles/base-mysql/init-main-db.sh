#!/bin/bash

echo "Creating Main Database (If not Exist)";
mysql -e "CREATE DATABASE IF NOT EXISTS php_services;"
mysql -e "ALTER DATABASE php_services DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;"

echo "Intitializing php_services Database";
mysql -uroot php_services < /docker-entrypoint-initdb.d/_db_main_structure.sql
