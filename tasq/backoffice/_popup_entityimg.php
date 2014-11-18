<? require_once "../_lib/_init.php" ?>

<?

$entity = get_string("entity");
$entity_id = get_string("entity_id");
$imgfield = get_string("imgfield");

$row = select_entity_row(array("id" => $entity_id), $entity);

$img_big = $row[$imgfield];
$img_big_txt = $row["ident"];

$img_wh = "";
$img_big_relpath = "$entity/$entity_id/$img_big";

if (!is_file($upload_abspath . $img_big_relpath)) {
	$img_big_relpath = "img/blank.gif";
	$img_big_txt = "Извините, изображение потеряно :(";
} else {
	$img_size = getimagesize($upload_path . $img_big_relpath);
	$img_wh = $img_size[3];

/*	$img_size = array (
		"img_width" => $row["img_big_w"],
		"img_height" => $row["img_big_h"],
		"img_wh" => "width='" . $row["img_big_w"] . "' height='" . $row["img_big_h"] . "'"
	);
	$img_wh = $img_size["img_wh"];
*/

}
?>

<html>
<head>
	<title><?=$site_name?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<link rel="stylesheet" href="default.css">
	<script>
	function layout_alert() {
		alert("Возможно, Вам стоит выбрать при печати\nальбомную ориентацию изображения (landscape)...");
		print();
	}
	</script>
</head>
<body topmargin=0 bottommargin=0 leftmargin=0 rightmargin=0>

<center>
<table cellpadding=0 cellspacing=0 height=100%><tr valign=center><td align=center>

<img src="<?=$upload_relpath?><?=$img_big_relpath?>" <?=$img_wh?> hspace=0 vspace=0 border=0>

<p><input type="button" value="закрыть" onclick="javascript:window.close();"></p>


</tr></td></table>
</center>

</body>

</html>