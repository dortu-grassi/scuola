<?php
	include "../pg/CPG.php";
	include "CDB.php";
	
	$pg=new CPG("ciao");
	$r="
		
		<div class='frmIntesta'>
			Iserisci gli account di root e di utente
		</div>
		<div>
			<form action='installazione.php' method='POST'>
			<input type='text' placeolder='licenza....'>
			</form>
		</div>
		
	";
	echo $pg->prendiPG($r);
?>
