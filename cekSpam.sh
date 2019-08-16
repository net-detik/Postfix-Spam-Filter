mailq|grep ^[A-F0-9]|cut -c 42-80|sort |uniq -c|sort -n|tail > /var/www/spam_filter/mailq.txt
