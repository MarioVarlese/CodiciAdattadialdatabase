<?php 
	session_start();
	include_once 'confDatabase.php';
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	//Invio un Email contentente NickName, password in chiaro e link per confermare la registrazione. 
	$mail=$_SESSION['email'];
	$pass=$_SESSION['psw'];
	//Attraverso la funzione md5 che sfrutta l'algoritmo MD5 cripto un messaggio che dopo utilizzero
	$msgid = md5(time());
	$msgid = md5((($msgid-rand(1,5))+15)+rand(1000,5000));
	
	$sql= "UPDATE utenti SET CODICE_ATTIVAZIONE='".$msgid."' WHERE EMAIL='".$mail."' AND PWD=PASSWORD('".$pass."');";
	echo $sql;
	$result = $mysqli->query($sql);
	//mi servirà per mandare $msgid al loginborsa.php

	
	$to =$mail; 
	$toname = $_SESSION['nomeAZ']; 
	$subject = "Completa la tua registrazione"; 
	//MIME, Multiporpouse Internet Mail Extensions 
	//sono estensioni del formato originario con cui venivano inviati i messaggi di posta elettronica
	$boundary = "==BORSA DELLE IDEE=="; /*Separatore per il "multipart message"*/
	
	//versione del MIME
	$headers = "MIME-Version: 1.0\r\n"; 
	//multipart/alternative per indicare che il messaggio è costituito da più parti 
	//(&#147;multipart&#148;) le quali sono tra loro alternative (&#147;alternative&#148;). Separate dal BOUNDARY 
	$headers .= "Content-type: multipart/alternative; boundary='$boundary'\r\n"; 
	// costruiamo intestazione generale
	$headers .= "From: BorsaIdeeTeam4@gmail.com \r\n";  
	
	//Costruisco il messaggio html
	$html_msg .= "Questi sono i dati della tua registrazione: \r\n"; 
	$html_msg .= "Username: " . $mail . "\r\n "; 
	$html_msg .= "Password: " . $pass . "\r\n";  
	
	//Messaggio di conferma
	$confirmmessage = "Salve,\n\n"; 
	$confirmmessage .= "per completare la registrazione della tua azienda devi cliccare sul link sottostante:\n\n"; 
	$confirmmessage .= $html_msg . "\n\n"; 
	// confirm_reg.php 
	$confirmmessage .= "http://borsaidee.altervista.org/index.php" . 
	  "?id=$msgid"."\r\n"; 
	
	//questa parte del messaggio viene visualizzata
	// solo se il programma non sa interpretare
	// i MIME poiché è posta prima della stringa boundary
	 
	$message .= "--$boundary\n"; 
	
	//la codifica con cui viene trasmesso il contenuto.
	$message .= $confirmmessage . "\n"; 
	$message .= "--$boundary--";
	
	//invio mail
	$mailsent = mail($to, $subject, $message, $headers); 
	//controllo 
	if ($mailsent) 
	{ 
	  echo "<body style='background-color:#8080ff; font-family: monospace; font-size: x-large; color: white; text-align: center;'>
	  		Salve,<br>"; 
	  echo "Un messaggio &egrave stato inviato all'indirizzo <b>" . $mail . "</b> da te fornito.<br><br>"; 
	  echo "IMPORTANTE:<br>"; 
	  echo "Per completare la registrazione al sito devi aprire la tua casella e-mail, leggere il messaggio di conferma e cliccare sul link che troverai all'interno.<br><br>Tra pochi secondi verrai reindirizzato alla pagina di login</body>"; 
	  // reindirizzamento a tempo
	  header( "refresh:10;index.php" );
	}
	 else { 
	  	echo "<body style='background-color:#8080ff; font-family: monospace; font-size: x-large; color: white; text-align: center;'>
	  			Errore durante l'invio dell'e-mail. 
	  		</body>"; 
	  	header( "refresh:10;index.php" );
	} 
?>