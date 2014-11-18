<? require_once "html_mime_mail_2.5/htmlMimeMail.php" ?>
<?

function system_debugging($content) {
	$mailto = select_field("content", array("hashkey" => "MAILTO_SYSTEM_DEBUGGING"), "constant");
	
	$url = $_SERVER["SCRIPT_NAME"];
	if (isset($_SERVER["QUERY_STRING"])) {
		$query_string = $_SERVER["QUERY_STRING"];
		$url = $url . "?" . $query_string;
	}

	$mailed_hash = array (
		  "CONTENT" => $content
		, "URL" => $url
		, "GET" => pr($_GET)
		, "POST" => pr($_POST)
		);

	send_tpl("SYSEM_DEBUGGING", $mailto, $mailed_hash);
}


function send_mtpl($mtpl_hashkey, $markers_hash = array(),
		$client_to = "", $replyto_admcopy = "",
		$mtpl_table = "mtpl", $attm = array()) {

	global $errormsg, $is_sent, $mail_relyon_issent, $entity, $id, $admtail;
	$ret = "";

	$row = select_entity_row(array("hashkey" => $mtpl_hashkey), $mtpl_table);
//	pre($row);
	if (count($row) > 0) {
		$subject = stripslashes($row["subject"]);
		$body = stripslashes($row["body"]);
//		$body = tpl_replace($body);
		$rcptto = stripslashes($row["rcptto"]);
		if ($rcptto == "") $rcptto = $replyto_admcopy;

		$admtail = stripslashes($row["admtail"]);
		if ($admtail == "") $admtail = "<p>#CLIENT_SENTMSG#</p>";


		// <p><a href="http://#HTTP_HOST#/backoffice/#ENTITY#-edit.php?id=#ID#" target=_blank>Написать ответ</a></p>
		if (!isset($markers_hash["http_host"])) $markers_hash["http_host"] = $_SERVER["HTTP_HOST"];
		if (!isset($markers_hash["entity"])) $markers_hash["entity"] = $entity;
		if (!isset($markers_hash["id"])) $markers_hash["id"] = $id;


		$admtail = hash_by_tpl($markers_hash, $admtail);

		$sentmsg = stripslashes($row["sentmsg"]);
		$sentmsg = hash_by_tpl($markers_hash, $sentmsg);


		if (is_array($markers_hash) && count($markers_hash) > 0) {
			$subject = hash_by_tpl($markers_hash, $subject);
			if (!isset($markers_hash["mtpl_subject"])) $markers_hash["mtpl_subject"] = $subject;
			$body = hash_by_tpl($markers_hash, $body);
		}
		$body = htmlize($body);

//		echo " send_tpl ";

		$markers_hash["client_sentmsg"] = "Клиент не указал email, ему не отправлено";
		if ($client_to != "") {
			$is_sent = sendmail($client_to, $subject, $body, $attm, $replyto_admcopy);
			$ret = $sentmsg;		// 1108 v2
			if ($rcptto != "" && $is_sent == FALSE && $mail_relyon_issent == 1) {
				$ret = "send_mtpl($mtpl_hashkey)\\nошибка при отправке сообщения по адресам:\\n$client_to";
			}
			$markers_hash["client_sentmsg"] = $errormsg;
		}

		if ($rcptto == "") {
			if ($client_to == "") {
				$ret = "send_mtpl($mtpl_hashkey)\\nПустой список получателей";
			}
		} else {
			$body .= hash_by_tpl($markers_hash, $admtail);

//			if ($replyto_admcopy != "") sendmail($replyto_admcopy, $subject, $body, $attm, $client_to);

			$is_sent = sendmail($rcptto, $subject, $body, $attm, $client_to);
			$ret = $sentmsg;
		
			if ($is_sent == FALSE && $mail_relyon_issent == 1) {
				$ret = "send_mtpl($mtpl_hashkey)\\nошибка при отправке сообщения по адресам:\\n$rcptto\\n$errormsg";
			}
		}
	} else {
		$ret = "шаблон письма [$mtpl_hashkey] не найден; письмо не отправлено";
	}

	return $ret;
}

