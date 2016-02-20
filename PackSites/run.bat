@echo off

set DeleteOldAssets=%1
set BuildAssets=%2
set BuildTableAndMsg=%3
set BuildClient=%4

set LOG_PATH="C:\Program Files\phpStudy\WWW\autobuild\msg_build.txt"

set SCRIPT_PATH="E:\auto_build_web\client\game\ClientLocalizeRes\Auto\web_auto_build_php.bat"

start %SCRIPT_PATH:"=% %DeleteOldAssets% %BuildAssets% %BuildTableAndMsg% %BuildClient%

exit
