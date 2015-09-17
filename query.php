<?php
class query{
	function byKey($query_str, $key=""){
		global $msg;
		
		if($key=="")
		$key="auto";
		
		if(!$query=mysql_query($l=$query_str)){
			$msg=mysql_error()." ($l) on query->byKey";
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
	function byTableKeySl($table, $key, $sl=false){
		global $msg;
		if($sl=="first")
		$query_str="SELECT `$key` FROM `$table` ORDER BY `$table`.`sl` ASC LIMIT 1";
		else if($sl=="last")
		$query_str="SELECT `$key` FROM `$table` ORDER BY `$table`.`sl` DESC LIMIT 1";
		else if($sl=="min")
		$query_str="SELECT `$key` FROM `$table` ORDER BY `$table`.`$key` ASC LIMIT 1";
		else if($sl=="max")
		$query_str="SELECT `$key` FROM `$table` ORDER BY `$table`.`$key` DESC LIMIT 1";
		else if($sl=="rand")
		$query_str="SELECT `$key` FROM `$table` ORDER BY  RAND() LIMIT 1";
		else{
			$msg="'sl' Not Defined on query->byTableKeySl";
			return false;
		}
		
		if(!$query=mysql_query($query_str)){
			$msg=mysql_error()." on query->byTableKeySl";
			return false;
		}
		
		while($row_ar=mysql_fetch_assoc($query)){
			$msg="OK";
			return $row_ar[$key];
		}
	}
	function arrayToSql($table, $parameters, $conditions=1, $orderBy="", $limit="", $key="auto"){
		global $msg;
		
		if(!$table){
			$msg="Table Not Defined on query->arrayToSql";
			return false;
		}
		else if(!$parameters){
			$msg="Parameters Not Defined on query->arrayToSql";
			return false;
		}
		else if(!$out_all_ar=$this->byKey($l="SELECT $parameters FROM `$table` WHERE $conditions ".($orderBy?"ORDER BY $orderBy":"").($limit?"LIMIT $limit":""), $key)){
			$msg="SQL Error ($l) on query->arrayToSql";
			return false;
		}
		else{
			$msg="OK";
			return $out_all_ar;
		}
	}
	function update($table, $post_ar, $sl, $history=true){
		global $msg;
		if(!$table){
			$msg="No Table Name on query->update";
			return false;
		}
		else if(!is_array($post_ar)){
			$msg="Post Error on query->update";
			return false;
		}
		else{
			$row_info_ar=$this->byKey("SELECT * FROM `$table` WHERE `sl`='$sl'", "auto");
			//--Creating Queryes
			foreach($post_ar as $key => $val){
				$q1_ar[]="`$key`='".mysql_real_escape_string($val)."'";
				
				if($history==true){
					if($val!=$row_info_ar[0][$key])
					$hist_ar[$key]=mysql_real_escape_string($row_info_ar[0][$key]);
				}
			}
			//--Query Action
			if(mysql_query($l="UPDATE `$table` SET ".implode(", ", $q1_ar)." WHERE `sl`='$sl';")){
				if($history==true && $hist_ar){
					foreach($hist_ar as $col => $oldVal){
						$values_all_ar[$col]['tbl']=$table;
						$values_all_ar[$col]['col']=$col;
						$values_all_ar[$col]['tsl']=$sl;
						$values_all_ar[$col]['oldVal']=$oldVal;
					}
					$this->insert_all("history", $values_all_ar);
				}
				$msg="OK";
				return true;
			}
			else{
				$msg="MySql Query ($l) Error on query->update";
				return false;
			}
		}
	}
	function insert_one($table, $post_ar){
		global $msg;
		if(!$table){
			$msg="No Table Name on query->insert_one";
			return false;
		}
		else if(!is_array($post_ar)){
			$msg="Post Error on query->insert_one";
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
				$msg="MySql Query ($l) Error on query->insert_one";
				return false;
			}
		}
	}
	function insert_all($table, $post_all_ar){
		global $msg;
		
		if(!$table){
			$msg="No Table Name on query->insert_all";
			return false;
		}
		else if(!is_array($post_all_ar)){
			$msg="Post Error on query->insert_all";
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
				$msg="MySql Query ($l) Error on query->insert_one";
				return false;
			}
		}
	}
	function delete($table, $conditions){
		global $msg;
		if(!$table){
			$msg="No Table Name on query->delete";
			return false;
		}
		else if(!$conditions){
			$msg="Condition Error on query->delete";
			return false;
		}
		else if(!mysql_query("DELETE FROM `$table` WHERE $conditions ")){
			$msg="MySql Query Error on query->delete";
			return false;
		}
		else
		return true;
	}
	function row($tbl, $col, $val, $condition="="){
		$out_ar=$this->byKey($l="SELECT * FROM `$tbl` WHERE `$col` $condition '$val'");
		if(!$out_ar){
			$msg="MySql Query Error (".mysql_error().") on query->row";
			return false;
		}
		else{
			$msg="OK";
			return $out_ar;
		}
	}
	function attrTblTsl_ar($tbl, $tsl_ar, $key_ar=array()){
		if(!is_array($tsl_ar)){
			$out_st=$tsl_ar;
			$tsl_ar=array($tsl_ar);
		}
		
		//--Collect TRSL Attr
		if($attr_all_ar=$this->byKey("SELECT `tsl`, `key_word`, `val` FROM `detinfo` WHERE `tbl`='$tbl' AND (`tsl`='".implode("' OR `tsl`='", $tsl_ar)."') AND `st`='a'", "auto"))
		foreach($attr_all_ar as $det_ar){
			$out_all_ar[$det_ar['tsl']][$det_ar['key_word']]=$det_ar['val'];
		}
		
		if($out_st)
		return $out_all_ar[$out_st];
		else
		return $out_all_ar;
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
	
	/*function orStrInv($ar, $key){
		return "(`$key`!='".implode("' AND `$key`!='", $ar)."')";
	}*/
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
	function mysql($queryTxt){
		global $msg;
		if(!$queryTxt){
			$msg="Invalide \$queryTxt on \$query->mysql";
			return false;
		}
		
		foreach($array2d as $ar){
			$out_ar[$ar[$key]]=$ar[$key];
		}
		return $out_ar;
	}
	function recentEntry($tbl){
		$tbl_ar=explode("`.`", $tbl);
		$row_info_ar=$this->byKey("SELECT * FROM `$tbl` WHERE `ip`='".USER_IP."' AND `time`='".TIME."' AND `oprtr`='$_SESSION[userSl]' ORDER BY `".($tbl_ar[1]?$tbl_ar[1]:$tbl_ar[0])."`.`sl` DESC LIMIT 1");
		return $row_info_ar[0];
	}
}
$query		= new query;
?>
