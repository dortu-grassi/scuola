<?php
	include "../pg/CPG.php";
	include "CDB.php";

	$pg=new CPG("ciao");
	$pg->setJS("installazione.js");
	$pg->setCSS("installazione.css");
	
		
	$r="
		
		<div id='frmIntesta'>
			Iserisci le credenziali
		</div>
		<div id='frmCorpo'>
			<div id='idLeft'>
				<form id='idFRM' action='installazione.php' method='POST'>
					<p><input class='it' id='txtUser' name='txtUser' type='text' placeholder='utente....' value='misterno'></p>
					<p><input class='it' id='txtUserPSW' name='txtUserPSW' type='text' placeholder='password utente....' value='OrtuOrtu66'></p>
					<p><button id='pulsante'>
						<img src='../img/chiave.jpeg' alt='Vai.. Gooo...' width='100px' height='50' 
							onclick=licenza('idFRM')>
						</button>
					</p>
					<p>
						<button id='pulsante'>
						<img src='../img/frecciaDestra.jpeg' alt='Vai.. Gooo...' width='100px' height='50' 
							onclick=installa('idFRM','txtUser','txtUserPSW','risposte')>
						</button>
					</p>
					<!--<p><textarea rows='5' cols='50' id='idmsg'></textarea></p>-->
				</form>
			</div>
			<div id='idmsg'></div>
		</div>

	";
	echo $pg->prendiPG($r);

	if( !empty($_POST) ){
		$db=new CDB();
		$db->installa($_POST,"idmsg");
				
	}
?>
