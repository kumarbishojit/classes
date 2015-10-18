<?php
//--Convart Some Parameters
class convert{
	function ip2int($ip){
		$int_ar=explode(".", $ip);
		return $int_ar[0]*256*256*256+$int_ar[1]*256*256+$int_ar[2]*256+$int_ar[3];
	}
	function int2ip($int){
		$ip_ar[3]=$int%256;
		$int=($int-$ip_ar[3])/256;
		
		$ip_ar[2]=$int%256;
		$int=($int-$ip_ar[2])/256;
		
		$ip_ar[1]=$int%256;
		$ip_ar[0]=round(($int-$ip_ar[1])/256);
		
		return $ip_ar[0].".".$ip_ar[1].".".$ip_ar[2].".".$ip_ar[3];
	}
	function date2int($in, $time=""){
		if($time=="")
		$time=TIME;
		
		$in_ar=explode(" ", $in);
		$in_date_ar=explode("/", $in_ar[0]);
		$in_h_ar=explode(":", $in_ar[1]);
		if(strtoupper($in_ar[2])=="PM")
		$in_a=12*3600;
		
		return strtotime("$in_date_ar[2]-$in_date_ar[1]-$in_date_ar[0]", $time)+($in_h_ar[0]%12*3600+$in_h_ar[1]*60+$in_h_ar[2])+$in_a;
	}
	function db_date2int($in){ //20150204
		return $this->date2int(substr($in, 6, 2)."/".substr($in, 4, 2)."/".substr($in, 0, 4));
	}
	function byte2kmg($bytes){
		if ($bytes >= 1073741824)
		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		elseif ($bytes >= 1048576)
		$bytes = number_format($bytes / 1048576, 2) . ' MB';
		elseif ($bytes >= 1024)
		$bytes = number_format($bytes / 1024, 2) . ' KB';
		elseif ($bytes > 1)
		$bytes = $bytes . ' bytes';
		elseif ($bytes == 1)
		$bytes = $bytes . ' byte';
		else
		$bytes = '0 byte';
		
		return $bytes;
	}
	function en_bn($in){
		$ar1=array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'AM', 'PM', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');
		$ar2=array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', 'এ. এম.', 'পি. এম.', 'জানুয়ারী', 'ফেব্রুয়ারী', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর', 'শনিবার', 'রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার', 'শুক্রবার');
		return str_replace($ar1, $ar2, $in);
	}
	function number_to_words($number){
		$hyphen      = '-';
		$conjunction = ' and ';
		$separator   = ', ';
		$negative    = 'negative ';
		$decimal     = ' point ';
		$dictionary  = array(
			0                   => 'zero',
			1                   => 'one',
			2                   => 'two',
			3                   => 'three',
			4                   => 'four',
			5                   => 'five',
			6                   => 'six',
			7                   => 'seven',
			8                   => 'eight',
			9                   => 'nine',
			10                  => 'ten',
			11                  => 'eleven',
			12                  => 'twelve',
			13                  => 'thirteen',
			14                  => 'fourteen',
			15                  => 'fifteen',
			16                  => 'sixteen',
			17                  => 'seventeen',
			18                  => 'eighteen',
			19                  => 'nineteen',
			20                  => 'twenty',
			30                  => 'thirty',
			40                  => 'fourty',
			50                  => 'fifty',
			60                  => 'sixty',
			70                  => 'seventy',
			80                  => 'eighty',
			90                  => 'ninety',
			100                 => 'hundred',
			1000                => 'thousand',
			1000000             => 'million',
			1000000000          => 'billion',
			1000000000000       => 'trillion',
			1000000000000000    => 'quadrillion',
			1000000000000000000 => 'quintillion'
		);
	
		if (!is_numeric($number)) {
			return false;
		}
	
		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
				'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
				E_USER_WARNING
			);
			return false;
		}
	
		if ($number < 0) {
			return $negative . convert_number_to_words(abs($number));
		}
	
		$string = $fraction = null;
	
		if (strpos($number, '.') !== false) {
			list($number, $fraction) = explode('.', $number);
		}
	
		switch (true) {
			case $number < 21:
				$string = $dictionary[$number];
				break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
				break;
			case $number < 1000:
				$hundreds  = $number / 100;
				$remainder = $number % 100;
				$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
				if ($remainder) {
					$string .= $conjunction . $this->number_to_words($remainder);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number % $baseUnit;
				$string = $this->number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .= $this->number_to_words($remainder);
				}
				break;
		}
	
		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $decimal;
			$words = array();
			foreach (str_split((string) $fraction) as $number) {
				$words[] = $dictionary[$number];
			}
			$string .= implode(' ', $words);
		}
	
		return $string;
	}
	function link2domain($link){
		$link=str_replace("https://www.", "", strtolower($link));
		$link=str_replace("https://", "", $link);
		
		$link=str_replace("http://www.", "", strtolower($link));
		$link=str_replace("http://", "", $link);
		$link=str_replace("www.", "", $link);
		
		$link_ar=explode("/", $link);
		return $link_ar[0];
	}
	function text2link($text, $target=""){
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		preg_match_all($reg_exUrl, $text, $matches);
		$usedPatterns = array();
		foreach($matches[0] as $pattern){
			if(!array_key_exists($pattern, $usedPatterns)){
				$usedPatterns[$pattern]=true;
				$text = str_replace($pattern, "<a href='".$pattern."' target='$target'>".$pattern."</a> ", $text);   
			}
		}
		return $text;            
	}
	function encoding($in){
		$enc_code=str_split("AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz0123456789-_");
		$in_ar=str_split($in);
		foreach($in_ar as $chr){
			$bit_ar[]=str_pad(decbin(ord($chr)), 8, "0", STR_PAD_LEFT);
		}
		$bit=implode("", $bit_ar);
		
		$bit6_ar=str_split($bit, 6);
		$bit6_ar[sizeof($bit6_ar)-1]=str_pad($bit6_ar[sizeof($bit6_ar)-1], 6, "0", STR_PAD_RIGHT);
		foreach($bit6_ar as $bit){
			$out .=$enc_code[bindec($bit)];
		}
		return $out;
	}
	
	function decoding($in){
		$enc_code=str_split("AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz0123456789-_");
		$in_ar=str_split($in);
		foreach($in_ar as $chr){
			$bit_ar[]=str_pad(decbin(array_search($chr, $enc_code)), 6, "0", STR_PAD_LEFT);
		}
		$bit=implode("", $bit_ar);
		
		$bit8_ar=str_split($bit, 8);
		
		foreach($bit8_ar as $bit){
			if(($chr=bindec($bit))>31 && $chr<127)
			$out .=chr($chr);
		}
		
		return $out;
	}
	function mobileno_bd($num){
		global $check;
		if(substr($num, 0, 3)=="+88")		//+8801925363333
		$num=substr($num, 3);				//01925363333
		else if(substr($num, 0, 2)=="88")	//8801925363333
		$num=substr($num, 2);				//01925363333
		else if(substr($num, 0, 4)=="0088")	//008801925363333
		$num=substr($num, 4);				//01925363333
		
		if(!$check->str($num, $num, 11, 11, "0-9"))
		return false;
	
		return $num;
	}
	function youtubeLinkToCode($string){
		preg_match('#(?:http://)?(?:www\.)?(?:youtube\.com/(?:v/|watch\?v=)|youtu\.be/)([\w-]+)(?:\S+)?#', $string, $match);
		return $match[1];
	}
	function youtubeCodeToLink($code){
		return "https://www.youtube.com/watch?v=$code";
	}
	function escStr($input){
		return mysql_real_escape_string($input);
	}
	function token($length){
                $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                $codeAlphabet_ar=str_split($codeAlphabet);
               
                for($i=0; $i<$length; $i++){
                	$token .= $codeAlphabet_ar[rand(0, 35)];
                }
                return $token;
        }
}
$convert		= new convert; 
?>
