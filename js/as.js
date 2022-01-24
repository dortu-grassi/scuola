/*****************************************************************
 * Questa classe è stata costruita da Daniele Ortu docente dell'itts "C.Grassi" di torino
 * email: daniele.ortu@itisgrassi.edu.it
********************************************************************/
function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
function creaAS(idiv,txtIn,txtOut){
	//alert("creaAS");
	
	var txtIn=document.getElementById(txtIn);
	var txtOut=document.getElementById(txtOut);
	var idiv=document.getElementById(idiv);
	
	//alert(idiv.innerText);
	
	if( isNaN(txtIn.value) ){
		alert("Inserisci un anno valido");
		return;
	}
	if( txtIn.value.length!=4 ){
		alert("l'anno scolastico deve contenere 4 numeri interi");
		return;
	}
	var param="txtas="+txtOut.value+txtIn.value;
	//alert(param)
	var xmlhttp = new XMLHttpRequest();
	//xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//alert(this.responseText);
			txtIn.value="";
			txtOut.value="";
			idiv.innerHTML=this.responseText;
		}
	};	
	xmlhttp.open("GET", "as.php?"+param, true);
	xmlhttp.send();
	//alert("anno valido: "+idiv.innerText);
}
function vai(){
	//alert("vai");
	var msg=document.getElementById('txtMessaggioDaCercare');
	if( msg.value=="configura" ){
		location.href="./inserisci.php";
		return;
	}
	var param="cerca="+msg.value;
	//alert(param);
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "ascolta.php", true);
	xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//document.getElementById(div).innerHTML = "prova ajax";
			//alert(this.responseText);
			document.getElementById("testoRisposta").value=this.responseText;
			msg.value="";
		}
	};	
	//alert(xmlhttp);
	xmlhttp.send(param);
}

function eventoOnKeyUP(txtIn,txtOut){
	//alert("evento onkeiUp");
	var txtIn=document.getElementById(txtIn);
	var txtOut=document.getElementById(txtOut);
	if( txtIn.value )
		txtOut.value=parseInt(txtIn.value)+1;
	else
		txtOut.value="";		
					
}