function send_tpl($tpl, $markers_hash = array()
		, $to, $replyto = "", $admtail = ""
		, $mtpl_table = "mtpl", $attm = array()) {

	global $errormsg, $entity, $id;
	$ret = 0;

	if ($to == "") return $ret;

	$row = select_entity_row(array("hashkey" => $tpl), $mtpl_table);
	if (count($row) > 0) {
		$subject = stripslashes($row["subject"]);
		$body = stripslashes($row["body"]);
//		$body = tpl_replace($body);

		if ($replyto == "" && isset($row["rcptto"])) $replyto = $row["rcptto"];
		if ($admtail == "" && isset($row["admtail"])) $admtail = $row["admtail"];

		if (is_array($markers_hash) && count($markers_hash) > 0) {

			if (!isset($markers_hash["http_host"])) $markers_hash["http_host"] = $_SERVER["HTTP_HOST"];
			if (!isset($markers_hash["entity"])) $markers_hash["entity"] = $entity;
			if (!isset($markers_hash["id"])) $markers_hash["id"] = $id;

			$subject = hash_by_tpl($markers_hash, $subject);
			$body = hash_by_tpl($markers_hash, $body);
			if ($admtail != "") $body .= hash_by_tpl($markers_hash, $admtail);
		}
		$body = htmlize($body);

//		echo " send_tpl ";
		$ret = sendmail($to, $subject, $body, array(), $replyto);
	}

	return $ret;
}

function tpl_replace($body) {
	$body = preg_replace ("/#(\w+)#/e", 
		"\$GLOBALS[strtolower('$1')]", 
//		"strtolower('$1')", 
		$body);
	
	return $body;
}

function htmlize($body) {
	if (strlen(strip_tags($body)) == strlen($body)) {
		$body = nl2br($body);
//		echo $body;
	} else {
//		echo "raw-length: " . strlen($body) . "<br>";
//		echo "stripped-length: " . strlen(strip_tags($body)) . "<br>";

//		echo "raw-body: [" . $body . "]<br>";
//		echo "stripped-body: [" . strip_tags($body) . "]<br>";
	}
//	$body = str_replace("\r\n", "<br>", $body);

	return $body;
}

function sendmail($to, $subject, $body, $attm = array(), $reply_to = "") {
	global $debug_sendmail, $errormsg, $mail_from, $mail_visor, $mail_sendSMTP, $mail_visor_debug;
	
	$hdrs  = "MIME-Version: 1.0\r\n";
	$hdrs .= "Content-type: text/html; charset=windows-1251\r\n";
	$hdrs .= "From: $mail_from\r\n";
	
	$to = str_replace("&lt;", "<", $to);
	$to = str_replace("&gt;", ">", $to);

	$to_htmlized = $to;

	$to_htmlized = str_replace("<", "&lt;", $to_htmlized);
	$to_htmlized = str_replace(">", "&gt;", $to_htmlized);

	$is_sent = true;
	if ($mail_sendSMTP == 1) {
		if ($reply_to == "") $reply_to = $mail_from;
//		$is_sent = mail($to, $subject, $body, $hdrs);
		$is_sent = send_multipart($mail_from, $to, $subject, $body, $body, $attm, $reply_to);
//		$is_sent = send_html_mime_mail($mail_from, $to, $subject, $body, $attm, $reply_to);
	} else {
		$errormsg .= "STUB: ";
	}

	if ($debug_sendmail == 1) {
		if ($errormsg != "") $errormsg .= "; ";
		if ($is_sent) $errormsg .= "Сообщение отправлено на [$to_htmlized]";
		else $errormsg .= "ОШИБКА при отправлении сообщения на $to_htmlized";
	}

	if ($mail_visor != "" && $mail_visor_debug == 1) {
		if ($mail_sendSMTP == 1) {
			$body_visor = $body . "<br><br> Отправлено по адресу: " . $to_htmlized;
//			mail($mail_visor, $subject, $body_visor, $hdrs);
			$is_sent = send_multipart($mail_from, $mail_visor, $subject, $body_visor, $body_visor, $attm);
//			$is_sent = send_html_mime_mail($mail_from, $mail_visor, $subject, $body_visor, $attm);
			if (!$is_sent) $errormsg .= "VISOR_SEND_ERROR $mail_visor; ";

		} else {
			echo "TO[$to] REPLY_TO[$reply_to] SUBJECT[$subject] BODY[$body]";
		}
	}

	return $is_sent;	
}

