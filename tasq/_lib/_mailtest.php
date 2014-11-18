<? require "_init.php" ?>
<?

$method_hash = array (
	1 => "тупо через mail()",
	2 => "умно через mail()",
	3 => "html_mime_mail_2.5",
);

$method = get_number("method");
if ($method == 0) $method = 1;

$smtp_apply = (get_string("smtp_apply") == "on") ? 1 : 0;


$markers_hash = array (
	"from" => "jujik@yahoo.com",
	"email" => "jujik@yahoo.com",
	"subject" => "тема письма русские буквы",
	"body" => "текст письма",
	"submit_HTMLrow" => "<tr><td align=right><input type='submit' value='отправить письмо'></td></tr>",
	"method" => $method,
	"method_select" => select_hash("method", $method, $method_hash),
	"smtp" => ini_get("SMTP"),
	"smtp_port" => 25,
	"smtp_apply_checked" => ($smtp_apply == 1) ? "checked" : "",
	"method" => $method,
);

$markers_hash = gethash_bytplhash($markers_hash, 0);
//pre($markers_hash);



$smtp_line = "";
if ($mode == "update") {
//	$alertmsg = send_mtpl("ASKME", $markers_hash, $markers_hash["email"]);
	$alertmsg = send_mtpl("ASKME", $markers_hash, $markers_hash["email"]);
	
	$from = $markers_hash["from"];
	$email = $markers_hash["email"];
	$subject = $markers_hash["subject"];
	$body = $markers_hash["body"];
	$method = $markers_hash["method"];

	$is_sent = false;

	$subject .= " [" . $method_hash[$method] . "]";
	$body .= "<hr>метод отправки: " . $method_hash[$method];

	if ($smtp_apply == 1) {
		ini_set("SMTP", $markers_hash["smtp"]);
		ini_set("smtp_port", $markers_hash["smtp_port"]);
		ini_set("sendmail_from", $from);
	}

	$smtp_line .= "<br>smtp_apply=[" . $smtp_apply . "]<br>"
		 . "SMTP=[" . ini_get("SMTP") . "]<br>"
		 . "smtp_port=[" . ini_get("smtp_port") . "]<br>"
		 . "sendmail_from=[" . ini_get("sendmail_from") . "]<br>";
	
	$body .= $smtp_line;

	switch ($method) {
		case 3:
			$is_sent = send_html_mime_mail($from, $email, $subject, $body);
			break;

		case 2:
			$is_sent = sendmail($from, $email, $subject, $body);
			break;

		case 1:
			$is_sent = mail($email, $subject, $body);
			break;

	}

	$errormsg = ($is_sent) ? "письмо отправлено" : "письмо не отправлено - ошибка";
}



$tpl = <<< EOT
<table cellpadding=3>
<form method=get name=form_edit id=form_edit>
<input type=hidden name=mode value=update>

	<tr valign=top>
	<td>
	<table cellpadding=3>

<tr><td>
	<b>Email отправителя</b><br>
  	<input type="text" size="44" id="from" name="from" value="#FROM#">
</td></tr>

<tr><td>
	<b>Email получателя</b><br>
	<input type="text" size="44" id="email" name="email" value="#EMAIL#">
</td></tr>

<tr><td>
	<b>Тема</b><br>
  	<input type="text" size="44" id="email" name="subject" value="#SUBJECT#">
</td></tr>

<tr><td>
	<b>Тело письма</b><br>
  	<textarea rows=9 cols=44 name="body">#BODY#</textarea>
</td></tr>


#SUBMIT_HTMLROW#


	</table>
	</td><td width=20></td><td>
	<table cellpadding=3>

<tr><td>
	<b>Способ отправки</b><br>
  	#METHOD_SELECT#
</td></tr>

<tr><td height=40></td><tr>

<tr><td>
	<b>Хост SMTP</b><br>
  	<input type="text" size="20" id="smtp" name="smtp" value="#smtp#">
</td></tr>

<tr><td>
	<b>Порт SMTP</b><br>
  	<input type="text" size="20" id="smtp_port" name="smtp_port" value="#SMTP_PORT#">
</td></tr>

<tr><td>
	 <input type="checkbox" id="smtp_apply" name="smtp_apply" #SMTP_APPLY_CHECKED#>
	 <label for=smtp_apply><b>Применять SMTP</b></label>
</td></tr>

<tr><td><p>$smtp_line</p></td></tr>


</form>

	</table>
	</td>
	</tr>
</table>
EOT;

$table = hash_by_tpl($markers_hash, $tpl);

//jsv_addvalidation("JSV_TF_CHAR", "cname", "Фамилия, имя, отчество");
//jsv_addvalidation("JSV_TF_EMAIL", "email", "Ваш Email");
//jsv_addvalidation("JSV_TF_CHAR", "comment", "Ваш вопрос");

?>


<html>
<head>
  <title><?=$site_name?> <?=$pagetitle_separator?> Бэкоффис</title>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
  <link href="../default.css" type="text/css" rel="stylesheet">
  <script src="../script.js" type="text/javascript"></script>

<? if ($alertmsg != "") { ?>
<script>alert('<?=$alertmsg?>')</script>
<? } ?>

</head>

<body>

<table cellpadding=0 cellspacing=20 border=0 align=center>
<tr valign=top>
	<td>

<?=$table?>

<p><?=$errormsg?></p>


<p align=right><a href=_mailtest.php>перечитать форму без отправки</a></p>


	</td>
</tr>
</table>



<p align=right>[<?= round(getmicrotime() - $start_execution_time, 2) ?> sec]</p>

<? if ($layers_total > 0) echo "<script>layers_total=$layers_total</script>" ?>

</body>
</html>