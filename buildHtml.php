<?php
//--All Menu Build on Here
class buildHtml{
	function paginationCon($nsl, $pgn, $cpp){  //Now SL[Start From 1], Page No[Start from 1], Content/Page
		$nsl--;
		$pgn--;
		if($cpp<1) $cpp==10;
		if($nsl>=$pgn*$cpp && $nsl<($pgn+1)*$cpp)
		return true;
	}
	function paginationNav($tco, $pgn, $cpp, $js){  //Total Content, Page No[Start from 1], Content/Page, $js var as #val#
		$js=str_replace("#val#", "\$val", $js);
		$pgn--;
		if(!$cpp)
		$cpp=1;
		
		if($cpp<1) $cpp==10;
		$tpg=ceil($tco/$cpp);
		for($i=0; $i<$tpg; $i++){
			$op .="<option value='".($i+1)."'".($i==$pgn?" selected='selected'":"")."> Page No: ".($i+1)."</option>";
		}
		$pgn++;
		
		$val=$pgn-1;
		eval("\$val_prev = \"$js\";");
		$prev="<input type='button' name='next' id='next' value='<< Previous' class=\"btn btn-small btn-success\" style='width:80px;' onclick=\"$val_prev\"".($pgn<=1?" disabled='disabled'":"").">";
		
		$val=$pgn+1;
		eval("\$val_next = \"$js\";");
		$next="<input type='button' name='prev' id='prev' value='Next >>' class=\"btn btn-small btn-success\" style='width:80px;' onclick=\"$val_next\"".($pgn>=$tpg?" disabled='disabled'":"").">";
		
		return "$prev <select name='pn_op' id='pn_op' class=\"btn btn-small btn-success\" onchange=\"".str_replace("\$val", "'+value+'", $js)."\" style='width:120px;'>$op</select> $next";
	}
	function radio_ar1($array, $name, $head='Select', $def=false, $attr=false, $extra_row=false){
		if($array)
		foreach($array as $key=>$val){
			$op .="<label><input type=\"radio\" name=\"$name\" id=\"$key\" value=\"".$key."\" ".($def==$key?"checked=\"checked\"":"")." /> ".$val."</label> <br>";
		}
		else
		return "---";
		
		return "<div $attr>$op $extra_row</div>";
	}
	function radio_ar2($array, $val_key, $title_key, $name, $head='Select', $def=false, $attr=false, $extra_row=false){
		if($array)
		foreach($array as $key=>$val_ar){
			$op .="<label><input type=\"radio\" name=\"$name\" id=\"$key\" value=\"".$val_ar[$val_key]."\" ".($def==$val_ar[$val_key]?"checked=\"checked\"":"")." /> ".$val_ar[$title_key]."</label> <br>";
		}
		else
		return "---";
		
		return "<div $attr>$op $extra_row</div>";
	}
	function select_ar1($array, $name, $head='Select', $def=false, $attr=false, $extra_row=false){
		if($head)
		$op .="<option value=''>$head</option>";
		
		if($array)
		foreach($array as $key=>$val){
			$op .="<option value='$key' ".($def==$key?"selected='selected'":"").">$val</option>";
		}
		else
		return "---";
		
		return "<select name='$name' $attr>$op $extra_row</select>";
	}
	function select_ar2($array, $val_key, $title_key, $name, $head='Select', $def=false, $attr=false, $extra_row=false){
		if($head)
		$op .="<option value=''>$head</option>";
		
		if($array)
		foreach($array as $key=>$val_ar){
			$op .="<option value='".$val_ar[$val_key]."' ".($def==$val_ar[$val_key]?"selected='selected'":"").">".$val_ar[$title_key]."</option>";
		}
		else
		return "---";
		
		return "<select name='$name' $attr>$op $extra_row</select>";
	}
	
	
	
	//--Need Rechack
	function select_ar2($array, $val_key, $title_key, $name, $head='Select', $def=false, $attr=false, $extra_row=false){
		if($head)
		$op .="<option value=''>$head</option>";
		
		if($array)
		foreach($array as $key=>$val_ar){
			$op .="<option value='".$val_ar[$val_key]."' ".($def==$val_ar[$val_key]?"selected='selected'":"").">".$val_ar[$title_key]."</option>";
		}
		else
		return "---";
		
		return "<select name='$name' $attr>$op $extra_row</select>";
	}
	function checkbox_ar1($array_ar, $nameAr, $defAsHash=false, $uiAttr=false, $extra_row=false){
		$def_ar=explode("#", $defAsHash);
		$def_ar=array_filter($def_ar);
		
		if($array_ar)
		foreach($array_ar as $key=>$val){
			$op .="<li><label><input type=\"checkbox\" name=\"".$nameAr."[]\" value=\"".$val."\" ".(in_array($val, $def_ar) || $defAsStr=="all"?"checked=\"checked\"":"")." $attr> ".$val_ar[$title_key]."</label></li>";
		}
		else{
			$op .="---";
		}
		
		if($extra_row) 
		$extra_row="<li><label>$extra_row</label></li>";
		
		return "<ul id=\"$nameAr\" uiAttr>".$op.$extra_row."</ul>";
	}
	function checkbox_ar2($array, $val_key, $title_key, $nameAr, $defAsHash=false, $uiAttr=false, $extra_row=false){
		$def_ar=explode("#", $defAsHash);
		$def_ar=array_filter($def_ar);
		
		if($array)
		foreach($array as $key=>$val_ar){
			$op .="<li><label><input type=\"checkbox\" name=\"".$nameAr."[".$val_ar[$val_key]."]\" value=\"".$val_ar[$val_key]."\" ".(in_array($val_ar[$val_key], $def_ar) || $defAsHash=="all"?"checked=\"checked\"":"")." $attr> ".$val_ar[$title_key]."</label></li>";
		}
		else{
			$op .="---";
		}
		
		if($extra_row) 
		$extra_row="<li><label>$extra_row</label></li>";
		
		return "<ul id=\"$nameAr\" uiAttr>".$op.$extra_row."</ul>";
	}
	function radio_ar1($array, $name, $head='Select', $def=false, $attr=false, $extra_row=false){
		//if($head)
		//$op .="<option value=''>$head</option>";
		
		if($array)
		foreach($array as $key=>$val){
			$op .="<label><input type=\"radio\" name=\"$name\" id=\"$key\" value=\"".$key."\" ".($def==$key?"checked=\"checked\"":"")." /> ".$val."</label> <br>";
		}
		else
		return "---";
		
		return "<div $attr>$op $extra_row</div>";
	}
	function radio_ar2($array, $val_key, $title_key, $name, $head='Select', $def=false, $attr=false, $extra_row=false){
		//if($head)
		//$op .="<option value=''>$head</option>";
		
		if($array)
		foreach($array as $key=>$val_ar){
			$op .="<label><input type=\"radio\" name=\"$name\" id=\"$key\" value=\"".$val_ar[$val_key]."\" ".($def==$val_ar[$val_key]?"checked=\"checked\"":"")." /> ".$val_ar[$title_key]."</label> <br>";
		}
		else
		return "---";
		
		return "<div $attr>$op $extra_row</div>";
	}
	function selectLoop($from, $to, $titlePrefix, $name, $head='Select', $def=false, $attr=false, $extra_row=false){
		if($head)
		$op .="<option value=''>$head</option>";
		
		for($i=$from; $i<=$to; $i++){
			$op .="<option value='$i' ".($def==$i?"selected='selected'":"").">$titlePrefix".$i."</option>";
		}
		
		return "<select name='$name' $attr>$op $extra_row</select>";
	}
}
$buildHtml 		= new buildHtml;
?>
