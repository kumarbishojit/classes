<?php
class tm{
	function ymdToDate($Ymd){
		return substr($Ymd, 6, 2)."/".substr($Ymd, 4, 2)."/".substr($Ymd, 0, 4);
	}
	function ymdToTime($Ymd){
		global $convert;
		return $convert->date2int(substr($Ymd, 6, 2)."/".substr($Ymd, 4, 2)."/".substr($Ymd, 0, 4));
	}
}
$tm		= new tm;
?>
