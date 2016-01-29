<?php
date_default_timezone_set('Asia/Shanghai');

if( PHP_OS == "Darwin" ){
	$IS_IOS = true;
}
else{
	$IS_IOS = false;	
}

if($IS_IOS == true){
	$PAGE_TITLE = "热血手游IOS一键打包平台";
}else{
	$PAGE_TITLE = "热血手游WebPlayer一键打包平台";
}

//---------------par---------------
$user_name = "";
$delete_old_assets = "F";
$build_assets = "F";
$build_table_and_msg = "F";
$build_client = "F";
$upload_family = "F";
$upload_ftp = "F";
//---------------par---------------

$is_build = false;
$can_build = true;
$is_write = false;

$msg_his_arr = array();
$msg_his = file("./msg_his.txt");

$msg_build = file("./msg_build.txt");

$msg_res = file("./msg_res.txt");
$msg_res_arr = array();
if(!empty($msg_res)){
	$msg_res_arr = explode("=", trim($msg_res[0]));
}

if(!empty($msg_his)){	
	$start_time = array();
	foreach($msg_his as $key){
		$key = trim($key);
		if(empty($key)){
			continue;
		}
		$key_arr = explode("=", $key);
		$msg_his_arr[] = $key_arr;
		$start_time[] = $key_arr[6];
	}
	array_multisort($start_time, SORT_DESC, $msg_his_arr);
	
	if(!empty($msg_res_arr) && $msg_his_arr[0][5] != $msg_res_arr[0]){
		$msg_his_arr[0][5] = $msg_res_arr[0];
		$msg_his_arr[0][7] = $msg_res_arr[1];
		$is_write = true;
	}
	
	if($msg_his_arr[0][5] == "R"){//第一个运行中
		$is_build = true;
	}
}

//---------------handle par---------------

if(isset($_REQUEST['user_name'])){
	$user_name = $_REQUEST['user_name'];
	if(empty($user_name)){
		$can_build = false;
	}
}
else{
	$can_build = false;
}

if(isset($_REQUEST['delete_old_assets'])){
	$delete_old_assets = $_REQUEST['delete_old_assets'];
}else{
	if($IS_IOS == false){
		$can_build = false;	
	}
}

if(isset($_REQUEST['build_assets'])){
	$build_assets = $_REQUEST['build_assets'];
}else{
	if($IS_IOS == false){
		$can_build = false;	
	}
}

if(isset($_REQUEST['build_table_and_msg'])){
	$build_table_and_msg = $_REQUEST['build_table_and_msg'];
}else{
	if($IS_IOS == false){
		$can_build = false;	
	}
}

if(isset($_REQUEST['build_client'])){
	$build_client = $_REQUEST['build_client'];
}else{
	if($IS_IOS == false){
		$can_build = false;	
	}
}

if(isset($_REQUEST['upload_family'])){
	$upload_family = $_REQUEST['upload_family'];
}else{
	if($IS_IOS == true){
		$can_build = false;	
	}
}

if(isset($_REQUEST['upload_ftp'])){
	$upload_ftp = $_REQUEST['upload_ftp'];
}else{
	if($IS_IOS == true){
		$can_build = false;	
	}
}

if($delete_old_assets == "T"){
	$build_assets = "T";
	$build_table_and_msg = "T";
}

//---------------handle par---------------

