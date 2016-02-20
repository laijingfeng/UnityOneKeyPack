@echo off

set DeleteOldAssets=%1
set BuildAssets=%2
set BuildTableAndMsg=%3
set BuildClient=%4

set SCRIPT_PATH="E:\auto_build_web\client\game\ClientLocalizeRes\Auto"
set LOG_PATH="C:\Program Files\phpStudy\WWW\autobuild\msg_build.txt"
set RES_PATH="C:\Program Files\phpStudy\WWW\autobuild\msg_res.txt"

set PROJECT_PATH=%SCRIPT_PATH:"=%\..\..\client
set TOOLS_PATH=%SCRIPT_PATH:"=%\..\..\..\tools\tools
set TABLE_PATH=%SCRIPT_PATH:"=%\..\..\..\..\common
set GAME_PATH="\\10.0.128.3\webclient\auto_web\WebPlayer"

echo Build Start >> %LOG_PATH%

if %DeleteOldAssets% EQU T (
    if %BuildAssets% EQU T (
        if %BuildTableAndMsg% EQU T (
            echo DeleteOldAssets Start >> %LOG_PATH%
            rd /q /s %GAME_PATH%\AssetBundles
            echo DeleteOldAssets Finish >> %LOG_PATH%
        )
    )
)

if %BuildAssets% EQU T (
	echo UpdateAssets Start >> %LOG_PATH%
	start /wait TortoiseProc.exe /command:update /path:%TOOLS_PATH% /closeonend:1
	echo UpdateAssets Finish >> %LOG_PATH%
    echo BuildAssets Start >> %LOG_PATH%
    start /wait Unity.exe -projectPath %TOOLS_PATH% -executeMethod AutoBuild.Build -quit -batchmode
    echo BuildAssets Finish >> %LOG_PATH%
)

if %BuildTableAndMsg% EQU T (
	echo UpdateTableAndMsg Start >> %LOG_PATH%
	start /wait TortoiseProc.exe /command:update /path:%TABLE_PATH% /closeonend:1
	echo UpdateTableAndMsg Finish >> %LOG_PATH%
)

if %BuildClient% EQU T (
	echo UpdateClient Start >> %LOG_PATH%
	start /wait TortoiseProc.exe /command:update /path:%PROJECT_PATH% /closeonend:1
	echo UpdateClient Finish >> %LOG_PATH%
)

echo BuildClientTableAndMsg Start >> %LOG_PATH%
start /wait Unity.exe -projectPath %PROJECT_PATH% -executeMethod AutoBuild.BuildWeb build_out_path-%GAME_PATH%-%BuildAssets%-%BuildTableAndMsg%-%BuildClient% -quit -batchmode
echo BuildClientTableAndMsg Finish >> %LOG_PATH%

:FINISH_FLAG

echo Build Finish >> %LOG_PATH%

set timeNow=%date:~0,4%-%date:~5,2%-%date:~8,2% %time:~0,2%:%time:~3,2%:%time:~6,2%
echo T=%timeNow% > %RES_PATH%

exit
