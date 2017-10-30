#!/bin/bash

videoDir='/home/vagrant/youku_server/storage/app/video/'$1
xml_file_path='/home/vagrant/youku_server/storage/app/csv_temp/'$2

if [ ! -d "$videoDir" ]; then
  echo -1
  exit 0
fi

if [ -z "$(find $videoDir -type f -name result.xml)" ]; then
  echo 0
else
  xml=$(find $videoDir -type f -name result.xml)
  cp -u $xml $xml_file_path
  echo 1
fi

exit
