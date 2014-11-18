<?


function img_merge_watermark($ar_from_abspath, $ar_fname, &$imgtype_row, $autoresize_type, $quality = 0) {
	global $upload_abspath;

	global $img_resize_quality_default;
	if (intval($quality) == 0) $quality = $imgtype_row[$autoresize_type . "_autoresize_qlty"];
	if (intval($quality) == 0) $quality = $img_resize_quality_default;

	$autoresize_debug = $imgtype_row[$autoresize_type . "_autoresize_debug"];


	$merge_seed = intval($imgtype_row["merge_seed"]);
	if ($merge_seed == 0) {
		$merge_seed = rand(100000, 999999);
		$imgtype_row["merge_seed"] = $merge_seed;
		update(array("merge_seed" => $merge_seed), array("id" => $imgtype_row["id"]), "imgtype");
	}


	$src_absfname = $ar_from_abspath . $ar_fname;
	if (!is_file($src_absfname)) {
		if ($autoresize_debug == 1) pre("img_merge_watermark(): autoresized image does not exists: [$dst_absname]");
		return;
	}

	$merge_img = $imgtype_row[$autoresize_type . "_merge_img"];
	$watermark_alpha = $imgtype_row[$autoresize_type . "_merge_alfa"];

//положение водяного знака может быть tl, t, tr, l, c, r, bl, b, br, random
//или комбинацией значений, перечисленных через запятую,
//например расположить водяной знак в случайном углу: "tl,tr,bl,br"
	if(!isset($imgtype_row[$autoresize_type . "_merge_position"])) $watermark_align = "br";
	else $watermark_align = $imgtype_row[$autoresize_type . "_merge_position"];

	$merge_absname = $upload_abspath . "imgtype/" . $imgtype_row["id"] . "/" . $merge_img;

	if (!is_file($merge_absname)) {
// watermark does not exist, but we still copy and rename src
		$dst_fname = preg_replace("~(.*?)-(.*)\.(.*)~", "\\1-$merge_seed.\\3", $ar_fname);
		$dst_absfname = $ar_from_abspath . $dst_fname;
		$copied = copy($src_absfname, $dst_absfname);

		if ($autoresize_debug == 1) pre("img_merge_watermark(" . $imgtype_row["hashkey"] . ":" . $autoresize_type . "): watermark image does not exists: [$merge_absname]; copied[$copied] src[$src_absfname] to[$dst_absfname]");


		return;
	}

//	pre($merge_absname);

	$merge_size = getimagesize($merge_absname);
	if (!isset($merge_size["mime"])) {
		if ($autoresize_debug == 1) pre("img_merge_watermark(): merging does not have a mime: [$merge_absname]");
		return;
	}

	$sw = $merge_size[0];
	$sh = $merge_size[1];

	$wm_src = null;
	switch($merge_size["mime"]) {
		case "image/gif":
			$wm_src = ImageCreateFromGIF($merge_absname);
			break;

		case "image/jpeg":
			$wm_src = ImageCreateFromJPEG($merge_absname);
			break;

		case "image/png":
			$wm_src = ImageCreateFromPNG($merge_absname);
			break;

		default:
			if ($autoresize_debug == 1) pre("img_merge_watermark(): merging is not a [gif/jpeg/png]: [$src_absfname]");
			return;
	}

	$src_size = getimagesize($src_absfname);
	if (!isset($src_size["mime"])) {
		if ($autoresize_debug == 1) pre("img_merge_watermark(): src is not an image : [$src_absfname]");
		return;
	}

	$tw = $src_size[0];
	$th = $src_size[1];

	switch($src_size["mime"]) {
		case "image/gif":
			$src = ImageCreateFromGIF($src_absfname);
			break;

		case "image/jpeg":
			$src = ImageCreateFromJPEG($src_absfname);
			break;

		case "image/png":
			$src = ImageCreateFromPNG($src_absfname);
			break;

		default:
			if ($autoresize_debug == 1) pre("img_merge_watermark(): src is not a [gif/jpeg/png]: [$src_absfname]");
	}


	$dst = imagecreatetruecolor($tw, $th);

	$pixelcolor = imagecolorallocatealpha($dst, 255, 255, 255, 127);
	imagefilledrectangle($dst, 0, 0, imagesx($dst), imagesy($dst), $pixelcolor);


	if($sw > $tw || $sh > $th){
		if($sw / $sh > $tw / $th){
			$scale = $sw / $tw;
			$sw = $tw;
			$sh = $sh / $scale;
		} else if($sh > $th){
			$scale = $sh / $th;
			$sh = $th;
			$sw = $sw / $scale;
		}
	}

	$wm = imagecreatetruecolor($sw, $sh);
	
	imagealphablending($wm, false);
	
	$pixelcolor = imagecolorallocatealpha($wm, 255, 255, 255, 127);
	imagefilledrectangle($wm, 0, 0, imagesx($wm), imagesy($wm), $pixelcolor);

	imagecopyresampled($wm, $wm_src, 0, 0, 0, 0, $sw, $sh, imagesx($wm_src), imagesy($wm_src));
	
	for($x = 0; $x < imagesx($wm); $x++){
		for($y = 0; $y < imagesy($wm); $y++){
			$rgba = imagecolorat($wm, $x, $y);
	
			$a = ($rgba & 0x7F000000) >> 24;
			$r = ($rgba & 0xFF0000) >> 16;
			$g = ($rgba & 0x00FF00) >> 8;
			$b = ($rgba & 0x0000FF);
			
			$a = 127 - floor((127 - $a) * $watermark_alpha / 100);
			
			$pixelcolor = imagecolorallocatealpha($wm, $r, $g, $b, $a);
			imagesetpixel($wm, $x, $y, $pixelcolor);
		}
	}

	$arr = preg_split("/[\s.,;]+/", $watermark_align);
	
	if(count($arr) == 1){
		$wm_align = $arr[0];
	} else {
		$wm_align = $arr[rand(0, count($arr)-1)];
	}
	
	switch($wm_align){
		case "t":
			$dx = floor((imagesx($dst) - imagesx($wm)) / 2);
			$dy = 0;
		break;

		case "tr":
			$dx = imagesx($dst) - imagesx($wm);
			$dy = 0;
		break;

		case "l":
			$dx = 0;
			$dy = floor((imagesy($dst) - imagesy($wm)) / 2);
		break;

		case "c":
			$dx = floor((imagesx($dst) - imagesx($wm)) / 2);
			$dy = floor((imagesy($dst) - imagesy($wm)) / 2);
		break;

		case "r":
			$dx = imagesx($dst) - imagesx($wm);;
			$dy = floor((imagesy($dst) - imagesy($wm)) / 2);
		break;

		case "bl":
			$dx = 0;
			$dy = imagesy($dst) - imagesy($wm);
		break;

		case "b":
			$dx = floor((imagesx($dst) - imagesx($wm)) / 2);
			$dy = imagesy($dst) - imagesy($wm);
		break;

		case "br":
			$dx = imagesx($dst) - imagesx($wm);
			$dy = imagesy($dst) - imagesy($wm);
		break;

		case "random":
			$dx = rand(0, imagesx($dst) - imagesx($wm));
			$dy = rand(0, imagesy($dst) - imagesy($wm));
		break;

		default:
			$dx = 0;
			$dy = 0;
		break;
	}
	
	imagealphablending($dst, false);
	imagecopy($dst, $src, 0, 0, 0, 0, imagesx($src), imagesy($src));
	
	imagealphablending($dst, true);
	imagecopy($dst, $wm, $dx, $dy, 0, 0, imagesx($wm), imagesy($wm));
	
	$dst_fname = preg_replace("~(.*?)-(.*)\.(.*)~", "\\1-$merge_seed.\\3", $ar_fname);
	$dst_absfname = $ar_from_abspath . $dst_fname;

	switch($src_size["mime"]) {
		case "image/gif":
			$dst_absfname = preg_replace("/(.*\.)(gif)$/i", "\\1jpg", $dst_absfname);

		case "image/jpeg":
			ImageJpeg($dst, $dst_absfname, $quality);
			break;
	
		case "image/png":
			imagealphablending($dst, false);
			imagesavealpha($dst, true);
			ImagePng($dst, $dst_absfname);
			break;
		
		default:
			if ($autoresize_debug == 1) pre("img_merge_watermark(): src is not a [gif/jpeg/png]: [$src_absfname]");
	}

	imagedestroy($wm);
	imagedestroy($wm_src);
	imagedestroy($dst);
	imagedestroy($src);
}

function img_resize($abspath, $src_fname, $dst_w = 0, $dst_h = 0, $dst_fname = "_global:img_resized_fname", $quality = 85, $crop_from_X = -1, $crop_from_Y = -1) {
	global $alertmsg, $debug_img;
	$ret = "";

	global $img_resize_quality_default;
	if (intval($quality) == 0) $quality = $img_resize_quality_default;
	$dst_w = intval($dst_w);
	$dst_h = intval($dst_h);

//	$display_errors_bak = ini_get("display_errors");
//	ini_set("display_errors", 0);

	$src_absfname = $abspath . $src_fname;

	$dst_fname = absorb_variable($dst_fname);
	if ($dst_fname == "") {
		$dst_fname = "thumb_" . $dst_w . "x" . $dst_h . "-" . $src_fname;
	}

	$dst_absfname = $abspath . $dst_fname;
	
	if (is_file($src_absfname) && !is_file($dst_absfname)) {
		$src_size = getimagesize($src_absfname);
		if (isset($src_size[1])) {

			$w = $src_size[0];
			$h = $src_size[1];

//если один из целевых размеров равен 0, то будем масштабировать пропорционально, но (!)
//сначала посмотрим на исходник и определим его больший размер - по нему и будем жать
			if(($dst_h == 0 && $dst_w != 0) ||
			   ($dst_w == 0 && $dst_h != 0)){
				if($w > $h && $dst_w == 0){
					$dst_w = $dst_h;
					$dst_h = 0;
				}
				
				if($h > $w && $dst_h == 0){
					$dst_h = $dst_w;
					$dst_w = 0;
				}
			}
	
			$crop_from_X = 0;
			$crop_from_Y = 0;

// Precalculations in case of insufficient input
			if($dst_h == 0 && $dst_w == 0){
				$dst_w = $w;
				$dst_h = $h;
			} else if($dst_h == 0){
				$dst_h = floor($h * $dst_w / $w);
			} else if($dst_w == 0){
				$dst_w = floor($w * $dst_h / $h);
			} else {
	
				if($dst_w / $dst_h > $w / $h){
					$temp_h = $h;
					$h = floor($dst_h / $dst_w * $w);
					$crop_from_Y = floor(($temp_h - $h) / 2);
				} else {
					$temp_w = $w;
					$w = floor($dst_w / $dst_h * $h);
					$crop_from_X = floor(($temp_w - $w) / 2);
				}
			}

			if ($debug_img == 1) pre("img_resize($abspath, $src_fname, $dst_w, $dst_h, $dst_fname, $quality, $crop_from_X, $crop_from_Y)");
		
			$dst_image = ImageCreateTrueColor($dst_w, $dst_h);
			
//			pre($src_size);
			switch($src_size["mime"]) {
				case "image/gif":
					$src_image = ImageCreateFromGIF($src_absfname);
					imagecopyResampled($dst_image, $src_image, 0, 0, $crop_from_X, $crop_from_Y, $dst_w, $dst_h, $w, $h);
					$dst_absfname = preg_replace("/(.*\.)(gif)$/i", "\\1jpg", $dst_absfname);
					$dst_fname = basename($dst_absfname);
					ImageJpeg($dst_image, $dst_absfname, $quality);
					imagedestroy($src_image);
					imagedestroy($dst_image);
					break;

				case "image/jpeg":
					$src_image = ImageCreateFromJPEG($src_absfname);
					imagecopyResampled($dst_image, $src_image, 0, 0, $crop_from_X, $crop_from_Y, $dst_w, $dst_h, $w, $h);
					ImageJpeg($dst_image, $dst_absfname, $quality);
					imagedestroy($src_image);
					imagedestroy($dst_image);
					break;
			
				case "image/png":
					$src_image = ImageCreateFromPNG($src_absfname);
					imagecopyResampled($dst_image, $src_image, 0, 0, $crop_from_X, $crop_from_Y, $dst_w, $dst_h, $w, $h);
					ImagePng($dst_image, $dst_absfname);
					imagedestroy($src_image);
					imagedestroy($dst_image);
					break;
				
				case "image/bmp":
					$src_image = @ImageCreateFromWBMP($src_absfname);
					if ($src_image != "") {
						imagecopyResampled($dst_image, $src_image, 0, 0, $crop_from_X, $crop_from_Y, $dst_w, $dst_h, $w, $h);
						ImageJpeg($dst_image, $dst_absfname, $quality);
						imagedestroy($src_image);
						imagedestroy($dst_image);
					} else {
						$unlinked = unlink($src_absfname);
						if ($debug_img == 1) pre("src_image=$src_image, dst_fname=$dst_fname, dst_absfname=$dst_absfname, src_absfname=$src_absfname, unlinked=$unlinked");
						$alertmsg .= 'Указанный Вами файл BMP не может быть преобразован.\nЗагружайте пожалуйста файлы JPG вместо BMP.\\n\\nФайл BMP может быть преобразован в JPG\\nс помощью программ: Photoshop, ACDSee, XnView...\n\nФайл удалён с сервера.\n\n';
					}
					break;
			
				case "image/tiff":
					$unlinked = unlink($src_absfname);
					if ($debug_img == 1) pre("src_image=$src_image, dst_fname=$dst_fname, dst_absfname=$dst_absfname, src_absfname=$src_absfname, unlinked=$unlinked");
					$alertmsg .= 'Указанный Вами файл TIFF не может быть преобразован.\nЗагружайте пожалуйста файлы JPG вместо TIFF.\n\nФайл TIFF может быть преобразован в JPG\nс помощью программ: Photoshop, ACDSee, XnView...\n\nФайл удалён с сервера\n\n';
					break;

				default:
					$unlinked = unlink($src_absfname);
					if ($debug_img == 1) pre("img_resize(): NO HANDLER FOR IMAGE TYPE [" . $src_size["mime"] . "]: $src_absfname");
					if ($debug_img == 1) pre("src_image=$src_image, dst_fname=$dst_fname, dst_absfname=$dst_absfname, src_absfname=$src_absfname, unlinked=$unlinked");
					$alertmsg .= 'Указанный Вами файл не может быть преобразован.\\nЗагружайте пожалуйста фотографии в формате JPG.\n\nФайл удалён с сервера\n\n';
			}
		} else {
			if ($debug_img == 1) "img_resize(): WRONG IMAGESIZE: $src_absfname [" . pr($src_size) . "]";
			$alertmsg .= 'Указанный Вами файл не может быть преобразован.\\nЗагружайте пожалуйста фотографии в формате JPG.\n\n';
		}
	} else {
		if ($debug_img == 1) "img_resize(): src_absfname=[$src_absfname] should exist and dst_absfname=[$dst_absfname] should NOT exist";
	}

//	ini_set("display_errors", $display_errors_bak);

	return $dst_fname;
}


function is_image_by_ext($img_abspath) {
	$ret = 0;
	$pathinfo = pathinfo($img_abspath);
//	pre($pathinfo);
	if (isset($pathinfo["extension"])) {
		$ext = strtolower($pathinfo["extension"]);
		if (preg_match("/^jpg|jpeg|gif|png$/i", $ext)) $ret = 1;
	}
	return $ret;
}


function imgsrc_input($row) {
	global $upload_abspath, $upload_relpath, $img_src_input_size;
	$ret = "";

	$img_field = "";

	if ($img_field == "" && img_exists($row, "img") == 1) $img_field = "img";
	if ($img_field == "" && img_exists($row, "img_big") == 1) $img_field = "img_big";
	if ($img_field == "") return $ret;

	$img_owner_entity = $row["owner_entity"];
	$img_owner_entity_id = $row["owner_entity_id"];
	$img_id = $row["id"];
	$img_fname = $row[$img_field];

	$img_abspath = $upload_abspath . "$img_owner_entity/$img_owner_entity_id/img/$img_id/$img_fname";
	$img_relpath = $upload_relpath . "$img_owner_entity/$img_owner_entity_id/img/$img_id/$img_fname";
	
	$tag = "";

	if (is_image_by_ext($img_abspath)) {
		$tag = "&lt;img src='$img_relpath' width=#IMG_W# height=#IMG_H# hspace=0 vspace=0 border=0&gt;";
	} else {
		$img_fsize = img_fsize($row, $img_field);
		$tag = "&lt;a href='$img_relpath' target='_blank'&gt;$img_fsize&lt;/a&gt;&lt;img src='img/shortcut.gif' width=7 height=7 style='border:0px solid #eeeeee' align=absmiddle hspace=2 vspace=2&lt;";
	}
	
	
	$tpl = <<< EOT
<input type=text size="$img_src_input_size" value="$tag" title="Click, [Ctrl-C], [Ctrl-V]" onfocus="select()">
EOT;
	$ret = hash_by_tpl($row, $tpl);

	return $ret;
}

