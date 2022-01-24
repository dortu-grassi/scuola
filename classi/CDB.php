<?php
class CDB{
	protected $server="localhost";
	protected $uroot="";
	protected $proot="";
	protected $utente="";
	protected $passwd="";
	protected $licenza="";
	protected $schema="dbscuola";
	protected $conn;
	protected $filename = '../classi/auto/licenza.txt';
	protected $key="A82DD517";
	
	/*************** nazioni *************************/
	protected $tbNazioni="tbNazioni";
	protected $en_nazioni=array(
			"idNazione"=>0,
			"continente"=>1,
			"area"=>2,
			"codiceStato"=>3,
			"denominazioneStato"=>4,
			"cittadinanza"=>5,
			"codiceStatoSIDI"=>6
	);
	protected $campiNazioni=array("idNazione","continente","area","codiceStato","denominazioneStato","cittadinanza","codiceStatoSIDI");
	protected $tipiNazioni=array(
		"bigint NOT NULL AUTO_INCREMENT",
		"varchar(20) NOT NULL",
		"bigint DEFAULT NULL",
		"bigint DEFAULT NULL",
		"varchar(100) NOT NULL",
		"varchar(50) DEFAULT NULL",
		"bigint DEFAULT NULL"
	);
	/*************** comuni *************************/
	protected $tbComuni="tbComuni";
	protected $en_comuni=array("idComune"=>0);
	protected $campiComuni=array("idComune","istat","nomeComune","provincia","regione","prefisso","CAP","codFiscale");
	protected $tipiComuni=array(
		"bigint NOT NULL AUTO_INCREMENT",
		"varchar(10) NOT NULL",
		"varchar(100) NOT NULL",
		"varchar(4) NOT NULL",
		"varchar(50) NOT NULL",
		"varchar(10) DEFAULT NULL",
		"varchar(5) NOT NULL",
		"char(4) NOT NULL"
	);
	/*************** componenti *************************/
	protected $tbComponenti="tbComponenti";
	protected $en_componenti=array("idComponente"=>0,"denominazione"=>1);
	protected $campiComponenti=array("idComponenti","denominazione");
	protected $tipiComponenti=array(
		"bigint NOT NULL AUTO_INCREMENT",
		"varchar(50)"
	);
	protected $valComponent="(1,'DOCENTI'),(2,'ALUNNI'),(3,'GENITORI'),(4,'ATA'),(5,'DS'),(6,'DSGA')";
	protected $valComponenti=array('docenti'=>1,'alunni'=>2,'genitori'=>3,'ata'=>4,'ds'=>5,'dsga'=>6);
	/*************** anagrafica *************************/
	protected $tbAnagrafica="tbAnagrafica";
	protected $en_anagrafica=array("idAnagrafica"=>0,"cf"=>4,"fkResidenza"=>5,"fkCittadinanza"=>6,"fkComponente"=>7);
	protected $campiAnagrafica=array("idAnagrafica","nome","cognome","dataNascita","cf","fkComuneResidenza",
		"fkCittadinanza","fkComponente","sesso");
	protected $tipiAnagrafica=array(
			"bigint NOT NULL AUTO_INCREMENT",
			"varchar(250) NOT NULL",
			"varchar(250) NOT NULL",
			"date NOT NULL",
			"char(16) NOT NULL",
			"bigint  NOT NULL",
			"bigint NOT NULL",
			"bigint NOT NULL",
			"char(1)"
	);
	protected $viewAnaStudenti="viewAnaStudenti";
	/****************** anni scolastici ************************************/
	protected $tbAS="tbas";
	protected $en_as=array("anno"=>0,"stato"=>1);
	protected $campiAS=array("anno","attivo");
	protected $tipiAS=array("char(8) not null","int(1) default 0");
	/*************** stati studente  *************************/
	protected $tbStatiStudente="tbstatistudente";
	protected $en_statiStudente=array("idStatoStudente"=>0,"denominazione"=>1);
	protected $campiStatiStudente=array("idStatoStudente","denominazione");
	protected $tipiStatiStudente=array(
		"bigint NOT NULL AUTO_INCREMENT",
		"varchar(50)"
	);
	//protected $valStatiStudent="(1,'PROMOSSO'),(2,'SOSPESO'),(3,'BOCCIATO'),(4,'R2'),(5,'RITIRATO'),(6,'TRASPERITO')";
	protected $valStatiStudente=array('PROMOSSO'=>1,'SOSPESO'=>2,'BOCCIATO'=>3,'R2'=>4,'RITIRATO'=>5,'TRASFERITO'=>6);
	/*************** indirizzi  *************************/
	protected $tbIndirizzi="tbindirizzi";
	protected $en_Indirizzi=array("idIndirizzo"=>0,"denominazione"=>1,"codice"=>2,"nomeCorto"=>3);
	protected $campiIndirizzi=array("idIndirizzi","denominazione","codice","nomeCorto");
	protected $tipiIndirizzi=array(
		"bigint NOT NULL AUTO_INCREMENT",
		"varchar(50) not null",
		"char(10) not null",
		"char(3) not null"
	);
	protected $valIndirizzi=array('mm'=>'MM','in'=>'IN','el'=>'EL','tl'=>'TL','cs'=>'CS','cd'=>'CD');	
	/*************** classi  *************************/
	protected $tbClassi="tbclassi";
	protected $en_Classi=array("idClassi"=>0,"annoscolastico"=>3);
	protected $campiClassi=array("idClassi","annoCorso","sezione","annoscolastico");
	protected $tipiClassi=array(
		"bigint auto_increment",
		"int not null",
		"varchar(100) not null",
		"char(8) not null"
	);
	/*************** BES  *************************/
	protected $tbBES="tbbes";
	protected $campiBES=array("idBES","tipo");
	protected $en_BES=array("idBES"=>0,"tipo"=>1);
	protected $tipiBES=array(
		"bigint auto_increment",
		"varchar(100) not null"
	);
	protected $valBES=array('BES'=>1,'DSA'=>2,'HC'=>3);
	/*************** curricula  *************************/
	protected $tbCurricula="tbcurricula";
	protected $en_Curricula=array(
		"idCurricula"=>0,"fkanagrafica"=>1,"fkBES"=>2,
		"fkstatostudente"=>3,"fkclasse"=>4,"fkindirizzo"=>8
	);
	protected $campiCurricula=array(
		"idCurricula","fkAnagrafica","fkBES","fkstatostudente",
		"fkclasse","religione","richiesta","voto","fkIndirizzo"
	);
	protected $tipiCurricula=array(
		"bigint NOT NULL AUTO_INCREMENT",
		"bigint not null",
		"bigint not null",
		"bigint not null",
		"bigint not null",
		"char(2) default 'NO'",
		"varchar(250) default null",
		"int default null",
		"bigint not null"
	);
	/***********************************************************************/
	
