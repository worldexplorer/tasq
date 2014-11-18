<? require_once "../_lib/_init.php" ?>
<?

$top = $top_href_suffix = $top_href = "";
$bottom = $bottom_href_suffix = $bottom_href = "";
$href_approve_tpl = $href_remove_tpl = "";


$row = select_entity_row();


$marker_atoms_hash = array(
	"http_host" => $_SERVER["HTTP_HOST"],
	"email" => $mail_visor,
);


$hashkey = $row["hashkey"];

$markers_tpl_hash = array (
	"user_name" => $mail_visor,
	"http_host" => $_SERVER["HTTP_HOST"],
	"email" => $mail_visor,
	"mmenu_id" => 0,
);


$markers_hash = array();
foreach ($markers_tpl_hash as $key => $value) {
	$markers_hash[$key] = hash_by_tpl($marker_atoms_hash, $value);
}
//pre($markers_hash);


$body = $row["body"];
$body = hash_by_tpl($markers_hash, $body);
$body = str_replace("/upload/", "http://" . $_SERVER["HTTP_HOST"] . "/upload/", $body);
$row["body"] = stripslashes($body);

$row["from"] = $row["rcptto"];
$row["to"] = htmlentities($mail_visor);

$attm_html = "";

$tpl = <<< EOT
<table cellpadding=3 cellspacing=8 width=100%>
<tr>
	<td align=right><b>From</b></td>
	<td style="border: 1px solid gray">#FROM#</td>
</tr>

<tr>
	<td align=right><b>To</b></td>
	<td style="border: 1px solid gray">#TO#</td>
</tr>

<tr>
	<td align=right><b>Subject</b></td>
	<td style="border: 1px solid gray">#SUBJECT#</td>
</tr>

$attm_html

<tr><td><a name=body></a>&nbsp;</td></tr>

<tr valign=top>
	<td align=right><b>Body</b></td>
	<td style="border: 1px solid gray">#BODY#</td>
</tr>

</table>
EOT;

$table = hash_by_tpl($row, $tpl);

?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>

<body>
<!--p><a href="javascript:window.close()" >закрыть</a></p-->


<?=$table?>


</body>
</html>