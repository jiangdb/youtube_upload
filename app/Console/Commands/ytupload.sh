#!/bin/bash
#upload
#$1 本地文件目录  /A/B
#$2 本地文件名   a.mp4 or /C/a.mp4
#$3 远程文件目录  /D
if [ $# -ne 3 ]; then
    echo "usage: sh ytupload.sh [Origin Folder] [Origin File] [Destination Folder]"
    echo "       [Origin Folder]: / or /A/B"
    echo "       [Origin File]: a.xml or /C/a.xml"
    echo "       [Destination Folder]: / or /D"
    exit 1
fi
if [[ "$1" == "/" ]]; then
    ascp -P 33001  -d -i ~/.ssh/Youtube\ 8sian --src-base=$1  $1$2 asp-8sian2@asia.aspera.googleusercontent.com:$3
else
    ascp -P 33001  -d -i ~/.ssh/Youtube\ 8sian --src-base=$1  $1/$2 asp-8sian2@asia.aspera.googleusercontent.com:$3
fi
if [ $? -eq 0 ]; then
	echo $2
else
	echo 1
fi
exit 0
