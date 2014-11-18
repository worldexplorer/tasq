<? //$mmenu_id = 6 ?>
<? require "_code.php" ?>
<?

//setcontext_item();
$_REQUEST[$entity] = $id;
$product = $id;
$bo_href = "$entity-edit.php?id=$id";

$action = get_string("action");


$markers_hash = array (
	"product" => $product,
	"rating" => "",
	"opinion" => "",
	"wish" => "",
	"replic" => "",
	"subject_HTMLrow" => "",
);

$markers_hash = gethash_bytplhash($markers_hash, 0);
$markers_hash["rating"] = intval($markers_hash["rating"]);
//pre($markers_hash);

if ($action == "opinion_edit") {
	if ($mode == "update" && $errormsg == "" && $markers_hash["rating"] == 0) {
		$errormsg = "Укажите оценку";
	}
	
	if ($mode == "update" && $errormsg == "" && $markers_hash["opinion"] == "") {
		$errormsg = "Укажите Ваше мнение о мыле";
	}
	
	if ($mode == "update" && $errormsg == "" && $markers_hash["wish"] == "") {
		$errormsg = "Укажите пожелание к мылу";
	}
}

if ($action == "replic_edit") {
	if ($mode == "update" && $errormsg == "" && $markers_hash["replic"] == "") {
		$errormsg = "Укажите чем конкретно Вы очарованы";
	}
}


$tpl_rating = <<< EOT
<tr valign=top>
    <td style="padding: 1ex;" width=90>#customer_first#</td>
    <td align=center>оценка:<h1>#RATING#</h1></td>
    <td style="padding: 1ex;" width=48%>#OPINION#</td>
    <td style="padding: 1ex;" width=48%>#WISH#</td>
</tr>
EOT;


$tpl_replic = <<< EOT
<tr valign=top>
    <td style="padding: 1ex;" width=90>#customer_first#</td>
    <td style="padding: 1ex;" width=96%>#CONTENT#</td>
</tr>
EOT;



if ($markers_hash["rating"] != "" || $markers_hash["replic"] != "") {
	$subject_HTMLrow_tpl = <<< EOT
<tr><td>
	<b>Тема сообщения</b><br>
  	<input type="text" size="44" name="subject" value="#SUBJECT#">
</td></tr>
EOT;
	$markers_hash["subject_HTMLrow"] = hash_by_tpl($markers_hash, $subject_HTMLrow_tpl);

	$subject_HTMLrow_tpl = <<< EOT
<tr><td>
    <b>Тема сообщения</b><br>
    <span class=filled>#SUBJECT#</span>
</td></tr>
EOT;
	$markers_hash["subject_HTMLrow_inmail"] = hash_by_tpl($markers_hash, $subject_HTMLrow_tpl);
}



function product_content($row) {
	$ret = "";

	$tpl = <<< EOT
	<div class="pcard_options">
	<!--h5>Описание</h5-->
    #CONTENT#
	</div>
EOT;

	if (strip_tags($row["content"]) != "") {
		$ret = hash_by_tpl($row, $tpl);
	}

	return $ret;
}

$tpl_product = <<< EOT
<div class="pcard_options fleft" style="width:180px; border-right:1px solid #e0e0e0; padding: 0 2em 0 0">
	@product_every@

	<!--p><br></p>
	<p style="clear:both">@prices_block@</p>

	<ul class="noleftmargin">
		<li><a href="product-order.php?id=#ID#">заказать лимузин в прокат</a></li>
		<li><a href="askme.php?product=#ID#&subject=#SUBJECT_URLENCODED#">задать вопрос</a></li>
		<li><a href="javascript:popup_blank('#ENTITY#.php?id=#ID#&print=1', 500, 400)">распечатать</a></li>
		<li><a href="pgroup.php?id=#PGROUP#">другие лимузины &laquo;#PGROUP_IDENT#&raquo;</a></li>
	</ul-->
</div>

<div class="pcard_options">
<h5>Информация о мыле</h5>
@product_iccontent@
</div>

<!--p style="clear:both"><br></p-->

<div class="pcard_options">
<h5>Подробное описание</h5>
@product_content@
</div>


EOT;

$query = "select p.*, pg.ident as pgroup_ident"
	. " from product p"
	. " left outer join pgroup pg on p.pgroup=pg.id"
	. " where p.id=$id"
	. " and p.published=1 and p.deleted=0"
	. " order by p." . get_entity_orderby("product")
	;

$query = "select p.*"
	. ", pg.id as pgroup, pg.ident as pgroup_ident"
	. ", cy1.ident as currency1_ident"
//	. " , cy2.ident as currency2_ident , cy3.ident as currency3_ident "
	. " from product p"
//	. " left outer join pgroup pg on p.pgroup=pg.id"

	. " left outer join m2m_product_pgroup m2m on m2m.product=p.id and m2m.published=1 and m2m.deleted=0"
	. " left outer join pgroup pg on m2m.pgroup=pg.id and pg.published=1 and pg.deleted=0"

	. " left outer join currency cy1 on p.currency_1=cy1.id"