function img_thumb_unlink($row) {
	global $upload_relpath, $upload_abspath;
//	global $img_thumb_width, $img_thumb_height;
	$ret = "[thumb here]";

//	pre($row);
	$img_thumb_width = $row["img_thumb_width"];
	$img_thumb_height = $row["img_thumb_height"];

	$field_thumbfrom = "";
	if ($field_thumbfrom == "" && img_exists($row, "img_big") == 1) $field_thumbfrom = "img_big";
	if (img_exists($row, "img") == 1) $field_thumbfrom = "img";

	if ($field_thumbfrom != "") {
		$thumb_fname = img_resized_fname($row, $field_thumbfrom, "thumb", $img_thumb_width, $img_thumb_height);
		$thumb_row = array_merge($row, array("img_thumb" => $thumb_fname));
		img_unlink($thumb_row, "img_thumb");
	}
}

function img_thumb($row) {
	global $upload_relpath, $upload_abspath, $options_color_gray;
//	global $img_thumb_width, $img_thumb_height;
	global $msg_bo_img_preview_only;

	$ret = "";

	if ($row["img_thumb_present"] == 0) return $ret;

//	pre($row);
	$img_thumb_width = $row["img_thumb_width"];
	$img_thumb_height = $row["img_thumb_height"];

	$field_thumbfrom = "";
	if ($field_thumbfrom == "" && img_exists($row, "img_big", 1) == 1) $field_thumbfrom = "img_big";
//	echo $field_thumbfrom;
	if (img_exists($row, "img", 1) == 1) $field_thumbfrom = "img";
	if ($field_thumbfrom != "" && is_image_by_ext($row[$field_thumbfrom]) == 0) return $ret;

	if ($field_thumbfrom != "") {
		$img_org = $row[$field_thumbfrom];
		$img_org_w = $row[$field_thumbfrom . "_w"];
		$img_org_h = $row[$field_thumbfrom . "_h"];

		$make_thumb = 1;
		if ($img_org_w <= $img_thumb_width && $img_thumb_width > 0) $make_thumb = 0;
//		echo "$img_org_w > $img_thumb_width && $img_thumb_width > 0 : [" . intval($img_org_w < $img_thumb_width && $img_thumb_width > 0) . "]<br>";

		if ($img_org_h <= $img_thumb_height && $img_thumb_height > 0) $make_thumb = 0;
//		echo "$img_org_h > $img_thumb_height && $img_thumb_height > 0 : [" . intval($img_org_h < $img_thumb_height && $img_thumb_height > 0) . "]<br>";
		
		if ($make_thumb == 1) {
//		echo "[$img_org_w : $img_org_h] > [$img_thumb_width : $img_thumb_height]<br>";
//		echo "will make thumb from $field_thumbfrom<br><br>";
			$thumb_fname = img_resized_fname($row, $field_thumbfrom, "thumb", $img_thumb_width, $img_thumb_height);
			$thumb_row = array_merge($row, array("img_thumb" => $thumb_fname));
			
			$thumb_from_absname = $upload_abspath . img_relpath($thumb_row, $field_thumbfrom);
			$thumb_from_abspath = dirname($thumb_from_absname) . "/";
	
	
			if (img_exists($thumb_row, "img_thumb") == 0) {
				img_resize($thumb_from_abspath, $thumb_row[$field_thumbfrom],
						$img_thumb_width, $img_thumb_height, $thumb_fname);
			}
		} else {
			$thumb_row = array_merge($row, array("img_thumb" => $img_org));
		}

		$thumb_relname = img_relpath($thumb_row, "img_thumb");
		$thumb_abspath = $upload_abspath . $thumb_relname;

		if (img_exists($thumb_row, "img_thumb") == 1) {
			$img_size = getimagesize($thumb_abspath);
//			pre($img_size);
			$thumb_row["img_thumb_wh"] = $img_size[3];
			$thumb_row["img_thumb_relpath"] = $upload_relpath . img_relpath($thumb_row, "img_thumb");

			$tpl = <<< EOT
<img src="#IMG_THUMB_RELPATH#" #IMG_THUMB_WH# border=1 style="border-color: $options_color_gray" alt="$msg_bo_img_preview_only">
EOT;

			$tpl = <<< EOT
<img src="#IMG_THUMB_RELPATH#" #IMG_THUMB_WH# border=1 style="border-color: $options_color_gray" alt="">
EOT;
			$ret = hash_by_tpl($thumb_row, $tpl);
		}
	}

	return $ret;
}

function img_resized_fname($row, $field, $prefix, $resized_width, $resized_height) {
	global $upload_abspath, $debug_img;

	$ret = "";

	$src_fname = $row[$field];
	if ($src_fname != "") {
		$src_absfname = $upload_abspath . img_relpath($row, $field);

		$src_size = getimagesize($src_absfname);
		if ($src_size == FALSE) pre("img_resized_fname(): getimagesize(" . $src_absfname . ") == FALSE");

		switch($src_size["mime"]) {
			case "image/gif":
				$src_fname = preg_replace("/(.*\.)(gif)$/i", "\\1jpg", $src_fname);
				break;
				
			default:
		}

		$ret = $prefix . "_" . $resized_width . "x" . $resized_height . "-" . $src_fname;
	}

	return $ret;
}

function img_row_src_html($row) {
	global $msg_bo_img_original, $msg_tag_shortcut;

	$ret = "";

	$tpl_has_src = <<<EOT
<a href="#IMG_SRC#" target="_blank">$msg_tag_shortcut $msg_bo_img_original</a>
EOT;

	$tpl_default = ($row["id"] == 0) ? "<nobr>&lt;URL URL…&gt;</nobr>" : "<nobr>&lt;URL&gt;</nobr>";

	$tpl = ($row["img_src"] != "") ? $tpl_has_src : $tpl_default;

	$ret = hash_by_tpl($row, $tpl);
	
	return $ret;
}


	if (!isset($max_execution_time)) $max_execution_time = ini_get("max_execution_time");
	if (!isset($options_color_gray)) $options_color_gray = "#AAAAAA";

	$img_layer_imgcnt = 0;
	$img_i = 0;
	$img_file_input_size = 40;
//	$img_file_input_size = $input_size["file"];
	$img_text_input_size = 40;
	$img_wh_input_size = 2;
	$img_qlty_input_size = 1;
	$img_pound_input_size = 9;
	$img_src_input_size = 20;

	$owner_entity = "_global:entity";
	$owner_entity_id = "_global:id";

//	$img_thumb_width = 0;
//	$img_thumb_height = 100;
	$img_resize_quality_default = 85;
	
	$tpl_img_big_resize = <<< EOT
<tr>
	<td><div id="layer_img_qresize_#ID#" style="display: #DISPLAY_CONTROL#"><span ondblclick="javascript:popup_bo('imgtype-edit.php?id=#IMGTYPE#&layer_opened_nr=1')">q</span>
		<input type="text" class="text" size="$img_qlty_input_size" name="#ID#_#IMGTYPE#_img_big_resize_q" value="#BIG_RESIZE_QLTY#" title="$msg_bo_img_jpeg_save_optimalq"></div>
	</td>
	<td><div id="layer_img_resize_#ID#" style="display: #DISPLAY_CONTROL#">
		<input type="text" class="text" size="$img_wh_input_size" name="#ID#_#IMGTYPE#_img_big_resize_w" value="#BIG_RESIZE_WIDTH#" title="$msg_bo_img_width_destination">
X
<input type="text" class="text" size="$img_wh_input_size" name="#ID#_#IMGTYPE#_img_big_resize_h" value="#BIG_RESIZE_HEIGHT#" title="$msg_bo_img_height_destination">&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="#ID#_#IMGTYPE#_img_big_resize" id="#ID#_#IMGTYPE#_img_big_resize" #BIG_RESIZE_CHECKED#><label for="#ID#_#IMGTYPE#_img_big_resize" title="$msg_bo_img_big_overwrite_tip">$msg_bo_img_big_overwrite</label></div>
	</td>
</tr>
EOT;

	$tpl_img_resize = <<< EOT
<tr>
	<td><div id="layer_img_big_qresize_#ID#" style="display: #DISPLAY_CONTROL#"><span ondblclick="javascript:popup_bo('imgtype-edit.php?id=#IMGTYPE#&layer_opened_nr=2')">q</span>
		<input type="text" class="text" size="$img_qlty_input_size" name="#ID#_#IMGTYPE#_img_resize_q" value="#RESIZE_QLTY#" title="$msg_bo_img_jpeg_save_optimalq"></div>
	</td>
	<td><div id="layer_img_big_resize_#ID#" style="display: #DISPLAY_CONTROL#">
		<input type="text" class="text" size="$img_wh_input_size" name="#ID#_#IMGTYPE#_img_resize_w" value="#RESIZE_WIDTH#" title="$msg_bo_img_height_destination">
X
<input type="text" class="text" size="$img_wh_input_size" name="#ID#_#IMGTYPE#_img_resize_h" value="#RESIZE_HEIGHT#" title="$msg_bo_img_height_destination">&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="#ID#_#IMGTYPE#_img_resize" id="#ID#_#IMGTYPE#_img_resize" #RESIZE_CHECKED#><label for="#ID#_#IMGTYPE#_img_resize" title="$msg_bo_img_small_create_frombig_tip">$msg_bo_img_small_create_frombig</label></div>
	</td>
</tr>
EOT;


function img_big_resize_control($row, $is_new = 0) {
	global $tpl_img_big_resize;
	
	$ret = "";
	
	$row["big_resize_checked"] = "";
	if ($is_new == 1 && $row["big_resize_default_checked"] == 1) $row["big_resize_checked"] = "checked";
	if ($row["img_big_present"] == 1 && $row["big_resize_published"] == 1) $ret = hash_by_tpl($row, $tpl_img_big_resize);
	
	return $ret;
}

function img_resize_control($row, $is_new = 0) {
	global $tpl_img_resize;
	
	$ret = "";
	
	$row["resize_checked"] = "";
	if ($is_new == 1 && $row["resize_default_checked"] == 1) $row["resize_checked"] = "checked";
	if ($row["img_present"] == 1 && $row["resize_published"] == 1) $ret = hash_by_tpl($row, $tpl_img_resize);
	
	return $ret;
}

function img_new_big_resize_control($row) {
	return img_big_resize_control($row, 1);
}

function img_new_resize_control($row) {
	return img_resize_control($row, 1);
}

function img_control_tr($row) {
	$ret = "";

	if (
		($row["img_present"] == 1 && $row["resize_published"] == 1)
		|| ($row["img_big_present"] == 1 &&  $row["big_resize_published"] == 1)
		) {
		$tpl = <<< EOT
<tr><td colspan=2><div id="layer_img_hr_#ID#" style="display: #DISPLAY_CONTROL#"><hr></td></tr>
EOT;
		$ret = hash_by_tpl($row, $tpl);
	}

	return $ret;
}

// MSG_ADD = Новая картинка
	$tpl_img_new = <<< EOT
<tr valign="middle">
	<td align=right>
		<font class="name">#MSG_ADD#</font>
		<!--br><br><br><input type="checkbox" name="0_img_pub" id="0_#IMGTYPE#_img_pub" checked><label for="0_#IMGTYPE#_img_pub" title="этот флажок действует, \nесли картинка вставляется \nчерез маркер &#35;IMG#ID#&#35;">опубликовано</label-->
	</td>
	<td style="padding-bottom:2ex;"><table cellpadding=0 cellspacing=5 style="border: 1px solid $options_color_gray; width: 37em">@img_singlerow@@img_control_tr@@img_new_big_resize_control@@img_new_resize_control@</table></td>
</tr>
EOT;

//extended version, используется в person-edit.php @richaclub.ru
/*
	$tpl_img_singlerow = <<< EOT
<tr valign="top">
	<td align=right><font class="name">#MSG_IDENT#</font> <input type="text" size="$img_pound_input_size" value="&#35;IMG#ID#&#35;" title="Click, [Ctrl-C], [Ctrl+V] в нужном месте \n\nэтот маркер, вставленный в текст: \n1. отображает [маленькую] картинку с подписью \n2. при клике на [маленкую] картинку \nотрывается всплывает окошко \n3. в окошке нужных размеров отображается \n[большая] картинка с подписью" onfocus="select()"><br>
		<!--div style="height:2.5em">@imgsrc_input@</div-->
		<input type="checkbox" name="#ID#_img_pub" id="#ID#_img_pub" #PUB_CHECKED#><label for="#ID#_img_pub" title="этот флажок действует, \nесли картинка вставляется \nчерез маркер &#35;IMG#ID#&#35;">опубл</label>&nbsp;&nbsp;
		<input type="checkbox" name="#ID#_img_main" id="#ID#_img_main" #MAIN_CHECKED#><label for="#ID#_img_main">глав</label>&nbsp;&nbsp;
		<input type="checkbox" name="#ID#_img_del" id="#ID#_img_del" onclick="alert_img_ondel(this)"><label for="#ID#_img_del" title="картинки физически \nстираются с диска, \nосвобождая место">уд</label><br>

		<input type="checkbox" name="#ID#_img_faceting" id="#ID#_img_faceting" #FACETING_CHECKED#><label for="#ID#_img_faceting">новая</label>&nbsp;&nbsp;
		<input type="checkbox" name="#ID#_img_faceted" id="#ID#_img_faceted" #FACETED_CHECKED#><label for="#ID#_img_faceted">годится:</label>&nbsp;&nbsp;<br>
		#DATE_FACETED#

		</td>
	<td style="padding-bottom:2ex;">
		<table cellpadding=0 cellspacing=0 border=0>
		<tr valign=top>
			<td style="padding-right:1em">
			<table cellpadding=0 cellspacing=5 style="border: 1px solid $options_color_gray; width: 37em" border=0>
				<tr><td colspan=2 align=right><a href="javascript:img_control_switch('#ID#')">#MSG_CHANGE# <img src="img/down.gif" width=10 height=6 border=0></a></td></tr>

				@img_singlerow@@img_control_tr@@img_big_resize_control@@img_resize_control@
				
			</table>
			</td>
			<td>@img_thumb@</td>
		</tr>
		</table>
	</td>
</tr>
<tr><td height=20></td></tr>
EOT;
*/

	$tpl_img_singlerow = <<< EOT
<tr valign="top">
	<td align=right><font class="name">#MSG_IDENT#</font> <input type="text" size="$img_pound_input_size" value="&#35;IMG#ID#&#35;" title="$msg_bo_img_marker_tip" onfocus="select()"><br>
		<!--div style="height:2.5em">@imgsrc_input@</div-->
		<input type="checkbox" name="#ID#_img_pub" id="#ID#_img_pub" #PUB_CHECKED#><label for="#ID#_img_pub" title="$msg_bo_img_published_tip">$msg_bo_img_published</label>&nbsp;&nbsp;<br>
		<input type="checkbox" name="#ID#_img_main" id="#ID#_img_main" #MAIN_CHECKED#><label for="#ID#_img_main">$msg_bo_img_main</label>&nbsp;&nbsp;<br>
		<input type="checkbox" name="#ID#_img_del" id="#ID#_img_del" onclick="alert_img_ondel(this)"><label for="#ID#_img_del" title="$msg_bo_img_delete_tip">$msg_bo_img_delete</label><br>

		</td>
	<td style="padding-bottom:2ex;">
		<table cellpadding=0 cellspacing=0 border=0>
		<tr valign=top>
			<td style="padding-right:1em">
			<table cellpadding=0 cellspacing=5 style="border: 1px solid $options_color_gray; width: 37em" border=0>
				<tr><td colspan=2 align=right><a href="javascript:img_control_switch('#ID#')">#MSG_CHANGE# <img src="img/down.gif" width=10 height=6 border=0></a></td></tr>

				@img_singlerow@@img_control_tr@@img_big_resize_control@@img_resize_control@
				
			</table>
			</td>
			<td>@img_thumb@</td>
		</tr>
		</table>
	</td>
</tr>
<tr><td height=20></td></tr>
EOT;




	$tpl_img_new_item = <<< EOT
<tr valign=top>
	<td align=right style="padding-top: 0.4em"><font class="name">#FIELD_TXT#</font></td>
	<td>
		@img_row_browse_control@
		@img_row_browsezip_control@
		@img_row_url_control@
	</td>
</tr>
@img_row_txt_control@
EOT;

	$tpl_img_nex = <<< EOT
<tr valign=top>
	<td align=right style="padding-top: 0.4em"><font class="name">#FIELD_TXT#</font></td>
	<td>
		@img_row_browse_control@
		@img_row_url_control@
	</td>
</tr>
<tr>
	<td align=right><font class="name">$msg_bo_img_label</font></td>
	<td><input type="text" class="text" size="$img_text_input_size" name="#ID#_#IMGTYPE#_img_#DB_FIELD#_txt" value="#IMG_TXT#" title="$msg_bo_img_label_tip"></td>
</tr>
EOT;
	
