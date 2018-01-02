#!/bin/bash
#upload
#$1 本地文件目录  /A/B
#$2 本地文件名   a.mp4 or /C/a.mp4
#$3 远程文件目录  /D
#$4 YouTube账户
#$5 YouTube秘钥文件名
#$6 CSV文件发送成功标志文件  /C/a.mp4
if [ $# -ne 5 ]; then
    echo "usage: sh ytupload.sh [Origin Folder] [Origin File] [Destination Folder] [YouTube account name] [ssh key filename] [csv delivery complete]"
    echo "       [Origin Folder]: / or /A/B"
    echo "       [Origin File]: a.xml or /C/a.xml"
    echo "       [Destination Folder]: / or /D"
    echo "       [YouTube account name]"
    echo "       [ssh key filename]"
    echo "       [csv delivery complete]"
    exit 1
fi
if [[ "$1" == "/" ]]; then
    ascp -P 33001  -d -i ~/.ssh/$5 --src-base=$1  $1$2 $6 $4@asia.aspera.googleusercontent.com:$3
else
    ascp -P 33001  -d -i ~/.ssh/$5 --src-base=$1  $1/$2 $6 $4@asia.aspera.googleusercontent.com:$3
fi
if [ $? -eq 0 ]; then
	echo $2
else
	echo 1
fi
exit 0
