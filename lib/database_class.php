<?php 
	require_once "config_class.php";
	require_once "checkvalid_class.php";
	
	class DataBase {
		private $config;
		private $mysqli;
		private $valid;
		
		public function __construct() {
			$this->config = new Config();
			$this->valid = new CheckValid();
			$this->mysqli = new mysqli($this->config->host, $this->config->user, $this->config->password, $this->config->db);
			$this->mysqli->query("SET NAMES 'UTF8'");
		}
		
		private function query($query) {
			return $this->mysqli->query($query);
		}
		
		private function select ($table_name, $fields, $where = "", $order = "", $up = 1, $limit = ""){
			for ($o = 0; $o < count($fields); $o++) {
				if((strpos($fields[$o], "(") === 0) && ($fields[$o] != "*")) $fields = "`".$fields[$o]."`";
			}
			
			$fields = implode(",", $fields);
			$table_name = $this->config->db_prefix.$table_name;
			if(!$order) $order = "ORDER BY `id`";
			else {
				if ($order != "RAND()") {
					$order = "ORDER BY `$order`";
					if(!$up) $order.= " DESC";
				}
				else $order = "ORDER BY $order";
			}
			if($limit) $limit = "LIMIT $limit";
			if($where) $query = "SELECT $fields FROM $table_name WHERE $where $order $limit";
			else $query = "SELECT $fields FROM $table_name $order $limit";
			$result_set = $this->query($query);
			if(!$result_set) return 0;
			$o = 0; 
			while ($row = $result_set->fetch_assoc()) {
				$data[$o] = $row;
				$o++;
			}
			
			$result_set->close;
			return $data;
		}
			
			public function  insert ($table_name, $new_values) {
				$table_name = $this->config->db_prefix.$table_name;
				$query = "INSERT INTO $table_name (";
				foreach ($new_values as $field => $value)  $query .= "`".$field."`,";
				$query  = substr($query, 0, -1);
				$query .= ") VALUES (";
				foreach ($new_values as $value)  $query .= "`".addslashes($value)."`,";
				$query  = substr($query, 0, -1);
				$query = ")";
				return $this->query($query);
			}
			
			private function update($table_name, $upd_fields, $where) {
				$table_name = $this->config->db_prefix.$table_name;
				$query = "UPDATE $table_name SET";
				foreach($upd_fields as $field => $value) $query .= 
				"`$field` = '".addslashes($value)."',";
				$query = substr($query, 0, -1);
				if($where) {
					$query .= " WHERE $where";
					return $this->query($query);
				}
				else return 0;
			}
			
			public function delete ($table_name, $where = "") {
				$table_name = $this->config->db_prefix.$table_name;
				if($where) {
					$query = " DELETE FROM $table_name WHERE $where";
					return $this->query($query);
				}
				else return 0;
			}
			
			public function deleteAll($table_name) {
				$table_name = $this->config->db_prefix.$table_name;
				$query = "TRUNCATE TABLE `$table_name`";
				return $this->query($query);
			}
			
			public function getField($table_name, $field_out, $field_in, $value_in) {
				$data = $this->select($table_name, array($field_out), "`$field_in`='".addslashes($value_in)."'");
				if(count($data) != 1) return 0;
				return $data[0][$field_out];
			}
			public function getFieldByID($table_name, $id, $field_out) {
				if(!$this->existsID($table_name, $id)) return 0;
				return $this->getField($table_name, $field_out, "id", $id);
			}
			
			public function getAll($table_name, $order, $up){
				return $this->select($table_name, array("*"), "", $order, $up);
			}
			
			public function deleteByID($table_name, $id) {
				if(!$this->existsID($table_name, $id)) return 0;
				return $this->delete($table_name, "`id` = '$id'");
			}
			
			public function setField($table_name, $field, $value, $field_in, $value_in) {
				if(!$this->existsID($table_name, $id)) return 0;
				return $this->update($table_name, array($field => $value), "`$field_in` = '".addslashes($value_in)."'");
			}
			
			public function setFieldByID($table_name, $id, $field, $value) { 
				if(!$this->existsID($table_name, $id)) return 0;
				return $this->setField($table_name, $field, $value, "id", $id);
			}
			
			protected function getAllByField($table_name, $field, $value, $order, $up) {
				return $this->select($table_name, array("*"), "`$field` = '".addslashes($value)."'", $order, $up);
			}
			
			public function getElementsByID($table_name, $id){
				if(!$this->existsID($table_name, $id)) return 0;
				$arr = $this->select($table_name, array("*"), "`id` = '$id'");
				return $arr[0];
			}
			
			public function getLastID($table_name) {
				$data = $this->select($table_name, array("MAX`id`"));
				return $data[0]["MAX(`id`)"];
			}
			
			public function getRandomElements($table_name, $counter) {
				return $this->select($table_name, array("*"), "", "RAND()", true, $counter);
			}
			
			public function getCountedSum() {
				$data = $this->select($table_name, array("COUNT(`id`)"));
				return $data[0]["COUNT(`id`)"];
			}
			
			public function isExists($table_name, $field, $value) {
				$data = $this->select($table_name, array("id"),  "`$field` = '".addslashes($value)."'");
				if(count($data) === 0) return 0;
				return 1;
			}
			
			private function existsID($table_name, $id){
				if(!$this->valid->validID($id)) return 0;
				$data = $this->select($table_name, array("id"), "`id`='".addslashes($id)."'");
				if(count($data) === 0) return 0;
				return 0;
			}
			
			public function __destruct() {
				if($this->mysqli) $this->mysqli->close();
			}
	}
?>