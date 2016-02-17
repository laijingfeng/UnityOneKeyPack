#!/bin/sh

#---
#tmp_address
#tmp_user_name
#tmp_user_password
#tmp_dir
#tmp_share_address
#---

#---------------Config---------------#
UNITY_PATH=/Applications/Unity/Unity.app/Contents/MacOS/Unity
#PROJECT_PATH=/Users/thomasmeng/Desktop/Hotwork/HotCode/trunk_auto/client/game/client
PROJECT_PATH=/Users/thomasmeng/Desktop/test
SCRIPT_PATH=/Users/thomasmeng/Sites/ios
LOG_PATH=/Users/thomasmeng/Sites/msg_build.txt
#---------------Config---------------#

#---------------Par---------------#
if [ $# != 2 ];then  
    echo "Params error!"
    exit      
fi

UPLOAD_FAMILY=$1
UPLOAD_FTP=$2

#---------------Par---------------#

PACK_NAME="`date +%Y.%m.%d.%H.%M`"
XCODE_PATH=${PROJECT_PATH}/${PACK_NAME}

function fileserverCopy() {
    local copyType=$1
    SMB=`df -h | grep tmp_share_address/ipa` # 连接到tmp_share_address下的ipa目录
	echo "$SMB"
    # 如果共享连接了，那么拷贝到文件服务器
    if [ -n "$SMB" ]; then
        $SCRIPT_PATH/Log.sh "link"
        if [ "$copyType" = "ios" ]; then
            cp $XCODE_PATH/hotblood_ios_test_${PACK_NAME}.ipa /Volumes/ipa/auto_build/ # 共享目录
			cp $SCRIPT_PATH/hotblood_ios_test_${PACK_NAME}.plist /Volumes/ipa/auto_build/
        elif [ "$copyType" = "android" ]; then
            $SCRIPT_PATH/Log.sh "android"
        elif [ "$copyType" = "qr" ]; then
            cp $SCRIPT_PATH/qr.png /Volumes/ipa/auto_build/
        else
            return 1
        fi
    else
        $SCRIPT_PATH/Log.sh "can not link to tmp_share_address/ipa"
        return 1
    fi    
}        

function familyFtpUpload() {
    local ftpType=$1
    local ftpCmd=""
    if [ "$ftpType" = "ios" ]; then
        ftpCmd="put $XCODE_PATH/hotblood_ios_test_${PACK_NAME}.ipa
				put $SCRIPT_PATH/hotblood_ios_test_${PACK_NAME}.plist"
    elif [ "$ftpType" = "android" ]; then
        return 1
    elif [ "$ftpType" = "qr" ]; then
        ftpCmd="put $SCRIPT_PATH/qr.png"
    else
        return 1
    fi
    # 能连上family的内网ftp，必须有机房vpn连接
    ping -c 3 -t 3 tmp_address #`tmp_address`是ip地址，如，x.x.x.x
    if [ $? -eq 0 ]; then
        lftp << EOF
        open tmp_address
        user tmp_user_name tmp_user_password
        cd tmp_dir
        $ftpCmd
        bye
EOF
    else
        return 1
    fi
}

$SCRIPT_PATH/Log.sh "将unity导出成xcode工程"

$SCRIPT_PATH/Log.sh "ProjectPath:"/$PROJECT_PATH

$UNITY_PATH -projectPath $PROJECT_PATH -executeMethod AutoBuild.BuildIOS build_out_path-$PACK_NAME -quit -batchmode

$SCRIPT_PATH/Log.sh "Xcode工程生成完毕"

$SCRIPT_PATH/Log.sh "开始生成ipa"

$SCRIPT_PATH/build_xcode.sh $XCODE_PATH hotblood_ios_test_$PACK_NAME

$SCRIPT_PATH/Log.sh "ipa生成完毕"

$SCRIPT_PATH/Log.sh "Generate QRCode Start"

rm $SCRIPT_PATH/*.png 
python $SCRIPT_PATH/qr.py $PACK_NAME

$SCRIPT_PATH/Log.sh "Generate QRCode Finish"

$SCRIPT_PATH/Log.sh "Generate Plist File Start"

rm $SCRIPT_PATH/*.plist
$SCRIPT_PATH/generate_plist.sh $PACK_NAME

$SCRIPT_PATH/Log.sh "Generate Plist File Finish"

if [ "$UPLOAD_FTP" = "T" ]; then
	$SCRIPT_PATH/Log.sh "Copy to ftp Start"
	fileserverCopy "qr"
	fileserverCopy "ios"
	$SCRIPT_PATH/Log.sh "Copy to ftp End"
fi

if [ "$UPLOAD_FAMILY" = "T" ]; then
	$SCRIPT_PATH/Log.sh "Copy to family Start"
	familyFtpUpload "qr"
	familyFtpUpload "ios"
	$SCRIPT_PATH/Log.sh "Copy to family End"
fi

$SCRIPT_PATH/Log.sh "Remove the xcode project Start"

#rm -rf $PROJECT_PATH

$SCRIPT_PATH/Log.sh "Romove the xcode project End"