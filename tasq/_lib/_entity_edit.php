<? require_once "__fixed.php" ?>
<?

if ($in_silent_mode == 0) {
	echo "<!-- BEGIN _entity_edit.php -->\n";
}


if ($id == 0) {
	if ($mode == "update") {
		if ($in_backoffice_readonly == 1) {
			$alertmsg = $in_backoffice_readonly_msg;
		} else {
			$fixed_sqlhash = array();
		
			foreach($fixed_fields as $fixed_field) {
				$m2m_dependtable = get_m2m_dependtable($entity, $fixed_field);
				if ($m2m_dependtable != "") {
//					pre("$entity and $fixed_field are m2m: $m2m_dependtable; skipping in update_fixed");
					continue;
				}

				if (!isset($$fixed_field)) {
					if (!isset($_REQUEST[$fixed_field])) {
						$errormsg .= "$msg_bo_required_parameter_missing $fixed_field<br>";
					} else {
						$fixed_value = $_REQUEST[$fixed_field];
					}
				} else {
					$fixed_value = $$fixed_field;
				}
				if (isset($fixed_value)) $fixed_sqlhash[$fixed_field] = $fixed_value;
			}
		
			$id = insert($insert_basehash);
			if (sizeof($fixed_sqlhash)) update($fixed_sqlhash);
		}
	} else {
		$ident = $ident_new;
	}
}

if ($id > 0) {
	if ($mode == "update") {

		if ($in_backoffice_readonly == 1) {
			$alertmsg = $in_backoffice_readonly_msg;
		} else {
			$sql_fields = mkupdatefields_fromform($entity_fields);
			
			$entity_before_update_function = $entity . "_before_update";
			if (function_exists($entity_before_update_function)) $entity_before_update_function();

			if ($sql_fields != "") {
				$query = "update " . TABLE_PREFIX . "$entity set $sql_fields where id=$id";
				$query = add_sql_table_prefix($query);
				if ($debug_query == 1) $query;
				mysql_query($query) or die ("UPDATE ENTITY failed:<br>$query<br>" . mysql_error());
				$rows_updated_onedit = mysql_affected_rows();
				update($update_basehash, array("id" => $id));
			} else {
//				echo "_entity_edit: nothing to update<br>";
			}
		
	// UPDATE IMAGES BEGIN
	
			if (!isset($entity_root_path)) $entity_root_path = $upload_abspath . "$entity/";
			if (!isset($entity_path)) $entity_path = $entity_root_path . "$id/";
	
			foreach ($entity_fields as $name => $entity_field) {
				$input_type = $entity_field[1];
				
				if ($input_type == "image" || $input_type == "upload" || $input_type == "image_random") {
			
					$query = "select $name from $entity where id=$id";
					$query = add_sql_table_prefix($query);
					$result = mysql_query($query) or $errormsg .= "SELECT_FILE [$name] failed";
					$row = mysql_fetch_row($result);
					$value_indb = $row[0];
			
					$del_cb_value = get_string("del_$name");
			
					if ($value_indb != "" && $value_indb != $ident_new && $del_cb_value == "on") {
						$del_path = $entity_path;
						if ($input_type == "image_random") $del_path = $upload_abspath . "random/image/";
	
						$file_dest = $del_path . $value_indb;
						if (file_exists($file_dest) && unlink($file_dest)) {
							$query = "update $entity set $name='' where id=$id";
							$query = add_sql_table_prefix($query);
							mysql_query($query)
								or $errormsg .= "DEL_FILE [$name] failed:<br>$query<br>" . mysql_error();
						} else {
							$errormsg .= "$msg_bo_file_delete_unable [" . $file_dest . "]<br>";
						}
					}
			
					if (isset($_FILES[$name]) && is_uploaded_file($_FILES[$name]["tmp_name"])) {
	//					if ($_FILES["$name"]['type'] == "image/pjpeg"
	//						|| $_FILES["$name"]['type'] == "image/jpeg"
	//						|| $_FILES["$name"]['type'] == "image/gif") {
							
							$moved_path = $entity_path;
							$moved_name = $_FILES["$name"]['name'];
	
							$moved_name = strtr($moved_name, $win_tran);
							$moved_name = strtr($moved_name, $fname_common);

							if ($input_type == "image_random") {
								$moved_path = $upload_abspath . "random/image/";
								$moved_name = rand(100000, 999999) . "-"
									. $_FILES["$name"]['name'];
							}
							
							create_entity_path($entity, $id);
							$moved = move_uploaded_file($_FILES["$name"]['tmp_name'],
								$moved_path . $moved_name);
							chmod($moved_path . $moved_name, 0644);
							
							$query = "update $entity set";
							$query .= " $name='" . $moved_name . "'";
							$query .= " where id=$id";
							$query = add_sql_table_prefix($query);
	//						echo $query;
							mysql_query($query)
								or $errormsg .= "UPDATE_FILE [$name] failed:<br>$query<br>" . mysql_error();
							$files_updated_onedit += mysql_affected_rows();
	//					} else {
	//						$errormsg .= "$msg_bo_file_format_wrong [" . $_FILES["$name"]['type'] . "]<br>";
	//					}
					}
				}
	
				if ($input_type == "imgtype_layer") {
					$query = "select * from imgtype where published='1' order by manorder";
					$query = add_sql_table_prefix($query);
					$result = mysql_query($query, $cms_dbc)
						or die("SELECT IMGTYPE failed:<br>$query:<br>" . mysql_error($cms_dbc));
	
					while ($imgtype_row = mysql_fetch_assoc($result)) {
						imglayer_update($imgtype_row);
					}
				}
				if ($input_type == "img_layer") {
					$field_txt = (isset($entity_field[0])) ? $entity_field[0] : "";
					$imgtype_row = select_entity_row(array("hashkey" => $field_txt), "imgtype");
					imglayer_update($imgtype_row);
				}
			}
	//		UPDATE IMAGES END
	
			if ($rows_updated_onedit == 1) {
				if ($errormsg != "") $errormsg .= "; ";
				$errormsg .= "$msg_bo_database_updated";
			}


			$entity_after_update_function = $entity . "_after_update";
			if (function_exists($entity_after_update_function)) $entity_after_update_function();
		}
	} else {
		$field = get_string("field");
		$swap_row = array();
		if ($field != "") $swap_row = select_entity_row();

		if (isset($swap_row["id"])) {
//			pre($swap_row);
			$index = get_swapdbfields_index_withfirst($field);
//			pre($index);
			if ($action == "up") $index--;
			else if ($action == "down") $index++;
			else $errormsg .= "unknown ACTION; ";
//			pre($index);
			
			$swapdbfields_array = $entity_swapdbfields_list[$entity];
			if ($index < 0 || $index >= count($swapdbfields_array)) {
				$alertmsg = "$msg_bo_database_swapfield_unable [$field]";
				$errormsg .= $alertmsg;
				$alertmsg = "";
			}

			if ($errormsg == "") {
//				pre($swapdbfields_array[$index][0]);
				$src_array = get_swapdbfields_array_withfirst($field);
				$dst_array = $swapdbfields_array[$index];
				swap_fieldgroup_within_row($src_array, $dst_array, $swap_row);
			}
		}
	}
}



if ($in_silent_mode == 0) {
	echo "<!-- END _entity_edit.php -->\n";
}

?>