function href_img_popup($img_row, $field_from = "img", $backoffice_mode = 0) {

	$tpl_popup_imgwindow = "javascript:popup_imgurl('#IMG_RELPATH#', #IMG_WIDTH#, #IMG_HEIGHT#)";
	$tpl_popup_imgwindow = "javascript:popup_img('#IMG_ID#', #IMG_WIDTH#, #IMG_HEIGHT#)";
	$tpl_popup_blank = "#IMG_RELPATH#\"  target=\"_blank";
	$tpl_justlink = "#IMG_RELPATH#";

	if ($backoffice_mode == 1) {
		$tpl_popup_imgwindow = "javascript:popup_imgurl('#IMG_RELPATH#', #IMG_W#, #IMG_H#)";
		$tpl_popup_imgwindow = "javascript:popup_img('#ID#', #IMG_W#, #IMG_H#)";
		$tpl_popup_blank = "#IMG_RELPATH#\"  target=\"_blank";
		$tpl_justlink = "#IMG_RELPATH#";
	}

	$tpl = $tpl_popup_imgwindow;
//	pre($img_row);
	
	if (isset($img_row[$field_from]) && $img_row[$field_from] != "") {
		$pathinfo  = pathinfo($img_row[$field_from]);
		if (isset($pathinfo["extension"])) {
			$ext = $pathinfo["extension"];
			$ext = strtolower($ext);
			
			switch ($ext) {
				case "jpg":
				case "jpe":
				case "jpeg":
				case "gif":
				case "png":
	//			case "bmp":
	//			case "tif":
	//			case "tiff":
					$tpl = $tpl_popup_imgwindow;
					break;
	
				case "avi":
				case "mpeg":
				case "mpg":
				case "mov":
				case "wmv":
				case "wma":
				case "mp3":
					$tpl = $tpl_justlink;
					break;
	
				case "pdf":
				case "doc":
				case "xls":
				case "ppt":
				case "zip":
				case "rar":
					$tpl = $tpl_popup_blank;
					break;
	
				default:
					$tpl = $tpl_popup_blank;
					break;
			}
		}
	}
	
	$ret = hash_by_tpl($img_row, $tpl);

	return $ret;
}



	$tpl_img_ex = <<< EOT
<tr valign=top>
	<td align=right width=60><a href="javascript:popup_imgurl('#IMG_RELPATH#', #IMG_WIDTH#, #IMG_HEIGHT#)" title="$msg_bo_img_try_dragging"><font class="name">#FIELD_TXT#</font></a></td>
	<td>[#FSIZE#]

<div id="layer_img_#DB_FIELD#_#ID#" style="display: none">

		@img_row_browse_control@
		@img_row_url_control@
<input type="text" class="text" size="$img_wh_input_size" name="#ID#_#IMGTYPE#_img_#DB_FIELD#_w" value="#IMG_WIDTH#" title="$msg_bo_img_maker_width">
X
<input type="text" class="text" size="$img_wh_input_size" name="#ID#_#IMGTYPE#_img_#DB_FIELD#_h" value="#IMG_HEIGHT#" title="$msg_bo_img_maker_height">&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="#ID#_#IMGTYPE#_img_#DB_FIELD#_del" id="#ID#_#IMGTYPE#_img_#DB_FIELD#_del"><label for="#ID#_#IMGTYPE#_img_#DB_FIELD#_del" title="$msg_bo_img_delete_tip">$msg_bo_img_delete_existing</label>

</div>

	</td>
</tr>
<tr>
	<td align=right><font class="name">$msg_bo_img_label</font></td>
	<td><input type="text" class="text" size="$img_text_input_size" name="#ID#_#IMGTYPE#_img_#DB_FIELD#_txt" value="#IMG_TXT#" title="$msg_bo_img_label_tip"></td>
</tr>
EOT;


function img_row_browse_control($row) {
	global $img_file_input_size, $max_execution_time, $msg_bo_img_upload_tip, $msg_bo_img_upload_only_one;
	$ret = "";

	$tpl_new = <<< EOT
<input type="file" class="file" size="$img_file_input_size" name="#ID#_#IMGTYPE#_img_#DB_FIELD#[]" title="$msg_bo_img_upload_tip"><br>
EOT;

	$tpl_already = <<< EOT
<input type="file" class="file" size="$img_file_input_size" name="#ID#_#IMGTYPE#_img_#DB_FIELD#" title="$msg_bo_img_upload_only_one"><br>
EOT;

	$tpl = ($row["id"] == 0) ? $tpl_new : $tpl_already;
	$ret .= hash_by_tpl($row, $tpl);

	if ($row["id"] == 0) {
//		pre($row);

		$present = 0;
		$limit = 1;

		if ($row["db_field"] == "img" && $row["img_newqnty"] > 1) {
			$present = 1;
			$limit = $row["img_newqnty"];
		}

		if ($row["db_field"] == "img_big" && $row["img_big_newqnty"] > 1) {
			$present = 1;
			$limit = $row["img_big_newqnty"];
		}
		
		if ($present == 1 && $limit > 1) {
			for ($i=1; $i<$limit; $i++) {
				$ret .= hash_by_tpl($row, $tpl);
			}
			$ret .= "<br>";
		}
	}
	
	return $ret;
}

function img_row_browsezip_control($row) {
	global $img_file_input_size, $max_execution_time, $msg_bo_img_upload_zip_tip, $msg_bo_img_upload_only_one;
	$ret = "";

	$present = 0;

	$tpl_new = <<< EOT
<input type="file" class="file" size="$img_file_input_size" name="#ID#_#IMGTYPE#_img_#DB_FIELD#_zip" title="$msg_bo_img_upload_zip_tip"> ZIP<br>
EOT;

	$tpl_already = <<< EOT
<input type="file" class="file" size="$img_file_input_size" name="#ID#_#IMGTYPE#_img_#DB_FIELD#" title="$msg_bo_img_upload_only_one"><br>
EOT;

	$tpl = ($row["id"] == 0) ? $tpl_new : $tpl_already;

	if ($row["id"] == 0) {
		if ($row["db_field"] == "img" && $row["img_zip_present"] == 1) $present = 1;
		if ($row["db_field"] == "img_big" && $row["img_big_zip_present"] == 1) $present = 1;
		if ($present == 1) $ret .= hash_by_tpl($row, $tpl);
	}
	
	return $ret;
}
	

function img_row_url_control($row) {
	global $img_text_input_size, $max_execution_time, $msg_bo_img_upload_url_tip, $msg_bo_img_upload_only_one;
	$ret = "";

	$present = 0;

	$tpl_new = <<< EOT
<input type="text" class="text" size="$img_text_input_size" name="#ID#_#IMGTYPE#_img_#DB_FIELD#_url" value="" title="$msg_bo_img_upload_url_tip"> @img_row_src_html@<br>
EOT;

	$tpl_already = <<< EOT
<input type="text" class="text" size="$img_text_input_size" name="#ID#_#IMGTYPE#_img_#DB_FIELD#_url" value="" title="msg_bo_img_upload_only_one"> @img_row_src_html@<br>
EOT;
	
	$tpl = ($row["id"] == 0) ? $tpl_new : $tpl_already;

	if ($row["db_field"] == "img" && $row["img_url_present"] == 1) $present = 1;
	if ($row["db_field"] == "img_big" && $row["img_big_url_present"] == 1) $present = 1;
	if ($present == 1) $ret .= hash_by_tpl($row, $tpl);
	
	return $ret;
}


function img_row_txt_control($row) {
	global $img_text_input_size, $msg_bo_img_label, $msg_bo_img_label_tip, $msg_bo_img_label_equal_filename, $msg_bo_img_label_equal_filename_tip;
	$ret = "";

	$tpl = <<< EOT
<tr>
	<td align=right><font class="name">$msg_bo_img_label</font></td>
	<td><input type="text" class="text" size="$img_text_input_size" name="#ID#_#IMGTYPE#_img_#DB_FIELD#_txt" value="#IMG_TXT#" title="$msg_bo_img_label_tip">
	<input type="checkbox" name="#ID#_#IMGTYPE#_img_#DB_FIELD#_txtfromfname" id="#ID#_#IMGTYPE#_img_#DB_FIELD#_txtfromfname" #TXT_EQ_FNAME_CHECKED# ><label for="#ID#_#IMGTYPE#_img_#DB_FIELD#_txtfromfname" title="$msg_bo_img_label_equal_filename_tip">$msg_bo_img_label_equal_filename</label></td>
</tr>
EOT;

	if ($row["db_field"] == "img" && $row["img_txt_present"] == 1) {
//		pre($row);
		$ret = hash_by_tpl($row, $tpl);
	}

	if ($row["db_field"] == "img_big" && $row["img_big_txt_present"] == 1) {
		$ret = hash_by_tpl($row, $tpl);
	}
	return $ret;
}


/*
function img_relpath_big($row) {
	return img_relpath($row, "img_big");
}
*/

function img_relpath($row, $field = "img", $force_creation = 0) {
	global $upload_abspath;
	$ret = "";

	global $owner_entity, $owner_entity_id;

	$owner_entity = absorb_variable($owner_entity);
	$owner_entity_id = absorb_variable($owner_entity_id);

	if (isset($row["owner_entity"]) && $row["owner_entity"] != "") {
		$ret .= $row["owner_entity"] . "/";
		if ($force_creation == 1 && !is_dir($upload_abspath . $ret)) {
			mkdir($upload_abspath . $ret);
			chmod($upload_abspath . $ret, 0777);
		}

		if (isset($row["owner_entity_id"]) && $row["owner_entity_id"] != "") {
			$ret .= $row["owner_entity_id"] . "/";
			if ($force_creation == 1 && !is_dir($upload_abspath . $ret)) {
				mkdir($upload_abspath . $ret);
				chmod($upload_abspath . $ret, 0777);
			}
		}
	}

	if (isset($row["id"])) {
		$ret .= "img/";
		if ($force_creation == 1 && !is_dir($upload_abspath . $ret)) {
			mkdir($upload_abspath . $ret);
			chmod($upload_abspath . $ret, 0777);
		}
		
		$ret .= $row["id"] . "/";
		if ($force_creation == 1 && !is_dir($upload_abspath . $ret)) {
			mkdir($upload_abspath . $ret);
			chmod($upload_abspath . $ret, 0777);
		}
		
		if (isset($row[$field])) {
			$ret .= $row[$field];
		}
	}
	
	return $ret;
}

function img_exists($row, $field, $fail_if_not_image = 0) {
	global $upload_abspath;

	$img_relname = img_relpath($row, $field);
	$img_abspath = $upload_abspath . $img_relname;


	$ret = (is_file($img_abspath)) ? 1 : 0;
	if ($ret == 1 && $fail_if_not_image == 1 && is_image_by_ext($img_abspath) == 0) $ret = 0;

//	echo $img_abspath . ":" . $ret . "<br>";
//	echo $img_relname . ":" . $ret . "<br>";
//	echo "$field=[$img_relname][$img_abspath]" . ":" . $ret . "<br>";
	
	return $ret;
}

function delete_img_forowner($owner_entity, $owner_entity_id, $img_table = "img") {
	$query = "select * from $img_table where owner_entity='$owner_entity' and owner_entity_id='$owner_entity_id' and deleted=0 order by manorder";
	$qa = select_queryarray($query);
	foreach ($qa as $img_row) {
		img_unlink_allrest_possible($img_row);
		delete(array("id" => $img_row["id"]), $img_table);
	}
}


function img_unlink_allrest_possible($img_row) {
	global $upload_abspath, $debug_img;
	global $msg_bo_img_file_deleted, $msg_bo_img_directory_deleted;

	$img_relname = img_relpath($img_row);
	$img_id_abspath = $upload_abspath . $img_relname;
	
	if (file_exists($img_id_abspath)) {
		if ($handle = opendir($img_id_abspath)) {
			while (false !== ($cur_file = readdir($handle))) {
				if ($cur_file == "." || $cur_file == "..") continue;
				$img_id_absfname = $img_id_abspath . $cur_file;
				$deleted = unlink($img_id_absfname);
				if ($debug_img == 1) echo "$msg_bo_img_file_deleted: [$cur_file] [$deleted]<br>\n";
			}
			closedir($handle);
		}
		$deleted = rmdir($img_id_abspath);
		if ($debug_img == 1) echo "$msg_bo_img_directory_deleted: [$img_id_abspath] [$deleted]<br>\n";
	}
	
	$img_abspath_is_empty = 1;
	$img_abspath = dirname(substr($img_id_abspath, 0, -1));
	if (file_exists($img_abspath)) {
		if ($handle = opendir($img_abspath)) {
			while (false !== ($cur_file = readdir($handle))) {
				if ($cur_file == "." || $cur_file == "..") continue;
				$img_abspath_is_empty = 0;
			}
			closedir($handle);
		}
		if ($img_abspath_is_empty == 1) {
			$deleted = rmdir($img_abspath);
			if ($debug_img == 1) echo "$msg_bo_img_directory_deleted: [$img_abspath] [$deleted]<br>\n";
		}
	}
	
	$entity_id_abspath_is_empty = 1;
	$entity_id_abspath = dirname(substr($img_abspath, 0, -1));
	if (file_exists($entity_id_abspath)) {
		if ($handle = opendir($entity_id_abspath)) {
			while (false !== ($cur_file = readdir($handle))) {
				if ($cur_file == "." || $cur_file == "..") continue;
				$entity_id_abspath_is_empty = 0;
			}
			closedir($handle);
		}
		if ($entity_id_abspath_is_empty == 1) {
			$deleted = rmdir($entity_id_abspath);
			if ($debug_img == 1) echo "$msg_bo_img_directory_deleted: [$entity_id_abspath] [$deleted]<br>\n";
		}
	}
}

function img_unlink($row, $field) {
	global $upload_abspath;

	if (img_exists($row, $field)) {
		$img_relname = img_relpath($row, $field);
		$img_abspath = $upload_abspath . $img_relname;
		unlink($img_abspath);
	}	
}


function img_fsize($row, $field, $print_value = 1) {
	global $upload_abspath;
	global $msg_bo_img_file_size_bytes, $msg_bo_img_file_lost;

	$ret = "";
	
	$img_abspath = $upload_abspath . img_relpath($row, $field);

	if (file_exists($img_abspath)) {
		$size = filesize($img_abspath);
		$size_formatted = format_fsize($size);
		$ret = "{$size_formatted}$msg_bo_img_file_size_bytes";
	} else {
		$ret = "$msg_bo_img_file_lost";
	}
	
	if ($print_value == 1 && $ret != "") $ret = $row[$field] . ", $ret";

	return $ret;
}

function imgtype_layer($name, $field_txt, $default) {
	global $cms_dbc;
	$ret = "";

	$query = "select * from imgtype where published='1' order by manorder";
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc)
		or die("SELECT IMGTYPE failed:<br>$query:<br>" . mysql_error($cms_dbc));

	while ($row = mysql_fetch_assoc($result)) {
		$imgtype = $row["id"];
		$imgtype_ident = $row["ident"];
		$imgtype_content = $row["content"];
		$imgtype_imglimit = $row["imglimit"];
		$imgtype_resize_default_width = $row["resize_default_width"];
		$imgtype_resize_default_height = $row["resize_default_height"];
		$ret .= img_layer($name, $imgtype_ident, $default, $imgtype, $imgtype_content, $imgtype_imglimit,
				$imgtype_resize_default_width, $imgtype_resize_default_height);
	}

	return $ret;
}

