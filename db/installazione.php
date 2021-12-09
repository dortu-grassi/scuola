<?php
function fpg(){
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
					<p><input hidden class='it' id='txtDB' name='txtDB' type='text' placeholder='nome database....' ></p>
					<p><input hidden id='txtStato' name='txtStato' type='text'/></p>
					<p>
						<button id='pulsante'>
						<img src='../img/frecciaDestra.jpeg' alt='Vai.. Gooo...' width='100px' height='50' 
							onclick=installa('idFRM','txtStato')>
						</button>
					</p>
					</form>
					<p><button id='pulsante'>
						<img src='../img/chiave.jpeg' alt='Vai.. Gooo...' width='100px' height='50' 
							onclick=licenza('idFRM','txtStato','txtUser','txtUserPSW','txtDB','idmsg')>
						</button>
					</p>
			</div>
			<div id='idmsg'></div>
		</div>

	";
	echo $pg->prendiPG($r);
}
	include "../pg/CPG.php";
	include "CInstallazione.php";
	if( !empty($_POST) ){
		$db=new CInstallazione();
		if( $_POST['txtStato']=="installa" ){
			fpg();
			//$db=new CInstallazione();
			$db->installaTBS($_POST,"idmsg");
		}
		else if( $_POST['txtStato']=="licenzia" ){
			//print_r($_POST);
			//$db=new CInstallazione();
			$db->creaLicenza($_POST);
			return;
		}
		else{
			echo "<H1>GRAVE ERRORE DI SISTEMA</H1>";
			return;
		}
	}
	else{
		fpg();
	}

?>
