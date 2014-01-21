#!/bin/sh

#cat /etc/mtab | grep /mnt/s3 >/dev/null
#if [ "$?" -eq "0" ]; then
#  echo "ok"
#else
  echo "remounting"
  umount /mnt
  s3fs isocket-screenshots -o default_acl=public-read-write -o allow_other /mnt
#fi
