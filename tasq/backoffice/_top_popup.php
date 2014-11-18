<? if ($is_inline == 0) { ?>
<html>
<head>
  <title><?=$site_name?> <?=$pagetitle_separator?> Бэкоффис</title>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
  <link href="default.css" type="text/css" rel="stylesheet">

<? if ($FTB_version == "303") { ?>
  <link href="/_FTB303/FTB-<?=$FTB_Style?>.css" type="text/css" rel="stylesheet">
  <script src="/_FTB303/FTB-Utility.js" type="text/javascript"></script>
  <script src="/_FTB303/FTB-FreeTextBox.js" type="text/javascript"></script>
  <script src="/_FTB303/FTB-ToolbarItems.js" type="text/javascript"></script>
  <script src="/_FTB303/FTB-Pro.js" type="text/javascript"></script>
<? } ?>


  <script src="script.js" type="text/javascript"></script>
  <script src="composite.js" type="text/javascript"></script>

<? if ($alertmsg != "") { ?>
<script>alert('<?=$alertmsg?>')</script>
<? } ?>

</head>

<body>
<table cellpadding=0 cellspacing=0 border=0 width=100%>
<tr valign=top>
	<!--td width=150><? require "_menu.php" ?></td-->
<? require "_submenu.php" ?>
<?=$_submenu_forms?>
	<td>

<table cellpadding=0 cellspacing=3>
	<tr><td><?=$errormsg?>&nbsp;</td></tr>
</table>
<table cellpadding=0 cellspacing=3 width=100%>

	<tr><td>

<!-- TOP END -->
<? } ?>