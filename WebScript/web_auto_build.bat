@echo off

set SCRIPT_PATH=%cd%
set PROJECT_PATH=%SCRIPT_PATH%\..\..\client
set TOOLS_PATH=%SCRIPT_PATH%\..\..\..\tools
set TABLE_PATH=%SCRIPT_PATH%\..\..\..\..\common
set GAME_PATH="\\10.0.128.3\webclient\auto_web\WebPlayer"

if %DeleteOldAssets% EQU true (
    if %BuildAssets% EQU true (
        if %BuildTableAndMsg% EQU true (
            echo DeleteOldAssets...
            rd /q /s %GAME_PATH%\AssetBundles
            echo DeleteOldAssetsFinish
        )
    )
)

if %BuildAssets% EQU true (
    echo BuildAssets...
    start /wait Unity.exe -projectPath %TOOLS_PATH% -executeMethod AutoBuild.Build -quit -batchmode
    echo BuildAssetsFinish
)

echo BuildClient...
start /wait Unity.exe -projectPath %PROJECT_PATH% -executeMethod AutoBuild.BuildWeb build_out_path-%GAME_PATH%-%BuildAssets%-%BuildTableAndMsg%-%BuildClient% -quit -batchmode
echo BuildClientFinish

echo FinishAll
