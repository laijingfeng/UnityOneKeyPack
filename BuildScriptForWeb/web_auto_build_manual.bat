@echo off

echo ==��Ѫ����Web��һ�����==

set DeleteOldAssets=false
set BuildAssets=false
set BuildTableAndMsg=false
set BuildAssets=false

set /p ask=ȷ��Unity/TortoiseSVN�����˻����������ر�Unity?(y/n)
if /i %ask% NEQ y (
	goto FINISH_FLAG
)

set /p ask=�Ƿ�ɾ������Դ?(y/n)
if /i %ask% NEQ y (
	set DeleteOldAssets=false
) else (
	set DeleteOldAssets=true
	set BuildAssets=true
	set BuildTableAndMsg=true
	echo ���ӽ�����Դ/���/Э��
)
	
if %DeleteOldAssets% EQU false (
	set /p ask=�Ƿ����Դ?(y/n)
	if /i %ask% NEQ y (
		set BuildAssets=false
	) else (
		set BuildAssets=true
	)

	set /p ask=�Ƿ����Э��?(y/n)
	if /i %ask% NEQ y (
		set BuildTableAndMsg=false
	) else (
		set BuildTableAndMsg=true
	)		
)

set /p ask=�Ƿ��ͻ���?(y/n)
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

echo -ɾ������Դ=%DeleteOldAssets%-
echo -����Դ=%BuildAssets%-
echo -����Э��=%BuildTableAndMsg%-
echo -��ͻ���=%BuildAssets%-

set /p ask=ȷ����������?(y/n)
if /i %ask% NEQ y (
	goto FINISH_FLAG
)

echo "��ʼ���"

if %DeleteOldAssets% EQU true (
    if %BuildAssets% EQU true (
        if %BuildTableAndMsg% EQU true (
            echo ��ʼɾ������Դ
            rd /q /s %GAME_PATH%\AssetBundles
            echo ���ɾ������Դ
        )
    )
)

if %BuildAssets% EQU true (
	echo ��ʼ������Դ
	start /wait TortoiseProc.exe /command:update /path:%TOOLS_PATH% /closeonend:1
	echo ��ɸ�����Դ
    echo ��ʼ����Դ
    start /wait Unity.exe -projectPath %TOOLS_PATH% -executeMethod AutoBuild.Build -quit -batchmode
    echo ��ɴ���Դ
)

if %BuildTableAndMsg% EQU true (
	echo ��ʼ���±��/Э��
	start /wait TortoiseProc.exe /command:update /path:%TABLE_PATH% /closeonend:1
	echo ��ɸ��±��/Э��
)

if %BuildClient% EQU true (
	echo ��ʼ���¿ͻ���
	start /wait TortoiseProc.exe /command:update /path:%PROJECT_PATH% /closeonend:1
	echo ��ɸ��¿ͻ���
)

echo ��ʼ����/Э��/�汾
::start /wait Unity.exe -projectPath %PROJECT_PATH% -executeMethod AutoBuild.BuildWeb build_out_path-%GAME_PATH%-%BuildAssets%-%BuildTableAndMsg%-%BuildClient% -quit -batchmode
echo ��ɴ���/Э��/�汾

:FINISH_FLAG

echo ==����==

pause
