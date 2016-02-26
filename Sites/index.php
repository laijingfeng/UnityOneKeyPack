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

$pack_par = array();
$pack_par = file("./pack_par");
$pack_par_arr = array();
if(!empty($pack_par)){

}

//---------------par---------------
$_jy_user_name = "";
$pack_par = array();
$pack_par = file("./pack_par");
$pack_par_arr = array();

if(!empty($pack_par)){
	foreach($pack_par as $key){
		$key = trim($key);
		if(empty($key)){
			continue;
		}
		$key_arr = explode("=", $key);
		$pack_par_arr[] = $key_arr;
	}
}
//---------------par---------------

$is_building = false;
$should_write = false;

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
		$start_time[] = $key_arr[1];
	}
	array_multisort($start_time, SORT_DESC, $msg_his_arr);
	
	if(!empty($msg_res_arr) && $msg_his_arr[0][3] != $msg_res_arr[0]){
		$msg_his_arr[0][2] = $msg_res_arr[1];
		$msg_his_arr[0][3] = $msg_res_arr[0];
		$should_write = true;
	}
	
	if($msg_his_arr[0][3] == "R"){//第一个运行中
		$is_building = true;
	}
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
<form action="gearman_client.php" method="post">
	<table border="1">
		<tr>
			<td align="center">
				姓名
			</td>
			<td>
				<input name="_jy_user_name" value="<?php echo $_jy_user_name;?>"/>
			</td>
		</tr>
		<?php 
		foreach($pack_par_arr as $key_arr){
			if(empty($key_arr)){
				continue;
			}
		?>
		<tr>
			<td align="center">
				<?php echo $key_arr[0]; ?>
			</td>
			<td>
				<input name=<?php echo $key_arr[1]; ?> type="radio" value="T" <?php if($key_arr[2] == "T") echo "checked='checked'";?>>是</input>
				&nbsp;
				<input name=<?php echo $key_arr[1]; ?> type="radio" value="F" <?php if($key_arr[2] == "F") echo "checked='checked'";?>>否</input>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="2" align="center">
				<?php if($is_building == false){?>
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
			<th>开始时间</th>
			<th>结束时间</th>
			<th>打包结果</th>
			<?php 
			foreach($pack_par_arr as $key_arr){
				if(empty($key_arr)){
					continue;
				}
			?>
			<th><?php echo $key_arr[0]; ?></th>
			<?php } ?>
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
			<td><?php echo $key_arr[1] ?></td>
			<td><?php echo $key_arr[2] ?></td>
			<td>
				<p style=<?php 
					if($key_arr[3]=="T"){
						echo "color:green";
					}
					else if($key_arr[3]=="F"){
						echo "color:red";
					}
					else {
						echo "color:blue";
					}
				?>>
				<?php
					if($key_arr[3]=="T"){
						echo "成功";
					}
					else if($key_arr[3]=="F"){
						echo "失败";
					}
					else {
						echo "进行中";
					}
				?>
				</p>
			</td>
			<?php
			for($i=4; $i<count($key_arr); $i++){
			?>
			<td><?php echo $key_arr[$i]=="T"?"是":"否" ?></td>
			<?php } ?>
		</tr>
	<?php }?>
</table>
<?php }else{?>
<p>无</p>
<?php } ?>
<?php
if(!empty($msg_res_arr) && $should_write == true){
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
<?php if($is_building){ ?>
setTimeout('re_fresh()',5000); //指定5秒刷新一次 
<?php } ?>
</script>

</body>
</html>

