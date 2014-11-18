<? require_once "_code.php" ?>
<?

setcontext_item();

function srchref($row) {
	$ret = "";

	if (isset($row["srchref"]) && $row["srchref"] != "") {
		$ret = <<< EOT
<p>Источник: <a href="#SRCURL#" target=_blank>#SRCURL#</a></p>
EOT;
	}
	
	return $ret;
}

$tpl = <<< EOT
<p align=right>@date_published@</p>
<table cellpadding=0 cellspacing=0>
<tr valign=top>
	<td width=1>@news_every@</td>
	<td>#CONTENT#</td>
</tr>
</table>
@srchref@

<p></p>
EOT;


$table = entity_tpl($tpl, "_global:entity", array(), $ctx_img_wrapped);
$row = select_entity_row();
$path_HTML .= $path_separator . $row["ident"];
$title = $row["ident"];
$ngroup = $row["ngroup"];
$pagetitle = $pagetitle_separator . $row["ident"];


//$return_href = "<a href='ngroup.php?id=$ngroup'>список новостей</a>";
//$prevnext_product_table90 = prevnext_product_table90($row, array("ngroup" => $ngroup), "предыдущая новость", "следующая новость", $return_href);

$return_href = "<a href='news-list.php'>список новостей</a>";
$prevnext_news_table90 = prevnext_product_table90($row, array(), "предыдущая новость", "следующая новость", $return_href);

?>

<? require "_top.php" ?>

<?=$table?>

<hr>
<?=$prevnext_news_table90?>
		 
<? require "_bottom.php" ?>