//	. " left outer join currency cy2 on p.currency_2=cy2.id"
//	. " left outer join currency cy3 on p.currency_3=cy3.id"
	. " where p.id=$product"
//	. " and p.published=1"	// мешает для превью
	. " and p.deleted=0"
	;

$qa = select_queryarray($query, "product");
$row = isset($qa[0]) ? $qa[0] : array();
//pre($row);
$_REQUEST["pgroup"] = $pgroup = isset($row["pgroup"]) ? $row["pgroup"] : 0;
//pre($pgroup);

$row["subject_urlencoded"] = urlencode($row["ident"]);
$row["subject_order_urlencoded"] = urlencode("Заказ объекта &laquo;" . $row["ident"] . "&raquo;");
$subject_urlencoded = urlencode($row["ident"]);
$subject = stripslashes($row["ident"]);

$table = hash_by_tpl($row, $tpl_product, "product");

$title = "";
if ($title == "" && isset($row["title"]) && $row["title"] != "") $title = hash_by_tpl($row, "#TITLE#");
if ($title == "") $title = hash_by_tpl($row, "#IDENT#");

$pagetitle = "";
if ($pagetitle == "" && isset($row["pagetitle"]) && $row["pagetitle"] != "") $pagetitle = $pagetitle_separator . hash_by_tpl($row, "#PAGETITLE#");
if ($pagetitle == "") $pagetitle = $pagetitle_separator . $title;

$title_right = <<< EOT
№<form action=product.php class=pid><input type=text name="id" value="$id" width=3></form>
EOT;

$pagetitle .= ", №$id";

/*
$title = "Продажа " . $ident;
$pagetitle = $title;
$meta_keywords = "$title. $meta_keywords";
*/


$fromend_pgroup_tree_content = select_root_tree_content("pgroup", $pgroup);
//pre($fromend_pgroup_tree_content);
$href_mmenu_upper_level = "/index.php";
//if (count($fromend_pgroup_tree_content) == 0) redirect ($href_mmenu_upper_level);
$fromend_pgroup_root_tree = array_keys($fromend_pgroup_tree_content);
$pgroup_root_tree = array_reverse($fromend_pgroup_root_tree);
$pgroup_root = $fromend_pgroup_tree_content[$pgroup_root_tree[0]];
$pgroup_row = $fromend_pgroup_tree_content[$fromend_pgroup_root_tree[0]];
$pgroup_ident = $pgroup_row["ident"];

$brief = ($row["brief"] != "") ? hash_by_tpl($row, "<p align=justify>#BRIEF#</p>") : "";

$path_HTML .= $path_separator . "<a href=pgroup.php?id=$pgroup>$pgroup_ident</a>";
$path_HTML .= $path_separator . "<a href=product.php?id=$id>$title</a>";



if ($id > 0) {
	$query = "update $entity set hits = hits + 1 where id=$id";
	$query = add_sql_table_prefix($query);
	mysql_query($query);
}






$my_opinion_select_hash = array (
	"product" => $id,
	"customer" => $unhashed["customer"],
	"deleted" => 0,
	"published" => 1,
);

$my_opinion_update_hash = array (
	"rating" => $markers_hash["rating"],
	"opinion" => $markers_hash["opinion"],
	"wish" => $markers_hash["wish"],
);

$my_opinion_insert_hash = array (
	"rating" => $markers_hash["rating"],
	"opinion" => addslashes($markers_hash["opinion"]),
	"wish" => addslashes($markers_hash["wish"]),
	"product" => $markers_hash["product"],
	"customer" => $unhashed["customer"],
//	"remote_address" => $_SERVER["REMOTE_ADDR"],
	"date_created" => "CURRENT_TIMESTAMP",
	"date_published" => "CURRENT_TIMESTAMP",
);


$my_opinion_id = 0;
if ($unhashed["customer"] > 0) {
	$my_opinion_id = select_field("id", $my_opinion_select_hash, "m2m_product_rating");
	$my_opinion_id = intval($my_opinion_id);
}

if ($unhashed["customer"] > 0 && $mode == "update" && $action == "opinion_edit" && $errormsg == "") {
	if ($my_opinion_id == 0) {
		$inserted = insert($my_opinion_insert_hash, "m2m_product_rating");
		if ($inserted == 0) {
			$alertmsg = "Ошибка добавления Вашей реплики в базу данных";
		} else {
			$alertmsg = "Реплика добавлена успешно";
			$my_opinion_id = $inserted;
		}
	} else {
		$updated = update($my_opinion_insert_hash, $my_opinion_select_hash, "m2m_product_rating");
		if ($updated == 0) {
			$alertmsg = "Ваша реплика не изменилась, системная ошибка";
		} else {
			$alertmsg = "Реплика обновлена успешно";
		}
	}

}


if ($unhashed["customer"] > 0 && $mode == "update" && $action == "opinion_delete") {
	if ($my_opinion_id > 0) {
		$deleted = delete(array("id" => $my_opinion_id, "deleted" => 0, "published" => 1), "m2m_product_rating");
		if ($deleted == 0) {
			$alertmsg = "Ваша реплика уже удалена ранее";
		} else {
			$alertmsg = "Реплика удалена успешно";
			$my_opinion_id = 0;
		}
	} else {
		$alertmsg = "Вы запросили удаление реплики, а я её не нашёл!... (удалена ранее?)";
	}
}

