<? require_once "../_lib/_init.php" ?>

<?

$imgurl = get_string("imgurl");
$imgurl = urldecode($imgurl);

$width = get_number("width");
$height = get_number("height");

$img_wh = "width=$width height=$height";

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

<img src="<?=$imgurl?>" <?=$img_wh?> hspace=0 vspace=0 border=0>

<p><input type="button" value="закрыть" onclick="javascript:window.close();"></p>


</tr></td></table>
</center>

</body>

</html>