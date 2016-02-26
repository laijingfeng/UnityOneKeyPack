<?php

date_default_timezone_set('Asia/Shanghai');
echo "test<br/>";
$user_name = "";
$par_arr = array();
foreach($_REQUEST as $key => $val){
	if(strpos($key,"jy_") == "1"){
		$par_arr[$key] = $val;
		if($key == "_jy_user_name"){
			$user_name = $val;
		}
	}
}

if(empty($user_name)){
	echo "name can not be empty<br/>";
	exit();
}

$msg_res = file("./msg_res.txt");
$msg_res_arr = array();
if(!empty($msg_res)){
	$msg_res_arr = explode("=", trim($msg_res[0]));
	if($msg_res_arr[0] == "R"){
		echo "last task still running<br/>";
		exit();
	}
}

$build_par = "";

$new_time = date("Y-m-d H:i:s", time());
$new_build = array($user_name, $new_time, "-", "R");
foreach($par_arr as $key => $val){
	if(!empty($build_par)){
		$build_par = $build_par."#";
	}
	$build_par = $build_par.$key."-".$val;
	if($key != "_jy_user_name"){
		$new_build[] = $val;
	}
}

if(empty($build_par)){
	echo "par is empty<br/>";
	exit();
}

$msg_his_arr = array();
$msg_his = file("./msg_his.txt");
$start_time = array();

if(!empty($msg_his)){	
	foreach($msg_his as $key){
		$key = trim($key);
		if(empty($key)){
			continue;
		}
		$key_arr = explode("=", $key);
		$msg_his_arr[] = $key_arr;
		$start_time[] = $key_arr[1];
	}
}

$start_time[] = $new_time;
$msg_his_arr[] = $new_build;

array_multisort($start_time, SORT_DESC, $msg_his_arr);
	
if(empty($msg_res_arr)){
	$msg_res_arr = array("R","-");
}
else {
	$msg_res_arr[0] = "R";
	$msg_res_arr[1] = "-";
}
	
$idx = 0;
$myfile = fopen("./msg_his.txt", "w") or die("Unable to open file!");
foreach($msg_his_arr as $key_arr){
	$tmp_tex = "";
	foreach($key_arr as $key){
		if(empty($tmp_tex)){
			$tmp_tex = $key;
		}
		else{
			$tmp_tex = $tmp_tex."=".$key;	
		}
	}
	fwrite($myfile, $tmp_tex."\r\n");
	$idx ++;
	if($idx >= 3){
		break;
	}
}
fclose($myfile);

$myfile = fopen("./msg_res.txt", "w") or die("Unable to open file!");
$tmp_tex = "";
foreach($msg_res_arr as $key){
	if(empty($tmp_tex)){
		$tmp_tex = $key;
	}
	else{
		$tmp_tex = $tmp_tex."=".$key;	
	}
}
fwrite($myfile, $tmp_tex."\r\n");
fclose($myfile);

$gmclient = new GearmanClient();
$gmclient->addServer("10.0.128.219");
$gmclient->doBackground("hotblood_pack_task", $build_par);

header("Location: ./index.php");

?>