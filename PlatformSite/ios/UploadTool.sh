#!/bin/sh

#---
#tmp_address
#tmp_user_name
#tmp_user_password
#tmp_dir
#tmp_share_address
#---

if [ $# != 2 ];then  
    echo "Params error!"
	echo "Need two params: desPath(family or fileServer) fileName"	
    exit      
fi

DES_PATH=$1  
FILE_NAME=$2

function fileserverCopy() {
    SMB=`df -h | grep tmp_share_address/ipa` # 连接到tmp_share_address下的ipa目录
    echo "$SMB"
    # 如果共享连接了，那么拷贝到文件服务器
    if [ -n "$SMB" ]; then
		echo "link"
		cp $FILE_NAME /Volumes/ipa/auto_build/
    else
        echo "can not link to tmp_share_address/ipa"
        return 1
    fi    
}   

function familyFtpUpload() {
    local ftpCmd="put $FILE_NAME"
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

if [ "$DES_PATH" = "family" ]; then
	familyFtpUpload
elif [ "$DES_PATH" = "fileServer" ]; then
	fileserverCopy
else
	echo "First param should be family or fileServer"
fi