$i_said_my_opinion = 0;


$tpl_rating_notmine = <<< EOT
<tr valign=top>
    <td style="padding: 1ex;" width=90>#customer_first#</td>
    <td align=center>оценка:<h1>#RATING#</h1><span class="date_small">@date_updated_shortyear@</span></td>
    <td style="padding: 1ex;" width=48%>#OPINION#</td>
    <td style="padding: 1ex;" width=48%>#WISH#</td>
</tr>
EOT;

$tpl_rating_my = <<< EOT
<tr valign=top bgcolor=yellow>
    <td style="padding: 1ex;" width=90>$customer_me_first</td>
    <td align=center><a name="my_rating"></a>оценка:<h1>#RATING#</h1><span class="date_small">@date_updated_shortyear@</span></td>
    <td style="padding: 1ex;" width=48%>#OPINION#</td>
    <td style="padding: 1ex;" width=48%>#WISH#</td>
</tr>
<tr valign=top bgcolor=yellow><td colspan=4 align=right>
	<b><a href="product.php?id=$id&action=opinion_edit#my_rating">[хочу изменить своё мнение]</a></b>
   	&nbsp;&nbsp;&nbsp;&nbsp;
	<b><a href="javascript:opinion_delete()">[хочу удалить своё мнение]</a></b>
</td></tr>


<form action="product.php?id=$id#opinions" method="post" id="form_opinion_delete" name="form_opinion_delete">
<input type=hidden name=mode value=update>
<input type=hidden name=action value=opinion_delete>
<input type=hidden name=product value=$id>
</form>

<script>
function opinion_delete() {
	imsure = confirm("Вы действительно хотите удалить свою реплику?");
	if (imsure == true) {
		document.form_opinion_delete.submit()
	}
}
</script>


EOT;


$tpl_rating_my_edit = <<< EOT
</td></tr>
<tr><td align=right colspan=4>

<a name=my_rating></a>
<table cellpadding=4 cellspacing=1 border=0 width=100%>

<form action="product.php?id=$id#opinions" method="post" id="form_opinion" name="form_opinion" class=rating>
<input type=hidden name=mode value=update>
<input type=hidden name=action value=opinion_edit>
<input type=hidden name=product value=$id>
<tr bgcolor=yellow>
	<th width=90>Ща пикну<a name=#my_rating></a></th>
	<th width=90>Моя оценка</th>
	<th width=48%>Что мне понравилось, а что нет</th>
	<th width=48%>Какие у меня есть предложения</th>
</tr>


<tr valign=top bgcolor=yellow>
    <td style="padding: 1ex;">#customer_first#</td>
    <td align=center>моя оценка:<br>#RATING_SELECTOR#<br><br><span class="date_small">@date_updated_shortyear@</span></td>
    <td style="padding: 1ex;">
		<textarea class=opinion name=opinion>#OPINION#</textarea>
    </td>
    <td style="padding: 1ex;">
		<textarea class=wish name=wish>#WISH#</textarea>
    </td>
</tr>

<tr valign=top bgcolor=yellow>
    <td colspan=4 align=center>
    	<input type=button class=cancel value="Пиииии!" onclick="javascript:form_opinion_submit()">
    	&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type=button class=cancel value="оставить без изменений" onclick="javascript:opinion_editing_finished()">
    	&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type=button class=delete value="удалить" onclick="javascript:opinion_delete()">
    </td>
</tr>

</form>

<form method="post" id="form_delete" name="form_delete">
<input type=hidden name=mode value=update>
<input type=hidden name=action value=opinion_delete>
<input type=hidden name=product value=$id>
</form>

<script>
function opinion_delete() {
	imsure = confirm("Вы действительно хотите удалить свою реплику?");
	if (imsure == true) {
		document.form_delete.submit()
	}
}

function opinion_editing_finished() {
	location.href = "product.php?id=$id";
}
</script>

</table>
</div>

</td></tr>


EOT;


$content_product_addrating_opinion = entity_tpl("#CONTENT#", "constant", array("hashkey" => "PRODUCT_ADDRATING_OPINION"));
$content_product_addrating_wish = entity_tpl("#CONTENT#", "constant", array("hashkey" => "PRODUCT_ADDRATING_WISH"));


$tpl_rating_my_edit_initial = <<< EOT
<tr><td colspan=4 align=right>

