<?php

	session_start();
	
	
	include "../classi/CPG.php";
	$pg=new CPG("ANNO SCOLASTICO");	
	$pg->setJS("../js/as.js");
	$pg->setCSS("../css/as.css");
	
	
	if( $r=$pg->not_permesso(
		(count($_SESSION)==0)? 0:$_SESSION['livello']
			,1 ) 
	){
		echo $r;
		return;
	}
	
	include "../classi/CAS.php";

	$as=new CAS();
	
	if( isset( $_GET['txtas'] ) ){
		$as->nuovoAS($_GET['txtas']);
		//echo $GET['txtas'];
		return;
	}
	echo $pg->prendiPG( $as->visupg() );

?>
