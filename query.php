<?php
class query{
	function str($str){
		global $l;
		return mysql_query($l=$str);
	}
	function byKey($query_str, $key=""){
		global $msg, $l;
		
		if($key=="")
		$key="auto";
		
		if(!$query=mysql_query($l=$query_str)){
			$msg=mysql_error()." on query->byKey";
			return false;
		}
		while($row_ar=mysql_fetch_assoc($query)){
			if($key=="auto")
			$out_ar[]=$row_ar;
			else
			$out_ar[$row_ar[$key]]=$row_ar;
		}
		
		if($out_ar){
			$msg="OK";
			return $out_ar;
		}
		else{
			$msg="No Row Collectd From DB on query->byKey ($l)";
			return false;
		}
	}
	function byKeySet($query_str, $key="sl"){
		$all_ar=$this->byKey($query_str);
		return $this->array2set($all_ar, $key);
	}
	function update($table, $post_ar, $sl, $history=true){
		global $msg, $l;
		
		if(!$table){
			$msg="No Table Name on query->update";
			return false;
		}
		else if(!is_array($post_ar)){
			$msg="Post Error on query->update";
			return false;
		}
		else if(!$row_info_ar=$this->byKey("SELECT * FROM `$table` WHERE `sl`='$sl'")){
			$msg="Nothing To Update on query->update";
			return false;
		}
		else{
			//--Creating Queryes
			foreach($post_ar as $key => $val){
				$q1_ar[]="`$key`='".mysql_real_escape_string($val)."'";
				
				if($history==true){
					if($val!=$row_info_ar[0][$key])
					$hist_ar[$key]=$row_info_ar[0][$key];
				}
			}
			//--Query Action
			if(mysql_query($l2="UPDATE `$table` SET ".implode(", ", $q1_ar)." WHERE `sl`='$sl';")){
				if($history==true && $hist_ar){
					foreach($hist_ar as $col => $oldVal){
						$values_all_ar[$col]['tbl']=$table;
						$values_all_ar[$col]['col']=$col;
						$values_all_ar[$col]['tsl']=$sl;
						$values_all_ar[$col]['oldVal']=$oldVal;
					}
					$this->insertAll("history", $values_all_ar);
				}
				$l=$l2;
				$msg="OK";
				return true;
			}
			else{
				$msg="MySql Query Error on query->update";
				return false;
			}
		}
	}
	function updateInc($table, $fields_ar, $sl){
		global $msg, $l;
		
		if(!$table){
			$msg="No Table Name on query->updateInc";
			return false;
		}
		else if(!is_array($fields_ar)){
			$msg="Post Error on query->updateInc";
			return false;
		}
		else{
			//--Creating Queryes
			foreach($fields_ar as $key => $val){
				$q1_ar[]="`$key`=$key + ".(mysql_real_escape_string($val)*1)."";
			}
			//--Query Action
			if(mysql_query($l="UPDATE `$table` SET ".implode(", ", $q1_ar)." WHERE `sl`='$sl';")){
				$msg="OK";
				return true;
			}
			else{
				$msg="MySql Query Error on query->update";
				return false;
			}
		}
	}
	function insertOne($table, $post_ar){
		global $msg, $l;
		
		if(!$table){
			$msg="No Table Name on query->insertOne";
			return false;
		}
		else if(!is_array($post_ar)){
			$msg="Post Error on query->insertOne";
			return false;
		}
		else{
			//--Default
			if(!$post_ar['ip']) 	$post_ar['ip']=USER_IP;
			if(!$post_ar['time']) 	$post_ar['time']=TIME;
			if(!$post_ar['oprtr']) 	$post_ar['oprtr']=$_SESSION['userSl'];
			
			//--Creating Queryes
			foreach($post_ar as $key => $val){
				$q1_ar[]="`$key`";
				$q2_ar[]="'".mysql_real_escape_string($val)."'";
			}
			//--Query Action
			if(mysql_query($l="INSERT INTO `$table` (".implode(", ", $q1_ar).") VALUES (".implode(", ", $q2_ar).");")){
				$msg="OK";
				return true;
			}
			else{
				$msg="MySql Query Error on query->insertOne";
				return false;
			}
		}
	}
	function insertAll($table, $post_all_ar){
		global $msg, $l;
		
		if(!$table){
			$msg="No Table Name on query->insertAll";
			return false;
		}
		else if(!is_array($post_all_ar)){
			$msg="Post Error on query->insertAll";
			return false;
		}
		else{
			
			//--Creating Queryes
			foreach($post_all_ar as $post_ar){
				//--Default
				if(!$post_ar['ip']) 	$post_ar['ip']=USER_IP;
				if(!$post_ar['time']) 	$post_ar['time']=TIME;
				if(!$post_ar['oprtr']) 	$post_ar['oprtr']=$_SESSION['userSl'];
				ksort($post_ar);
			
				foreach($post_ar as $key => $val){
					$q1_ar[$key]="`$key`";
					$q2_ar[]="'".mysql_real_escape_string($val)."'";
				}
				
				$qVal_ar[]="(".implode(", ", $q2_ar).")";
				unset($q2_ar, $post_ar);
			}
			//--Query Action
			if(mysql_query($l="INSERT INTO `$table` (".implode(", ", $q1_ar).") VALUES ".implode(", ", $qVal_ar).";")){
				$msg="OK";
				return true;
			}
			else{
				$msg="MySql Query Error on query->insertAll";
				return false;
			}
		}
	}
	function orStr($ar, $key, $emptyReturns=0){
		if(!$ar)
		return $emptyReturns;
		else
		return "(`$key`='".implode("' OR `$key`='", $ar)."')";
	}
	function orStrInv($ar, $key, $emptyReturns=0){
		if(!$ar)
		return $emptyReturns;
		else
		return "(`$key`!='".implode("' AND `$key`!='", $ar)."')";
	}
	function array2set($array2d, $key){
		global $msg;
		if(!is_array($array2d)){
			$msg="Invalide \$array2d on \$query->array2set";
			return false;
		}
		else if(!$key){
			$msg="Invalide \$key on \$query->array2set";
			return false;
		}
		
		foreach($array2d as $ar){
			$out_ar[$ar[$key]]=$ar[$key];
		}
		return $out_ar;
	}
	function recentEntry($tbl){
		global $msg, $l;
		$tbl_ar=explode("`.`", $tbl);
		$row_info_ar=$this->byKey($l="SELECT * FROM `$tbl` WHERE `ip`='".USER_IP."' AND `time`='".TIME."' AND `oprtr`='$_SESSION[userSl]' ORDER BY `".($tbl_ar[1]?$tbl_ar[1]:$tbl_ar[0])."`.`sl` DESC LIMIT 1");
		if(mysql_error()==true){
			$msg="mySql Error on \$query->recentEntry";
			return false;
		}
		else if(!$row_info_ar){
			$msg="No Row Found on \$query->recentEntry";
			return false;
		}
		else{
			$msg="OK";
			return $row_info_ar[0];
		}
	}
	function saveLog($tbl, $message){
		$in_ar['tbl']=$tbl;
		$in_ar['col']="saveLog";
		$in_ar['tsl']=0;
		$in_ar['oldVal']=$message;
		$this->insertOne(DB_USER."`.`history", $in_ar);
	}
	function listAllGlobal($type_hash){  //  #education#abc#def
		global $global_listing_all_ar, $userInfo_ar, $l;
		$global_listing_all_ar=false;
		
		$row_all_ar=$this->byKey($l="SELECT * FROM `".DB_USER."`.`list` WHERE (`orgSl`='10000000' OR `orgSl`='$userInfo_ar[orgSl]') AND (`type`='".str_replace("#", "' OR `type`='", $type_hash)."') AND `st`='act' ORDER BY `list`.`priority` ASC");
		
		if($row_all_ar)
		foreach($row_all_ar as $det_ar){
			$global_listing_all_ar[$det_ar['type']][$det_ar['keyTxt']]=$det_ar;
		}
		
		if($row_all_ar)
		return true;
		
		return false;
	}
	function listAr($type){
		global $global_listing_all_ar, $userInfo_ar, $l;
		
		if(!$global_listing_all_ar[$type])
		$global_listing_all_ar[$type]=$this->byKey($l="SELECT * FROM `".DB_USER."`.`list` WHERE (`orgSl`='10000000' OR `orgSl`='$userInfo_ar[orgSl]') AND `type`='$type' AND `st`='act' ORDER BY `list`.`priority` ASC", "keyTxt");
		return $global_listing_all_ar[$type];
	}
	function listToFull($type, $key, $col='val'){
		global $global_listing_all_ar, $userInfo_ar, $l;
		
		if(!$global_listing_all_ar[$type])
		$global_listing_all_ar[$type]=$this->byKey($l="SELECT * FROM `".DB_USER."`.`list` WHERE (`orgSl`='10000000' OR `orgSl`='$userInfo_ar[orgSl]') AND `type`='$type' AND `st`='act' ORDER BY `list`.`priority` ASC", "keyTxt");
		
		$global_listing_all_ar[$type]['$key'][$col];
	}
}
$query		= new query;
?>
