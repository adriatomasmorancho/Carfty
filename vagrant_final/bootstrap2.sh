#!/bin/sh

apt-get update
apt-get upgrade

sudo apt-get install -y apache2
sudo service apache2 restart

apt-get install -y mariadb-server mariadb-client

sudo add-apt-repository ppa:ondrej/php
sudo apt-get install -y php-cli php-xml php-mysql php libapache2-mod-php php-mbstring
sudo systemctl restart apache2


cp /Programas/apicrafty/conf_apache/php.ini /etc/php/8.2/cli/php.ini
sudo systemctl restart apache2


# # Crear la base de dades
# mysql << EOF
# CREATE DATABASE gymwrat charset='utf8mb4' collate='utf8mb4_unicode_ci';
# EOF

# Configuració de MariaDB:
# - Permet connexions des de qualsevol host
# - Activa GROUP BY estricte
# - Permet || com a CONCAT (PIPES_AS_CONCAT)
# - No permet " com a delimitador de cadenes, només ' (ANSI_QUOTES)
echo *** Configura MariaDB ***
cp /vagrant/50-server.cnf /etc/mysql/mariadb.conf.d

# Crea l'usuari admin amb accés remot
echo *** Crea usuari ***
mysql << EOF
CREATE OR REPLACE USER admin@'%' IDENTIFIED BY 'Aa123456?';
GRANT ALL ON *.* TO admin@'%';
EOF

systemctl restart mariadb

cd /var/www/html
ln -s /Programas/apicrafty/ apicrafty
# sudo cp -R /Programas/gymwrat /var/www

chown -R www-data:www-data /var/www/html/apicrafty
chmod -R 775 /var/www/html/apicrafty

# Habilitamos el soporte SSL
sudo a2enmod ssl

sudo cp /Programas/apicrafty/conf_apache/apicrafty.ssl  /etc/apache2/sites-available/default-ssl.conf

sudo touch /etc/apache2/sites-available/apicrafty.conf
sudo cp /Programas/apicrafty/conf_apache/apicrafty.conf  /etc/apache2/sites-available/apicrafty.conf

sudo a2enmod rewrite
sudo a2ensite apicrafty.conf

sudo a2dissite 000-default.conf
sudo a2enmod rewrite
sudo a2enmod headers
sudo systemctl restart apache2

# Reiniciamos el servicio
sudo service apache2 restart

cd /var/www/html/apicrafty
yes | php artisan migrate


# chmod -R 755 storage/app/public
# sudo rm -rf public/storage
# sudo php artisan storage:link
# sudo chmod 775 -R public/storage

# sudo nano /etc/apache2/apache2.conf
# ServerName gymwrat.com

# apachectl configtest