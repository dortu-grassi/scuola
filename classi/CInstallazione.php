<?php
	include_once "../classi/CDB.php";
class CInstallazione extends CDB{	
	
	public function installaTBS($post,$msg){
		//echo  $this->msg($msg,$this->utente."-".$this->passwd);
		//return;
		if( $r=$this->leggiAutorizzazioni()  ){
			echo $this->msg($msg,$r);
			return;
		}
		
		//echo  $this->msgERR($this->user."-".$post['txtUser']);
		if( ($post['txtUser']!=$this->utente) || ($post['txtUserPSW']!=$this->passwd) ){
			echo $this->msg($msg,"UTENTE O PASSWORD ERRATI");
			return;
		}
		ob_implicit_flush(true);
		ob_end_flush();
		
		if( $r=$this->connetti() ){
			echo $r;
			return;
		}

/******************tabella nazioni******************************/		

		$mezzo=$this->cctTB($this->campiNazioni,$this->tipiNazioni);
		$fine=$this->pk($this->campiNazioni[0]).",";
		$fine.=$this->uq(0,$this->campiNazioni[$this->en_nazioni["codiceStato"]]);
		$r=$this->creaTB($this->tbNazioni,$mezzo,$fine); 
		if( $r ) {
			echo $this->msg($msg,$r); 
			$this->conn->close();
			return;
		}
		echo $this->msg($msg,$this->tbNazioni." creata");
		sleep(1);
/**************** tabella comuni ****************************************/		

		$mezzo=$this->cctTB($this->campiComuni,$this->tipiComuni);
		$fine=$this->pk($this->campiComuni[$this->en_comuni['idComune']]);
		$r=$this->creaTB($this->tbComuni,$mezzo,$fine); 
		if( $r ) {
			echo $this->msg($msg,$r); 
			$this->conn->close();
			return;
		}
		echo $this->msg($msg,$this->tbComuni." creata");
		sleep(1);
/*****************tabella componenti scuola *****************************/		
		$mezzo=$this->cctTB($this->campiComponenti,$this->tipiComponenti);
		$fine=$this->pk($this->campiComponenti[$this->en_componenti['idComponente']]);
		$r=$this->creaTB($this->tbComponenti,$mezzo,$fine); 
		if( $r ) {
			echo $this->msg($msg,$r); 
			$this->conn->close();
			return;
		}
		$c="";
		foreach ($this->campiComponenti as $v){
			$c.=$v.",";
		}
		//echo $this->msg($msg,"ccc".$c); 
		$c=substr($c,0,strlen($c)-1);
		$r=$this->insertINTO($this->tbComponenti,$c,$this->valComponent);
		if( $r ) {
			echo $this->msg($msg,$r); 
			$this->conn->close();
			return;
		}
		echo $this->msg($msg,$this->tbComponenti." creata");
		sleep(1);
/*****************tabella anagrafica ************************************/		
		$mezzo=$this->cctTB($this->campiAnagrafica,$this->tipiAnagrafica);
		$fine=$this->pk($this->campiAnagrafica[$this->en_anagrafica['idAnagrafica']]).",";
		$fine.=$this->uq(1,$this->campiAnagrafica[$this->en_anagrafica['cf']]).",";
		$fine.=$this->fk("0",
				$this->campiAnagrafica[$this->en_anagrafica['fkResidenza']],
				$this->tbComuni,
				$this->campiComuni[$this->en_comuni['idComune']]
		).",";
		$fine.=$this->fk("0",
				$this->campiAnagrafica[$this->en_anagrafica['fkCittadinanza']],
				$this->tbNazioni,
				$this->campiNazioni[$this->en_nazioni['idNazione']]
		).",";
		$fine.=$this->fk("0",
				$this->campiAnagrafica[$this->en_anagrafica['fkComponente']],
				$this->tbComponenti,
				$this->campiComponenti[$this->en_componenti['idComponente']]
		);
		
		$r=$this->creaTB($this->tbAnagrafica,$mezzo,$fine); 
		if( $r ) {
			echo $this->msg($msg,$r); 
			$this->conn->close();
			return;
		}
		echo $this->msg($msg,$this->tbAnagrafica." creata");
		sleep(1);
/************** view anagrafica alunni***********************************/		
		$cmp=array();
		$cmp=$this->getCampi($this->tbAnagrafica,$this->campiAnagrafica);
		//echo "nr.cmp ".count($cmp);
		$fkComponente=$cmp[$this->en_anagrafica['fkComponente']];
		$cmp=array_merge($cmp,$this->getCampi($this->tbComuni,$this->campiComuni));
		$cmp=array_merge($cmp,$this->getCampi($this->tbNazioni,$this->campiNazioni));
		$cmp=array_merge($cmp,$this->getCampi($this->tbComponenti,$this->campiComponenti));
		$q="CREATE OR REPLACE VIEW ".$this->getNomeTB($this->viewAnaStudenti)." as ";
		$q.=" SELECT ";
		foreach( $cmp as $v){
			$q.=$v.",";
		}
		$q=substr($q,0,strlen($q)-1);
		$q.=" FROM ";
		$q.=$this->getNomeTB($this->tbNazioni).",";
		$q.=$this->getNomeTB($this->tbComuni).",";
		$q.=$this->getNomeTB($this->tbComponenti).",";
		$q.=$this->getNomeTB($this->tbAnagrafica)." ";
		$q.=" WHERE ".$fkComponente."=".$this->valComponenti['alunni'];
		//echo $this->msg($msg,$q);
		$this->conn->query($q);
		if( $this->conn->error ){
			echo $this->msg($msg,$this->conn->error);
			return;
		}
		echo $this->msg($msg,$this->viewAnaStudenti." creata");
		sleep(1);

/**************TABELLA ANNI SCOLASTICI***********************************/		
		$mezzo=$this->cctTB($this->campiAS,$this->tipiAS);
		$fine=$this->pk($this->campiAS[$this->en_as['anno']]);
		$r=$this->creaTB($this->tbAS,$mezzo,$fine); 
		if( $r ) {
			echo $this->msg($msg,$r); 
			$this->conn->close();
			return;
		}
		echo $this->msg($msg,$this->tbAS." creata");
		sleep(1);
/*****************tabella stati studente *****************************/		
		$mezzo=$this->cctTB($this->campiStatiStudente,$this->tipiStatiStudente);
		$fine=$this->pk($this->campiStatiStudente[$this->en_statiStudente['idStatoStudente']]);
		$r=$this->creaTB($this->tbStatiStudente,$mezzo,$fine); 
		if( $r ) {
			echo $this->msg($msg,$r); 
			$this->conn->close();
			return;
		}
		$c="";
		foreach ($this->campiStatiStudente as $v){
			$c.=$v.",";
		}
		$c=substr($c,0,strlen($c)-1);
		$valori="";
		foreach ($this->valStatiStudente as $ch=>$v){
			$valori.="(".$v.",'".$ch."'),";
		}
		$valori=substr($valori,0,strlen($valori)-1);
		$r=$this->insertINTO($this->tbStatiStudente,$c,$valori);
		if( $r ) {
			echo $this->msg($msg,$r); 
			$this->conn->close();
			return;
		}
		echo $this->msg($msg,$this->tbStatiStudente." creata");
		sleep(1);
/*****************tabella indirizzi *****************************/		
		$mezzo=$this->cctTB($this->campiIndirizzi,$this->tipiIndirizzi);
		$fine=$this->pk($this->campiIndirizzi[$this->en_Indirizzi['idIndirizzo']]).",";
		$fine.=$this->uq(1,$this->campiIndirizzi[$this->en_Indirizzi['codice']]);
		$r=$this->creaTB($this->tbIndirizzi,$mezzo,$fine); 
		if( $r ) {
			echo $this->msg($msg,$r); 
			$this->conn->close();
			return;
		}
		$c="";
		foreach ($this->campiIndirizzi as $v){
			$c.=$v.",";
		}
		$c=substr($c,0,strlen($c)-1);
		$valori="
			(1,'MECCATRONICA','MM','".$this->valIndirizzi['mm']."'),
			(2,'INFORMATICA','IN','".$this->valIndirizzi['in']."'),
			(3,'ELETTROTECNICA ELETTRONICA','EL','".$this->valIndirizzi['el']."'),
			(4,'TRASPORTI E LOGISTICA','TL','".$this->valIndirizzi['tl']."'),
			(5,'COSTRUTTORI','CS','".$this->valIndirizzi['cs']."'),
			(6,'CONDUTTORI','CD','".$this->valIndirizzi['cd']."')
		";
		$r=$this->insertINTO($this->tbIndirizzi,$c,$valori);
		if( $r ) {
			echo $this->msg($msg,$r); 
			$this->conn->close();
			return;
		}
		echo $this->msg($msg,$this->tbIndirizzi." creata");
		sleep(1);
/**************TABELLA classi***********************************/		
		$mezzo=$this->cctTB($this->campiClassi,$this->tipiClassi);
		$fine=$this->pk($this->campiClassi[$this->en_Classi['idClassi']]);
		$r=$this->creaTB($this->tbClassi,$mezzo,$fine); 
		if( $r ) {
			echo $this->msg($msg,$r); 
			$this->conn->close();
			return;
		}
		echo $this->msg($msg,$this->tbClassi." creata");
		sleep(1);
/*****************tabella BES *****************************/		
		$mezzo=$this->cctTB($this->campiBES,$this->tipiBES);
		$fine=$this->pk($this->campiBES[$this->en_BES['idBES']]);
		$r=$this->creaTB($this->tbBES,$mezzo,$fine); 
		if( $r ) {
			echo $this->msg($msg,$r); 
			$this->conn->close();
			return;
		}
		$c="";
		foreach ($this->campiBES as $v){
			$c.=$v.",";
		}
		$c=substr($c,0,strlen($c)-1);
		$valori="";
		foreach ($this->valBES as $ch=>$v){
			$valori.="(".$v.",'".$ch."'),";
		}
		$valori=substr($valori,0,strlen($valori)-1);
		$r=$this->insertINTO($this->tbBES,$c,$valori);
		if( $r ) {
			echo $this->msg($msg,$r); 
			$this->conn->close();
			return;
		}
		echo $this->msg($msg,$this->tbBES." creata");
		sleep(1);
/************************************************************************/	
		$this->conn->close();
		//$this->scriviAutorizzazioni();
		//return $this->leggiAutorizzazioni();
			
	}
	public function creaLicenza($post){
		if( empty($post) ){
			echo "<h5>ORRORE</h5>";
			return;
		}
		$handler = fopen($this->filename, 'w');

		if (false === $handler) {
			echo "<h5>Impossibile scrivere il file ".$this->filename."</h5>";
			return;
		}
		$en=$post['txtUser']."-".$post['txtUserPSW']."-".$post['txtDB'];
		$en=$this->cripta($en,$this->key);
		fwrite($handler, $en);
		fclose($handler);
		echo "<h5>licenza inserita</h5>";
	}

}	
?>
