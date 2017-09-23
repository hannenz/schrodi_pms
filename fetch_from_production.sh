!#/bin/bash

echo "Fetching files from media/"
mountdir="${HOME}/tempftpmount"
mkdir $mountdir
curlftpfs 128784.webmaster:CTs3tllXW1@ws.udag.de $mountdir
rsync -av $mountdir/schrodi-pms.de/media/ /var/www/html/schrodi_pms/media/
fusermount -u $mountdir
#--

