<?php
class CPG{
	
	private $testata;
	private $menu;
	private $footer;
	
	private $titolo="";
	private $contenitore="<h1>PAGINA IN COSTRUZIONE</h1>";
	private $js="";
	private $css="<link rel='stylesheet' href='../css/std.css'>";
	
	public function __construct($titolo){
		$this->setTitolo($titolo);
	}
	private function head(){
		return "
				<HEAD>
					<TITLE>$this->titolo</TITLE>
					<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
					<META NAME=author CONTENT='Ortu prof. Daniele dortu_grassi@hotmail.it'>
					$this->css
					$this->js
				</HEAD>
		";
	}
	public function setCSS($css){
		$this->css.="<link rel='stylesheet' href='../css/$css'>";
	}
	public function setJS($js){
		$this->js.="<script type='text/javascript' src='$js'></script>";
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
