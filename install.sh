SQL_ROOT_PASS=Admin@2020
SQL_USER=jens
SQL_USER_PASS=Admin@2020
GITLINK=https://github.com/jensvde/traxtest.git

#Get dependencies 
sudo apt install -y expect apache2 mysql-server git nano php libapache2-mod-php php-mysql

#Clone git
sudo rm /var/www/html/*.*
git clone $GITLINK /var/www/html/

#Automated mysql-secure-installation
./auto_sql_secure.sh $SQL_ROOT_PASS

#Create MySQL users and database
sudo mysql -e "CREATE DATABASE logs;"
sudo mysql -e "USE logs; CREATE TABLE logentries (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, username VARCHAR(300) NOT NULL, password VARCHAR(300) NOT NULL, ip_address VARCHAR(300), date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);"
sudo mysql -e "CREATE USER '$SQL_USER'@'localhost' IDENTIFIED WITH mysql_native_password BY '$SQL_USER_PASS';"
sudo mysql -e "GRANT ALL ON *.* TO '$SQL_USER'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

#Freedns update
wget -O - http://freedns.afraid.org/dynamic/update.php?VXUwMHBnNGx5bXBCa2NHQ09kZGZaSERnOjE5NDQ4NzY0 >> /tmp/freedns_traxio_problemen_be.log 2>&1 &
wget -O - http://freedns.afraid.org/dynamic/update.php?VXUwMHBnNGx5bXBCa2NHQ09kZGZaSERnOjE5NDQ4NzY4 >> /tmp/freedns_traxio_stonecloudsys_com.log 2>&1 &
(crontab -l 2>/dev/null; echo "*/5 * * * * wget -O - http://freedns.afraid.org/dynamic/update.php?VXUwMHBnNGx5bXBCa2NHQ09kZGZaSERnOjE5NDQ4NzY0 >> /tmp/freedns_traxio_problemen_be.log 2>&1 &") | crontab -
(crontab -l 2>/dev/null; echo "*/5 * * * * wget -O - http://freedns.afraid.org/dynamic/update.php?VXUwMHBnNGx5bXBCa2NHQ09kZGZaSERnOjE5NDQ4NzY4 >> /tmp/freedns_traxio_stonecloudsys_com.log 2>&1 &") | crontab -

#Done
echo 'Use sudo mysql -e "USE logs; SELECT * FROM logentries;" to view the logentries!'