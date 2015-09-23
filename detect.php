class detect{
	function os($user_agent=""){
		if($user_agent=="")
		$user_agent=$_SERVER['HTTP_USER_AGENT'];
		
		$os_array    = array(
      '/windows nt 10.0/i'    =>  'Windows-10',
			'/windows nt 6.3/i'     =>  'Windows-8.1',
			'/windows nt 6.2/i'     =>  'Windows-8',
			'/windows nt 6.1/i'     =>  'Windows-7',
			'/windows nt 6.0/i'     =>  'Windows-Vista',
			'/windows nt 5.2/i'     =>  'Windows-Server-2003/XP-x64',
			'/windows nt 5.1/i'     =>  'Windows-XP-5.1',
			'/windows xp/i'         =>  'Windows-XP',
			'/windows nt 5.0/i'     =>  'Windows-2000',
			'/windows me/i'         =>  'Windows-ME',
			'/win98/i'              =>  'Windows-98',
			'/windows 98/i'         =>  'Windows-98',
			'/win95/i'              =>  'Windows-95',
			'/win16/i'              =>  'Windows-3.11',
			'/macintosh|mac os x/i' =>  'Mac-OS-X',
			'/mac_powerpc/i'        =>  'Mac-OS-9',
			'/android/i'            =>  'Android',
			'/linux/i'              =>  'Linux',
			'/ubuntu/i'             =>  'Ubuntu',
			'/iphone/i'             =>  'iPhone',
			'/ipod/i'               =>  'iPod',
			'/ipad/i'               =>  'iPad',
			'/presto/i'             =>  'Presto',
			'/blackberry/i'         =>  'BlackBerry',
			'/webos/i'              =>  'Mobile',
			'/j2me/i'               =>  'Java Mobile',
			'/midp/i'               =>  'Java Mobile',
			'/java/i'               =>  'Java Mobile',
			'/windows mobile/i'     =>  'Windows Mobile',
			'/nokia/i'              =>  'NOKIA',
			'/series 60/i'          =>  'NOKIA',
			'/htc/i'                =>  'HTC',
			'/grameenphone/i'       =>  'Grameenphone',
			'/spreadtrum/i'         =>  'SpreadTrum',
			'/maui runtime/i'       =>  'Mobile App Dev',
			'/google/i'             =>  'Google Bot',
			'/admedia/i'            =>  'AdMedia Bot',
			'/contextad/i'          =>  'ContextAd Bot',
			'/facebook/i'           =>  'Facebook Bot',
			'/feedburner/i'         =>  'FeedBurner Bot',
			'/alexa/i'              =>  'Alexa Bot',
			'/cloudflare/i'         =>  'Cloudflare Bot',
			'/yandexbot/i'          =>  'Yandex Bot',
			'/bing/i'               =>  'Bing Bot'
		);
	
		foreach ($os_array as $regex => $value){ 
			if (preg_match($regex, $user_agent)) {
				return $value;
			}
		}
		return $user_agent;
	}
	
	function browser($user_agent=""){
		if($user_agent=="")
		$user_agent=$_SERVER['HTTP_USER_AGENT'];
		
		$browser_array = array(
			'/opr/i'          =>  'Opera',
			'/opera/i'        =>  'Opera',
			'/msie/i'         =>  'IE',
			'/firefox/i'      =>  'Firefox',
			'/nokia/i'        =>  'Nokia',
			'/chrome/i'       =>  'Chrome',
			'/trident/i'      =>  'Trident',
			'/mqqbrowser/i'   =>  'MQQBrowser',
			
			'/netscape/i'     =>  'Netscape',
			'/maxthon/i'      =>  'Maxthon',
			'/konqueror/i'    =>  'Konqueror',
			'/safari/i'			=>  'Safari',
			'/mobile/i'			=>  'Handheld Browser',
			'/google/i'       		=>  'Google Bot',
			'/admedia/i'   			=>  'AdMedia Bot',
			'/contextad/i'   		=>  'ContextAd Bot',
			'/facebook/i'   		=>  'Facebook Bot',
			'/feedburner/i'   		=>  'FeedBurner Bot',
			'/alexa/i'   			=>  'Alexa Bot',
			'/cloudflare/i'   		=>  'Cloudflare Bot',
			'/yandexbot/i'   		=>  'Yandex Bot',
			'/bing/i'   			=>  'Bing Bot'
		);
	
		foreach ($browser_array as $regex => $value) {
			if (preg_match($regex, $user_agent)) {
				return $value;
			}
		}
		return $user_agent;
	}
	function pc(){
		global $convart;
		global $query;
		
		$time=TIME;
		$ip=USER_IP;
		
		$os=$this->os();
		$browser=$this->browser();
		
		$pcSl=$_COOKIE['pcSl'];
		$oprtr=$_COOKIE['oprtr_val'];
		
		$country=$_COOKIE['country'];
		$city=$_COOKIE['city'];
		$region=$_COOKIE['region'];
		
		//--Collect From DB
		if($pc_info_ar=$query->byKey("SELECT * FROM `pc_detect` WHERE (`os`='$os' AND `ip`='$ip') OR (`oprtr`='$oprtr' AND `oprtr`!=0) OR (`sl`='$pcSl') ORDER BY `pc_detect`.`sl` DESC LIMIT 1")){
			//--Update DB
			$in_ar['browser']=$browser;
			$in_ar['os']=$os;
			$in_ar['country']=$country;
			$in_ar['city']=$city;
			$in_ar['region']=$region;
			$in_ar['ip']=$ip;
			$in_ar['oprtr']=$oprtr;
			$query->update("pc_detect", $in_ar, $pc_info_ar[0]['sl']);
			
			//--Update Cookie
			setcookie("pcSl", $pc_info_ar[0]['sl'], ($time+3600*24*365), '/', 'nufa-ad.com', false);
			if($pc_info_ar[0]['oprtr'])
			setcookie("oprtr_val", $pc_info_ar[0]['oprtr'], ($time+3600*24*365), '/', 'nufa-ad.com', false);
		}
		//--Record ON DB
		else{
			$in_ar['browser']=$browser;
			$in_ar['os']=$os;
			$in_ar['country']=$country;
			$in_ar['city']=$city;
			$in_ar['region']=$region;
			
			if($query->insart_one("pc_detect", $in_ar)){
				$pc_info_ar=$query->byKey("SELECT * FROM `pc_detect` WHERE (`os`='$os' AND `ip`='$ip') OR (`oprtr`='$oprtr' AND `oprtr`!=0) OR (`sl`='$pcSl') ORDER BY `pc_detect`.`sl` DESC LIMIT 1");
			
				//--Update Cookie
				setcookie("pcSl", $pc_info_ar[0]['sl'], ($time+3600*24*365), '/', 'nufa-ad.com', false);
				if($pc_info_ar[0]['oprtr'])
				setcookie("oprtr_val", $pc_info_ar[0]['oprtr'], ($time+3600*24*365), '/', 'nufa-ad.com', false);
			}
		}
	}
	function pcUpdate(){
		global $convart;
		global $query;
		
		$time=TIME;
		$ip=USER_IP;
		
		$os=$this->os();
		$browser=$this->browser();
		
		$pcSl=$_COOKIE['pcSl'];
		$oprtr=$_COOKIE['oprtrSl'];
		
		//--Update DB
		$in_ar['oprtr']=$oprtr;
		$query->update("pc_detect", $in_ar, $pcSl);
		
		//--Cookie Update
		if($_COOKIE['oprtrSl'])
		setcookie("oprtr_val", $_COOKIE['oprtrSl'], ($time+3600*24*365), '/', 'nufa-ad.com', false);
	}
	/*function pcDestroy(){
		if()
		
		
	}*/
}
$detect = new detect;