function img_layer($imgtype_row) {
	global $input_size, $mode, $cms_dbc, $img_i, $tpl_img_singlerow, $tpl_img_new;
	global $owner_entity, $owner_entity_id, $img_layer_imgcnt;

	$owner_entity = absorb_variable($owner_entity);
	$owner_entity_id = absorb_variable($owner_entity_id);

	$resolve_default_params = array (
		"resize_width" => $imgtype_row["resize_default_width"],
		"resize_height" => $imgtype_row["resize_default_height"],
		"resize_qlty" => $imgtype_row["resize_default_qlty"],
			
		"big_resize_width" => $imgtype_row["big_resize_default_width"],
		"big_resize_height" => $imgtype_row["big_resize_default_height"],
		"big_resize_qlty" => $imgtype_row["big_resize_default_qlty"]
	);
	$imgtype_row = array_merge($imgtype_row, $resolve_default_params);
//	pre($imgtype_row);
	
	$img_table = "img";
	if (isset($imgtype_row["img_table"]) && $imgtype_row["img_table"] != "") {
		 if (entity_present_in_db(TABLE_PREFIX . $imgtype_row["img_table"])) $img_table = $imgtype_row["img_table"];
		 else pre("imgtype[" . $imgtype_row["hashkey"] . "] has img_table=[" . $imgtype_row["img_table"] . "] which does not exists in database.");
	}


	$imgtype_id = $imgtype_row["id"];
	$imgtype_hashkey = $imgtype_row["hashkey"];

	$ret = "";

	$query = "select * from $img_table where owner_entity='$owner_entity' and owner_entity_id='$owner_entity_id' and imgtype=$imgtype_id and deleted=0 order by manorder";
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("img_layer(): SELECT IMG_LAYER[$imgtype_hashkey] failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$img_layer_imgcnt = mysql_num_rows($result);

	$i = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$row["i"] = ++$i;
		$row["pub_checked"] = ($row["published"] == '1') ? "checked" : "";
		$row["main_checked"] = ($row["img_main"] == '1') ? "checked" : "";
		$row["faceted_checked"] = (isset($row["faceted"]) && $row["faceted"] == '1') ? "checked" : "";
		$row["faceting_checked"] = (isset($row["faceting"]) && $row["faceting"] == '1') ? "checked" : "";

		$row = array_merge($imgtype_row, $row);

		if ($mode == "update") {
			$updated_parameters = array (
				"resize_width" => get_string(hash_by_tpl($row, "#ID#_#IMGTYPE#_img_resize_w")),
				"resize_height" => get_string(hash_by_tpl($row, "#ID#_#IMGTYPE#_img_resize_h")),
				"resize_qlty" => get_string(hash_by_tpl($row, "#ID#_#IMGTYPE#_img_resize_q")),
			
				"big_resize_width" => get_string(hash_by_tpl($row, "#ID#_#IMGTYPE#_img_big_resize_w")),
				"big_resize_height" => get_string(hash_by_tpl($row, "#ID#_#IMGTYPE#_img_big_resize_h")),
				"big_resize_qlty" => get_string(hash_by_tpl($row, "#ID#_#IMGTYPE#_img_big_resize_q")),
			);
//			$row = array_merge($row, $updated_parameters);


			$row["resize_width"] = ($updated_parameters["resize_width"] != "") ? $updated_parameters["resize_width"] : $imgtype_row["resize_width"];
			$row["resize_height"] = ($updated_parameters["resize_height"] != "") ? $updated_parameters["resize_height"] : $imgtype_row["resize_height"];
			$row["resize_qlty"] = ($updated_parameters["resize_qlty"] != "") ? $updated_parameters["resize_qlty"] : $imgtype_row["resize_qlty"];
			$row["big_resize_width"] = ($updated_parameters["big_resize_width"] != "") ? $updated_parameters["big_resize_width"] : $imgtype_row["big_resize_width"];
			$row["big_resize_height"] = ($updated_parameters["big_resize_height"] != "") ? $updated_parameters["big_resize_height"] : $imgtype_row["big_resize_height"];
			$row["big_resize_qlty"] = ($updated_parameters["big_resize_qlty"] != "") ? $updated_parameters["big_resize_qlty"] : $imgtype_row["big_resize_qlty"];

		}

		$row = array_merge($row, array("display_control" => "none"));
//		pre($row);
		$ret .= hash_by_tpl($row, $tpl_img_singlerow);
	}

	if (
		$imgtype_row["imglimit"] == 0 || ($imgtype_row["imglimit"] > 0 && $imgtype_row["imglimit"] > $i)
		) {
		$row = array (
			"id" => 0,
			"imgtype" => $imgtype_row["id"],
			"img" => "",
			"img_w" => 0,
			"img_h" => 0,
			"img_txt" => "",
			"img_src" => "",
			"img_big" => "",
			"img_big_w" => 0,
			"img_big_h" => 0,
			"img_big_txt" => "",
			"img_big_src" => "",
			"i" => $i,
			"display_control" => "block",
		);
		$row = array_merge($imgtype_row, $row);
//		pre($row);
		$ret .= hash_by_tpl($row, $tpl_img_new);
	}
	
	return $ret;
}

function img_singlerow($row) {
	global $upload_relpath, $tpl_img_ex, $tpl_img_nex, $tpl_img_txt, $tpl_img_big_txt, $img_i, $tpl_img_resize, $tpl_img_new_item;

	$ret = "";

//	pre($row);

	if ($row["img_present"] == 1) {
		$tpl = (img_exists($row, "img")) ? $tpl_img_ex : $tpl_img_nex;
		if ($row["id"] == 0) $tpl = $tpl_img_new_item;
		
		$tpl_parm = array (
//			"field_txt" => "маленькая",
			"field_txt" => $row["msg_img"],
			"id" => $row["id"],
			"imgtype" => $row["imgtype"],
			"img" => $row["img"],
			"img_relpath" => $upload_relpath . img_relpath($row, "img"),
			"img_width" => $row["img_w"],
			"img_height" => $row["img_h"],
			"img_txt" => htmlspecialchars($row["img_txt"]),
			"img_src" => $row["img_src"],
			"db_field" => "img",
			"fsize" => img_fsize($row, "img"),
			"img_newqnty" => $row["img_newqnty"],
			"img_url_present" => $row["img_url_present"],
			"img_zip_present" => $row["img_zip_present"],
			"img_txt_present" => $row["img_txt_present"],
		);
		
		$ret .= hash_by_tpl($tpl_parm, $tpl);
	}

	if ($row["img_present"] == 1 && $row["img_big_present"] == 1) {
		$ret .= "<tr><td colspan=2><hr></td></tr>";
	}

	if ($row["img_big_present"] == 1) {
		$tpl = (img_exists($row, "img_big")) ? $tpl_img_ex : $tpl_img_nex;
		if ($row["id"] == 0) $tpl = $tpl_img_new_item;
	
		$tpl_parm = array (
//			"field_txt" => "большая",
			"field_txt" => $row["msg_img_big"],
			"id" => $row["id"],
			"imgtype" => $row["imgtype"],
			"img" => $row["img_big"],
			"img_relpath" => $upload_relpath . img_relpath($row, "img_big"),
			"img_width" => $row["img_big_w"],
			"img_height" => $row["img_big_h"],
			"img_txt" => htmlspecialchars($row["img_big_txt"]),
			"img_src" => $row["img_big_src"],
			"db_field" => "img_big",
			"fsize" => img_fsize($row, "img_big"),
			"img_big_newqnty" => $row["img_big_newqnty"],
			"img_big_url_present" => $row["img_big_url_present"],
			"img_big_zip_present" => $row["img_big_zip_present"],
			"img_big_txt_present" => $row["img_big_txt_present"],
			"txt_eq_fname_checked" => ($row["img_big_txt_eq_fname"] == 1) ? "checked": "",
			"href_img_popup" => href_img_popup($row, "img_big", 1),
		);

		$ret .= hash_by_tpl($tpl_parm, $tpl);
	}

//	$ret .= "<tr><td colspan=2><hr></td></tr>";
//	$ret .= hash_by_tpl($tpl_parm, $tpl_img_resize);

	return $ret;
}

function img_singlerow_new($row) {
	global $upload_relpath, $tpl_img_ex, $tpl_img_nex, $tpl_img_txt, $tpl_img_big_txt, $img_i, $tpl_img_resize, $tpl_img_new_item;
	global $msg_bo_img_label_big, $msg_bo_img_label_small;

	$ret = "";

//	pre($row);

	if ($row["img_present"] == 1) {
		$tpl = (img_exists($row, "img")) ? $tpl_img_ex : $tpl_img_nex;
		if ($row["id"] == 0) $tpl = $tpl_img_new_item;
		
		$tpl_parm = array (
//			"field_txt" => "маленькая",
//			"field_txt" => $row["msg_img"],
			"field_txt" => $msg_bo_img_label_small,
			"id" => $row["id"],
			"imgtype" => $row["imgtype"],
			"img" => $row["img"],
			"img_relpath" => $upload_relpath . img_relpath($row, "img"),
			"img_width" => $row["img_w"],
			"img_height" => $row["img_h"],
			"img_txt" => $row["img_txt"],
			"img_src" => $row["img_src"],
			"db_field" => "img",
			"fsize" => img_fsize($row, "img")
		);
		
		$ret .= hash_by_tpl($tpl_parm, $tpl);
		$ret .= "<tr><td colspan=2><hr></td></tr>";
	}


	if ($row["img_big_present"] == 1) {
		$tpl = (img_exists($row, "img_big")) ? $tpl_img_ex : $tpl_img_nex;
		if ($row["id"] == 0) $tpl = $tpl_img_new_item;
	
		$tpl_parm = array (
//			"field_txt" => "большая",
//			"field_txt" => $row["msg_img_big"],
			"field_txt" => $msg_bo_img_label_big,
			"id" => $row["id"],
			"imgtype" => $row["imgtype"],
			"img" => $row["img_big"],
			"img_relpath" => $upload_relpath . img_relpath($row, "img_big"),
			"img_width" => $row["img_big_w"],
			"img_height" => $row["img_big_h"],
			"img_txt" => $row["img_big_txt"],
			"img_src" => $row["img_big_src"],
			"db_field" => "img_big",
			"fsize" => img_fsize($row, "img_big")
		);

		$ret .= hash_by_tpl($tpl_parm, $tpl);
	}

//	$ret .= "<tr><td colspan=2><hr></td></tr>";
//	$ret .= hash_by_tpl($tpl_parm, $tpl_img_resize);

	return $ret;
}


