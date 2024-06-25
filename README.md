# BigchainDB Integration in Laravel Project
This project demonstrates how to integrate BigchainDB into a Laravel application using a custom BigchainDB driver. This README will guide you through the setup and usage of the integration.

## 1. Installation on Ubuntu(RunPod.io)

### 1.1. Check Operating system
  Check Linux Version

    uname -a

  Check Linux Distributor

    lsb_release -a

### 1.2. Set Password & SSH
  Set Password of root user

    passwd

  Open the SSH configuration file with a text editor

    sudo nano /etc/ssh/sshd_config

  Ensure the following settings are configured (uncomment and set as needed)

    Port 22
    PermitRootLogin yes
    PasswordAuthentication yes

  Restart the SSH service to apply the changes
    
    sudo service ssh restart

### 1.3. Install Docker
  Update the existing list of packages:

    sudo apt-get update

  Install the required packages for Docker:

    sudo apt-get install \
        ca-certificates \
        curl \
        gnupg \
        lsb-release

  Add Docker’s official GPG key:

    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

  Set up the Docker repository:

    echo \
      "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu \
      $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

  Update the package index again:
    
    sudo apt-get update

  Install Docker Engine:

    sudo apt-get install docker-ce docker-ce-cli containerd.io

### 1.4. Start Docker as a Service
  Edit the Docker Init Script:
  Open the Docker init script in a text editor.

    sudo nano /etc/init.d/docker

  Locate the ulimit Line:
  Find the line that sets the ulimit. It might look something like this:

    ulimit -n 1048576

  Comment Out the ulimit Line:
  Comment out the line by adding a # at the beginning:

    # ulimit -n 1048576

  Start Docker using the service command:

    sudo service docker start

  Verify Docker is running:

    sudo service docker status

### 1.5. Setup BigChainDB using Docker
  Pull the latest version of the BigchainDB all-in-one Docker image

    sudo docker pull bigchaindb/bigchaindb:all-in-one

  Run BigChainDB Server using Docker on Port:9984

    sudo docker run \
      --detach \
      --name bigchaindb \
      --publish 9984:9984 \
      --publish 9985:9985 \
      --publish 27017:27017 \
      --publish 26657:26657 \
      --volume $HOME/bigchaindb_docker/mongodb/data/db:/data/db \
      --volume $HOME/bigchaindb_docker/mongodb/data/configdb:/data/configdb \
      --volume $HOME/bigchaindb_docker/tendermint:/tendermint \
      bigchaindb/bigchaindb:all-in-one

  Verify Server is running

    sudo docker ps -a

