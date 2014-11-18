<? require_once "__fixed.php" ?>

<!-- BEGIN _updown.php -->

<?
if (($action == "up" || $action == "down") && $id > 0) {
	if ($in_backoffice_readonly == 1) {
		$alertmsg = $in_backoffice_readonly_msg;
	} else {
		$query = "select $manorder_field, ident, date_updated from $entity where id=$id and deleted=0";
	//	echo "move from: $query<br>";
		$query = add_sql_table_prefix($query);
		$result = mysql_query($query) or die("Up\Down query1 failed:<br>$query");
		$row = mysql_fetch_assoc($result);
		$sort1 = $row[$manorder_field];
		$ident = $row["ident"];
		$date_updated1 = $row["date_updated"];
		
		$query_next = "select e.id, e.$manorder_field, e.date_updated from $entity e where e.$manorder_field>$sort1 $o2mfixed_cond and e.deleted=0 order by e.$manorder_field";
		$query_prev = "select e.id, e.$manorder_field, e.date_updated from $entity e where e.$manorder_field<$sort1 $o2mfixed_cond and e.deleted=0 order by e.$manorder_field desc";
		
		if ($m2mfixed_cond != "") {
// from _list.php, case '-m2mjoin oregons_s +got_backhref'
// criteria got for product.php when m2m_product_pgroup


			// to avoid duplicate joints from $entity_fixed_list and $table_columns
			$joint_list_tmp_updown = array();
			$list_left_fields_updown = "";
			$list_left_o2mjoins_updown = "";
			$list_left_m2mjoins_updown = "";
			
			
			// improved list_query with left joins to fixed tables (add slave tables entity is pointing to)
			if (isset($entity_fixed_list[$entity])) {
				foreach ($entity_fixed_list[$entity] as $dependant_entity) {
					if ($dependant_entity == "parent_id") continue;
			
					if (in_array($dependant_entity, $joint_list_tmp_updown)) {
						continue;
					} else {
						$joint_list_tmp_updown[] = $dependant_entity;
			//			pre(pr($entity_fixed_list[$entity]) . $joint_list_tmp_updown);
					}
				
					$dependant_entity = makestrict($dependant_entity);
					
					$m2m_dependtable_updown = get_m2m_dependtable($entity, $dependant_entity);
			//		pre("entity[$entity] dependant_entity[$dependant_entity] m2m_dependtable_updown[$m2m_dependtable_updown]");
				
					if ($m2m_dependtable_updown == "") {
						$list_left_fields_updown .= ", $dependant_entity.ident as ${dependant_entity}_ident";
						$list_left_o2mjoins_updown .=
							" left join $dependant_entity $dependant_entity"
								. " on e.$dependant_entity=$dependant_entity.id";
				
					} else {
						$list_left_fields_updown .= ", $dependant_entity.ident as ${dependant_entity}_ident";
						$list_left_m2mjoins_updown .=
							" left join $m2m_dependtable_updown m2m_$dependant_entity"
								. " on m2m_$dependant_entity.$entity=e.id and m2m_$dependant_entity.deleted=0"
							. " left join $dependant_entity $dependant_entity"
								. " on $dependant_entity.id=m2m_$dependant_entity.$dependant_entity";
				
					}
				}
			}

			$query_next = "select e.id, e.$manorder_field, e.date_updated"
				. " from $entity e"
				. $list_left_m2mjoins_updown
				. " where e.$manorder_field>$sort1"
				. $o2mfixed_cond
				. $m2mfixed_cond
				. " and e.deleted=0 order by e.$manorder_field";

			$query_prev = "select e.id, e.$manorder_field, e.date_updated"
				. " from $entity e"
				. $list_left_m2mjoins_updown
				. " where e.$manorder_field<$sort1"
				. $o2mfixed_cond
				. $m2mfixed_cond
				. " and e.deleted=0 order by e.$manorder_field desc";
		
		}
		
		if ($debug_query == 1) {
			echo "_updown query_next=[$query_next]<br>";
			echo "_updown query_prev=[$query_prev]<br>";
		}

		if ($order_dir == "desc") {
	//		echo "desc";
			if ($action == "up") {
				$query = $query_next;
				$direction = $msg_direction_up;
			} else if ($action == "down") {
				$query = $query_prev;
				$direction = $msg_direction_down;
			}
		} else {
	//		echo "asc";
			if ($action == "up") {
				$query = $query_prev;
				$direction = $msg_direction_up;
			} else if ($action == "down") {
				$query = $query_next;
				$direction = $msg_direction_down;
			}
		}
	
		$query = add_sql_table_prefix($query);
		$result = mysql_query($query) or die("Up\Down query2 failed:<br>$query");
		$row = mysql_fetch_assoc($result);
		if ($row != FALSE) {
			$id2 = $row["id"];
			$sort2 = $row["$manorder_field"];
			$date_updated2 = $row["date_updated"];
			if ($debug_query == 1) {
//				echo "move to: $query<br>";
				echo "_updown [$id:$sort1 to $id2:$sort2]<br>$query<br>";
			}

			$query_update = "update $entity set date_updated='$date_updated1', $manorder_field=$sort2 where id=$id";
			$query_update = add_sql_table_prefix($query_update);
			mysql_query($query_update) or die("Query failed");

			$query_update = "update $entity set date_updated='$date_updated2', $manorder_field=$sort1 where id=$id2";
			$query_update = add_sql_table_prefix($query_update);
			mysql_query($query_update) or die("Query failed");
			$errormsg .= "$msg_bo_updown_element [" . stripslashes($ident) . "] $msg_bo_updown_element_moved $direction";
		} else {
			$errormsg .= "$msg_bo_updown_element_move_unable";
		}
	}
}

if ($mode == "update") {
	$entity_before_update_function = $entity . "_before_update";
	if (function_exists($entity_before_update_function)) $entity_before_update_function();
}


?>

<!-- END _updown.php -->