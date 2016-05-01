<?php
session_start();
//include_once 'configurazioneDB.php';
include_once 'confDatabase.php';
$db = Database::getInstance();
$mysqli = $db->getConnection();

$email=$_REQUEST['email'];
$_SESSION['email']=$email; //mi servira per la confermaReg.php
$password=$_REQUEST['psw'];
$_SESSION['psw']=$password; //mi servira per la confermaReg.php
$nomeAZ=$_REQUEST['nomeAZ'];
$_SESSION['nomeAZ']=$nomeAZ;  //mi servira per la confermaReg.php
$Ncivico=$_REQUEST['Nciv'];
$nazione=$_REQUEST['nazione'];
$regione=$_REQUEST['regione'];
$facebook=$_REQUEST['sitowebfb'];
$twitter=$_REQUEST['sitowebtw'];
$linkedin=$_REQUEST['sitoweblk'];
$cf=$_REQUEST['cf'];
$indirizzo=$_REQUEST['indirizzo'];
$cap=$_REQUEST['cap'];
$province=$_REQUEST['provincia'];
$telefono=$_REQUEST['telefono'];
$fax=$_REQUEST['fax'];
$cell=$_REQUEST['telefono2'];
$sitoweb=$_REQUEST['sitoweb'];
$parlaci=$_REQUEST['parlaci'];
$citta=$_REQUEST['citta'];
//qui aggiungo gli escape alla domanda per evitare errori
$domanda=mysql_escape_string($parlaci);


//aggiungo nella tabella utenti
//INSERT INTO utenti (EMAIL, PWD, ONLINE, ATTIVO, TIPO) VALUES ...
$query=" insert into utenti (EMAIL, PWD, ONLINE, ATTIVO, TIPO) values('".$email."','".$password."',0 , 0 , 'azienda')";
echo "LA query: ".$query."         <br/> ";
//$result=mysqli_query($conn,$query);
$result = $mysqli->query($query);
if($result===false){
	$mysqli->close();
	header("Location: REGISTRAZIONE2.php?errore=1");//email già esistente
	exit;
}
$query="SELECT ID FROM utenti WHERE tipo='azienda' AND EMAIL='".$email."'";
echo "LA query: ".$query."         <br/> ";
//$result=mysqli_query($conn,$query);
$result = $mysqli->query($query);
$idUtente= $result->fetch_assoc();

//inserisco in province
if($province!=""){
	$query="select CODICE from province where NOME='".$province."';";
	echo "LA query: ".$query."         <br/> ";
	//$result=mysqli_query($conn,$query);
	$result = $mysqli->query($query);
	}
else{
		$query="select CODICE from province where NOME IS NULL;";
		echo "LA query: ".$query."         <br/> ";
		//$result=mysqli_query($conn,$query);
		$result = $mysqli->query($query);
	}
	$idProv= $result->fetch_assoc();
	//$idProv=mysqli_fetch_assoc($result);

// aggiungo nella tabella azienda
$query="INSERT INTO aziende VALUES ('".$idUtente['ID']."','".$cf."','".$nomeAZ."','".$indirizzo."','".$Ncivico."','".$cap."','".$citta."','".
		$nazione."','".$regione."','".$idProv['CODICE']."', DEFAULT";

if($parlaci!=="")
	$query=$query.", '".$paraci."');";
else if($parlaci=="")
	$query=$query.", NULL);";
echo "LA query: ".$query."         <br/> ";
//$result=mysqli_query($conn,$query);
$result = $mysqli->query($query);
if($result===false){
	$query="DELETE FROM utenti WHERE ID='".$idUtente."';";
	//$result=mysqli_query($conn,$query);
	$result = $mysqli->query($query);
	//mysqli_close($conn);
	$mysqli->close();
	header("Location: REGISTRAZIONE2.php?errore=2");//errore
	exit;
}
$query="INSERT INTO contatti VALUES(NULL,'".$telefono."'";
if($fax!=="")
	$query=$query.", '".$sitoweb."'";
else if($fax=="")
	$query=$query.", NULL";
if($cell!=="")
	$query=$query.", '".$cell."'";
else if($cell=="")
	$query=$query.", NULL";
if($sitoweb!=="")
	$query=$query.", '".$sitoweb."'";
else if($sitoweb=="")
	$query=$query.", NULL";
if($facebook!=="")
	$query=$query.", '".$facebook."'";
else if($facebook=="")
	$query=$query.", NULL";
if($twitter!=="")
	$query=$query.", '".$twitter."'";
else if($twitter=="")
	$query=$query.", NULL";
if($linkedin!=="")
	$query=$query.", '".$linkedin."'";
else if($linkedin=="")
	$query=$query.", NULL";

$query=$query.", '".$idUtente['ID']."');";
echo "LA query: ".$query."         <br/> ";
//$result=mysqli_query($conn,$query);
$result = $mysqli->query($query);
if($result===false){
	//mysqli_close($conn);
	$mysqli->close();
	header("Location: REGISTRAZIONE2.php?errore=2");//errore
	exit;
}
$mysqli->close();
//mysqli_close($conn);
//Vado alla pagina php di conferma registrazione
header("Location: ConfermaReg.php");


?>