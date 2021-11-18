<?php
class CDB{
	private $server="localhost";
	private $uroot="";
	private $proot="";
	private $utente="misterno";
	private $passwd="OrtuOrtu66";
	protected $schema="dbgriglie";
	protected $conn;
	private $filename = 'auto/file.txt';
	
	/*************** anagrafica *************************/
	protected $tbAnagrafica="tbAnagrafica";
	public $campiAnagrafica=array("idAnagrafica","nome","cognome");
	protected $tipiAnagrafica=array("bigint","varchar(250)","varchar(250)");
	protected $dimAnagrafica=array("","(250)","(250)");
	
	public function __construct(){
	}
	
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
	public function leggiAutorizzazioni(){
		
		$handler = fopen($this->filename, 'r');

		if (false === $handler) {
			return "Impossibile aprire il file $this->filename" ;
			
		}
		$size=10;
		$content = fread($handler, filesize($this->filename) );
		fclose($handler);
		
		return $content;
	}

	private function scriviAutorizzazioni(){
		$handler = fopen($this->filename, 'w');

		if (false === $handler) {
			return "Impossibile scrivere il file $this->filename";
		}
		$en=$this->uroot."-".$this->proot."-".$this->utente."-".$this->passwd;
		fwrite($handler, $en);
		fclose($handler);
	}
	private function cripta($s,$key_enc){
		$met_enc = 'aes256';
		$iv = '9ua1R0iHLD56hG13'; 
		return openssl_encrypt($s, $met_enc, $key_enc, 0, $iv);
	}
	/*
	public function installa($chiave){
	
	}
	*/
	public function installa($chiave,$uroot,$pswRoot,$user,$passwd){
		$this->uroot="$uroot";
		$this->proot=$pswRoot;
		$this->utente=$user;
		$this->passwd=$passwd;
		
		$this->scriviAutorizzazioni();
		return $this->leggiAutorizzazioni();
			
	}
}
/***********************************************
$db=new CDB();
echo $db->installa("1234","root","passroot","utente","passutente");

/**********************************************/
?>
