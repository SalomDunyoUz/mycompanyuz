<?php 
	require_once "config_class.php";
	require_once "checkvalid_class.php";
	require_once "database_class.php";
	
	abstract class GlobalClass {
	
		private $db;
		private $table_name;
		protected $config;
		protected $valid;
		
		protected function __construct($table_name, $db) {
			$this->db = $db;
			$this->table_name = $table_name;
			$this->config = new Config();
			$this->valid = new CheckValid();
		}
		
		protected function add($new_values) {
			return $this->db-insert($this->table_name, $new_values);
		}
		
		protected function edit($id, $upd_fields) {
			return $this->updateOnID($this->table_name, $id, $upd_fields);
		}
		
		public function delete($id) {
			return $this->db->deleteOnID($this->table_name, $id);
		}
		
		public function deleteAll(){
			return $this->db->deleteAll($this->table_name);
		}
		
		protected function getField($field_out, $field_in, $value_in){
			return $this->db->getField($this->table_name, $field_out, $field_in, $value_in);
		} 
		
		protected function getFieldByID($id, $field){
			return $this->db->getFieldByID($this->table_name, $id, $field);
		}
		
		protected function setFieldByID($id, $field, $value){
			return $this->db->setFieldByID($this->table_name, $id, $field, $value);
		}
		
		public function get($id){
			return $this->db->getElementsByID($this->db->table_name, $id);
		}
		
		public function getAll($order = "", $up = true) {
			return $this->db->getAll($this->table_name, $order, $up);
		}
		
		protected function getAllByField($field, $value, $order = "", $up = true) {
			return $this->db->getAllByField($this->table_name, $field, $value, $order, $up);
		}
		
		
		public function getRandomElements($counter) {
				return $this->db->getRandomElements($this->table_name, $counter);
		}
		
		public function getLastID() {
			return $this->db->getLastID($this->table_name);
		}
		
		public function getCounter() {
			return $this->db->getCounter($this->table_name);
		}
		
		protected function isExists($field, $value) {
			return $this->db->isExists($this->table_name, $field, $value);
		}
		
	}
	
	
 ?>