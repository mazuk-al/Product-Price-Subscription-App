<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Product Price Subscription App

The extension was developed in Laravel as a test task, in accordance with the technical requirements specified by Skif Trade.

### Installation instructions

- Install Composer
> sudo apt update
>
> sudo apt install php-cli unzip  
> 
> cd ~  
> curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php  
> 
> HASH=\`curl -sS https://composer.github.io/installer.sig`  
> 
> php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" 
> 
> sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer  
- Install Git
> sudo apt install git
- Install Nginx, MySQL, PHP, and PHP Packages
> sudo apt install nginx mysql-server php8.2-fpm php8.2-mysql
>
> sudo apt install openssl php8.2-bcmath php8.2-curl php8.2-json php8.2-mbstring php8.2-mysql php8.2-tokenizer php8.2-xml php8.2-zip
- Clone Repository
> git clone https://github.com/mazuk-al/Product-Price-Subscription-App
- Run Composer:
> cd Product-Price-Subscription-App
> 
> composer install
- Configure Nginx  
  /etc/nginx/sites-available/product-price-subscription-app
>     server {
>         listen 8080;
>         server_name ***domain or ip***;
>         root /***path-to-project***/public;
>         index index.php index.html index.htm;
>         location / {
>             try_files $uri $uri/ /index.php?$query_string;
>         }
>         location ~ \.php$ {
>             include snippets/fastcgi-php.conf;
>             fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
>             fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
>             include fastcgi_params;
>         }
>         location ~ /\.ht {
>             deny all;
>         }
>     }
- Create Symbolic Link:
> sudo ln -s /etc/nginx/sites-available/product-price-subscription-app /etc/nginx/sites-enabled
- Restart Nginx:
> sudo systemctl restart nginx
- Run MySQL 
> service mysql start  
> mysql -u root -p  
> CREATE DATABASE ***product-price-subscription-app-db***;  
> exit;
- Edit \`.env` in the Root of the Project:
> DB_CONNECTION=mysql  
> DB_HOST=127.0.0.1  
> DB_PORT=3306  
> DB_DATABASE=***product-price-subscription-app-db***  
> DB_USERNAME=***root***  
> DB_PASSWORD=***root***
- Run Migration:
> php artisan migrate
- Add Mail Credentials to `.env`  
  Example:
> MAIL_MAILER=smtp  
> MAIL_HOST=***sandbox.smtp.mailtrap.io***  
> MAIL_PORT=***2525***  
> MAIL_USERNAME=***username***  
> MAIL_PASSWORD=***password***

For mail tests **[mailtrap](https://mailtrap.io/)** was used.

Please replace placeholders like `***domain or ip***`, `***path-to-project***`, `***product-price-subscription-app-db***`, `***root***`, `***username***`, and `***password***` with your actual values.
