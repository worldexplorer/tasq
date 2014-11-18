// ondblclick="javascript:popup_url('imgtype-edit.php?id=$imgtype_id')"
function popup_blank(url) {
	width = (arguments.length >= 1) ? arguments[1] : "";
	height = (arguments.length >= 2) ? arguments[2] : "";
	window_name = (arguments.length >= 3) ? arguments[3] : "";
	params = (arguments.length >= 4) ? arguments[4] : "";

//	params = ""
	if (params == "") {
		params = "resizable=1,scrollbars=1,toolbar=0,location=0,directories=0,status=1,menubar=0"
	} else if (params == "clean") {
		params = "resizable=1,scrollbars=1,toolbar=1,location=1,directories=1,status=1,menubar=1"
	}

	if (width != "") {
		if (params != "") params += ","
		params += "width=" + width
	}
	if (height != "") {
		if (params != "") params += ","
		params += "height=" + height
	}

//	alert(params)
	popup_win = window.open(url, window_name, params);
	popup_win.focus();
	return popup_win;
}

function popup_url(url) {
	window_name = (arguments.length >= 2) ? arguments[1] : "";
//	alert(window_name)
	window_params = (arguments.length >= 3) ? arguments[2] : "resizable=1,scrollbars=1,toolbar=0,location=0,directories=0,status=1,menubar=0";
//	alert(window_params)
	popup_win = window.open(url, window_name, window_params);
	popup_win.focus();
}

function popup_imgurl(imgurl, width, height) {
	p_width = width + 40
	p_height = height + 70
	
	popup_win = window.open("_popup_imgurl.php?imgurl=" + imgurl + "&width=" + width + "&height=" + height, "",
		"resizable=1,scrollbars=1,toolbar=0,location=0,directories=0,status=1,menubar=0,width="+p_width+",height="+p_height);
	popup_win.focus();
}

function popup_img(img_id, width, height){
	width += 50
	height += 70
	
//	if (width > 800) width = 800
//	if (height > 600) height = 600
//	if (width < 320) width = 320
//	if (height < 200) height = 200

	popup_win = window.open("_popup_img.php?img_id=" + img_id, "img_" + img_id, "resizable=1,scrollbars=1,toolbar=0,location=0,directories=0,status=0,menubar=0,width="+width+",height="+height)
	popup_win.focus()
}

function popup_entityimg(entity, entity_id, imgfield, width, height){
	width += 40
	height += 70
	
	popup_win = window.open("_popup_entityimg.php?entity=" + entity + "&entity_id=" + entity_id + "&imgfield=" + imgfield, entity + "_" + entity_id + "_" + imgfield,
		"resizable=1,scrollbars=1,toolbar=0,location=0,directories=0,status=0,menubar=0,width="+width+",height="+height)
	popup_win.focus()
}

