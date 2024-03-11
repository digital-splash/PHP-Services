<?php
	namespace DigitalSplash\Classes\Database;

	use DigitalSplash\Classes\Core\Database;

	class VariableCategory extends Database {

		public function __construct($id=0) {
			parent::__construct();

			$this->_table	= "variable_category";
			$this->_key		= "id";
			
			$this->clearDeleted();
			$this->clearAutoOrder();
			$this->getInstance();

			$this->autoSaveCreate	= false;
			$this->autoSaveUpdate	= false;

			if ($id > 0) {
				parent::load($id);
			}
		}

		
	}

?>
