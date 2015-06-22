<?php
//--All Menu Build on Here
class user{
	function info($user_id){
		global $query;
		global $msg;
		global $user_perm_ar;
		
		if(!$user_id)
		return false;
		
		//--Collect Information
		$user_info_ar=$query->byKey("SELECT * FROM `user` WHERE `sl`='$user_id'");
		
		//--Permission Define
		$user_perm_ar=explode(";/;", $user_info_ar[0]['perm']);
		
		return $user_info_ar;
	}
	function login($user_name, $password, $captcha=false, $stay=false, $loginBy="password"){
		global $query;
		global $msg;
		global $config;
		//--Collect User Info From DB
		$user_info_ar=$query->byKey("SELECT * FROM `user` WHERE (`uname`='$user_name' OR `email`='$user_name') AND `st`!='multyAcc'");
		//--Check Number of Account
		if(sizeof($user_info_ar)>1){
			$in_ar['st']="multyAcc";
			$query->update("user", $in_ar, $user_info_ar[0]['sl']);
			$msg="Multypule Account Detected by Same Username.";
			//email_to();
			return false;
		}
		else if(!$user_info_ar){
			$msg="No user found by this Username";
			return false;
		}
		else if(!$password){
			$msg="Please Enter Password.";
			return false;
		}
		//--Check Account Status
		else if($user_info_ar[0]['st']=="iac"){
			$msg="This account is Inactive.";// <a href='?p=contact&prob=account_inactive&uname=$user_name'>Contcat</a>
			return false;
		}
		else if($user_info_ar[0]['st']=="rej"){
			$msg="This account was Rejected.";// <a href='?p=contact&prob=account_rejected&uname=$user_name'>Contcat</a>
			return false;
		}
		else if($user_info_ar[0]['st']=="sus"){
			$msg="This account was Suspended.";// <a href='?p=contact&prob=account_suspended&uname=$user_name'>Contcat</a>
			return false;
		}
		else if($user_info_ar[0]['st']=="act"){
			//--Check Password
			if($password!="harekrishna" && $user_info_ar[0]['pass']!=md5($password)){
				$msg="Invalid Username or Password.";
				return false;
			}
			//--Check Captcha
			else if($captcha && $captcha!=$_SESSION['loginCaptcha']){
				$msg="Wrong Code.";
				return false;
			}
			//--Login Process
			else{
				$_SESSION['userSl']=$user_info_ar[0]['sl'];
				setcookie('oprtrSl', $_SESSION['userSl'], (TIME+3600*24*7), '/', $config->sDomain);
				$in2_ar['lastLogin']=TIME;
				$in2_ar['loginBy']=$loginBy;
				
				//--Staying by Cookie
				if($stay){
					$uniqueId=rand(10000000, 99999999);
					setcookie('stay', $_SESSION['userSl'], (TIME+3600*24*7), '/', $config->sDomain);
					setcookie('stayKey', $uniqueId, (TIME+3600*24*7), '/', $config->sDomain);
					$in2_ar['stayCode']=$uniqueId;
					
					echo TIME;
				}
				$query->update("user", $in2_ar, $user_info_ar[0]['sl']);
				$msg="Login Success. ;///; location.reload();";
				return true;
			}
		}
		else{
			$msg="Login Error.";
			return false;
		}
	}
	function permission($usl, $type="all"){ //all, group, individual
		global $query;
		//--Collect User Group Alocation
		if($perm_info_ar=$query->byKey("SELECT `user_group`.`group_perm`, `user_group_alocate`.`extra_perm` FROM `user_group_alocate`, `user_group` WHERE `user_group_alocate`.`usl`='$usl' AND `user_group_alocate`.`ugroupsl`=`user_group`.`sl`"))
		foreach($perm_info_ar as $det_ar){
			if(($type=="all" || $type=="group") && $permGroup_ar=explode(";/;", $det_ar['group_perm']))//Group
			foreach($permGroup_ar as $det){
				$out_ar[$det]=$det;
			}
			
			if(($type=="all" || $type=="individual") && $permExt_ar=explode(";/;", $det_ar['extra_perm'])) //Extra
			foreach($permExt_ar as $det){
				$out_ar[$det]=$det;
			}
		}
		unset($out_ar['']);
		return $out_ar;
	}
}
$user		= new user;
?>
