@echo off
set SCRIPT_PATH="E:\auto_build\workspace\BuildWebPlayer\trunk\client\game\ClientLocalizeRes\Auto\web_auto_build.bat"
call %SCRIPT_PATH%

BuildAssets
�Ƿ����Դ�����ѣ�����Դ�Ƚ�����

BuildTableAndMsg
�Ƿ�����Э��

BuildClient
�Ƿ��汾

DeleteOldAssets
�Ƿ�ɾ������Դ(��ѡ��Ҫ��ѡ��BuildAssets��BuildTableAndMsg������Ч)

<div style='color:red'>ʹ��˵����</div>
���������Web�桿�ұߵ�С���ǣ�ѡ��Build with parameters����Ȼ��ѡ��Ҫ�Ĳ�����ִ�����<br/>
<br/>
<a href="http://10.0.128.3/webclient/auto_web/WebPlayer/WebPlayer.html" target="_blank">����Web��(�Ҽ������±�ǩ�д�)</a><br/>
<a href="http://10.0.128.3/dev/devtools/dumptable" target="_blank">���������(�Ҽ������±�ǩ�д�)</a><br/>