function img_update($img_id, $imgtype_row = array(), $img_table = "img") {
	global $upload_abspath, $owner_entity, $owner_entity_id, $debug_query, $debug_img;
	global $win_tran2russian, $ident_new;

	$imgtype = $imgtype_row["id"];

	$owner_entity = absorb_variable($owner_entity);
	$owner_entity_id = absorb_variable($owner_entity_id);

//	if ($img_id == 0 && isset($_FILES["{$img_id}_{$imgtype}_img_img"])) pre($_FILES["{$img_id}_{$imgtype}_img_img"]);
//	if ($img_id == 0 && isset($_FILES["{$img_id}_{$imgtype}_img_img_big"])) pre($_FILES["{$img_id}_{$imgtype}_img_img_big"]);

//	pre($_FILES);
	
	$img_hash = array(
		"img_absname_u" => isset($_FILES["{$img_id}_{$imgtype}_img_img"])
			? $_FILES["{$img_id}_{$imgtype}_img_img"]["tmp_name"]
			: "",
		"img_fname_u" => isset($_FILES["{$img_id}_{$imgtype}_img_img"])
			? $_FILES["{$img_id}_{$imgtype}_img_img"]["name"]
			: "",
		"img_url" => get_string("{$img_id}_{$imgtype}_img_img_url"),
		"img_zipabsname_u" => isset($_FILES["{$img_id}_{$imgtype}_img_img_zip"])
			? $_FILES["{$img_id}_{$imgtype}_img_img_zip"]["tmp_name"]
			: "",
		"img_zipfname_u" => isset($_FILES["{$img_id}_{$imgtype}_img_img_zip"])
			? $_FILES["{$img_id}_{$imgtype}_img_img_zip"]["name"]
			: "",
		"img_txt" => get_string("{$img_id}_{$imgtype}_img_img_txt"),
		"img_w" => get_number("{$img_id}_{$imgtype}_img_img_w"),
		"img_h" => get_number("{$img_id}_{$imgtype}_img_img_h"),
		"img_del" => get_string("{$img_id}_{$imgtype}_img_img_del"),



		"img_big_absname_u" => isset($_FILES["{$img_id}_{$imgtype}_img_img_big"])
			? $_FILES["{$img_id}_{$imgtype}_img_img_big"]["tmp_name"]
			: "",
		"img_big_fname_u" => isset($_FILES["{$img_id}_{$imgtype}_img_img_big"])
			? $_FILES["{$img_id}_{$imgtype}_img_img_big"]["name"]
			: "",
		"img_big_url" => get_string("{$img_id}_{$imgtype}_img_img_big_url"),
		"img_big_zipabsname_u" => isset($_FILES["{$img_id}_{$imgtype}_img_img_big_zip"])
			? $_FILES["{$img_id}_{$imgtype}_img_img_big_zip"]["tmp_name"]
			: "",
		"img_big_zipfname_u" => isset($_FILES["{$img_id}_{$imgtype}_img_img_big_zip"])
			? $_FILES["{$img_id}_{$imgtype}_img_img_big_zip"]["name"]
			: "",
		"img_big_txt" => get_string("{$img_id}_{$imgtype}_img_img_big_txt"),
		"img_big_w" => get_number("{$img_id}_{$imgtype}_img_img_big_w"),
		"img_big_h" => get_number("{$img_id}_{$imgtype}_img_img_big_h"),
		"img_big_del" => get_string("{$img_id}_{$imgtype}_img_img_big_del"),


		"img_resize" => get_string("{$img_id}_{$imgtype}_img_resize"),
		"img_resize_w" => get_number("{$img_id}_{$imgtype}_img_resize_w"),
		"img_resize_h" => get_number("{$img_id}_{$imgtype}_img_resize_h"),
		"img_resize_qlty" => get_string("{$img_id}_{$imgtype}_img_resize_q"),

		"img_big_resize" => get_string("{$img_id}_{$imgtype}_img_big_resize"),
		"img_big_resize_w" => get_number("{$img_id}_{$imgtype}_img_big_resize_w"),
		"img_big_resize_h" => get_number("{$img_id}_{$imgtype}_img_big_resize_h"),
		"img_big_resize_qlty" => get_string("{$img_id}_{$imgtype}_img_big_resize_q"),

		"img_id" => $img_id,
		"del" => get_string("{$img_id}_img_del"),

		"published" => (get_string("{$img_id}_img_pub") == "on" || $img_id == 0) ? 1 : 0,
		"img_main" => (get_string("{$img_id}_img_main") == "on" || $img_id == 0) ? 1 : 0,
		"faceting" => (get_string("{$img_id}_img_faceting") == "on" || $img_id == 0) ? 1 : 0,
		"faceted" => (get_string("{$img_id}_img_faceted") == "on" || $img_id == 0) ? 1 : 0,

//		"published" => (get_string("{$img_id}_img_pub") == "on") ? 1 : 0,
//		"img_main" => (get_string("{$img_id}_img_main") == "on") ? 1 : 0,
//		"faceting" => (get_string("{$img_id}_img_faceting") == "on") ? 1 : 0,
//		"faceted" => (get_string("{$img_id}_img_faceted") == "on") ? 1 : 0,


		"img_url_array" => array(),
		"img_big_url_array" => array(),
		"img_src" => "",
		"img_big_src" => "",
	);

//	pre($_POST);
//	pre($img_hash);

	$img_hash["img_url_array"] = split_urls($img_hash["img_url"]);
	$img_hash["img_big_url_array"] = split_urls($img_hash["img_big_url"]);

	$img_row = array();

	$thumb_unlink_needs_parameters = array (
		"img_thumb_present" => $imgtype_row["img_thumb_present"],
		"img_thumb_qlty" => $imgtype_row["img_thumb_qlty"],
		"img_thumb_width" => $imgtype_row["img_thumb_width"],
		"img_thumb_height" => $imgtype_row["img_thumb_height"],
	);


	if (isset($imgtype_row["img_table"]) && $imgtype_row["img_table"] != "") {
		 if (entity_present_in_db(TABLE_PREFIX . $imgtype_row["img_table"])) $img_table = $imgtype_row["img_table"];
		 else pre("imgtype[" . $imgtype_row["hashkey"] . "] has img_table=[" . $imgtype_row["img_table"] . "] which does not exists in database.");
	}


	if ($img_id > 0) {
		$img_row = select_entity_row(array("id" => $img_id), $img_table);
		$img_row = array_merge($img_row, $thumb_unlink_needs_parameters);

//		$img_row["img_thumb_present"] = $imgtype_row["img_thumb_present"];
//		$img_row["img_thumb_qlty"] = $imgtype_row["img_thumb_qlty"];
//		$img_row["img_thumb_width"] = $imgtype_row["img_thumb_width"];
//		$img_row["img_thumb_height"] = $imgtype_row["img_thumb_height"];

		$img_hash["img_src"] = $img_row["img_src"];
		$img_hash["img_big_src"] = $img_row["img_big_src"];


		if ($img_hash["del"] == "on") {
			if ($debug_img == 1) pre("deleting image [$img_id]");
			img_thumb_unlink($img_row);
			img_unlink($img_row, "img");
			img_unlink($img_row, "img_big");

//			rmdir($upload_abspath . img_relpath($img_row, "dir_removing"));
			img_unlink_allrest_possible($img_row);

			delete(array("id" => $img_id), $img_table);
			return;
		}

		if ($img_hash["img_del"] == "on") {
			$img_hash["img_absname4del"] = $upload_abspath . img_relpath($img_row, "img");

			img_thumb_unlink($img_row);
			if (is_file($img_hash["img_absname4del"])) {
				img_unlink($img_row, "img");
				update (array("img" => "", "img_w" => 0, "img_h" => 0), array("id" => $img_id), $img_table);
				if ($img_row["img"] == $img_row["img_big"]) update (array("img_big" => ""), array("id" => $img_id), $img_table);
//				echo "img_del=[" . $img_hash["img_del"] . "], deleted [" . $img_hash["img_absname4del"] . "] ";
			}
		}

		if ($img_hash["img_big_del"] == "on") {
			$img_hash["img_big_absname4del"] = $upload_abspath . img_relpath($img_row, "img_big");

			img_thumb_unlink($img_row);
			if (is_file($img_hash["img_big_absname4del"])) {
				img_unlink($img_row, "img_big");
				update (array("img_big" => "", "img_big_w" => 0, "img_big_h" => 0), array("id" => $img_id), $img_table);
				if ($img_row["img"] == $img_row["img_big"]) update (array("img" => ""), array("id" => $img_id), $img_table);
//				echo "img_big_del=[" . $img_hash["img_big_del"] . "], deleted [" . $img_hash["img_big_absname4del"] . "] ";
			}
		}
		
		if (count($img_hash["img_url_array"]) > 1) {
			$img_hash["img_url_array"] = array($img_hash["img_url_array"][0]);
		}

		if (count($img_hash["img_big_url_array"]) > 1) {
			$img_hash["img_big_url_array"] = array($img_hash["img_big_url_array"][0]);
		}
		
		
	} else {
		if (is_uploaded_file($img_hash["img_absname_u"]) || is_uploaded_file($img_hash["img_big_absname_u"])) {
			$img_row = insert_newimg_and_createdir($imgtype, $img_table);
			$img_row = array_merge($img_row, $thumb_unlink_needs_parameters);

//			$img_row["img_thumb_present"] = $imgtype_row["img_thumb_present"];
//			$img_row["img_thumb_qlty"] = $imgtype_row["img_thumb_qlty"];
//			$img_row["img_thumb_width"] = $imgtype_row["img_thumb_width"];
//			$img_row["img_thumb_height"] = $imgtype_row["img_thumb_height"];

			$img_id = $img_row["id"];


			$ident_owner_entity = select_field("ident", array("id" => $owner_entity_id), $owner_entity);
//			echo "ident_owner_entity=[$ident_owner_entity] $ident_new=[$ident_new]";
			if ($ident_owner_entity == $ident_new) {
				$ident_from_file = "";
				if (is_uploaded_file($img_hash["img_absname_u"])) $ident_from_file = basename($img_hash["img_fname_u"]);
				if (is_uploaded_file($img_hash["img_big_absname_u"])) $ident_from_file = basename($img_hash["img_big_fname_u"]);
				if ($ident_from_file != "") {
					$ident_from_file = preg_replace("/^(.*?)(\.+.*)$/U", "\\1", $ident_from_file);
					$update_ident_hash["ident"] = basename($ident_from_file);
//					pre($update_ident_hash);
					update($update_ident_hash, array("id" => $owner_entity_id), $owner_entity);
				}
			}
			
			$img_txtfromfname = get_string("0_" . $imgtype . "_img_img_txtfromfname");
			if ($img_txtfromfname == "on"
					&& $img_hash["img_fname_u"] != ""
					&& $img_hash["img_txt"] == "")  {
				$img_hash["img_txt"] = preg_replace("/\..*$/", "", urldecode($img_hash["img_fname_u"]));
				$img_hash["img_txt"] = strtr($img_hash["img_txt"], $win_tran2russian);
			}
//			pre ("0_{$imgtype}_img_img_txtfromfname == [" . $img_txtfromfname . "] "
//					. "img_hash[img_fname_u] = [" . $img_hash["img_fname_u"] . "] "
//					. "img_hash[img_txt] = [" . $img_hash["img_txt"] . "] ");
		

			$img_big_txtfromfname = get_string("0_" . $imgtype . "_img_img_big_txtfromfname");
			if ($img_big_txtfromfname == "on"
					&& $img_hash["img_big_fname_u"] != ""
					&& $img_hash["img_big_txt"] == "")  {
				$img_hash["img_big_txt"] = preg_replace("/\..*$/", "", urldecode($img_hash["img_big_fname_u"]));
				$img_hash["img_big_txt"] = strtr($img_hash["img_big_txt"], $win_tran2russian);
			}
//			pre ("0_{$imgtype}_img_img_big_txtfromfname == [" . $img_big_txtfromfname . "] "
//					. "img_hash[img_big_fname_u] = [" . $img_hash["img_big_fname_u"] . "] "
//					. "img_hash[img_txt] = [" . $img_hash["img_txt"] . "] ");
			
		}
	}

//	$debug_query = 0;

	if (count($img_hash["img_url_array"]) == 0 && count($img_hash["img_big_url_array"]) == 0) {
//		pre($img_hash);
//не залито ничего, img_id=0 не выбирали
		if (isset($img_row["id"])) img_update_postupload($img_row, $img_hash, $img_table);
	}

	if ($img_id == 0) {
		$img_row = array_merge($img_row, $thumb_unlink_needs_parameters);
//		pre("img_zip_process");
		img_zip_process($img_hash, $img_row, $imgtype, "", $imgtype_row);
//		pre("img_zip_process_big");
		img_zip_process($img_hash, $img_row, $imgtype, "_big", $imgtype_row);
	}

	if (count($img_hash["img_url_array"]) > 0) {
		foreach ($img_hash["img_url_array"] as $url2fetch) {
			$fetched_abspath = fetch_url($url2fetch);
			if ($fetched_abspath != "") {
				$img_hash_override = array (
					"img_absname_u" => $fetched_abspath,
					"img_fname_u" => basename($fetched_abspath),
					"img_big_absname_u" => "",
					"img_big_fname_u" => "",
					"img_src" => $url2fetch
				);

				if ($img_id == 0) {
					$img_row = insert_newimg_and_createdir($imgtype, $img_table);
					$img_row = array_merge($img_row, $thumb_unlink_needs_parameters);

//					$img_row["img_thumb_present"] = $imgtype_row["img_thumb_present"];
//					$img_row["img_thumb_qlty"] = $imgtype_row["img_thumb_qlty"];
//					$img_row["img_thumb_width"] = $imgtype_row["img_thumb_width"];
//					$img_row["img_thumb_height"] = $imgtype_row["img_thumb_height"];

					$img_txtfromfname = get_string("0_" . $imgtype . "_img_img_txtfromfname");
					if ($img_txtfromfname == "on"
							&& $img_hash_override["img_fname_u"] != ""
							&& $img_hash["img_txt"] == "")  {
//						$img_hash_override["img_txt"] = preg_replace("/\..*$/", "", urldecode($img_hash_override["img_fname_u"]));
						$img_hash_override["img_txt"] = preg_replace("/^(.*?)(\.+.*)$/U", "\\1", urldecode($img_hash_override["img_fname_u"]));
						$img_hash_override["img_txt"] = strtr($img_hash_override["img_txt"], $win_tran2russian);
					}


					$img_big_txtfromfname = get_string("0_" . $imgtype . "_img_img_big_txtfromfname");
					if ($img_big_txtfromfname == "on"
							&& $img_hash_override["img_big_fname_u"] != ""
							&& $img_hash["img_big_txt"] == "")  {
//						$img_hash_override["img_big_txt"] = preg_replace("/\..*$/", "", urldecode($img_hash_override["img_big_fname_u"]));
						$img_hash_override["img_big_txt"] = preg_replace("/^(.*?)(\.+.*)$/U", "\\1", urldecode($img_hash_override["img_big_fname_u"]));
						$img_hash_override["img_big_txt"] = strtr($img_hash_override["img_big_txt"], $win_tran2russian);
					}

					$img_id = $img_row["id"];
				}

				$img_hash_item = array_merge($img_hash, $img_hash_override);
//				pre($img_hash_item);
				img_update_postupload($img_row, $img_hash_item, $img_table);
			}
		}
	}

	if (count($img_hash["img_big_url_array"]) > 0) {
		foreach ($img_hash["img_big_url_array"] as $url2fetch) {
			$fetched_abspath = fetch_url($url2fetch);
			if ($fetched_abspath != "") {
				if ($img_id == 0) {
					$img_row = insert_newimg_and_createdir($imgtype, $img_table);
					$img_row = array_merge($img_row, $thumb_unlink_needs_parameters);

//					$img_row["img_thumb_present"] = $imgtype_row["img_thumb_present"];
//					$img_row["img_thumb_qlty"] = $imgtype_row["img_thumb_qlty"];
//					$img_row["img_thumb_width"] = $imgtype_row["img_thumb_width"];
//					$img_row["img_thumb_height"] = $imgtype_row["img_thumb_height"];

					$img_id = $img_row["id"];
				}

				$img_hash_override = array (
					"img_big_absname_u" => $fetched_abspath,
					"img_big_fname_u" => basename($fetched_abspath),
					"img_absname_u" => "",
					"img_fname_u" => "",
					"img_big_src" => $url2fetch
				);

				$img_hash_item = array_merge($img_hash, $img_hash_override);
//				pre($img_hash_item);
				img_update_postupload($img_row, $img_hash_item, $img_table);
			}
		}
	}


	return $img_id;
}


function img_zip_process($img_hash, $img_row, $imgtype, $img_field_suffix = "", $imgtype_row) {
	global $tmp_relpath, $tmp_path, $win_tran, $dos_tran, $fname_common, $fetchlog, $debug_query;
	global $upload_abspath, $owner_entity, $owner_entity_id, $debug_query, $debug_img;

	$owner_entity = absorb_variable($owner_entity);
	$owner_entity_id = absorb_variable($owner_entity_id);

	$thumb_unlink_needs_parameters = array (
		"img_thumb_present" => $img_row["img_thumb_present"],
		"img_thumb_qlty" => $img_row["img_thumb_qlty"],
		"img_thumb_width" => $img_row["img_thumb_width"],
		"img_thumb_height" => $img_row["img_thumb_height"],
	);

	$img_table = "img";
	if (isset($imgtype_row["img_table"]) && $imgtype_row["img_table"] != "") {
		 if (entity_present_in_db(TABLE_PREFIX . $imgtype_row["img_table"])) $img_table = $imgtype_row["img_table"];
		 else pre("imgtype[" . $imgtype_row["hashkey"] . "] has img_table=[" . $imgtype_row["img_table"] . "] which does not exists in database.");
	}

	if (is_uploaded_file($img_hash["img" . $img_field_suffix . "_zipabsname_u"])) {
		$zip = zip_open($img_hash["img" . $img_field_suffix . "_zipabsname_u"]);
		if ($zip) {
			$ident_owner_entity = get_string("ident");
//			$ident_owner_entity = select_field("ident", array("id" => $owner_entity_id), $owner_entity);
/*			if ($ident_owner_entity == $ident_new) {
				$zipfname_u = basename($img_hash["img" . $img_field_suffix . "_zipfname_u"]);
				$zipfname_u = preg_replace("/^(.*?)(\.+.*)$/U", "\\1", $zipfname_u);
				$update_ident_hash["ident"] = $zipfname_u;
				update($update_ident_hash, array("id" => $owner_entity_id), $owner_entity);
			}
*/

			$resort_idarray = array();
			while ($zip_entry = zip_read($zip)) {
				$zip_frelname = zip_entry_name($zip_entry);
				$zip_fname_txt = $zip_fname = basename($zip_frelname);
				$zip_fname = strtr($zip_fname, $dos_tran);
				$zip_fname = strtr($zip_fname, $fname_common);

				$zip_fname_txt = convert_cyr_string($zip_fname_txt, "d", "w");
//				$zip_fname_txt = preg_replace("/\.jpg|\.jpeg|\.gif$/i", "", $zip_fname_txt);
//				echo $zip_fname_txt;
				$zip_fname_txt = preg_replace("/^(.*?)(\.+.*)$/U", "\\1", $zip_fname_txt);
//				echo "-" . $zip_fname_txt . "<br>";
//				$zip_fname_txt_pathinfo = pathinfo($zip_fname_txt);
//				$zip_fname_txt = $zip_fname_txt_pathinfo["basename"];
//				pre($zip_fname_txt);

				$zip_fsize = zip_entry_filesize($zip_entry);
				
				$zip_frelname = convert_cyr_string($zip_frelname, "d", "w");
				plog($zip_frelname . ":" . $zip_fsize);

				if ($zip_fsize == 0) continue;	//directory entry

				$fetchlog .= "<tr><td>$zip_frelname</td><td width=15></td>";

/*				if (is_image_by_ext($zip_frelname) == 0) {
					$fetchlog .= "<td>... skipping, wrong extension</td></tr>\n";
					continue;
				}
*/

				if (zip_entry_open($zip, $zip_entry, "r")) {
					$zip_fcontent = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
					
					$tmp_absfname = tempnam($tmp_path, "php_unzip_");
// windows adds "\" like ...tmp/\php0D8.tmp
					$tmp_absfname = str_replace("\\", "", $tmp_absfname);

					$fp = fopen($tmp_absfname, "w");
					if ($fp == FALSE) {
						$fetchlog .= "<td>... failed on fopen($tmpfname)</td></tr>\n";
						continue;
					}

					fwrite($fp, $zip_fcontent);
					fclose($fp);
					
					$tmp_filesize = filesize($tmp_absfname);
					if($tmp_filesize != $zip_fsize) {
						$fetchlog .= "<td>... failed, zip_fsize=[$zip_fsize] tmp_filesize=[$tmp_filesize]</td></tr>\n";
						unlink($tmp_absfname);
						continue;
					}

					$img_select_hash = array(
						"img" . $img_field_suffix => $zip_fname,
						"owner_entity" => $owner_entity,
						"owner_entity_id" => $owner_entity_id,
//						"published" => 0,
//						"deleted" => 1,
					);

//					pre($img_select_hash);
//					$debug_query = 1;
					$img_row = select_entity_row($img_select_hash, $img_table);
//					$debug_query = 0;
					$destiny_txt = "";

					if (isset($img_row["id"])) {
//						pre($img_row);
						$destiny_txt = ", found old img_id=" . $img_row["id"];
						$updated_imgtype_visibility = update(
								array("imgtype" => $imgtype, "published" => 1, "deleted" => 0),
								array("id" => $img_row["id"]), $img_table);
						
						$img_row = select_entity_row(array("id" => $img_row["id"]), $img_table);
					} else {
						$img_row = insert_newimg_and_createdir($imgtype, $img_table);
						$destiny_txt = ", inserted new img_id=" . $img_row["id"];
					}

					$fetchlog .= "<td>... extracted ok $tmp_filesize bytes $destiny_txt</td></tr>\n";


					if (isset($img_row["id"])) $resort_idarray[] = $img_row["id"];

					$img_hash_override = array (
						"img_absname_u" =>		($img_field_suffix == "") ? $tmp_absfname : "",
						"img_fname_u" =>		($img_field_suffix == "") ? $zip_fname : "",
						"img_big_absname_u" =>	($img_field_suffix == "_big") ? $tmp_absfname : "",
						"img_big_fname_u" =>	($img_field_suffix == "_big") ? $zip_fname : "",
					);
					
					if (get_string("0_" . $imgtype . "_img_img" . $img_field_suffix . "_txtfromfname") == "on") {
						if ($img_field_suffix == "_big") {
							$img_hash_override["img_big_txt"] = $zip_fname_txt;
						} else {
							$img_hash_override["img_txt"] = $zip_fname_txt;
						}
					}

					$img_hash_item = array_merge($img_hash, $img_hash_override);
//					pre($img_hash_item);

					$img_row = array_merge($img_row, $thumb_unlink_needs_parameters);
					img_update_postupload($img_row, $img_hash_item, $img_table);
// postupload moved the file
//					unlink($tmp_absfname);
		
					zip_entry_close($zip_entry);
				} else {
					$fetchlog .= "<td>... cant zip_entry_open()</td></tr>";
				}
			}
			zip_close($zip);

			if (count($resort_idarray) > 0) {
//				pre($resort_idarray);


				$resorting_idarray = array();
				$query = "select id, manorder, img" . $img_field_suffix
					. " from $img_table where id in (" . sqlin_fromarray($resort_idarray) . ")"
					. " order by img" . $img_field_suffix;
				$qa = select_queryarray($query);
				foreach ($qa as $row) {
					$resorting_idarray[] = $row["id"];
				}
//				pre($resorting_idarray);


				$resorted_manorderarray = array();
				$query = "select id, manorder, img" . $img_field_suffix
					. " from $img_table where id in (" . sqlin_fromarray($resort_idarray) . ")"
					. " order by manorder";
				$qa = select_queryarray($query);
				foreach ($qa as $row) {
					$resorted_manorderarray[] = $row["manorder"];
				}
//				pre($resorted_manorderarray);


				for ($i=0; $i<count($resort_idarray); $i++) {
//					$id = $resort_idarray[$i];
					$id = $resorting_idarray[$i];
					$manorder = $resorted_manorderarray[$i];
					update(array("manorder" => $manorder), array("id" => $id), $img_table);
				}
			}

		} else {
			plog(pr("cant open zip(" . $img_hash["img" . $img_field_suffix . "_zipabsname_u"] . ")"));
		}
	} else {
//		plog(pr("!ZIP is_uploaded_file(" . $img_hash["img" . $img_field_suffix . "_zipabsname_u"] . ")"));
//		plog(pr($_FILES));
	}
}