if($can_build == true && $is_build == false){
	
	$tmp1 = array($user_name, $delete_old_assets, $build_assets, $build_table_and_msg, $build_client, "R", date("Y-m-d H:i:s", time()), "-", $upload_family, $upload_ftp);
	$msg_his_arr[] = $tmp1;
	$start_time = array();
	foreach($msg_his_arr as $key_arr){
		$start_time[] = $key_arr[6];
	}
	array_multisort($start_time, SORT_DESC, $msg_his_arr);
	
	$_REQUEST['user_name'] = "";
	$user_name = "";
	
	unset($msg_build);
	
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
	
	$myfile = fopen("./msg_build.txt", "w") or die("Unable to open file!");
	fwrite($myfile, "");
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
	
	if($IS_IOS == true) {
		$res = popen("./run.sh ".$upload_family." ".$upload_ftp, 'r');
		if($res != false){
			pclose($res);
		}else{
			echo "Error";
		}
	}else{
		pclose(popen("run.bat ".$delete_old_assets." ".$build_assets." ".$build_table_and_msg." ".$build_client, 'r'));
	}
	
	$is_build = true;
	
	header("Location: ./index.php");//before header can not have output...echo
	exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>热血手游一键打包平台</title>
<meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta http-equiv="Access-Control-Allow-Origin" content="*">
<meta name="full-screen" content="true" />
<meta name="screen-orientation" content="portrait" />
<meta name="x5-fullscreen" content="true" />
<meta name="360-fullscreen" content="true" />
<style>
    body, div {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        -khtml-user-select: none;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    }
    a{outline:none;text-decoration:none;}
</style>
</head>
<body style="padding:1%; margin: 0; background: #FFF;" >

<h1>欢迎来到<?php echo $PAGE_TITLE; ?></h1>
<?php if($IS_IOS == false){ ?>
<a href="http://10.0.128.3/webclient/auto_web/WebPlayer/WebPlayer.html" target="_blank">打开Web版</a>
<br/><br/>
<a href="http://10.0.128.3/dev/devtools/dumptable?client=7&server=5&label=rxjb" target="_blank">服务器打表</a>
<?php } ?>
<h2>新建打包</h2>
<form action="index.php" method="get">
	<table border="1">
		<tr>
			<td align="center">
				姓名
			</td>
			<td>
				<input name="user_name" value="<?php echo $user_name;?>"/>
			</td>
		</tr>
		<?php if($IS_IOS == false){ ?>
		<tr>
			<td align="center">
				是否删除旧资源<br/><p style="color:green">(选中时将默认要打资源表格协议)</p>
			</td>
			<td>
				<input name="delete_old_assets" type="radio" value="T" <?php if($delete_old_assets == "T") echo "checked='checked'";?>>是</input>
				&nbsp;
				<input name="delete_old_assets" type="radio" value="F" <?php if($delete_old_assets == "F") echo "checked='checked'";?>>否</input>
			</td>
		</tr>
		<tr>
			<td align="center">
				是否打资源
			</td>
			<td>
				<input name="build_assets" type="radio" value="T" <?php if($build_assets == "T") echo "checked='checked'";?>>是</input>
				&nbsp;
				<input name="build_assets" type="radio" value="F" <?php if($build_assets == "F") echo "checked='checked'";?>>否</input>
			</td>
		</tr>
		<tr>
			<td align="center">
				是否打表格协议
			</td>
			<td>
				<input name="build_table_and_msg" type="radio" value="T" <?php if($build_table_and_msg == "T") echo "checked='checked'";?>>是</input>
				&nbsp;
				<input name="build_table_and_msg" type="radio" value="F" <?php if($build_table_and_msg == "F") echo "checked='checked'";?>>否</input>
			</td>
		</tr>
		<tr>
			<td align="center">
				是否打客户端
			</td>
			<td>
				<input name="build_client" type="radio" value="T" <?php if($build_client == "T") echo "checked='checked'";?>>是</input>
				&nbsp;
				<input name="build_client" type="radio" value="F" <?php if($build_client == "F") echo "checked='checked'";?>>否</input>
			</td>
		</tr>
		<?php } ?>
		<?php if($IS_IOS == true){ ?>
		<tr>
			<td align="center">
				是否上传family
			</td>
			<td>
				<input name="upload_family" type="radio" value="T" <?php if($upload_family == "T") echo "checked='checked'";?>>是</input>
				&nbsp;
				<input name="upload_family" type="radio" value="F" <?php if($upload_family == "F") echo "checked='checked'";?>>否</input>
			</td>
		</tr>
		<tr>
			<td align="center">
				是否上传ftp
			</td>
			<td>
				<input name="upload_ftp" type="radio" value="T" <?php if($upload_ftp == "T") echo "checked='checked'";?>>是</input>
				&nbsp;
				<input name="upload_ftp" type="radio" value="F" <?php if($upload_ftp == "F") echo "checked='checked'";?>>否</input>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="2" align="center">
				<?php if($is_build == false){?>
					<input type="submit" value="开始打包"/>
				<?php }else{?>
					<p style="color:green">正在打包中...</p>
				<?php }?>
			</td>
		</tr>
	</table>
</form>
<h2>打包输出</h2>
<?php
if(!empty($msg_build)){
	foreach($msg_build as $key){
		echo $key."<br/>";
	}
}
?>
<h2>历史信息</h2>
<?php if(!empty($msg_his_arr)) {?>
<table border="1">
	<thead align="center">
		<tr>
			<th>姓名</th>
			
			<?php if($IS_IOS == false){ ?>
			<th>是否删除旧资源</th>
			<th>是否打资源</th>
			<th>是否打表格协议</th>
			<th>是否打客户端</th>
			<?php } ?>
			
			<?php if($IS_IOS == true){ ?>
			<th>是否上传family</th>
			<th>是否上传ftp</th>
			<?php } ?>
			
			<th>结果</th>
			<th>开始时间</th>
			<th>结束时间</th>
		</tr>
	</thead>
	<?php
	foreach($msg_his_arr as $key_arr){
		if(empty($key_arr)){
			continue;
		}
	?>	
		<tr align="center">
			<td><?php echo $key_arr[0] ?></td>
			
			<?php if($IS_IOS == false){ ?>
			<td><?php echo $key_arr[1]=="T"?"是":"否" ?></td>
			<td><?php echo $key_arr[2]=="T"?"是":"否" ?></td>
			<td><?php echo $key_arr[3]=="T"?"是":"否" ?></td>
			<td><?php echo $key_arr[4]=="T"?"是":"否" ?></td>
			<?php } ?>
			
			<?php if($IS_IOS == true){ ?>
			<td><?php echo $key_arr[8]=="T"?"是":"否" ?></td>
			<td><?php echo $key_arr[9]=="T"?"是":"否" ?></td>
			<?php } ?>
			
			<td>
				<p style=<?php 
					if($key_arr[5]=="T"){
						echo "color:green";
					}
					else if($key_arr[5]=="F"){
						echo "color:red";
					}
					else {
						echo "color:blue";
					}
				?>>
				<?php
					if($key_arr[5]=="T"){
						echo "成功";
					}
					else if($key_arr[5]=="F"){
						echo "失败";
					}
					else {
						echo "进行中";
					}
				?>
				</p>
			</td>
			<td><?php echo $key_arr[6] ?></td>
			<td><?php echo $key_arr[7] ?></td>
		</tr>
	<?php }?>
</table>
<?php }else{?>
<p>无</p>
<?php } ?>
<?php
if(!empty($msg_res_arr) && $is_write == true){
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
}
?>

<script language="JavaScript"> 
function re_fresh() { 
	window.location.reload();
} 
<?php if($is_build){ ?>
setTimeout('re_fresh()',2000); //指定2秒刷新一次 
<?php } ?>
</script>

</body>
</html>

