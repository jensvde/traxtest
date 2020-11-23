SQL_ROOT_PASS=Admin@2020
SQL_USER=jens
SQL_USER_PASS=Admin@2020
GITLINK=https://github.com/jensvde/traxtest.git
GITPREFIX=traxtest

sudo apt install -y apache2 mysql-server git nano php libapache2-mod-php php-mysql

sudo rm /var/www/html/*.*
git clone $GITLINK /var/www/html/
sudo mv /var/www/html/$GITPREFIX/*.* /var/www/html/

#Automated mysql-secure-installation
./auto_sql_secure.sh $SQL_ROOT_PASS

#Create MySQL users and database
sudo mysql -e "CREATE DATABASE logs;"
sudo mysql -e "CREATE TABLE logentries (id INT, username VARCHAR2(300), password VARCHAR2(300), ip_address VARCHAR2(300), datetime));"
sudo mysql -e "CREATE USER '$SQL_USER'@'localhost' IDENTIFIED WITH mysql_native_password BY '$SQL_USER_PASS';"
sudo mysql -e "GRANT ALL ON *.logs TO '$SQL_USER'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"