function insert_newimg_and_createdir($imgtype, $img_table = "img") {
	global $owner_entity, $owner_entity_id; 

	$img_insert_hash = array (
		"imgtype" => $imgtype,
		"owner_entity" => $owner_entity,
		"owner_entity_id" => $owner_entity_id,
//		"published" => 1,
		"published" => (get_string("0_img_pub") == "on") ? 1 : 0,
		"date_created" => "CURRENT_TIMESTAMP"
	);

	$img_id = insert ($img_insert_hash, $img_table);

	$img_row = array (
		   "owner_entity" => $owner_entity
		 , "owner_entity_id" => $owner_entity_id
		 , "id" => $img_id
		 , "dir_creation" => ""
	);

// добавление в риче с лица давало ошибки
	$img_row = select_entity_row(array("id" => $img_id), $img_table);

	$dir_created = img_relpath($img_row, "dir_creation", 1);
//	echo "img_id=[$img_id]; just_inserted=[1]; dir_created=[$dir_created]<br>";
//	pre("inserted img_id=[$img_id]; dir_created=[$dir_created]");
				
	return $img_row;
}

function fetch_url($url) {
	global $tmp_path, $fetchlog, $msg_tag_shortcut;
	$ret = "";

	$tmp_absfname = $tmp_path . basename($url);
	
	$fetchlog .= "<tr><td><a href='$url' target='_blank'>$msg_tag_shortcut $url</a></td><td width=15></td>";
	if (!copy($url, $tmp_absfname)) {
		$fetchlog .= "<td>failed to fetch</td>\n";
//		logf("$fetchmsg\n");
	} else {
		$tmp_filesize = filesize($tmp_absfname);
		$fetchlog .= "<td>...fetched ok $tmp_filesize bytes</td>";
		$ret = $tmp_absfname;
	}
	$fetchlog .= "</tr>";

	return $ret;
}


function split_urls($str) {
	$ret = array();
	
	$tmp = preg_split("/(http:\/\/|\s)/", $str, -1, PREG_SPLIT_NO_EMPTY);
	foreach ($tmp as $item_tmp) {
		if (!preg_match("/^http:\/\//", $item_tmp)) $item_tmp = "http://" . $item_tmp;

		$had_already = 0;
		foreach ($ret as $item) {
			if ($item_tmp == $item) {
				$had_already = 0;
				break;
			}
		}

		if ($had_already == 0) $ret[] = $item_tmp;
	}
	
	return $ret;
}

function shrink_fname_32chars($file_name, $fname_limit = 40) {
	$ret = "";

//	pre($file_name);
//	$dirname = dirname($file_name);
//	pre($dirname);

//	if ($dirname != "") return $file_name;

	if (strlen($file_name) > $fname_limit) {
		$matches = array();
		preg_match("~^(.*?)\.(.*)$~", $file_name, $matches);
//		pre($matches);
		if (isset($matches[2]) && $matches[2] != "") {
			$fname_noext = $matches[1];
			$fname_noext = substr($fname_noext, 0, $fname_limit - 4);	// we suppose 4 chars max to extension
			$fname_ext = $matches[2];
			$ret = $fname_noext . "." . $fname_ext;
//			pre($ret);
		} else {
			$ret = $file_name;
		}
	} else {
		$ret = $file_name;
	} 

	return $ret;
}

function img_update_postupload($img_row, $img_hash, $img_table = "img") {
	global $win_tran, $fname_common, $img_rename0_copy1_moveupload2;
	global $upload_abspath, $debug_query, $debug_img;

//	$debug_query = 1;
//	pre($img_row);
//	pre($img_hash);
	$img_id = $img_row["id"];

//	pre($_POST);

	if (is_file($img_hash["img_absname_u"])) {
//		echo "NORMAL uploaded [" . $img_hash["img_absname_u"] . "] [" . $img_hash["img_fname_u"] . "]";
		img_thumb_unlink($img_row);
		img_unlink($img_row, "img");

		$img_row["img"] = $img_hash["img_fname_u"];
		$img_row["img"] = strtr($img_row["img"], $win_tran);
		$img_row["img"] = strtr($img_row["img"], $fname_common);
		if (strlen($img_row["img"]) > 32) $img_row["img"] = shrink_fname_32chars($img_row["img"]);
		$moving_absname = $upload_abspath . img_relpath($img_row, "img");

		switch ($img_rename0_copy1_moveupload2) {
			case 0: $moved = rename($img_hash["img_absname_u"], $moving_absname); break;
			case 1: $moved = copy($img_hash["img_absname_u"], $moving_absname); break;
			case 2: $moved = move_uploaded_file($img_hash["img_absname_u"], $moving_absname); break;
		}
		if ($moved == 1) $chmoded = chmod($moving_absname, 0777);
//		echo "NORMAL uploaded [" . $img_hash["img_absname_u"] . "] [" . $img_hash["img_fname_u"] . "] -> [" . $moving_absname . "][$moved][$chmoded]";

		$img_size = getimagesize($moving_absname);

		$img_fields = array (
			"img" => $img_row["img"],
			"img_w" => $img_size[0],
			"img_h" => $img_size[1]
		);
		if (isset($img_hash["img_txt"])) $img_fields["img_txt"] = $img_hash["img_txt"];

		update ($img_fields, array("id" => $img_id), $img_table);
	} else {
		if ($img_hash["img_w"] != "") update (array("img_w" => $img_hash["img_w"]), array("id" => $img_id), $img_table);
		if ($img_hash["img_h"] != "") update (array("img_h" => $img_hash["img_h"]), array("id" => $img_id), $img_table);
	}
	
	if (is_file($img_hash["img_big_absname_u"])) {
//		echo "BIG uploaded [" . $img_hash["img_big_absname_u"] . "] [" . $img_hash["img_big_fname_u"] . "]";
		img_thumb_unlink($img_row);
		img_unlink($img_row, "img_big");

		if ($img_hash["img_big_resize"] == "on") {
			$resize_from_abspath = dirname($img_hash["img_big_absname_u"]) . "/";
			$resize_from_basename = basename($img_hash["img_big_absname_u"]);
			$resize_fname = "bigresize_"
						. $img_hash["img_big_resize_w"] . "x" . $img_hash["img_big_resize_h"]
						. "-" . $img_hash["img_big_fname_u"];
			$resized_fname = img_resize($resize_from_abspath, $resize_from_basename,
						$img_hash["img_big_resize_w"], $img_hash["img_big_resize_h"],
						$resize_fname, $img_hash["img_big_resize_qlty"]);

			$resized_absname = $resize_from_abspath . $resized_fname;

/* when loading Desktop-ini we should get resized name anyway. file does not exists and we will not FILEmove&DBupdate
			if (is_file($resized_absname)) {
				$img_hash["img_big_absname_u"] = $resized_absname;
				$img_hash["img_big_fname_u"] = $resized_fname;
			} else {
				if ($debug_img == 1) pre("img_update_postupload(): BIGRESIZE IS ABSENT: !is_file($resized_absname)");
			} 
*/

			$img_hash["img_big_absname_u"] = $resized_absname;
			$img_hash["img_big_fname_u"] = $resized_fname;
			if (!is_file($resized_absname)) {
				if ($debug_img == 1) pre("img_update_postupload(): BIGRESIZE IS ABSENT: !is_file($resized_absname)");
			} 

		}

		$img_row["img_big"] = $img_hash["img_big_fname_u"];
		$img_row["img_big"] = strtr($img_row["img_big"], $win_tran);
		$img_row["img_big"] = strtr($img_row["img_big"], $fname_common);
		if (strlen($img_row["img_big"]) > 32) $img_row["img_big"] = shrink_fname_32chars($img_row["img_big"]);

		$moving_absname = $upload_abspath . img_relpath($img_row, "img_big", 1);

		if (is_file($img_hash["img_big_absname_u"])) {
			switch ($img_rename0_copy1_moveupload2) {
				case 0: 
					$moved = rename($img_hash["img_big_absname_u"], $moving_absname);
//					pre ("$moved = rename(" . $img_hash["img_big_absname_u"] . ", $moving_absname");
					break;

				case 1:
					$moved = copy($img_hash["img_big_absname_u"], $moving_absname);
//					pre ("$moved = copy(" . $img_hash["img_big_absname_u"] . ", $moving_absname");
					break;

				case 2:
					$moved = move_uploaded_file($img_hash["img_big_absname_u"], $moving_absname);
//					pre ("$moved = move_uploaded_file(" . $img_hash["img_big_absname_u"] . ", $moving_absname");
					break;
			}
			if ($moved == 1) $chmoded = chmod($moving_absname, 0777);

//			pre("moving destination [$moving_absname] exists [" . file_exists($moving_absname) . "]");
//			pre("rename(" . $img_hash["img_big_absname_u"] . ", $moving_absname); moved == $moved");

			$img_size = getimagesize($moving_absname);
			if ($img_size == FALSE) {
				if ($debug_img == 1) pre("img_update_postupload(): getimagesize(" . $moving_absname . ") == FALSE");
			}
	
			$img_fields = array (
				"img_big" => $img_row["img_big"],
				"img_big_w" => $img_size[0],
				"img_big_h" => $img_size[1]
			);

			if (isset($img_hash["img_big_txt"])) $img_fields["img_big_txt"] = $img_hash["img_big_txt"];
			update ($img_fields, array("id" => $img_id), $img_table);
		} else {
			if ($debug_img) pre("moving source [$moving_absname] does not exists [" . file_exists($img_hash["img_big_absname_u"]) . "]");
		}
	} else {
		if ($img_hash["img_big_w"] != "") update (array("img_big_w" => $img_hash["img_big_w"]), array("id" => $img_id), $img_table);
		if ($img_hash["img_big_h"] != "") update (array("img_big_h" => $img_hash["img_big_h"]), array("id" => $img_id), $img_table);
	}
		
// в чегесе были побиты оригиналы-большие. загрузка в зипе стирала все оригинальные подписи.
// было откоментарено, добавил проверку "обновлять только если изменилось"
	$txt_pub_hash = array();
//	if (isset($img_hash["img_big_txt"])	&& $img_hash["img_big_txt"] != "")	$txt_pub_hash["img_big_txt"] =	$img_hash["img_big_txt"];
//	if (isset($img_hash["img_txt"])		&& $img_hash["img_txt"] != "")		$txt_pub_hash["img_txt"] =		$img_hash["img_txt"];
//	if (isset($img_hash["img_src"])		&& $img_hash["img_src"] != "")		$txt_pub_hash["img_src"] =		$img_hash["img_src"];
//	if (isset($img_hash["img_big_src"])	&& $img_hash["img_big_src"] != "")	$txt_pub_hash["img_big_src"] =	$img_hash["img_big_src"];
//	if (isset($img_hash["published"])	&& $img_hash["published"] != "")	$txt_pub_hash["published"] =	$img_hash["published"];

	if (isset($img_hash["img_big_txt"])	&& $img_hash["img_big_txt"] != ""
		&& $img_hash["img_big_txt"] != $img_row["img_big_txt"])	$txt_pub_hash["img_big_txt"] =	$img_hash["img_big_txt"];
	if (isset($img_hash["img_txt"])		&& $img_hash["img_txt"] != ""
		&& $img_hash["img_txt"] != $img_row["img_txt"])		$txt_pub_hash["img_txt"] =		$img_hash["img_txt"];
	if (isset($img_hash["img_src"])		&& $img_hash["img_src"] != ""
		&& $img_hash["img_src"] != $img_row["img_src"])		$txt_pub_hash["img_src"] =		$img_hash["img_src"];
	if (isset($img_hash["img_big_src"])	&& $img_hash["img_big_src"] != ""
		&& $img_hash["img_big_src"] != $img_row["img_big_src"])	$txt_pub_hash["img_big_src"] =	$img_hash["img_big_src"];

//	pre($img_hash["img_main"]);
//	pre($img_row["img_main"]);


// убрал _POST - в бэкоффисе не работало сохранение убранных галок
//	if (isset($_POST["{$img_id}_img_main"]) && $img_hash["img_main"] != $img_row["img_main"]) {
	if ($img_hash["img_main"] != $img_row["img_main"]) {
		$txt_pub_hash["img_main"] = $img_hash["img_main"];
	}

//	if (isset($_POST["{$img_id}_img_pub"]) && $img_hash["published"] != $img_row["published"]) {
	if ($img_hash["published"] != $img_row["published"]) {
		$txt_pub_hash["published"] = $img_hash["published"];
	}


// только тут нормальное место по ходу чтобы отфейсконтролить? img_hash это только поля и галки в интерфейсе
// в img_update - только заполнение img_hash, в img_post_update их надо записать и файлы подчистить
	if (isset($img_row["faceted"]) && $img_hash["faceted"] != $img_row["faceted"]) {			//isset($_POST["{$img_id}_img_faceted"]) && 
		$txt_pub_hash["faceted"] = $img_hash["faceted"];
		if ($txt_pub_hash["faceted"] == 1) $txt_pub_hash["date_faceted"] = "CURRENT_TIMESTAMP";
		else $txt_pub_hash["date_faceted"] = "0";
	}

	if (isset($img_row["faceting"]) && $img_hash["faceting"] != $img_row["faceting"]) {		//isset($_POST["{$img_id}_img_faceting"]) && 
		$txt_pub_hash["faceting"] =	$img_hash["faceting"];
	}

//	pre("img_hash=[" . pr($img_hash) . "]"
//		. " img_row=[" . pr($img_row) . "]"
//		. " txt_pub_hash=[" . pr($txt_pub_hash) . "]");

	if (count($txt_pub_hash) > 0) update ($txt_pub_hash, array("id" => $img_id), $img_table);


	if ($img_hash["img_resize"] == "on") {
		$thumb_unlink_needs_parameters = array (
			"img_thumb_present" => $img_row["img_thumb_present"],
			"img_thumb_qlty" => $img_row["img_thumb_qlty"],
			"img_thumb_width" => $img_row["img_thumb_width"],
			"img_thumb_height" => $img_row["img_thumb_height"],
		);

		$img_row = select_entity_row(array("id" => $img_id), $img_table);

		if (img_exists($img_row, "img_big")) {
		
			$resize_fname = img_resized_fname($img_row, "img_big", "resize",
					$img_hash["img_resize_w"], $img_hash["img_resize_h"]);
			$resize_row = array_merge($img_row, array("img_resize" => $resize_fname));
	
			$resize_from_absname = $upload_abspath . img_relpath($resize_row, "img_big");
			$resize_from_abspath = dirname($resize_from_absname) . "/";
	
//			if (img_exists($resize_row, "img_resize") == 0) {
				img_thumb_unlink(array_merge($resize_row, $thumb_unlink_needs_parameters));
				img_unlink($resize_row, "img");

				img_resize($resize_from_abspath, $resize_row["img_big"],
						$img_hash["img_resize_w"], $img_hash["img_resize_h"],
						$resize_fname, $img_hash["img_resize_qlty"]);
				
				$img_size = getimagesize($upload_abspath . img_relpath($resize_row, "img_resize"));
		
				$img_fields = array (
					"img" => $resize_row["img_resize"],
					"img_w" => $img_size[0],
					"img_h" => $img_size[1]
				);
		
				update ($img_fields, array("id" => $img_id), $img_table);
//			}
		

		}	
	}
}

function imglayer_update($imgtype_row = array()) {
	global $owner_entity, $owner_entity_id, $cms_dbc;

	$owner_entity = absorb_variable($owner_entity);
	$owner_entity_id = absorb_variable($owner_entity_id);
	$imgtype_id = $imgtype_row["id"];
	$imgtype_hashkey = $imgtype_row["hashkey"];
	
	//pre($_POST);


	$img_table = "img";
	if (isset($imgtype_row["img_table"]) && $imgtype_row["img_table"] != "") {
		 if (entity_present_in_db(TABLE_PREFIX . $imgtype_row["img_table"])) $img_table = $imgtype_row["img_table"];
		 else pre("imgtype[" . $imgtype_row["hashkey"] . "] has img_table=[" . $imgtype_row["img_table"] . "] which does not exists in database.");
	}

	$query = "select * from $img_table where owner_entity='$owner_entity' and owner_entity_id='$owner_entity_id' and imgtype=$imgtype_id and deleted=0 order by manorder";
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc)
		or die("imglayer_update() SELECT IMG_LAYER[$imgtype_hashkey] failed:<br>$query:<br>" . mysql_error($cms_dbc));

	while ($row = mysql_fetch_assoc($result)) {
		$img_id = $row["id"];
		img_update($img_id, $imgtype_row);
	}


