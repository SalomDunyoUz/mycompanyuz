<?php
	require_once "config_class.php";
	require_once "article_class.php";
	require_once "user_class.php";
	require_once "section_class.php";
	require_once "bannes_class.php";
	require_once "menu_class.php";
	require_once "message_class.php";
	
	abstract class Modules {
		
		protected $config;
		protected $article;
		protected $user;
		protected $section;
		protected $banner;
		protected $menu;
		protected $message;
		protected $data;
		
		public function __construct($db) {
			session_start();
			$this->config = new Config($db);
			$this->article = new Article($db);
			$this->user = new User($db);
			$this->section = new Section($db);
			$this->banner = new Banner($db);
			$this->menu = new Menu($db);
			$this->message = new Message();
			$this->data = $this->secureData($_GET);
		}
		
		public function getContent() {
			$sr["title"] = $this->getTitle();
			$sr["meta_desc"] = $this->getDescription();
			$sr["meta_key"] = $this->getKeyWords();
			$sr["menu"] = $this->getMenu();
			$sr["auth_user"] = $this->getAuthUser();
			$sr["banners"] = $this->getBanners();
			$sr["top"] = $this->getTop();
			$sr["middle"] = $this->getMiddle();
			$sr["bottom"] = $this->getBottom();
			return $this->getReplaceTemplate($sr, "main");
		}
		
		abstract protected function getTitle();
		abstract protected function getDescription();
		abstract protected function getKeyWords();
		abstract protected function getMiddle();
		
		protected function getMenu() {
			$menu = $this->menu->getAll();
			for($o = 0; $o < count($menu); $o++) {
				$sr["link"] = $menu[$o]["link"];
				$sr["title"] = $menu[$o]["title"];				
				$text .= $this->getReplaceTemplate($sr, "menu_item");
			}
			return $text;
		}
		
		protected function getAuthUser() {
			$sr["message_auth"] = "%auth_user%";
			return $this->getReplaceTemplate($sr, "auth_user");
		}
		
		protected function getBanners() {
			$banners = $this->banner->getAll();
			for($o = 0; $o < count($banner); $o++) {
				$sr["code"] = $banners[$o]["code"];
				$text .= $this->getReplaceTemplate($sr, "banners");
			}
			return $text;
		}
		
		protected function getTop() {
			return "chto to est";
		}
		
		protected function getBottom() {
			return "nimadir bor";
		}
		
		private function secureData($data) {
			foreach($data as $key => $value){
				if(is_array($value)) $this->secureData($value);
				else $data[$key] = htmlspecialchars($value);
			}
			return $data;
		}
		
		protected function getTemplate($name) {
			$text = file_get_contents($this->config->dir_tpml.$name.".tpl");
			return str_replace("%address%", $this->config->address, $text);
		}
		
		protected function getArticlesBlog($articles, $page) {
			$start = ($page - 1) * $this->config->count_articles;
			$end = (count($articles) > $start +  $this->config->count_articles)? $start +  $this->config->count_articles:count($articles);
			for($o = $start; $o < $end; $o++)
			{
				$sr["title"] = $articles[$o]["title"];
				$sr["intro_text"] = $articles[$o]["intro_text"];
				$sr["date"] = $this->formatDate($articles[$o]["date"]);
				$sr["link_article"] = $this->address."?view=articles&amp;id=".$articles[$o]["id"];
				$text .= $this->getReplaceTemplate($sr, "article_intro");
			}
			return $text;
		}
		
		protected function formatDate($time) {
			return date("Y-m-d H:i:s");
		}
		protected function getReplaceTemplate($sr, $template) {
			return $this->getReplaceContent($sr, $this->getTemplate($template));
		}
		
		private function getReplaceContent($sr, $content){
			$search = array();
			$replace = array();
			$o = 0;
			foreach($sr as $key => $value) {
				$search[$o] = "%$key%";
				$replace[$o] = $value;
				$o++;
			}
			return str_replace($search, $replace, $content);
		}
			
	}
?>