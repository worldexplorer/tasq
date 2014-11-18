<?

$_menu = "";

function padding($row) {
	$ret = $row["level"] * 1;
	return $ret;
}

function menu_bo_makehref ($_entity_or_url, $txt, $level) {
	global $entity, $id, $mmenu_id, $pgroup_parentchain, $menu_bo;

	$ret = "";

	$mitem = "$txt";
	$hrefto = "";
	$selected = "";
	$selected_aattr = "class=cur";
	
	$controlchar = "";
	$len = strlen($_entity_or_url);
	if ($len > 1) $controlchar = substr($_entity_or_url, 0, 1);

	switch($controlchar) {
		case "=":	// "=pgroup-edit.php?id=5&parent_id=1" => "text in href"
			$hrefto = substr($_entity_or_url, 1);
			$pos = strpos($_SERVER["REQUEST_URI"], $hrefto);
			$selected = ($pos === false) ? "" : $selected_aattr;
			break;

		case "~":	// "~51" => "raw html"
			break;

		default:	// "pgroup" => "text in href"
			$hrefto = $_entity_or_url . ".php";
			$selected = ($entity == $_entity_or_url) ? $selected_aattr : "";
	}
	
	if ($txt == "") {
		$ret .= "<tr><td></td></tr>\n";
		return $ret;
	}


	if ($hrefto != "") $mitem = "<a href='$hrefto' $selected>$mitem</a>";
	$ret .= "<tr><td nowrap style='padding-left: " . $level . "em'>$mitem</td></tr>\n";

	if ($_entity_or_url == "product"
			&& $entity == "product"

/*
	if ($_entity_or_url == "product"
//			&& $entity != "news"
//			&& $entity != "ngroup"
			&& $entity != "mmenu"
			&& $entity != "ugroup"
*/

//		 || $_entity_or_url == "pgroup"
//		&& ($entity == "product" || $entity == "pgroup")
		&& function_exists("multicompositecontent")
		) {
		$pgroup = get_number("pgroup");
		$pgroup_parentchain = array_reverse(select_root_tree("pgroup", $pgroup));
		//pre($pgroup_parentchain);

		$pgroup_table = "";
//		$pgroup_table = multicompositecontent("pgrouptree", "m2m_product_fake4navigation",  array("pgroup", "supplier"), 1, 0);
//		$pgroup_table = multicompositecontent("pgrouptree", "m2m_product_fake4navigation",  array("pgroup", "product"), 1, 0);
		$pgroup_table = multicompositecontent("pgrouptree", "m2m_product_pgroup",  array("pgroup", "product"), 1, 0);

//		$pgroup_table = multicompositecontent("supplierbypgroup_nav", "m2m_product_fake4navigation",  array("pgroup", "supplier"), 1, 0);
//		$pgroup_table = multicompositecontent("pgrouptree", "m2m_product_fake4navigation",  array("pgroup"), 1, 0, "pgroup", "product_disabled_for_checkxboxes_to_open");
		$ret .= "<tr><td nowrap style='padding-left: " . ($level+1) . "em'>$pgroup_table</td></tr>";
	}


	if ($_entity_or_url == "mmenu" //&& $entity == "mmenu"
//		|| $_entity_or_url == "=mmenu.php?mode=mmenu_open" && get_string("mode") == "mmenu_open"

//	if ($_entity_or_url == "mmenu" &&
//		(!isset($menu_bo)) || (isset($menu_bo) && $entity == "mmenu")

		) {
		$tpl = <<< EOT
<tr><td style="padding-left: @padding@em; padding-bottom: 0.2em;">
	<table cellpadding=0 cellspacing=0><tr>
		<td>&#149;&nbsp;&nbsp;</td>
		<td><a href="mmenu-edit.php?id=#ID#">#IDENT#</a></td>
		</tr>
	</table>
</td></tr>
#DOWN_CONTENT#
EOT;
		$tpl_current = <<< EOT
<tr><td style="padding-left: @padding@em; padding-bottom: 0.2em;">
	<table cellpadding=0 cellspacing=0><tr>
		<td>&#149;&nbsp;&nbsp;</td>
		<td><a href="mmenu-edit.php?id=#ID#" class="cur">#IDENT#</a></td>
		</tr>
	</table>
</td></tr>
#DOWN_CONTENT#
EOT;
		$mmenu_id = 0;
		if ($entity == "mmenu") $mmenu_id = $id;
//		if ($entity == "pgroup") $mmenu_id = $pgroup;
		
		$ret .= tree_tpl($tpl, $tpl_current, "mmenu", $mmenu_id);
		$ret .= "<tr><td nowrap>&nbsp;</td></tr>";
	}
	
	return $ret;
}

function walktree_menu_bo($menu_bo, $level=0) {
	$ret = "";

//	pre($menu_bo);
	foreach ($menu_bo as $entity_or_url => $entry) {
		if (is_array($entry)) {
			$ret .= walktree_menu_bo($entry, $level+1);
			if ($level == 1) $ret .= "<tr><td nowrap>&nbsp;</td></tr>\n";
		} else {
			$txt = $entry;
			if ($level == 2) $txt = "<b>$txt</b>";
			$ret .= menu_bo_makehref($entity_or_url, $txt, $level);
		}
	}

	return $ret;
}

$_menu = "";
if (isset($menu_bo)) {
	$_menu .= walktree_menu_bo($menu_bo);
} else {
	$_menu .= menu_bo_makehref("mmenu", "Структура сайта", 0);

/*	modev in _menu_right.php
	foreach ($entity_list as $_entity_or_url => $txt) {
		$_menu .= menu_bo_makehref($_entity_or_url, $txt, 0);
	}
*/
}

$_menu = <<< EOT
<table cellpadding=0 cellspacing=3 width=50>
$_menu
</table>
EOT;

echo $_menu;
?>
