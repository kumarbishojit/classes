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
		
		return "<select name='$name' $attr>$op $extra_row</select>";
	}
	function select_ar2($array, $val_key, $title_key, $name, $head='Select', $def=false, $attr=false, $extra_row=false){
		if($head)
		$op .="<option value=''>$head</option>";
		
		if($array)
		foreach($array as $key=>$val_ar){
			$op .="<option value='".$val_ar[$val_key]."' ".($def==$val_ar[$val_key]?"selected='selected'":"").">".$val_ar[$title_key]."</option>";
		}
		
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
	function selectCountry($name, $head='Select', $def=false, $attr=false, $extra_row=false){
		$country_array = array(
		"AF" => "Afghanistan",
		"AL" => "Albania",
		"DZ" => "Algeria",
		"AS" => "American Samoa",
		"AD" => "Andorra",
		"AO" => "Angola",
		"AI" => "Anguilla",
		"AQ" => "Antarctica",
		"AG" => "Antigua and Barbuda",
		"AR" => "Argentina",
		"AM" => "Armenia",
		"AW" => "Aruba",
		"AU" => "Australia",
		"AT" => "Austria",
		"AZ" => "Azerbaijan",
		"BS" => "Bahamas",
		"BH" => "Bahrain",
		"BD" => "Bangladesh",
		"BB" => "Barbados",
		"BY" => "Belarus",
		"BE" => "Belgium",
		"BZ" => "Belize",
		"BJ" => "Benin",
		"BM" => "Bermuda",
		"BT" => "Bhutan",
		"BO" => "Bolivia",
		"BA" => "Bosnia and Herzegovina",
		"BW" => "Botswana",
		"BV" => "Bouvet Island",
		"BR" => "Brazil",
		"BQ" => "British Antarctic Territory",
		"IO" => "British Indian Ocean Territory",
		"VG" => "British Virgin Islands",
		"BN" => "Brunei",
		"BG" => "Bulgaria",
		"BF" => "Burkina Faso",
		"BI" => "Burundi",
		"KH" => "Cambodia",
		"CM" => "Cameroon",
		"CA" => "Canada",
		"CT" => "Canton and Enderbury Islands",
		"CV" => "Cape Verde",
		"KY" => "Cayman Islands",
		"CF" => "Central African Republic",
		"TD" => "Chad",
		"CL" => "Chile",
		"CN" => "China",
		"CX" => "Christmas Island",
		"CC" => "Cocos [Keeling] Islands",
		"CO" => "Colombia",
		"KM" => "Comoros",
		"CG" => "Congo - Brazzaville",
		"CD" => "Congo - Kinshasa",
		"CK" => "Cook Islands",
		"CR" => "Costa Rica",
		"HR" => "Croatia",
		"CU" => "Cuba",
		"CY" => "Cyprus",
		"CZ" => "Czech Republic",
		"CI" => "Côte d’Ivoire",
		"DK" => "Denmark",
		"DJ" => "Djibouti",
		"DM" => "Dominica",
		"DO" => "Dominican Republic",
		"NQ" => "Dronning Maud Land",
		"DD" => "East Germany",
		"EC" => "Ecuador",
		"EG" => "Egypt",
		"SV" => "El Salvador",
		"GQ" => "Equatorial Guinea",
		"ER" => "Eritrea",
		"EE" => "Estonia",
		"ET" => "Ethiopia",
		"FK" => "Falkland Islands",
		"FO" => "Faroe Islands",
		"FJ" => "Fiji",
		"FI" => "Finland",
		"FR" => "France",
		"GF" => "French Guiana",
		"PF" => "French Polynesia",
		"TF" => "French Southern Territories",
		"FQ" => "French Southern and Antarctic Territories",
		"GA" => "Gabon",
		"GM" => "Gambia",
		"GE" => "Georgia",
		"DE" => "Germany",
		"GH" => "Ghana",
		"GI" => "Gibraltar",
		"GR" => "Greece",
		"GL" => "Greenland",
		"GD" => "Grenada",
		"GP" => "Guadeloupe",
		"GU" => "Guam",
		"GT" => "Guatemala",
		"GG" => "Guernsey",
		"GN" => "Guinea",
		"GW" => "Guinea-Bissau",
		"GY" => "Guyana",
		"HT" => "Haiti",
		"HM" => "Heard Island and McDonald Islands",
		"HN" => "Honduras",
		"HK" => "Hong Kong SAR China",
		"HU" => "Hungary",
		"IS" => "Iceland",
		"IN" => "India",
		"ID" => "Indonesia",
		"IR" => "Iran",
		"IQ" => "Iraq",
		"IE" => "Ireland",
		"IM" => "Isle of Man",
		"IL" => "Israel",
		"IT" => "Italy",
		"JM" => "Jamaica",
		"JP" => "Japan",
		"JE" => "Jersey",
		"JT" => "Johnston Island",
		"JO" => "Jordan",
		"KZ" => "Kazakhstan",
		"KE" => "Kenya",
		"KI" => "Kiribati",
		"KW" => "Kuwait",
		"KG" => "Kyrgyzstan",
		"LA" => "Laos",
		"LV" => "Latvia",
		"LB" => "Lebanon",
		"LS" => "Lesotho",
		"LR" => "Liberia",
		"LY" => "Libya",
		"LI" => "Liechtenstein",
		"LT" => "Lithuania",
		"LU" => "Luxembourg",
		"MO" => "Macau SAR China",
		"MK" => "Macedonia",
		"MG" => "Madagascar",
		"MW" => "Malawi",
		"MY" => "Malaysia",
		"MV" => "Maldives",
		"ML" => "Mali",
		"MT" => "Malta",
		"MH" => "Marshall Islands",
		"MQ" => "Martinique",
		"MR" => "Mauritania",
		"MU" => "Mauritius",
		"YT" => "Mayotte",
		"FX" => "Metropolitan France",
		"MX" => "Mexico",
		"FM" => "Micronesia",
		"MI" => "Midway Islands",
		"MD" => "Moldova",
		"MC" => "Monaco",
		"MN" => "Mongolia",
		"ME" => "Montenegro",
		"MS" => "Montserrat",
		"MA" => "Morocco",
		"MZ" => "Mozambique",
		"MM" => "Myanmar [Burma]",
		"NA" => "Namibia",
		"NR" => "Nauru",
		"NP" => "Nepal",
		"NL" => "Netherlands",
		"AN" => "Netherlands Antilles",
		"NT" => "Neutral Zone",
		"NC" => "New Caledonia",
		"NZ" => "New Zealand",
		"NI" => "Nicaragua",
		"NE" => "Niger",
		"NG" => "Nigeria",
		"NU" => "Niue",
		"NF" => "Norfolk Island",
		"KP" => "North Korea",
		"VD" => "North Vietnam",
		"MP" => "Northern Mariana Islands",
		"NO" => "Norway",
		"OM" => "Oman",
		"PC" => "Pacific Islands Trust Territory",
		"PK" => "Pakistan",
		"PW" => "Palau",
		"PS" => "Palestinian Territories",
		"PA" => "Panama",
		"PZ" => "Panama Canal Zone",
		"PG" => "Papua New Guinea",
		"PY" => "Paraguay",
		"YD" => "People's Democratic Republic of Yemen",
		"PE" => "Peru",
		"PH" => "Philippines",
		"PN" => "Pitcairn Islands",
		"PL" => "Poland",
		"PT" => "Portugal",
		"PR" => "Puerto Rico",
		"QA" => "Qatar",
		"RO" => "Romania",
		"RU" => "Russia",
		"RW" => "Rwanda",
		"RE" => "Réunion",
		"BL" => "Saint Barthélemy",
		"SH" => "Saint Helena",
		"KN" => "Saint Kitts and Nevis",
		"LC" => "Saint Lucia",
		"MF" => "Saint Martin",
		"PM" => "Saint Pierre and Miquelon",
		"VC" => "Saint Vincent and the Grenadines",
		"WS" => "Samoa",
		"SM" => "San Marino",
		"SA" => "Saudi Arabia",
		"SN" => "Senegal",
		"RS" => "Serbia",
		"CS" => "Serbia and Montenegro",
		"SC" => "Seychelles",
		"SL" => "Sierra Leone",
		"SG" => "Singapore",
		"SK" => "Slovakia",
		"SI" => "Slovenia",
		"SB" => "Solomon Islands",
		"SO" => "Somalia",
		"ZA" => "South Africa",
		"GS" => "South Georgia and the South Sandwich Islands",
		"KR" => "South Korea",
		"ES" => "Spain",
		"LK" => "Sri Lanka",
		"SD" => "Sudan",
		"SR" => "Suriname",
		"SJ" => "Svalbard and Jan Mayen",
		"SZ" => "Swaziland",
		"SE" => "Sweden",
		"CH" => "Switzerland",
		"SY" => "Syria",
		"ST" => "São Tomé and Príncipe",
		"TW" => "Taiwan",
		"TJ" => "Tajikistan",
		"TZ" => "Tanzania",
		"TH" => "Thailand",
		"TL" => "Timor-Leste",
		"TG" => "Togo",
		"TK" => "Tokelau",
		"TO" => "Tonga",
		"TT" => "Trinidad and Tobago",
		"TN" => "Tunisia",
		"TR" => "Turkey",
		"TM" => "Turkmenistan",
		"TC" => "Turks and Caicos Islands",
		"TV" => "Tuvalu",
		"UM" => "U.S. Minor Outlying Islands",
		"PU" => "U.S. Miscellaneous Pacific Islands",
		"VI" => "U.S. Virgin Islands",
		"UG" => "Uganda",
		"UA" => "Ukraine",
		"SU" => "Union of Soviet Socialist Republics",
		"AE" => "United Arab Emirates",
		"GB" => "United Kingdom",
		"US" => "United States",
		"ZZ" => "Unknown or Invalid Region",
		"UY" => "Uruguay",
		"UZ" => "Uzbekistan",
		"VU" => "Vanuatu",
		"VA" => "Vatican City",
		"VE" => "Venezuela",
		"VN" => "Vietnam",
		"WK" => "Wake Island",
		"WF" => "Wallis and Futuna",
		"EH" => "Western Sahara",
		"YE" => "Yemen",
		"ZM" => "Zambia",
		"ZW" => "Zimbabwe",
		"AX" => "Åland Islands",
		);
		
		
		if($country_array)
		foreach($country_array as $key=>$val){
			$op .="<option value='".$key."' ".($def==$key?"selected='selected'":"").">".$val."</option>";
		}
		else
		return "---";
		
		return "<select name='$name' $attr>$op $extra_row</select>";
	}
}
$buildHtml 		= new buildHtml;
?>