<b><a href="javascript:layer_switch(1)"><img src="img/down.gif" width=10 height=6 border=0 alt="я попробовал(а) [#subject#] и хочу высказать своё мнение об этом мыле" class=noborder style="border:0"> я попробовал(а) [#subject#] и хочу высказать своё мнение об этом мыле</a></b>

</td></tr>


<tr><td align=right colspan=4>

<div id="layer_1" style="display:none">
<table cellpadding=4 cellspacing=1 border=0 width=100%>

<form action="product.php?id=$id#opinions" method="post" id="form_opinion" name="form_opinion" class=rating>
<input type=hidden name=mode value=update>
<input type=hidden name=action value=opinion_edit>
<input type=hidden name=product value=$id>

<tr bgcolor=yellow>
	<th width=90>Ща пикну</th>
	<th width=90>Моя оценка</th>
	<th width=48%>Что мне понравилось, а что нет</th>
	<th width=48%>Какие у меня есть предложения</th>
</tr>


<tr valign=top bgcolor=yellow>
    <td style="padding: 1ex;">$customer_me_first</td>
    <td align=center>оценка:#RATING_SELECTOR#</td>
    <td style="padding: 1ex;">
		<textarea class=opinion name=opinion onfocus="javascript:clearfield_ungray('opinion', '$content_product_addrating_opinion')" style="color:gray">$content_product_addrating_opinion</textarea>
    </td>
    <td style="padding: 1ex;">
		<textarea class=wish name=wish onfocus="javascript:clearfield_ungray('wish', '$content_product_addrating_wish')" style="color:gray">$content_product_addrating_wish</textarea>
    </td>
</tr>

<tr valign=top bgcolor=yellow>
    <td colspan=4 align=center>
    	<input type=button class=cancel value="Пиииии!" onclick="javascript:form_opinion_submit()">
    	&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type=button class=cancel value="Я передумал(а)" onclick="javascript:opinion_changed_mind()">
    </td>
</tr>
</form>
</table>
</div>

<script>
function clearfield_ungray(textarea_id, text_to_clear) {
	field = MM_findObj(textarea_id)
	if (field == null) return

//	alert(field.value)
	if (field.value == text_to_clear) {
		field.value = ""
		field.style.color = "black"
	}
}

function opinion_changed_mind() {
	alert("... ну и пожалуйста! и скорлупу свою не забудь... тоже мне, птенец, нахрен...")
	location.href = "product.php?id=$id#opinions"
}
</script>

</td></tr>

EOT;


$tpl_rating_nocustomer_edit = <<< EOT
<tr><td colspan=4 align=right>

<b><a href="javascript:layer_switch(1)"><img src="img/down.gif" width=10 height=6 border=0 alt="я попробовал(а) [#subject#] и хочу высказать своё мнение об этом мыле" class=noborder style="border:0"> я тоже хочу поставить оценку</a></b>

</td></tr>


<tr><td align=right colspan=4 align=right>

<div id="layer_1" style="display:none">
<table cellpadding=30 cellspacing=1 border=0 width=60% style="border:1px solid gray">
<tr><td align=center>
	Оставить мнение могут только зарегистрированные пользователи.<br>
	Пожалуйста, <b><a href="register.php">зарегистрируйтесь</a></b> или <b><a href="auth.php">авторизуйтесь</a></b>.<br>
	Если Вы не пользовались этим мылом, сначала <b><a href="askme.php?subject=хочу попробовать $subject">попробуйте</a></b> :)<br>
	
</td></tr>
</table>
</div>

<br><br>
</td></tr>

EOT;



$array_0_10 = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
$array_0_10[0] = "";

$rating_form_initial_hash = array (
	"rating_selector" => select_hash("rating", 0, $array_0_10),
	"opinion" => "",
	"wish" => "",
	"subject" => $subject,
	);


$tpl_separator = <<< EOT

<tr><td colspan=4 height=0 bgcolor=#F0F0F0></td></tr>

EOT;


$query_rating = "select e.*, product.ident as product_ident, customer.ident as customer_ident"
	. " from m2m_product_rating e"
	. " left join product product on e.product=product.id"
	. " left join customer customer on e.customer=customer.id"
	. " where 1=1 and e.published=1 and e.deleted=0"
	. " and e.product=$id"
//	. " order by e.date_created desc";
//	. " order by " . get_entity_orderby("m2m_product_rating")
	. " order by e.date_updated desc"
	;

$query_rating = add_sql_table_prefix($query_rating);
$qa_rating = select_queryarray($query_rating);
//pre($qa);

$rating_table = "";
foreach ($qa_rating as $row_rating) {
//	pre($row_rating);
	$tpl_rating = $tpl_rating_notmine;

	
//	if ($row_rating["customer"] == $unhashed["customer"]) {
//		if ($i_said_my_opinion == 1) continue;
//		$i_said_my_opinion = 1;

	if ($row_rating["id"] == $my_opinion_id) {
		$tpl_rating = ($action == "opinion_edit") ? $tpl_rating_my_edit : $tpl_rating_my;
	}

	$row_rating["rating_selector"] = select_hash("rating", $row_rating["rating"], $array_0_10);
	
	$row_rating["opinion"] = stripslashes($row_rating["opinion"]);
	$row_rating["opinion"] = str_replace("<br />", "\n", $row_rating["opinion"]);

	$row_rating["wish"] = stripslashes($row_rating["wish"]);
	$row_rating["wish"] = str_replace("<br />", "\n", $row_rating["wish"]);

	$row_rating["customer_first"] = customer_first($row_rating);
//	$row_rating["date_updated_shortyear"] = date_updated_shortyear($row_rating);

//	$rating_table .= hash_by_tpl($row_rating, $tpl_rating, "", 0, 0);
	$rating_table .= hash_by_tpl($row_rating, $tpl_rating);

	if ($row_rating["i"] < $row_rating["rows_total"]) $rating_table .= $tpl_separator;
}

