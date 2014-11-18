<?

$_menu_right = "";

foreach ($entity_list as $_entity_or_url => $txt) {
	if ($_entity_or_url == "mmenu") continue;
	$_menu_right .= menu_bo_makehref($_entity_or_url, $txt, 0);
}


$_menu_right = <<< EOT
<table cellpadding=0 cellspacing=3>
$_menu_right
</table>
EOT;

echo $_menu_right;

?>
