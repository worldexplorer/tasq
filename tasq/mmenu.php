<? require "_code.php" ?>
<?

if ($mmenu_hashkey != "") {
	$bo_href = "$mmenu_hashkey.php";

	if ($mmenu_tpl_list_item != "") {
		$table = entity_list_tpl($mmenu_tpl_list_item, $mmenu_tpl_list_item, $mmenu_hashkey);

		if ($mmenu_tpl_list_wrapper != "") {
			$table = hash_by_tpl(array("list_items" => $table), $mmenu_tpl_list_wrapper);
		}

	}
	
	if ($table != "") {
		if (!preg_match("/#LIST_ITEMS#/i", $mmenu_content)) $mmenu_content .= "#LIST_ITEMS#";
		$mmenu_content = hash_by_tpl(array("list_items" => $table), $mmenu_content);
	}
}

if ($mmenu_content == "") {
	$mmenu_content =  <<< EOT
<p align=left style="clear: both">
<em>Для изменения любой страницы на сайте можно кликнуть в ссылку "бэкоффис" – откроется
новое окно для редактирования страницы, просматриваемой в данный момент. В дальнейшем, ссылка
будет спрятана под какой-нибудь незаметный элемент дизайна и запаролена, чтобы туда смогли
зайти только уполномоченные лица.</em>
</p>
EOT;

}

?>
<? require "_top.php" ?>

<?=$mmenu_content?>

<? require "_bottom.php" ?>