// hack for 0_1_img_img[] became array

	if (
			(isset($_FILES["0_{$imgtype_id}_img_img"]["tmp_name"]) && is_array($_FILES["0_{$imgtype_id}_img_img"]["tmp_name"]))
		||	(isset($_FILES["0_{$imgtype_id}_img_img_big"]["tmp_name"]) && is_array($_FILES["0_{$imgtype_id}_img_img_big"]["tmp_name"]))
		) {


		$file_not_uploaded = array (
				"name" => "",
				"type" => "",
				"tmp_name" => "",
				"error" => 4,
				"size" => 0,
			);
		
		$originalzero_FILES = isset($_FILES["0_{$imgtype_id}_img_img"]) ? $_FILES["0_{$imgtype_id}_img_img"] : array();
		$originalzero_big_FILES = isset($_FILES["0_{$imgtype_id}_img_img_big"]) ? $_FILES["0_{$imgtype_id}_img_img_big"] : array();

//		pre($originalzero_FILES);
//		pre($originalzero_big_FILES);
		
		unset($_FILES["0_{$imgtype_id}_img_img"]);
		unset($_FILES["0_{$imgtype_id}_img_img_big"]);



		$original_zip_FILE = isset($_FILES["0_{$imgtype_id}_img_img_zip"]) ? $_FILES["0_{$imgtype_id}_img_img_zip"] : $file_not_uploaded;
		$original_big_zip_FILE = isset($_FILES["0_{$imgtype_id}_img_img_big_zip"]) ? $_FILES["0_{$imgtype_id}_img_img_big_zip"] : $file_not_uploaded;

		unset($_FILES["0_{$imgtype_id}_img_img_zip"]);
		unset($_FILES["0_{$imgtype_id}_img_img_big_zip"]);



		$original_url = get_string("0_{$imgtype_id}_img_img_url");
		$original_big_url = get_string("0_{$imgtype_id}_img_img_big_url");

		unset($_GET["0_{$imgtype_id}_img_img_url"]);
		unset($_POST["0_{$imgtype_id}_img_img_url"]);
		unset($_REQUEST["0_{$imgtype_id}_img_img_url"]);

		unset($_GET["0_{$imgtype_id}_img_img_big_url"]);
		unset($_POST["0_{$imgtype_id}_img_img_big_url"]);
		unset($_REQUEST["0_{$imgtype_id}_img_img_big_url"]);



		$original_length = 0;
		if (isset($originalzero_FILES["tmp_name"])) $original_length = count($originalzero_FILES["tmp_name"]);
		if (isset($originalzero_big_FILES["tmp_name"])) {
			$count_big = count($originalzero_big_FILES["tmp_name"]);
			if ($original_length < $count_big) $original_length = $count_big;
		}

		for ($i=0; $i<$original_length; $i++) {
			$hack_singlehash_instead_of_arrayhash = $hack_singlehash_instead_of_arrayhash_big = $file_not_uploaded;

			if (isset($originalzero_FILES["name"][$i])) {
				$hack_singlehash_instead_of_arrayhash = array (
					"name" =>		isset($originalzero_FILES["name"][$i])
										? $originalzero_FILES["name"][$i] : "",
					"type" => 		isset($originalzero_FILES["type"][$i])
										? $originalzero_FILES["type"][$i] : "",
					"tmp_name" => 	isset($originalzero_FILES["tmp_name"][$i])
										? $originalzero_FILES["tmp_name"][$i] : "",
					"error" => 		isset($originalzero_FILES["error"][$i])
										? $originalzero_FILES["error"][$i] : "",
					"size" => 		isset($originalzero_FILES["size"][$i])
										? $originalzero_FILES["size"][$i] : "",
				);

//			    pre($hack_singlehash_instead_of_arrayhash);
			    $_FILES["0_{$imgtype_id}_img_img"] = $hack_singlehash_instead_of_arrayhash;
			}
		    
			if (isset($originalzero_big_FILES["name"][$i])) {
				$hack_singlehash_instead_of_arrayhash_big = array (
					"name" =>		isset($originalzero_big_FILES["name"][$i])
										? $originalzero_big_FILES["name"][$i] : "",
					"type" => 		isset($originalzero_big_FILES["type"][$i])
										? $originalzero_big_FILES["type"][$i] : "",
					"tmp_name" => 	isset($originalzero_big_FILES["tmp_name"][$i])
										? $originalzero_big_FILES["tmp_name"][$i] : "",
					"error" => 		isset($originalzero_big_FILES["error"][$i])
										? $originalzero_big_FILES["error"][$i] : "",
					"size" => 		isset($originalzero_big_FILES["size"][$i])
										? $originalzero_big_FILES["size"][$i] : "",
			    );

//			    pre($hack_singlehash_instead_of_arrayhash_big);
			    $_FILES["0_{$imgtype_id}_img_img_big"] = $hack_singlehash_instead_of_arrayhash_big;
		    }
		    

			img_update(0, $imgtype_row);
		}
		
//restore and process zip and url
		$_FILES["0_{$imgtype_id}_img_img_zip"] = $original_zip_FILE;
		$_FILES["0_{$imgtype_id}_img_img_big_zip"] = $original_big_zip_FILE;

		$_GET["0_{$imgtype_id}_img_img_url"] = $original_url;
		$_POST["0_{$imgtype_id}_img_img_url"] = $original_url;
		$_REQUEST["0_{$imgtype_id}_img_img_url"] = $original_url;

		$_GET["0_{$imgtype_id}_img_img_big_url"] = $original_big_url;
		$_POST["0_{$imgtype_id}_img_img_big_url"] = $original_big_url;
		$_REQUEST["0_{$imgtype_id}_img_img_big_url"] = $original_big_url;

		unset($_FILES["0_{$imgtype_id}_img_img"]);
		unset($_FILES["0_{$imgtype_id}_img_img_big"]);

		img_update(0, $imgtype_row);

//restore arrayhash, already processed
		$_FILES["0_{$imgtype_id}_img_img"] = $originalzero_FILES;
		$_FILES["0_{$imgtype_id}_img_img_big"] = $originalzero_big_FILES;

	} else {
		img_update(0, $imgtype_row);
	}

}


function prepare_imgrow($row, $img_id = 0, $tpl = "", $img_serno = 0, $entity_ = "_global", $check_exists = 1, $may_modify_tpl = 1, $may_use_big_if_empty = 1, $set_txt_as_fsize = 0) {
	global $upload_relpath, $upload_abspath, $entity, $id, $debug_img;
	global $msg_bo_img_popuphref_zoom_unable;

	$ret = array();

	if ($img_id == 0) {
		if (!isset($row["img_id"])) return $ret;		// using from external
//		$img_id = $row["id"];							// using from prepare_img
	}




//	$img_popuphref = "javascript:alert('Sorry, this image can not be enlarged :(');";
//	$img_popuphref = "javascript:alert('К сожалению, это изображение не увеличивается :(');";
//	$img_popuphref = "javascript:void(0);";
	$img_popuphref = "javascript:alert('$msg_bo_img_popuphref_zoom_unable');";

	$imghash = array();

	$img_relpath = img_relpath($row, "img");

	$pathinfo = pathinfo($img_relpath);
	$extension = isset($pathinfo["extension"]) ? strtolower($pathinfo["extension"]) : "";
	$extension_big = "";
	//isset($pathinfo["extension"]) ? strtolower($pathinfo["extension"]) : "";

	if ($check_exists == 1 && !img_exists($row, "img")) {
		if ($debug_img == 1) echo "PREPARE_IMGROW [#IMG{$img_id}# !is_file(" . $upload_abspath . $img_relpath . ")]<br>";
		$img_relpath = "";
	}

	$img_big_relpath = img_relpath($row, "img_big");
	if ($check_exists == 1 && !img_exists($row, "img_big")) {
		if ($debug_img == 1) echo "PREPARE_IMGROW [#IMG{$img_id}# BIG !is_file(" . $upload_abspath . $img_big_relpath . ")]<br>";
		$img_big_relpath = "";
	} else {
		if ($row["img_big"] != "") {
			$img_big_w = $row["img_big_w"];
			$img_big_h = $row["img_big_h"];
//			$img_popuphref = "javascript:popup_img('/upload/$img_big', $img_big_w, $img_big_h)";
			$img_popuphref = "javascript:popup_img($img_id, $img_big_w, $img_big_h)";

			$pathinfo = pathinfo($img_big_relpath);
			$extension_big = isset($pathinfo["extension"]) ? strtolower($pathinfo["extension"]) : "";
		} else {
			$img_big_relpath = "";
		}
	}
	
//	if ($img_relpath != "" || $img_big_relpath != "") {
//		$img_size = getimagesize($upload_abspath . $img);
//		print_r($img_size);

		$imghash = array(
			"img_id" => $img_id,
			"img_serno" => $img_serno,

			"img" => $row["img"],
			"img_relpath" => $upload_relpath . $img_relpath,
			"img_width" => $row["img_w"],
			"img_height" => $row["img_h"],
			"img_wh" => "width='" . $row["img_w"] . "' height='" . $row["img_h"] . "'",
			"img_txt" => $row["img_txt"],

			"img_big" => $row["img_big"],
			"img_big_relpath" => $upload_relpath . $img_big_relpath,
			"img_big_width" => $row["img_big_w"],
			"img_big_height" => $row["img_big_h"],
			"img_big_wh" => "width='" . $row["img_big_w"] . "' height='" . $row["img_big_h"] . "'",
			"img_big_txt" => $row["img_big_txt"],

			"img_popuphref" => $img_popuphref,

			"img_extension" => $extension,
			"img_fsize" => img_fsize($row, "img"),

			"img_big_extension" => $extension_big,
			"img_big_fsize" => img_fsize($row, "img_big"),

			
			);
			
		if ($may_use_big_if_empty == 1) {
			if ($imghash["img"] == "") $imghash["img"] = $imghash["img_big"];
			if ($imghash["img_relpath"] == $upload_relpath) $imghash["img_relpath"] = $imghash["img_big_relpath"];
			if ($imghash["img_width"] == 0) $imghash["img_width"] = $imghash["img_big_width"];
			if ($imghash["img_height"] == 0) $imghash["img_height"] = $imghash["img_big_height"];
			if ($imghash["img_wh"] == "width='0' height='0'") $imghash["img_wh"] = $imghash["img_big_wh"];
			if ($imghash["img_txt"] == "") $imghash["img_txt"] = $imghash["img_big_txt"];
		}
			
		if ($debug_img == 1) echo "PREPARE_IMGROW imghash = " . nl2br(pr($imghash));

		$tpl_ = $tpl;
//		if ($img_relpath == "" && $img_big_relpath != "" && $may_modify_tpl == 1) {
//		pre ("img_relpath=[$img_relpath] img_big_relpath=[$img_big_relpath] may_modify_tpl=[$may_modify_tpl] ");

		if (!img_exists($row, "img") && img_exists($row, "img_big") && $may_modify_tpl == 1) {
			$tpl_ = <<< EOT
<a href="#IMG_POPUPHREF#">#IMG_TXT#</a>
EOT;
		}

		if ($imghash["img_txt"] == "" && $may_modify_tpl == 1 && $set_txt_as_fsize == 1) {
			$imghash["img_txt"] = $imghash["img_fsize"];
		}
		
		if ($imghash["img_big_txt"] == "" && $may_modify_tpl == 1 && $set_txt_as_fsize == 1) {
			$imghash["img_big_txt"] = $imghash["img_big_fsize"];
		}
		
		if ($imghash["img_big_relpath"] == $upload_relpath) {
			$tpl_ = preg_replace ("~<a([^>]*)#IMG_POPUPHREF#([^>]*)>(.*)</a>~i", "\$3", $tpl_);
		}

		$imghash["img_html"] = hash_by_tpl($imghash, $tpl_, $entity_);
		$ret = $imghash;
//	}
	
	return $ret;
}

$imgtypes = array();
function get_cached_imgtype($imgtype_hashkey = "IMG_CONTENT", $asked_field = "id") {
//	static $imgtypes = array();
	global $imgtypes;

	$imgtype_hashkey_default = "IMG_CONTENT";
	if ($imgtype_hashkey == "_global") $imgtype_hashkey = $imgtype_hashkey_default;
	
	if (!isset($imgtypes[$imgtype_hashkey])) {
		$imgtype_row = select_entity_row(array("hashkey" => $imgtype_hashkey), "imgtype");
//		pre($imgtype_row);

		if (isset($imgtype_row["img_newqnty"])) {
			$imgtype_row["img_newqnty"] = intval($imgtype_row["img_newqnty"]);
			if ($imgtype_row["img_newqnty"] == 0) $imgtype_row["img_newqnty"] = 1;
		}
		if (isset($imgtype_row["img_big_newqnty"])) {
			$imgtype_row["img_big_newqnty"] = intval($imgtype_row["img_big_newqnty"]);
			if ($imgtype_row["img_big_newqnty"] == 0) $imgtype_row["img_big_newqnty"] = 1;
		}

		$imgtypes[$imgtype_hashkey] = $imgtype_row;
	}

	if (!isset($imgtypes[$imgtype_hashkey])) {
		die("get_cached_imgtype([$imgtype_hashkey], [$asked_field]): seems imgtype_hashkey=[$imgtype_hashkey] does not exists in <a href=/backoffice/imgtype.php>imgtype</a>");
	}

	if ($asked_field == "whole_row") return $imgtypes[$imgtype_hashkey];

	if (!isset($imgtypes[$imgtype_hashkey][$asked_field])) {
		die("get_cached_imgtype([$imgtype_hashkey], [$asked_field]): seems imgtype_hashkey=[$imgtype_hashkey] does not exists in <a href=/backoffice/imgtype.php>imgtype</a>");
	}

	return $imgtypes[$imgtype_hashkey][$asked_field];
}


function prepare_img($tpl, $imgtype_hashkey = "_global", $id_ = "_global", $entity_ = "_global", $check_exists = 1, $may_modify_tpl = 1, $set_txt_as_fsize = 0, $imgid_list = array(), $img_table = "img") {
	global $entity, $id, $upload_relpath, $cms_dbc, $debug_query;

	$ret = array();

	static $img_serno = array();
	
	$imgtype = get_cached_imgtype($imgtype_hashkey);
	if (!isset($img_serno[$imgtype])) $img_serno[$imgtype] = 0;
	
	if ($entity_ == "_global") $entity_ = $entity;
	if ($id_ == "_global") $id_ = $id;

	
	$imgid_sqlin = "";
	if (count($imgid_list) > 0) $imgid_sqlin = sqlin_fromarray($imgid_list);
	if ($imgid_sqlin != "") $imgid_sqlin = " and id in ($imgid_sqlin)";

	$query = "select * from $img_table where published=1 and deleted=0 and owner_entity='$entity_' and owner_entity_id='$id_' and imgtype='$imgtype' $imgid_sqlin order by manorder";
	$query = add_sql_table_prefix($query);
	if ($debug_query == 1) echo "<br>PREPARE_IMG[$query]<br>";
	$result = mysql_query($query, $cms_dbc) 
		or die("SELECT PREPARE_IMG failed:<br>$query:<br>" . mysql_error($cms_dbc));

	while ($row = mysql_fetch_assoc($result)) {
//		pre($row);
		$img_id = $row["id"];
		$img_published = $row["published"];
		
		$tag_name = "img" . $img_id;
		$img_html = "";

		if ($img_published == "1") {
			$img_serno[$imgtype]++;
			$prepare_imghash = prepare_imgrow($row, $img_id, $tpl, $img_serno[$imgtype], $entity_, $check_exists, $may_modify_tpl, 1, $set_txt_as_fsize);
//			pre($prepare_imghash);
			if (isset($prepare_imghash["img_html"])) {
				$img_html = $prepare_imghash["img_html"];
				$img_html = hash_by_tpl(array("i" => $img_serno[$imgtype]), $img_html);
			}
		}

		$ret[$tag_name] = $img_html;
	}
	
	return $ret;
}

