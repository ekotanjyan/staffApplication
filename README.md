# Getting started

## Installation

Initiate by checking your webserver has the Laravel requirements: (https://laravel.com/docs/7.x/installation#server-requirements).

Switch to the folder where you want the project to be installed.
e.g.:

    cd liquidity

Clone the repository.

    git clone https://github.com/UPR-Consulting-AG/crowd-liquidity.git .

Install all the dependencies using composer. 

*If you do not have composer installed, here is the offical [documentation.](https://getcomposer.org/doc/00-intro.md#system-requirements)
Once you have it installed check by typing in your OS terminal/shell/cmd:*

    composer -v
You should see:  `Composer version x.x.x YY-MM-DD`

Then run:

    composer install

Copy the example env file and make the required configuration changes in the .env file. (*Or just rename .env.example to .env*).

    cp .env.example .env


(**Set the database connection in .env before migrating**).

    DB_CONNECTION=mysql
    DB_HOST=localhost
    DB_PORT=3306
    DB_DATABASE=databasename
    DB_USERNAME=user
    DB_PASSWORD=pass


Generate a new application key

    php artisan key:generate

Run the database migrations and seed with demo data.

    php artisan migrate --seed

If you are using apache make sure vhosts redirects to project `/public` folder. 


    <VirtualHost *:80>
        ServerName localhost
        DocumentRoot "/var/www/liquidity/public"
        #or may be "/var/www/public_html/liquidity/public"
        <Directory "/var/www/liquidity/public">
            Options Indexes FollowSymLinks MultiViews
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>


*Usual path for vhosts config: `/etc/apache2/sites-available/000-default.conf`*


You can now access the application at http://localhost:8000



## Emails
Emails are queued and deliver after running artisan command:
    
    php artisan queue:work

Keep alive the service after ssh exit or terminal close:

    nohup php artisan queue:work --daemon &


## Environment Keys (.env)

Use `mailtrap` service for email testing. Just replace MAIL_USERNAME and MAIL_PASSWORD with the ones from your personal mailtrap account.
    
    MAIL_DRIVER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=mailtrapusername
    MAIL_PASSWORD=mailtrappassword
    MAIL_ENCRYPTION=tls

Stripe test keys.

    client_id=ca_HG8K775ftltsll0s6j1X85rqyiaNtrvQ
    STRIPE_KEY=pk_test_nqKt8IBhw6mfszStGQuGHYQN00fJVai18h
    STRIPE_SECRET=sk_test_utCzxjXIUdH76jMvqTxCO1am00gmsaMGUq