	public function __construct(){
	}
	private function msgERR($s){
		return "<h4>$s</h4>";
	}
	protected function msg($d,$s){
		$s=str_replace("'","\'",$s);
		return "
			<script>
			document.getElementById('".$d."').innerHTML+='<h5>".str_replace(array("\n","\r"), "", $s)."</h5>';
			document.getElementById('".$d."').scrollTop=document.getElementById('".$d."').scrollHeight;
			</script>";
	}
	public function setLicenza($u){
			$this->licenza=$u;
	}
	public function setUser($u){
			$this->user=$u;
	}
	public function setPasswd($u){
			$this->passwd=$u;
	}
	public function getNomeTB($tb){return $this->schema.".".$tb;}
	public function getNomeTBAnagrafica(){
		return $this->schema.".".$this->tbAnagrafica;
	}
	public function getCampi($tb,$ar){
		$r=array();
		foreach($ar as $val){
			$r[]=$tb.".".$val;
		}
		return $r;
	}
	
	protected  function connetti(){
		if( $r=$this->leggiAutorizzazioni()  ){
			//echo $this->msg($msg,$r);
			return $r;
		}

		$this->conn = new mysqli($this->server,$this->utente,$this->passwd,$this->schema);
		if($this->conn->connect_error) {
    		return 'Errore di connessione (' . $this->conn->connect_errno . ') '
            . $this->conn->connect_error;
		}
		return "";
	}
	protected function chiudi(){
		$this->conn->close();
	}
	protected function leggiAutorizzazioni(){
		
		if( !file_exists($this->filename) ){
			return "licenza non esistente";
		}
		$handler = fopen($this->filename, 'r');

		if (false === $handler) {
			//echo "Impossibile aprire il file $this->filename" ;
			return "Impossibile leggere la licenza";
			
		}
		$size=10;
		$content = fread($handler, filesize($this->filename) );
		//echo $content;
		$content=$this->decripta($content,$this->key);
		//echo $content;
		$content=explode("-",$content);
		
		$this->utente=$content[0];
		$this->passwd=$content[1];
		$this->schema=$content[2];

		fclose($handler);
		
		return "";
	}

	protected function cripta($s,$key_enc){
		$met_enc = 'aes256';
		$iv = '9ua1R0iHLD56hG13'; 
		return openssl_encrypt($s, $met_enc, $key_enc, 0, $iv);
	}
	protected function decripta($s,$key_enc){
		$met_enc = 'aes256';
		$iv = '9ua1R0iHLD56hG13'; 
		return openssl_decrypt($s, $met_enc, $key_enc, 0, $iv);
	}
	
	protected function licenzia($post){
		
		echo $this->creaLicenza($post['txtUser'],$post['txtUserPSW']);
		
	}
	protected function creaTB($tb,$mezzo,$fine){
		$q= "
			create table if not exists ".$this->getNomeTB($tb)." (
				$mezzo,
				$fine
			)ENGINE=InnoDB
		";
		//return $q;
		
		$this->conn->query($q);
		if( $this->conn->error ){
			return $this->conn->error."<br>".$q;
		}
		
		return "";
	}
	protected function cctTB($cmp,$tipi){
		$r="";
		for($i=0;$i<count($cmp);$i++){
			$r.=$cmp[$i]." ".$tipi[$i].",";
		}
		return substr($r,0,strlen($r)-1);
	}
	protected function pk($pk){
		return "PRIMARY KEY ($pk)";
	}
	protected function uq($n,$uq){
		return "UNIQUE KEY ".$uq.$n."_UNIQUE ($uq)";
	}
	protected function fk($n,$fk,$tbfk,$idfk){
		return "
			CONSTRAINT FK_".$n.$fk."
			FOREIGN KEY ($fk) REFERENCES ".$this->getNomeTB($tbfk)."($idfk)
		";
		
	}
	protected function insertINTO($tb,$cmp,$val){
		$q= "
			replace into $tb( $cmp )
			values  $val 
		";

		$this->conn->query($q);
		if( $this->conn->error ){
			return $this->conn->error."<br>".$q;
		}
		
		return "";
	}
}
/***********************************************
$db=new CDB();
echo $db->installa("1234","root","passroot","utente","passutente");

/**********************************************/
?>
