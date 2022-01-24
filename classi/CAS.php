<?php

include "../classi/CDB.php";

class CAS extends CDB{
	
	public function nuovoAS($as){
		if( $r=$this->connetti() ){
			//echo $r;
			echo $r;
		}
/*****************tabella curricula ************************************/		
		$mezzo=$this->cctTB($this->campiCurricula,$this->tipiCurricula);
		$fine=$this->pk($this->campiCurricula[$this->en_Curricula['idCurricula']]).",";
		$fine.=$this->uq(1,$this->campiCurricula[$this->en_Curricula['fkanagrafica']]).",";
		
		$fine.=$this->fk($as,
				$this->campiCurricula[$this->en_Curricula['fkanagrafica']],
				$this->tbAnagrafica,
				$this->campiAnagrafica[$this->en_anagrafica['idAnagrafica']]
		).",";
		$fine.=$this->fk($as,
				$this->campiCurricula[$this->en_Curricula['fkBES']],
				$this->tbBES,
				$this->campiBES[$this->en_BES['idBES']]
		).",";
		$fine.=$this->fk($as,
				$this->campiCurricula[$this->en_Curricula['fkstatostudente']],
				$this->tbStatiStudente,
				$this->campiStatiStudente[$this->en_statiStudente['idStatoStudente']]
		).",";
		$fine.=$this->fk($as,
				$this->campiCurricula[$this->en_Curricula['fkclasse']],
				$this->tbClassi,
				$this->campiClassi[$this->en_Classi['idClassi']]
		).",";
		$fine.=$this->fk($as,
				$this->campiCurricula[$this->en_Curricula['fkindirizzo']],
				$this->tbIndirizzi,
				$this->campiIndirizzi[$this->en_Indirizzi['idIndirizzo']]
		);
		
		$r=$this->creaTB($this->tbCurricula.$as,$mezzo,$fine); 
		if( $r ) {
			echo $r; 
			$this->conn->close();
			return;
		}
		
/************************************************************************/	
		$cmp="";
		foreach($this->campiAS as $val)
			$cmp.=$val.",";
		
		$val="($as,0)";
		$r=$this->insertINTO($this->tbAS,substr($cmp,0,strlen($cmp)-1),$val);
		if( $r ) {
			echo $r; 
			$this->conn->close();
			return;
		}
		$this->conn->close();
		
		echo $this->getListaAnni();
	}
	
	private function getListaAnni(){
		if( $r=$this->connetti() ){
			//echo $r;
			return $r;
		}
		$q=	"SELECT * FROM ".$this->getNomeTB($this->tbAS).
			" ORDER BY ".$this->campiAS[$this->en_as['anno']];

		$tb=$this->conn->query($q);
		if( $this->conn->error ){
			return $this->conn->error."<br>".$q;
		}
		if( $this->conn->affected_rows==0){
			$this->conn->close();
			return "Non ci sono anni scolastici disponibili";
		}
		
		$r="<SELECT name='selas' id='selas'>";
		while( $rs=$tb->fetch_array(MYSQLI_ASSOC) ){
			$anno=$rs[$this->campiAS[$this->en_as['anno']]];
			if( $rs[$this->campiAS[$this->en_as['stato']]]==1 ){
				$r.="<OPTION selected>".substr($anno,4,4)." - ".strlen($anno,4)."</OPTION>";
			}
			else{
				$r.="<OPTION>".substr($anno,4,4)." - ".substr($anno,0,4)."</OPTION>";
			}
		}
		$r.="</SELECT>";
		
		$this->conn->close();
		
		return $r;
	}
	function visupg(){
		
			
		$r="		
			<div id='frmIntesta'>
				Gestisci anni scolastici
			</div>
			<div id='frmCorpo'>
				<div id='idLeft'>
						<!--<form id='idFRM' action='installazione.php' method='POST'>-->
						<TABLE border='0' height='250px'>
						<TR>
							<TD align='right'><input class='txt' id='txtASPRE' name='txtUser' type='text' placeholder='anno ' maxlength='4' onkeyup=eventoOnKeyUP('txtASPRE','txtASPOST')></TD>
							<TD> - </TD>
							<TD><input class='txt' id='txtASPOST' name='txtUserPSW' type='text'  value='' readonly ></TD>
							
							
						</TR>
						<TR>
							<TD ></TD>
							<TD ></TD>
							<TD ></TD>
						</TR>
						<TR>
							<tD>
							<button id='pulsante'>
							<img src='../img/psnuovo.jpg' alt='Vai.. Gooo...' width='100%' height='100%' 
								onclick=creaAS('lstAnni','txtASPRE','txtASPOST')>
							</button>
							</tD>
						</TR>
						</TABLE>
						<!--</form>-->
						
				</div>
				<input hidden id='txtStato' name='txtStato' type='text'/>
				<div id='idmsg'><div id='lstAnni'>".$this->getListaAnni()."</div></div>
			</div>

		";
		//return $this->pg->prendiPG($r);
		return $r;
	}
}

?>
