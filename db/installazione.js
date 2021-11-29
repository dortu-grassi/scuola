//alert("installazione.js");
/****************************************/
function installa(idFRM,txtUser,txtPSW,msg){
	var frm=document.getElementById(idFRM);
	frm.submit();
}
/**************************************
function installa(idFRM,txtUser,txtPSW,msg){
	//alert("installa");
	var frm=document.getElementById(idFRM);
	var u=document.getElementById(txtUser).value;
	var p=document.getElementById(txtPSW).value;
	var m=document.getElementById(msg).value;
	
	frm.innerHTML="";
	
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "installazione.php", true);
	xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			frm.innerHTML=this.responseText;
		}
	};	
	var param=txtUser+"="+u;
	param+="&"+txtPSW+"="+p;
	alert(param);
	xmlhttp.send(param);
	//xmlhttp.send(param);

}
/********************************
function installa(idFRM){
	for (var i = 0; i < index.length; i++) {

		var url = "https://wind-bow.glitch.me/twitch-api/channels/" + index[i];

		let request = new XMLHttpRequest();
		request.open("GET", url);
		request.onreadystatechange = function() {
			if(request.readyState === XMLHttpRequest.DONE && request.status === 200) {
				var data = JSON.parse(request.responseText);
				console.log('-->' + data._id);
			}
		}
		request.send();
	}
}
**************************************/
