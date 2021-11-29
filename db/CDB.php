<?php
class CDB{
	private $server="localhost";
	private $uroot="";
	private $proot="";
	private $utente="";
	private $passwd="";
	private $licenza="";
	protected $schema="dbgriglie";
	protected $conn;
	private $filename = 'auto/licenza.txt';
	private $key="A82DD517";
	
	/*************** anagrafica *************************/
	protected $tbAnagrafica="tbAnagrafica";
	public $campiAnagrafica=array("idAnagrafica","nome","cognome");
	protected $tipiAnagrafica=array("bigint","varchar(250)","varchar(250)");
	protected $dimAnagrafica=array("","(250)","(250)");
	
	public function __construct(){
	}
	private function msgERR($s){
		return "<h4>$s</h4>";
	}
	private function msg($d,$s){
		return "
			<script>
			document.getElementById('".$d."').innerHTML+='<h5>".$s."</h5>';
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

		fclose($handler);
		
		return "";
	}

	private function creaLicenza($u,$p){
		$handler = fopen($this->filename, 'w');

		if (false === $handler) {
			return "Impossibile scrivere il file $this->filename";
		}
		$en=$u."-".$p;
		$en=$this->cripta($en,$this->key);
		fwrite($handler, $en);
		fclose($handler);
		return "";
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
		
		echo $this->msg($msg,"p1");
		
		sleep(1);
		echo $this->msg($msg,"p2");
		
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
