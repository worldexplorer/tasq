<? require_once "../_lib/_init.php" ?>

<?

$img_id = get_number("img_id");
$row = select_entity_row(array("id" => $img_id), "img");

$img_big = $row["img_big"];
$img_big_txt = $row["img_big_txt"];
//if ($img_big_txt == "") $img_big_txt = $row["img_txt"];

$img_wh = "";
//$img_big_relpath = "img/$img_id/$img_big";
$img_big_imgpath = img_relpath($row, "img_big");
$img_big_relpath = $upload_relpath . $img_big_imgpath;

//if (!is_file($upload_path . $img_big_imgpath)) {
if (!img_exists($row, "img_big") && 0) {
	$img_big_relpath = "img/blank.gif";
	$img_big_txt = "Извините, изображение потеряно :(";
} else {
//	$img_size = getimagesize($upload_path . $img_big_relpath);
//	$img_wh = $img_size[3];

	$img_size = array (
		"img_width" => $row["img_big_w"],
		"img_height" => $row["img_big_h"],
		"img_wh" => "width='" . $row["img_big_w"] . "' height='" . $row["img_big_h"] . "'"
	);

	$img_wh = $img_size["img_wh"];
//	if($img_size["img_width"] > 450) $img_wh="class=\"prn_fwidth\"";

}

?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<title><?=$site_name?> | <?=$img_big_txt?></title>
	<link rel="stylesheet" type="text/css" href="default.css">
	<script>
	function layout_alert() {
		alert("Возможно, Вам стоит выбрать при печати\nальбомную ориентацию изображения (landscape)...");
		print();
	}
	</script>
</head>
<body>

<center>
<table cellpadding=0 cellspacing=0 height=100%><tr valign=center><td align=center>


<table cellpadding=0 cellspacing=0><tr valign=center><td>

<div class="image_popup">
<img src="<?=$img_big_relpath?>" <?=$img_wh?> ><br>
<p style="text-align: center;"><?=$img_big_txt?></p>
</div>


<!--img src="<?=$img_big_relpath?>" <?=$img_wh?> ><br>
<p class="img_desc" style="text-align: center;"><?=$img_big_txt?></p-->
</td></tr></table>

<p><input type="button" value="закрыть" onclick="javascript:window.close();"></p>

</td></tr></table>
</center>

</body>
</html>