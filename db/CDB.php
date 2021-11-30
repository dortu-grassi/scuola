<?php
class CDB{
	private $server="localhost";
	private $uroot="";
	private $proot="";
	private $utente="";
	private $passwd="";
	private $licenza="";
	protected $schema="dbscuola";
	protected $conn;
	private $filename = 'auto/licenza.txt';
	private $key="A82DD517";
	
	/*************** nazioni *************************/
	protected $tbNazioni="tbtbNazioni";
	protected $en_campiNazioni=array(
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
	protected $campiComuni=array("idComune","istat","denominazione","provincia","regione","prefisso","CAP","codFiscale");
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
	/*************** anagrafica *************************/
	protected $tbAnagrafica="tbAnagrafica";
	public $campiAnagrafica=array("idAnagrafica","nome","cognome");
	protected $tipiAnagrafica=array("bigint","varchar(250)","varchar(250)");
	protected $dimAnagrafica=array("","(250)","(250)");
	/***********************************************************************/
	
	public function __construct(){
	}
	private function msgERR($s){
		return "<h4>$s</h4>";
	}
	private function msg($d,$s){
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
	private function getNomeTB($tb){return $this->schema.".".$tb;}
	public function getNomeTBAnagrafica(){
		return $this->schema.".".$this->tbAnagrafica;
	}
	public function getCampi($ar){
		$r=array();
		foreach($ar as $val){
			$r[]=$this->tbAnagrafica.".".$val;
		}
		return $r;
	}
	
	protected  function connetti(){
		$this->conn = new mysqli($this->server,$this->utente,$this->passwd,$this->schema);
		if($this->conn->connect_error) {
    		return 'Errore di connessione (' . $this->conn->connect_errno . ') '
            . $this->conn->connect_error;
		}
		return "";
	}
	private function chiudi(){
		$this->conn->close();
	}
	private function leggiAutorizzazioni(){
		
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
	private function cripta($s,$key_enc){
		$met_enc = 'aes256';
		$iv = '9ua1R0iHLD56hG13'; 
		return openssl_encrypt($s, $met_enc, $key_enc, 0, $iv);
	}
	private function decripta($s,$key_enc){
		$met_enc = 'aes256';
		$iv = '9ua1R0iHLD56hG13'; 
		return openssl_decrypt($s, $met_enc, $key_enc, 0, $iv);
	}
	
	public function licenzia($post){
		
		echo $this->creaLicenza($post['txtUser'],$post['txtUserPSW']);
		
	}
	private function creaTB($tb,$mezzo,$fine){
		$q= "
			create table ".$this->getNomeTB($tb)." (
				$mezzo,
				$fine
			)ENGINE=InnoDB
		";
		return $q;
		
		$this->conn->query($q);
		if( $this->conn->error ){
			return $this->conn->error;
		}
		
		return "";
	}
	private function cctTB($cmp,$tipi){
		$r="";
		for($i=0;$i<count($cmp);$i++){
			$r.=$cmp[$i]." ".$tipi[$i].",";
		}
		return substr($r,0,strlen($r)-1);
	}
	private function pk($pk){
		return "PRIMARY KEY ($pk)";
	}
	private function uq($n,$uq){
		return "UNIQUE KEY ".$uq.$n."_UNIQUE ($uq)";
	}
	
	public function installa($post,$msg){
		if( $r=$this->leggiAutorizzazioni()  ){
			echo $this->msg($msg,$r);
			return;
		}
		//echo  $this->msgERR($this->utente."-".$this->passwd);
		//return;
		
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
		
		$mezzo=$this->cctTB($this->campiNazioni,$this->tipiNazioni);
		$fine=$this->pk($this->campiNazioni[0]).",";
		$fine.=$this->uq(0,$this->campiNazioni[$this->en_campiNazioni["codiceStato"]]);
		
		echo $this->msg($msg,$this->creaTB($this->tbNazioni,$mezzo,$fine)); 
		sleep(1);
		return;
		echo $this->msg($msg,"g222222");
		
		sleep(1);
		echo $this->msg($msg,"p3");
		sleep(1);
		echo $this->msg($msg,"p3");
		sleep(1);
		echo $this->msg($msg,"p3");
		sleep(1);
		echo $this->msg($msg,"p5");
		
		//$this->scriviAutorizzazioni();
		//return $this->leggiAutorizzazioni();
			
	}
}
/***********************************************
$db=new CDB();
echo $db->installa("1234","root","passroot","utente","passutente");

/**********************************************/
?>
