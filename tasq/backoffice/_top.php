<? if ($is_inline == 0) { ?>
<html>


<head>
<?
if (isset($title_string)) {
	$title_string = $pagetitle_separator . $title_string;
} else {
	$title_string = "";

	if ($entity_msg_h != "" && $entity_msg_h != "Entity_msg") {
		$title_string .= $pagetitle_separator . $entity_msg_h;
	}
	if ($id > 0) {
		$title_string .= $pagetitle_separator . select_field("ident");
	}
}

$title_string = stripslashes($title_string);
$title_string = strip_tags($title_string);
?>
<title><?=$site_name?> <?=$title_string?></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="default.css" type="text/css" rel="stylesheet">

<link rel="shortcut icon" href="/favicon.gif" type="image/gif">
<link rel="icon" href="/favicon.gif" type="image/gif">

<? if ($FTB_version == "303-disabled") { ?>
<link href="/_FTB303/FTB-<?=$FTB_Style?>.css" type="text/css" rel="stylesheet">
<script src="/_FTB303/FTB-Utility.js" type="text/javascript"></script>
<script src="/_FTB303/FTB-FreeTextBox.js" type="text/javascript"></script>
<script src="/_FTB303/FTB-ToolbarItems.js" type="text/javascript"></script>
<script src="/_FTB303/FTB-Pro.js" type="text/javascript"></script>
<? } ?>


<script src="script.js" type="text/javascript"></script>

<script>
function body_onload() {
<? if ($alertmsg != "") { ?>
	alert('<?=$alertmsg?>')
<? } ?>
	focus_itname("q", "form_submenu")
}
</script>


<? if ($header_include != "") { ?>
<!-- header_include -->
<?=$header_include?>
<!-- /header_include -->
<? } ?>

</head>

<body onload="body_onload()">

<? if ($body_include != "") { ?>
<!-- body_include -->
<?=$body_include?>
<!-- /body_include -->
<? } ?>

<table cellpadding=0 cellspacing=0 border=0 width=100%>
<tr valign=top>
<td width=50><? require "_menu.php" ?></td>
<? require "_submenu.php" ?>
<?=$_submenu_forms?>
<td style="width:3em;"></td>
<td>

<table cellpadding=0 cellspacing=3>
<tr><td><?=$errormsg?>&nbsp;</td></tr>
</table>
<table cellpadding=0 cellspacing=3 width=100%>

<tr><td>

<!-- TOP END -->
<? } ?>