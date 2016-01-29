@echo off

set DeleteOldAssets=F
set BuildAssets=F
set BuildTableAndMsg=T
set BuildClient=F

set LOG_PATH="D:\Program Files\phpStudy\WWW\autobuild\msg_build.txt"

set LOG_PATH2=D:\Program Files\phpStudy\WWW\autobuild\msg_build.txt

echo SSS >> lai.txt

set SCRIPT_PATH="E:\HotSvn\HotCode\trunk\client_web\game\ClientLocalizeRes\Auto\web_auto_build_php.bat"

start E:\HotSvn\HotCode\trunk\client_web\game\ClientLocalizeRes\Auto\test1.bat T F

::call %SCRIPT_PATH% %DeleteOldAssets% %BuildAssets% %BuildTableAndMsg% %BuildClient%

echo %LOG_PATH% >> lai.txt

echo %LOG_PATH:"=% >> lai.txt

echo EEE >> lai.txt

exit

