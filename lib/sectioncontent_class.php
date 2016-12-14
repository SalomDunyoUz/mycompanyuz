<?php 
 
	require_once "modules_class.php";
	
	class SectionContent extends Modules {
		private $articles;
		private $section_info;
		private $page;
		
		public function __construct($db){
			parent::__construct($db);
			$this->articles = $this->article->getSectionByID($this->data["id"]);
			$this->section_info = $this->section->get($this->data["id"]);
			$this->page = (isset($this->data["page"]))? $this->data["page"]: 1;
		}
		
		protected function getTitle() {
			if($this->page > 1) return $this->section_info["title"]." - Section sahifa ".$this->page;
			else return "Shunchaki maqsad";
		}
		
		protected function getDescription() {
			return $this->section_info["meta_desc"];
		}
		
		protected function getKeyWords() {
			return $this->section_info["meta_key"];
		}
		
		protected function getTop() {
			$sr["title"] = $this->section_info["title"];
			$sr["description"] = $this->section_info["description"];
			return $this->getReplaceTemplate($sr, "section");
		}
		
		protected function getMiddle() {
		
		}
	}
 ?>