function send_html_mime_mail($from, $to, $subject = "empty subject", $html = "empty html mail body", $attmfiles = array(), $reply_to = "") {
	$mail = new htmlMimeMail();
	
	$mail->setFrom($from);

	if (ini_get("SMTP") != "") {
		$mail->setSMTPParams(ini_get("SMTP"), 25, $_SERVER["HTTP_HOST"]);
	}
	
	if ($reply_to == "") $reply_to = $from;
	$mail->setReturnPath($reply_to);

	$mail->setSubject($subject);
//	$mail->setHeader('X-Mailer', 'HTML Mime mail class (http://www.phpguru.org)');

	for($i=0; $i<count($attmfiles); $i++) {
		$content_type = "";
		$abspath = $attmfiles[$i];
		$relname = basename($abspath);

		$mail->addAttachment($abspath, $relname, $content_type);
	}
	
	$mail->setHtml($html, make_txt_from_html ($html));
	
	$build_params = array (
	    "html_encoding" => "quoted-printable",
    	"text_encoding" => "8bit",
		"html_charset" => "windows-1251",
		"text_charset" => "windows-1251",
		"head_charset" => "windows-1251"
	);
	$mail->buildMessage($build_params);

	$is_sent = $mail->send(array($to));
	if (!$is_sent && isset($mail->errors)) {
		print_r($mail->errors);
	} else {
//		echo 'Mail sent!';
	}

	return $is_sent;
} 


function make_txt_from_html ($body) {
	$body_astext = $body;
	$body_astext = preg_replace("~(.*)(<head>.*</head>)(.*)~si", "\\1\\3", $body_astext);
	$body_astext = preg_replace("~(.*)(body {.*})(.*)~si", "\\1\\3", $body_astext);	
	$body_astext = preg_replace("~<br[^>]*?>~i", "\n", $body_astext);
	$body_astext = strip_tags($body_astext);
	$body_astext = str_replace("&nbsp;", " ", $body_astext);
	$body_astext = trim($body_astext);
	return $body_astext;
}

function send_multipart($From,$To,$Subject,$Text,$Html,$AttmFiles = array(), $reply_to = ""){
 $OB="----=_OuterBoundary_000";
 $IB="----=_InnerBoundery_001";
 $Html=$Html?$Html:preg_replace("/\n/","{br}",$Text) 
  or die("neither text nor html part present.");
 $Text=$Text?$Text:"Sorry, but you need an html mailer to read this mail.";
 $From or die("sender address missing");
 $To or die("recipient address missing");
    
 $headers ="MIME-Version: 1.0\r\n"; 
 $headers.="From: ".$From."\n"; 
// $headers.="To: ".$To."\n";
	if ($reply_to == "") $reply_to = $From;
 $headers.="Reply-To: ".$reply_to."\n"; 
// $headers.="X-Priority: 1\n"; 
// $headers.="X-MSMail-Priority: High\n"; 
// $headers.="X-Mailer: My PHP Mailer\n"; 
 $headers.="Content-Type: multipart/mixed;\n\tboundary=\"".$OB."\"\n";

 //Messages start with text/html alternatives in OB
 $Msg ="This is a multi-part message in MIME format.\n";
 $Msg.="\n--".$OB."\n";
 $Msg.="Content-Type: multipart/alternative;\n\tboundary=\"".$IB."\"\n\n";

 //plaintext section 
 $Msg.="\n--".$IB."\n";
 $Msg.="Content-Type: text/plain;\n\tcharset=\"windows-1251\"\n";
 $Msg.="Content-Transfer-Encoding: quoted-printable\n\n";
 // plaintext goes here
 $Msg.=$Text."\n\n";

 // html section 
 $Msg.="\n--".$IB."\n";
 $Msg.="Content-Type: text/html;\n\tcharset=\"windows-1251\"\n";
 $Msg.="Content-Transfer-Encoding: base64\n\n";
 // html goes here 
 $Msg.=chunk_split(base64_encode($Html))."\n\n";

 // end of IB
 $Msg.="\n--".$IB."--\n";

 // attachments
 if(is_array($AttmFiles) && count($AttmFiles) > 0){
  foreach($AttmFiles as $AttmFile){
   $patharray = explode ("/", $AttmFile); 
   $FileName=$patharray[count($patharray)-1];
   $Msg.= "\n--".$OB."\n";
   $Msg.="Content-Type: application/octetstream;\n\tname=\"".$FileName."\"\n";
   $Msg.="Content-Transfer-Encoding: base64\n";
   $Msg.="Content-Disposition: attachment;\n\tfilename=\"".$FileName."\"\n\n";
            
   //file goes here
   $fd=fopen ($AttmFile, "r");
   $FileContent=fread($fd,filesize($AttmFile));
   fclose ($fd);
   $FileContent=chunk_split(base64_encode($FileContent));
   $Msg.=$FileContent;
   $Msg.="\n\n";
  }
 }
    
 //message ends
 $Msg.="\n--".$OB."--\n";
 $is_sent = mail($To,$Subject,$Msg,$headers);    
 //syslog(LOG_INFO,"Mail: Message sent to $ToName <$To>");
 return $is_sent;
} 

?>