if ($rating_table == "") {
	$rating_table = <<<EOT
<tr valign=top>
    <td colspan=4 style="padding: 1ex;" align=center><b>никто так и не пикнул ничего хорошего :(</b></td>
</tr>
EOT;

}










$my_replic_select_hash = array (
	"product" => $id,
	"customer" => $unhashed["customer"],
	"deleted" => 0,
	"published" => 1,
);

$my_replic_update_hash = array (
	"content" => $markers_hash["replic"],
);

$my_replic_insert_hash = array (
	"content" => $markers_hash["replic"],
	"product" => $markers_hash["product"],
	"customer" => $unhashed["customer"],
//	"remote_address" => $_SERVER["REMOTE_ADDR"],
	"date_created" => "CURRENT_TIMESTAMP",
	"date_published" => "CURRENT_TIMESTAMP",
);


$my_replic_id = 0;
if ($unhashed["customer"] > 0) {
	$my_replic_id = select_field("id", $my_replic_select_hash, "m2m_product_replic");
	$my_replic_id = intval($my_replic_id);
}

if ($unhashed["customer"] > 0 && $mode == "update" && $action == "replic_edit" && $errormsg == "") {
	if ($my_replic_id == 0) {
		$inserted = insert($my_replic_insert_hash, "m2m_product_replic");
		if ($inserted == 0) {
			$alertmsg = "Ошибка добавления Вашей реплики в базу данных";
		} else {
			$alertmsg = "Реплика добавлена успешно";
			$my_replic_id = $inserted;
		}
	} else {
		$updated = update($my_replic_insert_hash, $my_replic_select_hash, "m2m_product_replic");
		if ($updated == 0) {
			$alertmsg = "Ваша реплика не изменилась, системная ошибка";
		} else {
			$alertmsg = "Реплика обновлена успешно";
		}
	}

}


if ($unhashed["customer"] > 0 && $mode == "update" && $action == "replic_delete") {
	if ($my_replic_id > 0) {
		$deleted = delete(array("id" => $my_replic_id, "deleted" => 0, "published" => 1), "m2m_product_replic");
		if ($deleted == 0) {
			$alertmsg = "Ваша реплика уже удалена ранее";
		} else {
			$alertmsg = "Реплика удалена успешно";
			$my_replic_id = 0;
		}
	} else {
		$alertmsg = "Вы запросили удаление реплики, а я её не нашёл!... (удалена ранее?)";
	}
}

$i_said_my_replic = 0;


$tpl_replic_notmine = <<< EOT
<tr valign=top>
    <td style="padding: 1ex;" width=90>#customer_first#</td>
    <td style="padding: 1ex;" width=48%>
    	#CONTENT#
    	<div align=right class="date_small">@date_updated_shortyear@</div>
    </td>
</tr>
EOT;

$tpl_replic_my = <<< EOT
<tr valign=top bgcolor=yellow>
    <td style="padding: 1ex;" width=90>$customer_me_first</td>
    <td style="padding: 1ex;" width=48%>
    	#CONTENT#
    	<div align=right class="date_small">@date_updated_shortyear@</div>
    </td>
</tr>
<tr valign=top bgcolor=yellow><td colspan=4 align=right>
	<b><a href="product.php?id=$id&action=replic_edit#my_replic">[хочу изменить свою реплику]</a></b>
   	&nbsp;&nbsp;&nbsp;&nbsp;
	<b><a href="javascript:replic_delete()">[хочу удалить свою реплику]</a></b>
</td></tr>


<form action="product.php?id=$id#replics" method="post" id="form_replic_delete" name="form_replic_delete">
<input type=hidden name=mode value=update>
<input type=hidden name=action value=replic_delete>
<input type=hidden name=product value=$id>
</form>

<script>
function replic_delete() {
	imsure = confirm("Вы действительно хотите удалить свою реплику?");
	if (imsure == true) {
		document.form_replic_delete.submit()
	}
}
</script>


EOT;


$tpl_replic_my_edit = <<< EOT
</td></tr>
<tr><td align=right colspan=4>

<a name=my_replic></a>
<table cellpadding=4 cellspacing=1 border=0 width=100%>

<form action="product.php?id=$id#replics" method="post" id="form_replic" name="form_replic" class=replic>
<input type=hidden name=mode value=update>
<input type=hidden name=action value=replic_edit>
<input type=hidden name=product value=$id>
<tr bgcolor=yellow>
	<th width=90>Ща пикну<a name=#my_replic></a></th>
	<th width=96%>Чем я собсно очарован(а)</th>
</tr>


<tr valign=top bgcolor=yellow>
    <td style="padding: 1ex;">#customer_first#</td>
    <td style="padding: 1ex;">
		<textarea class=replic name=replic>#CONTENT#</textarea>
    	<div align=right class="date_small">@date_updated_shortyear@</div>
    </td>
</tr>

<tr valign=top bgcolor=yellow>
    <td colspan=2 align=center>
    	<input type=button class=cancel value="Пиииии!" onclick="javascript:form_replic_submit()">
    	&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type=button class=cancel value="оставить без изменений" onclick="javascript:replic_editing_finished()">
    	&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type=button class=delete value="удалить" onclick="javascript:replic_delete()">
    </td>
</tr>

</form>

<form method="post" id="form_delete" name="form_delete">
<input type=hidden name=mode value=update>
<input type=hidden name=action value=replic_delete>
<input type=hidden name=product value=$id>
</form>

<script>
function replic_delete() {
	imsure = confirm("Вы действительно хотите удалить свою реплику?");
	if (imsure == true) {
		document.form_delete.submit()
	}
}

function replic_editing_finished() {
	location.href = "product.php?id=$id";	//#my_replic";
}
</script>

</table>
</div>

</td></tr>


EOT;


$content_product_addreplic = entity_tpl("#CONTENT#", "constant", array("hashkey" => "PRODUCT_ADDREPLIC"));


$tpl_replic_my_edit_initial = <<< EOT
<tr><td colspan=4 align=right>

<b><a href="javascript:layer_switch(2)"><img src="img/down.gif" width=10 height=6 border=0 alt="я не попробовал(а) [#subject#] но очень хочу выразить своё очарование этим мылом" class=noborder style="border:0"> я не попробовал(а) [#subject#] но очень хочу выразить своё очарование этим мылом</a></b>

</td></tr>


<tr><td align=right colspan=4>

<div id="layer_2" style="display:none">
<table cellpadding=4 cellspacing=1 border=0 width=100%>

<form action="product.php?id=$id#replics" method="post" id="form_replic" name="form_replic" class=replic>
<input type=hidden name=mode value=update>
<input type=hidden name=action value=replic_edit>
<input type=hidden name=product value=$id>

<tr bgcolor=yellow>
	<th width=90>Ща пикну</th>
	<th width=96%>Чем я собсно очарован(а)</th>
</tr>


<tr valign=top bgcolor=yellow>
    <td style="padding: 1ex;">$customer_me_first</td>
    <td style="padding: 1ex;">
		<textarea class=replic name=replic onfocus="javascript:clearfield_ungray('replic', '$content_product_addreplic')" style="color:gray">$content_product_addreplic</textarea>
    </td>
</tr>

<tr valign=top bgcolor=yellow>
    <td colspan=2 align=center>
    	<input type=button class=cancel value="Пиииии!" onclick="javascript:form_replic_submit()">
    	&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type=button class=cancel value="Я передумал(а)" onclick="javascript:replic_changed_mind()">
    </td>
</tr>
</form>
</table>
</div>

<script>
function clearfield_ungray(textarea_id, text_to_clear) {
	field = MM_findObj(textarea_id)
	if (field == null) return

//	alert(field.value)
	if (field.value == text_to_clear) {
		field.value = ""
		field.style.color = "black"
	}
}

function replic_changed_mind() {
	alert("... ну и пожалуйста! и скорлупу свою не забудь... тоже мне, птенец, нахрен...")
	location.href = "product.php?id=$id#replics"
}
</script>

</td></tr>

EOT;


$tpl_replic_nocustomer_edit = <<< EOT
<tr><td colspan=4 align=right>

<b><a href="javascript:layer_switch(2)"><img src="img/down.gif" width=10 height=6 border=0 alt="я не попробовал(а) [#subject#] но очень хочу выразить своё очарование этим мылом" class=noborder style="border:0"> я очень хочу выразить своё очарование этим мылом</a></b>

</td></tr>


<tr><td align=right colspan=4 align=right>

<div id="layer_2" style="display:none">
<table cellpadding=30 cellspacing=1 border=0 width=60% style="border:1px solid gray">
<tr><td align=center>
	Выразить своё очарование могут только зарегистрированные пользователи.<br>
	Пожалуйста, <b><a href="register.php">зарегистрируйтесь</a></b> или <b><a href="auth.php">авторизуйтесь</a></b>.<br>
	Если Вы не пользовались этим мылом, можно <b><a href="askme.php?subject=хочу попробовать $subject">попробовать</a></b> :)<br>
	
</td></tr>
</table>
</div>

<br><br>
</td></tr>

EOT;


$replic_form_initial_hash = array (
	"replic" => "",
	"subject" => $subject,
	);


$tpl_separator = <<< EOT

<tr><td colspan=4 height=0 bgcolor=#F0F0F0></td></tr>

EOT;


$query_replic = "select e.*, product.ident as product_ident, customer.ident as customer_ident"
	. " from m2m_product_replic e"
	. " left join product product on e.product=product.id"
	. " left join customer customer on e.customer=customer.id"
	. " where 1=1 and e.published=1 and e.deleted=0"
	. " and e.product=$id"
//	. " order by e.date_created desc";
//	. " order by " . get_entity_orderby("m2m_product_replic")
	. " order by e.date_updated desc"
	;

$query_replic = add_sql_table_prefix($query_replic);
$qa_replic = select_queryarray($query_replic);
//pre($qa);

$replic_table = "";
foreach ($qa_replic as $row_replic) {
//	pre($row_replic);
	$tpl_replic = $tpl_replic_notmine;

	
//	if ($row_replic["customer"] == $unhashed["customer"]) {
//		if ($i_said_my_replic == 1) continue;
//		$i_said_my_replic = 1;

	if ($row_replic["id"] == $my_replic_id) {
		$tpl_replic = ($action == "replic_edit") ? $tpl_replic_my_edit : $tpl_replic_my;
	}

	$row_replic["content"] = stripslashes($row_replic["content"]);
	$row_replic["content"] = str_replace("<br />", "\n", $row_replic["content"]);

	$row_replic["customer_first"] = customer_first($row_replic);
//	$row_replic["date_updated_shortyear"] = date_updated_shortyear($row_replic);

//	$replic_table .= hash_by_tpl($row_replic, $tpl_replic, "", 0, 0);
	$replic_table .= hash_by_tpl($row_replic, $tpl_replic);

	if ($row_replic["i"] < $row_replic["rows_total"]) $replic_table .= $tpl_separator;
}

if ($replic_table == "") {
	$replic_table = <<<EOT
<tr valign=top>
    <td colspan=4 style="padding: 1ex;" align=center><b>никто ничем не очарован :(</b></td>
</tr>
EOT;

}












if ($unhashed["customer"] > 0 ) {
	if ($my_opinion_id == 0) {
		$rating_table .= hash_by_tpl($rating_form_initial_hash, $tpl_rating_my_edit_initial);
	} else {
	
	}

	if ($my_replic_id == 0) {
		$replic_table .= hash_by_tpl($replic_form_initial_hash, $tpl_replic_my_edit_initial);
	} else {
	
	}

	jsv_addvalidation("JSV_SELECT_SELECTED", "rating", "Моя оценка", 0, "form_opinion");
	jsv_addvalidation("JSV_TF_CHAR", "opinion", "Что мне понравилось, а что нет", 0, "form_opinion", $content_product_addrating_opinion);
	jsv_addvalidation("JSV_TF_CHAR", "wish", "Какие у меня есть предложения", 0, "form_opinion", $content_product_addrating_wish);


	jsv_addvalidation("JSV_TF_CHAR", "replic", "Чем же я так очарован(а)", 0, "form_replic", $content_product_addreplic);
}


if ($unhashed["customer"] == 0) {
	$rating_table .= hash_by_tpl($rating_form_initial_hash, $tpl_rating_nocustomer_edit);
}


?>

<? require "_top.php" ?>

<?=$mmenu_content?>

<?=$table?>

<br clear=all>
<a name=opinions></a>
<h3>Отзывы тех кто попробовал</h3>
<table cellpadding=4 cellspacing=1 border=0 width=100%>
<tr bgcolor=#F0F0F0>
	<th width=120>Кто пикнул</th>
	<th width=120>Оценка</th>
	<th width=40%>Что понравилось, а что нет</th>
	<th width=40%>Какие есть предложения</th>
</tr>

<?=$rating_table?>


</table>


<a name=replics></a>
<h3>Просто обсуждение</h3>
<table cellpadding=4 cellspacing=1 border=0 width=100%>
<tr bgcolor=#F0F0F0>
	<th width=120>Кто пикнул</th>
	<th width=90%>Чем собсно очарован(а)</th>
</tr>

<?=$replic_table?>


</table>



<? require "_bottom.php" ?>




<!--tr valign=top>
    <td style="padding: 1ex;" width=90>
		<table style="width: 60px; height: 60px; border: 1px solid gray; vertical-align: bottom; padding:0px; margin:0px; float: left"><tr><td align=center><a href="product.php?id=3">аватар</a></td></tr></table>
    </td>
    <td align=center>2</td>
    <td style="padding: 1ex;" width=48%>
    Мнравица что рыженькое и махровое на ощупь. Листики земляники отлично смотрятся на кафеле - любуюсь! Мылица прекрасно, моя шерсть теперь шелковиста и легко расчёсываема. Полосатенькое - сила!
    </td>
    <td style="padding: 1ex;" width=48%>
    Тока бы чуть пажощще, можно?
    </td>
</tr>

<tr valign=top>
    <td style="padding: 1ex;" width=90>
		<table style="width: 60px; height: 60px; border: 1px solid gray; vertical-align: bottom; padding:0px; margin:0px; float: left"><tr><td align=center><a href="product.php?id=3">аватар</a></td></tr></table>
    </td>
    <td align=center>10</td>
    <td style="padding: 1ex;" width=48%>
    Мнравица что рыженькое и махровое на ощупь. Листики земляники отлично смотрятся на кафеле - любуюсь! Мылица прекрасно, моя шерсть теперь шелковиста и легко расчёсываема. Полосатенькое - сила!
    </td>
    <td style="padding: 1ex;" width=48%>
    Тока бы чуть пажощще, можно?
    </td>
</tr-->



<!--tr valign=top>
    <td style="padding: 1ex 1ex 1ex 5em;">
		<table style="width: 60px; height: 60px; border: 1px solid gray; vertical-align: bottom; padding:0px; margin:0px 10px 0px 0px; float: left"><tr><td align=center><a href="javascript:alert('на профайле пользователя - чем пользовался, какие оценки какому мылу поставил, ')">аватар</a></td></tr></table>
    Мнравица что рыженькое и махровое на ощупь. Листики земляники отлично смотрятся на кафеле - любуюсь! Мылица прекрасно, моя шерсть теперь шелковиста и легко расчёсываема. Полосатенькое - сила!
    </td>
</tr>

<tr valign=top>
    <td style="padding: 1ex 1ex 1ex 10em;">
		<table style="width: 60px; height: 60px; border: 1px solid gray; vertical-align: bottom; padding:0px; margin:0px 10px 0px 0px; float: left"><tr><td align=center><a href="javascript:alert('на профайле пользователя - чем пользовался, какие оценки какому мылу поставил, ')">аватар</a></td></tr></table>
    Мнравица что рыженькое и махровое на ощупь. Листики земляники отлично смотрятся на кафеле - любуюсь! Мылица прекрасно, моя шерсть теперь шелковиста и легко расчёсываема. Полосатенькое - сила!
    </td>
</tr>


<tr valign=top>
    <td style="padding: 1ex 1ex 1ex 0em;">
		<table style="width: 60px; height: 60px; border: 1px solid gray; vertical-align: bottom; padding:0px; margin:0px 10px 0px 0px; float: left"><tr><td align=center><a href="javascript:alert('на профайле пользователя - чем пользовался, какие оценки какому мылу поставил, ')">аватар</a></td></tr></table>

    Мнравица что рыженькое и махровое на ощупь. Листики земляники отлично смотрятся на кафеле - любуюсь! Мылица прекрасно, моя шерсть теперь шелковиста и легко расчёсываема. Полосатенькое - сила!
    </td>
</tr>


<tr valign=top>
    <td style="padding: 1ex 1ex 1ex 0em;">
		<table style="width: 60px; height: 60px; border: 1px solid gray; vertical-align: bottom; padding:0px; margin:0px 10px 0px 0px; float: left"><tr><td align=center><a href="javascript:alert('на профайле пользователя - чем пользовался, какие оценки какому мылу поставил, ')">аватар</a></td></tr></table>

    Мнравица что рыженькое и махровое на ощупь. Листики земляники отлично смотрятся на кафеле - любуюсь! Мылица прекрасно, моя шерсть теперь шелковиста и легко расчёсываема. Полосатенькое - сила!
    </td>
</tr>

<tr valign=top>
    <td style="padding: 1ex 1ex 1ex 5em;">
		<table style="width: 60px; height: 60px; border: 1px solid gray; vertical-align: bottom; padding:0px; margin:0px 10px 0px 0px; float: left"><tr><td align=center><a href="javascript:alert('на профайле пользователя - чем пользовался, какие оценки какому мылу поставил, ')">аватар</a></td></tr></table>
    Мнравица что рыженькое и махровое на ощупь. Листики земляники отлично смотрятся на кафеле - любуюсь! Мылица прекрасно, моя шерсть теперь шелковиста и легко расчёсываема. Полосатенькое - сила!
    </td>
</tr-->



<!--h3>Просто обсуждение</h3>
<table cellpadding=4 cellspacing=1 border=0 width=100%>
<tr bgcolor=#F0F0F0>
	<th>Кто пикнул, что сказал</th>
</tr>


<tr valign=top>
    <td style="padding: 1ex 1ex 1ex 0em;">
		<table style="width: 60px; height: 60px; border: 1px solid gray; vertical-align: bottom; padding:0px; margin:0px 10px 0px 0px; float: left"><tr><td align=center><a href="javascript:alert('на профайле пользователя - чем пользовался, какие оценки какому мылу поставил, ')">аватар</a></td></tr></table>

    Мнравица что рыженькое и махровое на ощупь. Листики земляники отлично смотрятся на кафеле - любуюсь! Мылица прекрасно, моя шерсть теперь шелковиста и легко расчёсываема. Полосатенькое - сила!
    </td>
</tr>
</table>


<table cellpadding=4 cellspacing=1 border=0 width=100%>
<form class=replic>
<tr bgcolor=#F0F0F0>
	<th colspan=2>Ну-ка скажи чонить?</th>
</tr>

<tr valign=top>
    <td style="padding: 1ex 1ex 1ex 0em;" width=1>
		<table style="width: 60px; height: 60px; border: 1px solid gray; vertical-align: bottom; padding:0px; margin:0px 10px 0px 0px; float: left"><tr><td align=center><a href="javascript:alert('на профайле пользователя - чем пользовался, какие оценки какому мылу поставил, ')">мой аватар</a></td></tr></table>
	</td><td>
		<textarea class=replic disabled>Мнравица что рыженькое и махровое на ощупь. Листики земляники отлично смотрятся на кафеле - любуюсь! Мылица прекрасно, моя шерсть теперь шелковиста и легко расчёсываема. Полосатенькое - сила!
    	</textarea>
    </td>
</tr>

<tr valign=top>
    <td align=center colspan=2>
    	<input type=button class=piii value="Пиииии!" onclick="javascript:alert('Пока не работает')">
    </td>
</tr>

</form>
</table-->