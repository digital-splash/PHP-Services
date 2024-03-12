<?php
	namespace DigitalSplash\Tests\Utils;

	use Exception;
	use DigitalSplash\Database\DbConn;
	use DigitalSplash\Database\QueryAttributes\Condition;

	class DbTestUtils {

		public static function truncateTable(
			string $table_name
		): bool {
			return DbConn::executeRawQueryStatic("TRUNCATE TABLE `$table_name`");
		}

		public static function clearTable(
			string $table_name
		): bool {
			return DbConn::executeRawQueryStatic("DELETE FROM `$table_name`");
		}

		public static function insertIntoTable(
			array $data,
			string $class
		): string {
			$id = 0;

			try {
				/**
				 * @var DbConn
				 */
				$obj = new $class();
				$row = $obj->save($data);
				$id = $row[$obj->getKeyName()];
				unset($obj);
			} catch (Exception $e) {}

			return $id;
		}

		public static function deleteFromTableByKeyValue(
			int $value,
			string $key,
			string $class,
		): void {
			try {
				/**
				 * @var DbConn
				 */
				$obj = new $class();
				$filterDeleted = $obj->getFilterDeleted();
				$obj->filterDeletedClear();
				$obj->setConditions([
					new Condition($key, $value)
				]);
				$obj->selectFromDB();
				$obj->hardDelete();
				$obj->setFilterDeleted($filterDeleted);
			} catch (Exception $e) {}
		}

		public static function deleteFromTableWhereKeyNotEqualTo(
			$value,
			string $key,
			string $class,
		): void {
			try {
				/**
				 * @var DbConn
				 */
				$obj = new $class();
				$obj->filterDeletedClear();
				$obj->setConditions([
					new Condition($key, $value, '!=')
				]);
				$obj->selectFromDB();
				$obj->hardDelete();
			} catch (Exception $e) {}
		}

		public static function selectFromTable(
			string $class,
			string $condition = '',
			string $fields = '',
			string $order = '',
			string $direction = 'ASC'
		): array {
			/**
			 * @var DbConn
			 */
			$obj = new $class();

			$fields = empty($fields) ? '*' : $fields;
			$condition = empty($condition) ? '' : " WHERE {$condition}";
			$order = empty($order) ? '' : " ORDER BY {$order} {$direction}";
			$query = "SELECT {$fields} FROM {$obj->getTable()}{$condition} {$order}";

			return $obj->executeRawQuery($query);
		}
	}
