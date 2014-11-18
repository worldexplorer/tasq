<?
/*
if (isset($importlog) && $importlog != "" && isset($start_import) && $start_import == 1) {
	if ($log_indb = 1) {
		$insert_hash = array (
			"ident" => $import_ident,
			"content" => $content,
			"file1" => $_FILES['csv']['name']
		);
	
		$id = insert($insert_hash, "importlog");

		if (isset($_FILES["csv"]) && is_uploaded_file($_FILES['csv']['tmp_name'])) {
			$entity_root_path = $upload_path . "importlog/";
			$entity_path = $entity_root_path . "$id/";
		
			create_entity_path($entity, $id);
		
			move_uploaded_file($_FILES['csv']['tmp_name'], $entity_path . $_FILES['csv']['name']);
		}
	}
	
	if ($log_infile == 1) {
		if (isset($log_fhandle) && $log_fhandle != "") {
			logf("</pre>\n");
			if (fclose ($log_fhandle) == TRUE) {
				echo "Closed log file [$log_fhandle]";
			}
		}
	}
}
*/
?>

<? if ($is_inline == 0) { ?>
<!-- BOTTOM BEGIN -->

<? if ($fetchlog != "") { ?>
<table cellpadding=0 cellspacing=5 style="border: 1 solid gray">
<tr><th>Fetch log</th></tr>
<tr><td>
	<table cellpadding=0 cellspacing=0>
	<?=$fetchlog?>
	</table>
</td></tr>
</table>
<? } ?>


		</td>
	</tr>
</table>
		</td>
		<? if (!isset($menu_bo)) { ?>
		<td width=20></td>
		<td align=right><? require "_menu_right.php" ?></td>
		<? } ?>
	</tr>
</table>
<p align=right>[<?= round(getmicrotime() - $start_execution_time, 2) ?> sec]</p>


<? if ($layers_total > 0) echo "<script>layers_total=$layers_total</script>" ?>

<? if ($itnames_jsarray != "") { ?>
<script>
itnames_array = new Array (<?=$itnames_jsarray?>)
if (itnames_array.length > 0) {
	itname_first = itnames_array[0]
	it = document.all(itname_first)
	it.focus()
}
</script>
<? } ?>


<!-- jsv_flush_validation_functions_and_core -->
<?=jsv_flush_validation_functions_and_core()?>
<!-- /jsv_flush_validation_functions_and_core -->


<? freetext_MCE_jsinheader() ?>

</body>
</html>
<? } ?>
