<?php
	define('DEBUG', true);
class CPG{
	
	private $testata;
	private $menu;
	private $footer;
	private $css="";	
	private $titolo="";
	private $contenitore="<h1>PAGINA IN COSTRUZIONE</h1>";
	private $js="";

	
	
	public function __construct($titolo){
		$this->setTitolo($titolo);
		if( DEBUG )
			$this->css="<link rel='stylesheet' href='../css/std.css?v=".time()."'>";
		else
			$this->css="<link rel='stylesheet' href='../css/std.css'>";
	}
	public function not_permesso($l,$richiesto){
		return "";
		if( $l<1 || $l>$richiesto ){
			return "<h1>NON PUOI ENTRARE</h1>";
		}
		return "";
	}
	private function head(){
		return "
				<HEAD>
					<TITLE>$this->titolo</TITLE>
					<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
					<META NAME=author CONTENT='Ortu prof. Daniele dortu_grassi@hotmail.it'>
					<link rel='icon' type='image/png' sizes='16x16' href='../img/favicon-16x16.png'>
					$this->css
					$this->js
				</HEAD>
		";
	}
	public function setCSS($css){
		if( DEBUG )
			$this->css.="<link rel='stylesheet' href='../css/".$css."?v=".time()."'>";
		else
			$this->css.="<link rel='stylesheet' href='../css/".$css."'>";
	}
	public function setJS($js){
		if( DEBUG ){
			$this->js.="<script type='text/javascript' src='".$js."?v=".time()."'></script>";
		}
		else{
			$this->js.="<script type='text/javascript' src='$js'></script>";
		}
	}
	public function setTitolo($t){
		$this->titolo=substr($t,0,9);
	}
	private function intestazione(){
		return "
			<div class='intestazione'>
			intestazione
			</div>
		";
	}
	private function contenitore(){
		return "
			<div id='contenitore'>
				".$this->contenitore."
			</div>
		";
	}
	private function footer(){
		return "
			<div class='footer'>
				foorter
			</div>
		";
	}	
	private function body(){
		return "
			<body>
				".$this->intestazione()."
				".$this->contenitore()."
				".$this->footer()."
			</body>
		";
	}
	
	public function prendiPG($pg){
		$this->contenitore=$pg;
		return "
			<HTML>
			".$this->head()."
			".$this->body()."
			</HTML>
		";
	}
}
	//$pg=new CPG();
	
	//echo $pg->prendiPG();
?>
