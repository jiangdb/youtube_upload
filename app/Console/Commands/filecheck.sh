#!/bin/bash

videoDir='/home/vagrant/youtube_upload/storage/app/video/'$1
xml_file_path='/home/vagrant/youtube_upload/storage/app/csv_temp/'

if [ ! -d "$videoDir" ]; then
  echo -1
  exit 0
fi

if [ -z "$(find $videoDir -type f -name '*.xml')" ]; then
  echo 1
else
  xml=$(find $videoDir -type f -name '*.xml')
  filename=$(basename "$xml")
  cp -u $xml $xml_file_path$filename
  echo $filename
fi

exit
