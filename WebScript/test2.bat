@echo off
set SCRIPT_PATH=%cd%
set TABLE_PATH="E:\HotSvn\HotCode\trunk\common\table"
echo "hi"
echo %TABLE_PATH%
::start /wait TortoiseProc.exe /command:update /path:%TABLE_PATH% /closeonend:1
echo "hihi" > lai.txt
pause