function popup_bo(bo_href) {
	popup_win = window.open("backoffice/" + bo_href);
	popup_win.focus();
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function form_find_it(it_name, form_name) {
	ret = null

	form_name = (arguments.length >= 2) ? arguments[1] : "form_edit";
//	alert(form_name)

	form_element = MM_findObj(form_name)
//	alert(form_element)
	
//	alert(form_edit[it_name])
	if (form_element[it_name]+"~" != "undefined") ret = form_element[it_name]
	
	return ret
}

function layer_isopened(nr) {
	ret = 0
	layer_nr = MM_findObj("layer_" + nr)
//	alert ("layer_open(" + nr + "): " + layer_nr)
	if (layer_nr != null) {
		if (layer_nr.style.display == 'block') ret = 1
	}
	return ret
}

function layer_open(nr) {
	layer_nr = MM_findObj("layer_" + nr)
//	alert ("layer_open(" + nr + "): " + layer_nr)
	if (layer_nr != null) layer_nr.style.display = 'block'

	layer_opened_nr = MM_findObj("layer_opened_nr")
	if (layer_opened_nr != null) layer_opened_nr.value = nr
//	alert ("layer_open(" + nr + "): layer_opened_nr=" + layer_opened_nr.value)
}

function layer_close(nr) {
	layer_nr = MM_findObj("layer_" + nr)
//	alert ("layer_close(" + nr + "): " + layer_nr)
	if (layer_nr != null) layer_nr.style.display = 'none'

	layer_opened_nr = MM_findObj("layer_opened_nr")
	if (layer_opened_nr != null) layer_opened_nr.value = 0
//	alert ("layer_close(" + nr + "): layer_opened_nr=" + layer_opened_nr.value)
}

function layer_switch(nr) {
	for (i = 1; i <= layers_total; i++) {
	    if (i != nr) layer_close(i)
	}

	if (layer_isopened(nr)) layer_close(nr)
	else layer_open(nr)
}

function layer_switch_forceopened(nr) {
	for (i = 1; i <= layers_total; i++) {
	    if (i == nr) layer_open(i)
	    else layer_close(i)
	}
}

function ilayer_open(nr) {
	layer_nr = MM_findObj("layer_" + nr)
//	alert ("layer_open(" + nr + "): " + layer_nr)
	if (layer_nr != null) layer_nr.style.display = 'block'
}

function ilayer_close(nr) {
	layer_nr = MM_findObj("layer_" + nr)
//	alert ("layer_close(" + nr + "): " + layer_nr)
	if (layer_nr != null) layer_nr.style.display = 'none'

}

function ilayer_switch(nr) {
	if (layer_isopened(nr)) ilayer_close(nr)
	else ilayer_open(nr)
}


function ilayer_switch_focusing_wrapper(nr) {
	if (layer_isopened(nr)) {
		ilayer_close(nr)
	} else {
		ilayer_open(nr)
		a_focus =  MM_findObj(nr + "_focus_after_open")
		if (a_focus != null) {
//			alert("focusing to " + nr + "_focus_after_open")
			a_focus.focus();
//			location = "#" + nr + "_focus_after_open"
			window.scrollBy(0, 50)
		}
	}
}

function focus_itname(it_name, form_name) {
//	vitem = MM_findObj(it_name)
//	alert(vitem)
//	if (vitem != null) vitem.focus()
	
	form_name = (arguments.length >= 2) ? arguments[1] : "form_edit";
	vitem = form_find_it(it_name, form_name)
	if (vitem != null) vitem.focus()
}



function getformelements_startingfrom(form_name, startingfrom) {
	ret = new Array()

	j = 0
	felem_all = document.forms[form_name].elements

	for (i=0; i<felem_all.length; i++) {
		i_name = felem_all[i].name
//		alert("e[" + i_name + "] s[" + startingfrom + "]: " + i_name.indexOf(startingfrom))
		if (i_name.indexOf(startingfrom) != -1) {
			ret[j] = felem_all[i]
			j++
		}
	}
	
	return ret
}




function alert_img_ondel(cb) {
	if (cb.checked == true) {
		alert('Лучше снять флажок "опубликовано"       \n\nРекомендуется удалять только:\n1. когда не хватает места на диске\n2. когда из текста вычищены все\n    маркеры и ссылки на эту картинку')
	}
}


function img_control_switch(nr) {
	ilayer_switch("img_img_" + nr)
	ilayer_switch("img_img_big_" + nr)

	ilayer_switch("img_hr_" + nr)
	ilayer_switch("img_qresize_" + nr)
	ilayer_switch("img_resize_" + nr)
	ilayer_switch("img_big_qresize_" + nr)
	ilayer_switch("img_big_resize_" + nr)
}



function pgroup_onclick(id) {
//	alert(id)
	layer_onclick("pgroup_" + id)
}

function supplier_onclick(id) {
//	alert(id)
	layer_onclick("supplier_" + id)
}

function layer_onclick(item_id) {
//	alert(item_id)

	layer = document.getElementById(item_id)

	if (layer.style.display == "none") {
		layer.style.display = "inline"
	} else {
		layer.style.display = "none"
	}
	
}