<?php
class tm{
	function ymdToDate($Ymd){
		return substr($Ymd, 6, 2)."/".substr($Ymd, 4, 2)."/".substr($Ymd, 0, 4);
	}
	function ymdToTime($Ymd){
		global $convert;
		return $convert->date2int(substr($Ymd, 6, 2)."/".substr($Ymd, 4, 2)."/".substr($Ymd, 0, 4));
	}
	
	function dateToDayStart($date=false){
		global $convert;
		
		if(!$date)
		$date=date("d/m/Y", TIME);
		
		return $convert->date2int($date, TIME);
	}
	function dateToDayEnd($date=false){
		global $convert;
		
		if(!$date)
		$date=date("d/m/Y", TIME);
		
		return $convert->date2int($date, TIME)+24*3600-1;
	}
	function timeToDayStart($time=false){
		global $convert;
		
		if(!$time)
		$time=TIME;
		
		$date=date("d/m/Y", $time);
		
		return $convert->date2int($date, TIME);
	}
	function timeToDayEnd($date=false){
		global $convert;
		
		if(!$time)
		$time=TIME;
		
		$date=date("d/m/Y", $time);
		
		return $convert->date2int($date, TIME)+24*3600-1;
	}
	function Ymd2Date($in){
		return substr($in, 6, 2)."/".substr($in, 4, 2)."/".substr($in, 0, 4);
	}
	function Ymd2DayStart($in){
		return strtotime($in);
	}
	function Ymd2DayEnd($in){
		return strtotime($in)+24*3600-1;
	}
	function yearStart($year=false){
		global $convert;
		
		if(!$year)
		$year=date("Y", TIME);
		
		return $convert->date2int("01/01/".$year);
	}
	function yearEnd($year=false){
		global $convert;
		
		if(!$year)
		$year=date("Y", TIME);
		
		return $convert->date2int("01/01/".($year+1))-1;
	}
	function prevMonthDate($inDate){
		global $convert;
		
		$d=substr($inDate, 0, 2);
		
		$monyhStartTime=$convert->date2int("01/".substr($inDate, 3))-1;
		$preD=date("d", $monyhStartTime);
		
		if($d>$preD)
		$d=$preD;
		
		return $d."/".date("m/Y", $monyhStartTime);
	}
}
$tm		= new tm;
?>
