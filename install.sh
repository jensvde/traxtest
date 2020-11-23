SQL_ROOT_PASS=Admin@2020
SQL_USER=jens
SQL_USER_PASS=Admin@2020
GITLINK=https://github.com/jensvde/traxtest.git
GITPREFIX=traxtest

sudo apt install -y expect apache2 mysql-server git nano php libapache2-mod-php php-mysql

sudo rm /var/www/html/*.*
git clone $GITLINK /var/www/html/
sudo mv /var/www/html/$GITPREFIX/*.* /var/www/html/

#Automated mysql-secure-installation
./auto_sql_secure.sh $SQL_ROOT_PASS

#Create MySQL users and database
sudo mysql -e "CREATE DATABASE logs;"
mysql -e "CREATE TABLE logentries (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(300) NOT NULL,
password VARCHAR(300) NOT NULL,
ip_address VARCHAR(300),
date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)"
sudo mysql -e "CREATE USER '$SQL_USER'@'localhost' IDENTIFIED WITH mysql_native_password BY '$SQL_USER_PASS';"
sudo mysql -e "GRANT ALL ON *.logs TO '$SQL_USER'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"
