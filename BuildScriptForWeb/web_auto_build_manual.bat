@echo off

echo ==热血手游Web版一键打包==

set DeleteOldAssets=false
set BuildAssets=false
set BuildTableAndMsg=false
set BuildAssets=false

set /p ask=确认Unity/TortoiseSVN设置了环境变量并关闭Unity?(y/n)
if /i %ask% NEQ y (
	goto FINISH_FLAG
)

set /p ask=是否删除旧资源?(y/n)
if /i %ask% NEQ y (
	set DeleteOldAssets=false
) else (
	set DeleteOldAssets=true
	set BuildAssets=true
	set BuildTableAndMsg=true
	echo 附加将打资源/表格/协议
)
	
if %DeleteOldAssets% EQU false (
	set /p ask=是否打资源?(y/n)
	if /i %ask% NEQ y (
		set BuildAssets=false
	) else (
		set BuildAssets=true
	)

	set /p ask=是否打表和协议?(y/n)
	if /i %ask% NEQ y (
		set BuildTableAndMsg=false
	) else (
		set BuildTableAndMsg=true
	)		
)

set /p ask=是否打客户端?(y/n)
if /i %ask% NEQ y (
	set BuildAssets=false
) else (
	set BuildAssets=true
)

set SCRIPT_PATH=%cd%
set PROJECT_PATH=%SCRIPT_PATH%\..\..\client
set TOOLS_PATH=%SCRIPT_PATH%\..\..\..\tools
set TABLE_PATH=%SCRIPT_PATH%\..\..\..\..\common
set GAME_PATH="\\10.0.128.3\webclient\auto_web\WebPlayer"

echo -删除旧资源=%DeleteOldAssets%-
echo -打资源=%BuildAssets%-
echo -打表和协议=%BuildTableAndMsg%-
echo -打客户端=%BuildAssets%-

set /p ask=确认以上配置?(y/n)
if /i %ask% NEQ y (
	goto FINISH_FLAG
)

echo "开始打包"

if %DeleteOldAssets% EQU true (
    if %BuildAssets% EQU true (
        if %BuildTableAndMsg% EQU true (
            echo 开始删除旧资源
            rd /q /s %GAME_PATH%\AssetBundles
            echo 完成删除旧资源
        )
    )
)

if %BuildAssets% EQU true (
	echo 开始更新资源
	start /wait TortoiseProc.exe /command:update /path:%TOOLS_PATH% /closeonend:1
	echo 完成更新资源
    echo 开始打资源
    start /wait Unity.exe -projectPath %TOOLS_PATH% -executeMethod AutoBuild.Build -quit -batchmode
    echo 完成打资源
)

if %BuildTableAndMsg% EQU true (
	echo 开始更新表格/协议
	start /wait TortoiseProc.exe /command:update /path:%TABLE_PATH% /closeonend:1
	echo 完成更新表格/协议
)

if %BuildClient% EQU true (
	echo 开始更新客户端
	start /wait TortoiseProc.exe /command:update /path:%PROJECT_PATH% /closeonend:1
	echo 完成更新客户端
)

echo 开始打表格/协议/版本
::start /wait Unity.exe -projectPath %PROJECT_PATH% -executeMethod AutoBuild.BuildWeb build_out_path-%GAME_PATH%-%BuildAssets%-%BuildTableAndMsg%-%BuildClient% -quit -batchmode
echo 完成打表格/协议/版本

:FINISH_FLAG

echo ==结束==

pause
