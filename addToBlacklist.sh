#!/bin/bash
#read target untuk blacklist
#@zakimanhamid

#--------------------------------------------
# CARA CONFIGURASI 							@
#--------------------------------------------

#file daftar email yang akan diblacklist
blacklistSource=/var/www/spam_filter/source/blacklist.txt
#ssh-keygen untuk akses ke server email
#sshKeygen=-i/home/zakimanhamid/.ssh/ns1cron
#simpan daftar email yang akan diblaklist ke variabel
target=$(</$blacklistSource)
#server email
#server=root@192.168.xx.xx
#port ssh server email
#port=22
#direktori/file tempat penyimpanan alamat email yang diblaklist pada email server
dirPostfixTargetBlacklist=/etc/postfix/header_checks
#dirPostfixTargetBlacklist=/var/www/spam_filter/test.txt
#file log jika berhasil ditambahkan ke server email 
BlacklistLog=/var/www/spam_filter/blacklist.local.txt

if [ "$target" = "0" ]; 
then 
	#Tidak ada alamat email yang akan di-blacklist
	#echo 'No blacklist';
	echo $'No blacklist' >> $BlacklistLog
else
	echo "Menambahkan $target ke daftar blacklist..." >> $BlacklistLog
	#send cmd blacklist data
	if echo $'/^From: .*'$target$'/ DISCARD\n/^To: .*'$target$'/ DISCARD' >> $dirPostfixTargetBlacklist 
	then
		echo "$target ditambahkan ke daftar blacklist" >> $BlacklistLog
		#success 
		echo $'target ditambahkan ke blacklist' >> $BlacklistLog
		#kosong kan data target blacklist
		echo '0' > $blacklistSource
		#@Restarting postfix
		/etc/init.d/postfix restart
		#@pindahkan spam ke folder spam
		sleep 3
		moveallspam $target
		sleep 3
		moveallspam $target
		sleep 3
		moveallspam $target
	else
		#Ekseskusi gagal
		echo $'Gagal menambah $target' >> $blacklistSource
	fi
fi
