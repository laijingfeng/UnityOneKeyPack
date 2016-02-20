<?php
$is_wechat = false;
$is_apple = false;
$is_android = false;
$is_qq = false;

// QQ浏览器目前是可以安装包的
if (strpos($_SERVER['HTTP_USER_AGENT'], "QQ") !== false && strpos($_SERVER['HTTP_USER_AGENT'], "MQQBrowser") === false) {
    $is_qq = true;
}
if (strpos($_SERVER['HTTP_USER_AGENT'], "MicroMessenger") !== false) {
    $is_wechat = true;
}
if (strpos($_SERVER['HTTP_USER_AGENT'], "iPhone") !== false || strpos($_SERVER['HTTP_USER_AGENT'], "iPad") !== false) {
    $is_apple = true;
}
// 华为浏览器banner可能没有Android，但有Linux
if (strpos($_SERVER['HTTP_USER_AGENT'], "Android") !== false || strpos($_SERVER['HTTP_USER_AGENT'], "Linux") !== false) {
    $is_android = true;
}

if (!isset($_REQUEST['v'])) {
	if($is_apple) {
		$newipa = exec("ls -t hotblood_* | grep ipa | head -1");
	}
	else {
		$newipa = exec("ls -t hotblood_* | grep apk | head -1");
	}
    if(!empty($newipa)) {
		$name = explode("_", $newipa);
		$_REQUEST['v'] = substr($name[3], 0, -4);
	}else {
		exit;
	}
}

if(!isset($_REQUEST['v'])) {
	exit;
}

if (!$is_wechat && $is_apple) {
    header("Location: itms-services://?action=download-manifest&url=https://download_address/testing/rexue/hotblood_ios_test_".$_REQUEST['v'].".plist");
    exit;
}
if ($is_wechat == false && strpos($_SERVER['HTTP_USER_AGENT'], "Android") !== false) {
    header("Location: hotblood_android_test_".$_REQUEST['v'].".apk");
    exit;
}

$version = $_REQUEST['v'];
$arr = explode(".",$version);
$version = $arr[0]."-".$arr[1]."-".$arr[2]." ".$arr[3].":".$arr[4];

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>热血手游</title>
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
<body style="padding:0; margin: 0; background: #FFF;">

<?php
if ($is_apple == false) {
?>
<p align="center" style="margin-top:100px">
    <a href="hotblood_android_test_<?php echo $_REQUEST['v'];?>.apk" <?php echo($is_wechat ? "onClick=document.getElementById(\"share\").style.display=\"\";" : '');?>>
        <img src="icon.png" border="0">
        <br>
        <span style="font-size:14px; color:gray">点击安装格斗学院</span>
        <br>
        <span style="font-size:12px; color:gray">[版本号：<?php echo $version ?>]</span>
    </a>
</p>
<div id=share style="display:none;">
    <img width="100%" src="guide-android.jpg"  style="position:absolute;top:0;left:0;display:" onClick="document.getElementById('share').style.display='none';">
</div>
<?php
} else {
?>
<p align="center" style="margin-top:100px">
    <a href="hotblood_ios_test_<?php echo $_REQUEST['v'];?>.ipa" <?php echo($is_wechat ? "onClick=document.getElementById(\"share\").style.display=\"\";" : '');?>>
        <img src="icon.png" border="0">
        <br>
        <span style="font-size:14px; color:gray">点击安装格斗学院</span>
        <br>
        <span style="font-size:12px; color:gray">[版本号：<?php echo $version ?>]</span>
    </a>
</p>
<div id=share style="display:none;">
    <img width="100%" src="guide-ios.jpg"  style="position:absolute;top:0;left:0;display:" onClick="document.getElementById('share').style.display='none';">
</div>
<?php
}
?>

<?php
if ($is_wechat == true) {
?>
<script>
    document.getElementById('share').style.display='';
</script>
<?php
}
?>

</body>
</html>