### 1.6. Install Node.js
  Download and install NVM:

    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.3/install.sh | bash

  Load NVM:
  
    export NVM_DIR="$([ -z "${XDG_CONFIG_HOME-}" ] && printf %s "${HOME}/.nvm" || printf %s "${XDG_CONFIG_HOME}/nvm")"
    [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" # This loads nvm
    [ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion" # This loads nvm bash_completion

  Reload your shell:

    source ~/.bashrc

  Install the latest stable version of Node.js:

    nvm install node

  Verify Node.js installation:

    node -v

  Verify npm installation:
    
    npm -v

### 1.7. Run BigChainDB Driver
  Make and Move to the Local Directory

    mkdir /home/diamond
    cd /home/diamond

  Clone the Git Repository

    git clone https://<token>@github.com/diamond120/js-bigchain-driver.git

  Move into the Repository

    cd js-bigchain-driver

  Install Dependencies

    npm install

  Generate Public & Private Key

    node generate_keys.js

  Configure Environment <br/>
  Set Generated Public & Private Key and Port to 2466

    cp .env.example .env
    nano .env

  Install pm2 for Process Management

    npm install pm2 -g

  Start Server using pm2

    pm2 start app.js

  Verify Servier is running

    pm2 list
    pm2 logs

### 1.8. Install PHP v7.4
  Move to the Local Directory
    
    cd /home/diamond

  Update the Package List:

    sudo apt update

  Add the PHP Repository:

    sudo add-apt-repository ppa:ondrej/php

  Update the Package List Again:
  
    sudo apt update

  Install PHP 7.4

    sudo apt install php7.4

  Install additional extensions

    sudo apt install php7.4-cli php7.4-fpm php7.4-json php7.4-common php7.4-mysql php7.4-zip php7.4-gd php7.4-mbstring php7.4-curl php7.4-xml php7.4-bcmath php7.4-json

  Install PHP and Required Extensions:

    sudo apt install php libapache2-mod-php php-mysql php-xml php-mbstring php-zip

  Set PHP Default Version to 7.4

    sudo update-alternatives --set php /usr/bin/php7.4

  Verify the Installation

    php -v

### 1.9. Install Composer v1.10
  Update the Package List:

    sudo apt update

  Install Dependencies:

    sudo apt install php-cli unzip

  Download the Composer Installer:

    curl -sS https://getcomposer.org/installer -o composer-setup.php

  Verify the Installer's Signature:

    HASH=$(curl -sS https://composer.github.io/installer.sig)
    php -r "if (hash_file('sha384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

  Install Composer:

    sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

  Downgrade Composer to version 1:

    composer self-update --1

  Verify the Installation:

    composer --version

### 1.10. Live RobotBulls Website
  Move to the Local Directory
    
    cd /home/diamond

  Clone the Git Repository

    git clone https://<token>@github.com/diamond120/bcdb-robotbulls-laravel.git

  Move into the Repository

    cd bcdb-robotbulls-laravel

  Install Dependencies

    composer install

  Configure Environment <br/>
  Set Twilio & Database Credentials

    cp .env.<mode>.example .env
    nano .env

  Create a new virtual host configuration file for Laravel project:
  
    sudo nano /etc/apache2/sites-available/robotbulls.conf
  
  Add the following configuration to the file:

    <VirtualHost *:8000>
        ServerAdmin webmaster@localhost
        DocumentRoot /home/diamond/bcdb-robotbulls-laravel/public

        <Directory /home/diamond/bcdb-robotbulls-laravel>
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>

  Configure Apache to listen on port 8000
    
    sudo nano /etc/apache2/ports.conf

  Add the following line to make Apache listen on port 8000:

    Listen 8000
  
  Edit the Apache Configuration File:
  
    sudo nano /etc/apache2/apache2.conf

  Add the ServerName Directive:

    ServerName ow98ozu66ol5vc-8000.proxy.runpod.net

  Set File Permissions:

    sudo chown -R www-data:www-data .
    sudo chmod -R 775 ./storage
    sudo chmod -R 775 ./bootstrap/cache

  Enable the virtual host configuration and the Apache rewrite module:

    sudo a2ensite robotbulls.conf
    sudo a2enmod rewrite
    sudo service apache2 restart

  Remove Apache Logs

    sudo truncate -s 0 /var/log/apache2/*.log

  View Apache Error Log
    
    sudo tail -f /var/log/apache2/error.log

  View Apache Access Log
  
    sudo tail -f /var/log/apache2/access.log

## 2. Database Migration
  Update Package Index
    
    sudo apt update

  Install the MySQL server package using the package manager:

    sudo apt install mysql-server

  Start the MySQL service

    sudo service mysql start

  Secure MySQL Installation
    
    sudo mysql_secure_installation

  Configure MySQL to Use Password Authentication
    
    sudo nano /etc/mysql/my.cnf

  Add the following line under the [mysqld] section:

    [mysqld]
    default_authentication_plugin=mysql_native_password

  Restart MySQL Service
    
    sudo service mysql restart

  Access the MySQL Shell

    sudo mysql

  Change Root Password
    
    ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '<new password>';

  Flush Privileges

    FLUSH PRIVILEGES;

  Exit the MySQL Shell
    
    EXIT;

  Access the MySQL Shell

    sudo mysql -p

  Run the MySQL query

    source /home/diamond/bcdb-robotbulls-laravel/robotbulls_mysql.sql;
  
  Exit the MySQL Shell
    
    EXIT;

  Move to the BigChain Driver Repository

    cd /home/diamond/js-bigchain-driver
    cd migration

  Run the Migration Script

    node migrate.js

  Move to the Robotbulls Repository

    cd /home/diamond/bcdb-robotbulls-laravel

  Skip Laravel Installer

    sudo nano ./storage/installed

  Write this text
    
    MySQL Installed Successfully!

## 3. Register Admin & User
  Register Admin & Users with the following SMS (first one will be admin and the others will be users)

    12345

  Fill out email_verified_at fields with the following HTTP Request

    POST https://ow98ozu66ol5vc-2466.proxy.runpod.net
    Body
    {
      "object": "users",
      "data": {"email_verified_at":1718637580}
    }