function autoresize($row, $imgtype_hashkey = "IMG_PRODUCT", $autoresize_type = "first"
		, $tpl_ex = "<div class=image style='width: #IMG_WIDTH#;'><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></div>"
		, $tpl_nex = "<div style='width: #IMG_WIDTH#; height: #IMG_HEIGHT#; border: 1px solid gray; vertical-align: bottom; text-align: center; padding:5' title='#IMG_NEX_DEBUGMSG#'><br><br>изображение недоступно</div>"
//		, $tpl_nex = "<img src='#UPLOAD_RELPATH#/#ENTITY#/default.gif' alt='#IMG_NEX_DEBUGMSG#' class=imgfloat border=1>"
		, $entity_ = "_global:entity", $id_ = "_global:id", $field_from = "img_big", $img_table = "img", $manorder_field = "manorder"
		, $limit_first = 0, $imgid_list = array(), $query_spec = "", $get_next_until_ok = 0) {

	global $upload_relpath, $upload_abspath, $debug_query, $autoresize_imghash, $datetime_fmt;
	global $msg_bo_img_autoresize_element_this, $msg_bo_img_autoresize_element_has_no_big_uploaded, $msg_bo_img_autoresize_element_has_no_resize_apply_checked, $msg_bo_img_autoresize_element_has_no_big_uploaded_or_HW_zero;

	$ret = "";
	
	$imgtype_row = get_cached_imgtype($imgtype_hashkey, "whole_row");
//	pre($imgtype_row);

	$imgtype = $imgtype_row["id"];
	$autoresize_debug = $imgtype_row[$autoresize_type . "_autoresize_debug"];

	global $img_resize_quality_default;
	$quality = $imgtype_row[$autoresize_type . "_autoresize_qlty"];
	if (intval($quality) == 0) $quality = $img_resize_quality_default;


//dont forget to cleanup old-dimensioned resizes (by fname prefix and expire = imgtype.date_updated)
	$ar_width = intval($imgtype_row[$autoresize_type . "_autoresize_width"]);
	$ar_height = intval($imgtype_row[$autoresize_type . "_autoresize_height"]);

//cleanup if autoresizes are older than
//	$img_overwrite_ts = mktime (00, 00, 00, 03, 28, 2005);

	$imgtype_updated = $imgtype_row["date_updated"];
	$imgtype_updated_datehash = parse_datetime($imgtype_updated);
//	foreach ($imgtype_updated_datehash as $key => $value) $imgtype_updated_datehash[$key] = intval($value);
	$imgtype_updated_ts = mktime($imgtype_updated_datehash["hour"], $imgtype_updated_datehash["minute"], $imgtype_updated_datehash["second"]
								, $imgtype_updated_datehash["month"], $imgtype_updated_datehash["day"], $imgtype_updated_datehash["year"]);
//	pre("imgtype_updated=$imgtype_updated, imgtype_updated_ts=$imgtype_updated_ts");

/* нахер не нужно для should_resize! авторесайз обновлять тогда, когда картинка-оригинал свежее авторесайза
	$entityrow_updated = $row['date_updated'];
	$entityrow_updated_datehash = parse_datetime($entityrow_updated);
//	foreach ($entityrow_updated_datehash as $key => $value) $entityrow_updated[$key] = intval($value);
	$entityrow_updated_ts = mktime($entityrow_updated_datehash["hour"], $entityrow_updated_datehash["minute"], $entityrow_updated_datehash["second"]
								, $entityrow_updated_datehash["month"], $entityrow_updated_datehash["day"], $entityrow_updated_datehash["year"]);
//	pre("entityrow_updated=$entityrow_updated, entityrow_updated_ts=$entityrow_updated_ts");
*/

	$id = isset($row["id"]) ? $row["id"] : absorb_variable($id_);
	$entity = isset($row["entity"]) ? $row["entity"] : absorb_variable($entity_);
	$ident = isset($row["ident"]) ? $row["ident"] : "";
	$ident = str_replace("\r\n", " ", $ident);
	$ident = stripslashes($ident);
	$ident = strip_tags($ident);

	$row["id"] = $id;
	$row["entity"] = $entity;
	$row["ident"] = $ident;

	$ar_img_row = array();
	$assumed_ar_height = ($ar_height != 0) ? $ar_height : intval($ar_width * 0.75);
	$ar_img_row["IMG_WH"] = " width='$ar_width' height='$assumed_ar_height'";
	$ar_img_row["IMG_WIDTH"] = $ar_width;
	$ar_img_row["IMG_HEIGHT"] = $assumed_ar_height;
	$ar_img_row["IMG_EXISTS"] = 0;
	$ar_img_row["IMG_NEX_DEBUGMSG"] = "$msg_bo_img_autoresize_element_this [$entity:$id] $msg_bo_img_autoresize_element_has_no_big_uploaded [$imgtype_hashkey]:[$autoresize_type]";
	$ar_img_row["IMG_RESIZABLE_BYEXTENSION"] = 1;

	$imgid_sqlin = "";
	if (count($imgid_list) > 0) $imgid_sqlin = sqlin_fromarray($imgid_list);
	if ($imgid_sqlin != "") $imgid_sqlin = " and id in ($imgid_sqlin)";

	$query = "select * from $img_table where owner_entity='$entity' and owner_entity_id='$id' and imgtype='$imgtype' and published=1 and deleted=0 $imgid_sqlin";
	if ($imgid_sqlin == "") {
		if ($autoresize_type == "first") {
			$query .= " order by img_main desc, $manorder_field " . get_entity_orderdir($img_table);
		} else {
			$query .= " order by $manorder_field " . get_entity_orderdir($img_table);
		}
	}
	
	
	if ($query_spec != "") $query = $query_spec;

// если вызываем autoresize(every, limit=1, $get_next_until_ok=1), то первая может failed; используем тогда $successful_images
	if ($limit_first > 0 && $get_next_until_ok == 0) $query .= " limit $limit_first";
	$query = add_sql_table_prefix($query);
	if ($debug_query == 1) echo "<br>AUTORESIZE[$query]<br>";
	$result = mysql_query($query) or die("autoresize(): SELECT IMGLIST failed:<br>$query:<br>" . mysql_error());


	$autoresize_imghash = array();

	$successful_images = 0;
	$i = 0;
	while ($img_row = mysql_fetch_assoc($result)) {
//		pre($img_row);

		$imgrow_updated = $img_row["date_updated"];
		$imgrow_updated_datehash = parse_datetime($imgrow_updated);
		$imgrow_updated_ts = mktime($imgrow_updated_datehash["hour"], $imgrow_updated_datehash["minute"], $imgrow_updated_datehash["second"]
									, $imgrow_updated_datehash["month"], $imgrow_updated_datehash["day"], $imgrow_updated_datehash["year"]);
//		pre("imgrow_updated=$imgrow_updated, imgrow_updated_ts=$imgrow_updated_ts");

// при перезаписи с лица в риче - авторесайз+ватермарк имеет то же самое имя, IE берёт из кеша
// делаем новое имя файла вида every_0x80-198382.jpg, прибавляя к 198382 ts обновления картинки
		$imgtype_row["merge_seed"] += substr($imgrow_updated_ts, -1, 6);

		if ($imgtype_row[$autoresize_type . "_autoresize_apply"] == 0) {
			$ar_img_row["IMG_NEX_DEBUGMSG"] = "$msg_bo_img_autoresize_element_this [$entity:$id] [$imgtype_hashkey]:[$autoresize_type] $msg_bo_img_autoresize_element_has_no_resize_apply_checked";
			break;
		}
		$i++;

		$img_resizable_byextension = 0;

//		$matches = array();
//		preg_match ("/\.(\w*)$/", $img_row[$field_from], $matches);
//		if (isset($matches[1])) {
//			$ext = strtolower($matches[1]);

		$pathinfo = pathinfo($img_row[$field_from]);
//		pre($field_from);
//		pre($pathinfo);

		if (isset($pathinfo["extension"])) {
			$ext = $pathinfo["extension"];
			$ext = strtolower($ext);

			switch ($ext) {
				case "jpg":
				case "jpeg":
				case "gif":
				case "png":
//				case "tif":
//				case "tiff":
//				case "bmp":
					$img_resizable_byextension = 1;
					break;

				default:
			}
		}

		if ($img_resizable_byextension == 0) {
			$ar_img_row["img_id"] = $img_row["id"];
			$ar_img_row["IMG_RESIZABLE_BYEXTENSION"] = 0;

//			pre($img_row);
//			pre($field_from);
			if ($get_next_until_ok == 1) continue;

			if (img_exists($img_row, $field_from) == 1) {
				$ar_img_row["IMG_EXISTS"] = 1;
				$ar_img_row["IMG_RELPATH"] = $upload_relpath . img_relpath($img_row, $field_from);
				$ar_img_row["IMG_WIDTH"] = $ar_width;
				$ar_img_row["IMG_HEIGHT"] = $ar_height;
				$ar_img_row["IMG_WH"] = " width='$ar_width' height='$ar_height' ";
				$ar_img_row["IMG_I"] = $i;
				unset($ar_img_row["IMG_NEX_DEBUGMSG"]);
			} else {
				$ar_img_row["IMG_EXISTS"] = 0;
				$ar_img_row["UPLOAD_RELPATH"] = $upload_relpath;
				$ar_img_row["IMG_NEX_DEBUGMSG"] = "[$entity:$id] failed: img_resizable_byextension=[$img_resizable_byextension] field_from=[$field_from]";
			}

			$ar_img_row = array_merge($ar_img_row, $img_row, $row);
			$tpl = ($ar_img_row["IMG_EXISTS"] == 1) ? $tpl_ex : $tpl_nex;
	
//			pre($ar_img_row);

			$ar_img_row["img_popuphref"] = href_img_popup($ar_img_row, $field_from);

			$ret .= hash_by_tpl($ar_img_row, $tpl);
			if (strpos($autoresize_type, "first") !== false) break;

		} else {
//			pre($img_row);
//			pre($ar_img_row);	// blind merging

			$ar_img_row["IMG_RESIZABLE_BYEXTENSION"] = 1;
			if (img_exists($img_row, $field_from) == 1 && ($ar_width > 0 || $ar_height > 0)) {
				$ar_img_row["img_id"] = $img_row["id"];
//				pre($img_row);
	
//				$ar_img_row["IMG_RELPATH"] = $upload_relpath . img_relpath($img_row, "img");

				$merge_apply = $imgtype_row[$autoresize_type . "_merge_apply"];
				$merge_seed = intval($imgtype_row["merge_seed"]);
	
				$ar_fname = img_resized_fname($img_row, $field_from, $autoresize_type, $ar_width, $ar_height);
				if ($merge_apply == 1) {
					$ar_fname = preg_replace("~(.*?)-(.*)?\.(.*)~", "\\1-$merge_seed.\\3", $ar_fname);
	//				pre($ar_fname);
				}
	
	//	uncomment to disable merged image in output hash
	//			$ar_fname = img_resized_fname($img_row, $field_from, $autoresize_type, $ar_width, $ar_height);
//				$ar_img_row = array_merge($img_row, array("img_ar" => $ar_fname));
				$ar_img_row = array_merge($ar_img_row, $img_row, array("img_ar" => $ar_fname));
	//			pre($img_row);
//				pre($ar_img_row);
				
				$ar_from_absname = $upload_abspath . img_relpath($ar_img_row, $field_from);
				$ar_from_abspath = dirname($ar_from_absname) . "/";
				
				$ar_dst_absname = $ar_from_abspath . $ar_fname;
	
				$should_resize = 1;
	
				// когда кончается место на хостинге
				if (file_exists($ar_dst_absname) && filesize($ar_dst_absname) == 0) unlink($ar_dst_absname);
	
				if (file_exists($ar_dst_absname)) {
	//				clearstatcache();
					$ar_mtime_ts = filemtime($ar_dst_absname);
					$ar_mtime = strftime("%Y-%m-%d %H:%M:%S", $ar_mtime_ts);
//					pre("ar_mtime=$ar_mtime, ar_mtime_ts=$ar_mtime_ts");

					if ($ar_mtime_ts > $imgrow_updated_ts && $ar_mtime_ts > $imgtype_updated_ts) $should_resize = 0;
					if ($should_resize == 1) $unlinked = unlink($ar_dst_absname);
/*
					pre("autoresize($ar_dst_absname)\n"
						. "if (ar_mtime > imgrow_updated_ts && ar_mtime > imgtype_updated_ts) should_resize = 0\n"
						. "if ($ar_mtime_ts > $imgrow_updated_ts && $ar_mtime_ts > $imgtype_updated_ts) should_resize = $should_resize\n"
						. "if ($ar_mtime > $imgrow_updated && $ar_mtime > $imgtype_updated) should_resize = $should_resize\n"
						. (($should_resize == 1) ? "unlink($ar_dst_absname) was done unlinked=$unlinked\n" : "unlink($ar_dst_absname) was not done\n")
						);
*/
				}
				
				if ($should_resize == 1) {
					if ($autoresize_debug == 1) pre("new autoresize[$imgtype_hashkey:$autoresize_type] " . basename($ar_dst_absname));
	//				img_resize($ar_from_abspath, $img_row[$field_from], $ar_width, $ar_height, $ar_fname, $quality);
					img_resize($ar_from_abspath, $ar_img_row[$field_from], $ar_width, $ar_height, $ar_fname, $quality);
	
					if ($merge_apply == 1) {
						img_merge_watermark($ar_from_abspath, $ar_fname, $imgtype_row, $autoresize_type);
					}
				}
		
				if (file_exists($ar_dst_absname)) {
					$ar_img_row["IMG_EXISTS"] = 1;
					$ar_img_row["IMG_RELPATH"] = $upload_relpath . img_relpath($ar_img_row, "img_ar");
					$img_size = getimagesize($ar_dst_absname);
					$ar_img_row["IMG_WIDTH"] = $img_size[0];
					$ar_img_row["IMG_HEIGHT"] = $img_size[1];
					$ar_img_row["IMG_WH"] = $img_size[3];
					$ar_img_row["IMG_I"] = $i;
					$ar_img_row["IMG_BIG_RELPATH"] = $upload_relpath . img_relpath($img_row, "img_big");
				} else {
					pre("autoresize does not exists");
					$ar_img_row["IMG_EXISTS"] = 0;
					$ar_img_row["UPLOAD_RELPATH"] = $upload_relpath;
					$ar_img_row["IMG_NEX_DEBUGMSG"] = "[$entity:$id] failed: [$ar_from_absname] => [$ar_dst_absname]";
				}
		
	
	//dont forget to cleanup old-dimensioned resizes (by fname prefix and expire = imgtype.date_updated)
				if ($dir = opendir($ar_from_abspath)) {
					while (($fname = readdir($dir)) !== false) {
						if ($fname == "." || $fname == ".." || $fname == $ar_fname) continue;
	//					pre($fname);
	//					if (preg_match("/" . $autoresize_type . "_.*/", $fname)) {
						if (strpos($fname, $autoresize_type . "_") !== false) {
							$unlinked = 0;
							$unlinked = unlink($ar_from_abspath . $fname);
							if ($autoresize_debug == 1) pre("[$entity:$id] unlinked=[$unlinked] unlink($fname), had [" . $autoresize_type . "_]");
						} else {
	//						if ($autoresize_debug == 1) pre("[$entity:$id] left unlinked fname=[$fname], had no [" . $autoresize_type . "_]");
						}
	
						$artypes = array ("first", "every", "first2", "every2", "first3", "every3", "first4", "every4");
						foreach ($artypes as $artype) {
							if (isset($imgtype_row[$artype . "_autoresize_apply"])
									&& $imgtype_row[$artype . "_autoresize_apply"] == 0
								) {
	//							pre("стирать $artype");
								if (strpos($fname, $artype . "_") !== false) {
									$unlinked = unlink($ar_from_abspath . $fname);
									if ($imgtype_row[$artype . "_autoresize_debug"] == 1) {
										pre("[$entity:$id] unlinked=[$unlinked] unlink($fname)");
									}
								}
							}
						}
					}
					closedir($dir);
				}
				$successful_images++;

			} else {
//				pre($img_row);
				if ($get_next_until_ok == 1) continue;
				$ar_img_row["IMG_NEX_DEBUGMSG"] = "$msg_bo_img_autoresize_element_this [$entity:$id] $msg_bo_img_autoresize_element_has_no_big_uploaded_or_HW_zero [$imgtype_hashkey]:[{$autoresize_type}_autoresize_width]=[$ar_width] [$imgtype_hashkey]:[{$autoresize_type}_autoresize_height]=[$ar_height]";
				$ar_img_row["IMG_TXT"] = $img_row["img_txt"];
				$ar_img_row["IMG_BIG_TXT"] = $img_row["img_big_txt"];
			}
			
			$tpl = ($ar_img_row["IMG_EXISTS"] == 1) ? $tpl_ex : $tpl_nex;
	
	//		$ar_img_row["img_id"] = $ar_img_row["id"];
	//	workaround for tpl_nex when not exists...
			if (isset($ar_img_row["id"])) $ar_img_row["img_id"] = $ar_img_row["id"];
			unset($ar_img_row["id"]);
			$ar_img_row = array_merge($ar_img_row, $row);
//			pre($ar_img_row);
	
			if (img_exists($img_row, $field_from) == 1) {

				$ar_img_row["img_popuphref"] = "javascript:popup_img(" . $img_row["id"]
						. ", " . $img_row[$field_from . "_w"] . ", " . $img_row[$field_from . "_h"] . ")";

// sarges 0703 popup resizable by IE
//				$ar_img_row["img_popuphref"] = "javascript:popup_blank('" . $ar_img_row["IMG_BIG_RELPATH"] . "'"
//						. ", " . $img_row[$field_from . "_w"] . ", " . $img_row[$field_from . "_h"] . ")";
			} else {
				$tpl = preg_replace ("~<a([^>]*)#IMG_POPUPHREF#([^>]*)>(.*)</a>~i", "\$3", $tpl);
			}
	
	//		$autoresize_imghash["#IMG" . $ar_img_row["img_id"] . "#"] = $ar_img_row;
	//	workaround for tpl_nex when not exists...
	//		if (isset($ar_img_row["img_id"])) $autoresize_imghash["#IMG" . $ar_img_row["img_id"] . "#"] = $ar_img_row;
			$ret .= hash_by_tpl($ar_img_row, $tpl);
	
			if (strpos($autoresize_type, "first") !== false) break;
		}

// если вызываем autoresize(every, limit=1, $get_next_until_ok=1), но первая failed, а следующая удалась - выходим
		if ($limit_first > 0 && $get_next_until_ok == 1 && $successful_images > 0) break;
	}
	
	if ($i == 0) {
		$ar_img_row = array_merge($ar_img_row, $row);
//		pre($ar_img_row);
		$ret .= hash_by_tpl($ar_img_row, $tpl_nex);
	}
	
	
	return $ret;
}


?>