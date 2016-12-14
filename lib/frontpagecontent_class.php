<?php 
 
	require_once "modules_class.php";
	
	class FrontPageContent extends Modules {
		private $articles;
		private $page;
		
		public function __construct($db){
			parent::__construct($db);
			$this->articles = $this->article->getAllSortDate();
			$this->page = (isset($this->data["page"]))? $this->data["page"]: 1;
		}
		
		protected function getTitle() {
			if($this->page > 1) return "Bu yerda sahifaning yo'nalishi yoziladi".$this->page;
			else return "Shunchaki maqsad";
		}
		
		protected function getDescription() {
			return "Saytning maqsadfi yo'nalishi haqida matn";
		}
		
		protected function getKeyWords() {
			return "site, function, php, mysql, html, css";
		}
		
		protected function getTop() {
			return $this->getTemplate("main_article");
		}
		
		protected function getMiddle() {
			return $this->getArticlesBlog($this->articles, $this->page);
		}
	}
 ?>