@echo off
set SCRIPT_PATH="E:\auto_build\workspace\BuildWebPlayer\trunk\client\game\ClientLocalizeRes\Auto\web_auto_build.bat"
call %SCRIPT_PATH%

BuildAssets
是否打资源（提醒：打资源比较慢）

BuildTableAndMsg
是否打表格和协议

BuildClient
是否打版本

DeleteOldAssets
是否删除旧资源(该选项要勾选了BuildAssets和BuildTableAndMsg才能生效)

<div style='color:red'>使用说明：</div>
点击【发布Web版】右边的小三角，选择【Build with parameters】，然后勾选需要的参数，执行命令。<br/>
<br/>
<a href="http://10.0.128.3/webclient/auto_web/WebPlayer/WebPlayer.html" target="_blank">访问Web版(右键，在新标签中打开)</a><br/>
<a href="http://10.0.128.3/dev/devtools/dumptable" target="_blank">打服务器表(右键，在新标签中打开)</a><br/>
