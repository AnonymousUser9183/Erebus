# Installation Guide for Erebus Marketplace Script

## Comprehensive Setup Guide for Erebus Marketplace Script Beta 1.0

This comprehensive guide will walk you through the installation process of Erebus Marketplace Script on your system.

---

## Operating System Requirements

This guide is based on **Ubuntu 22.04 LTS (Jammy Jellyfish)**. We strongly recommend using a Linux distribution for optimal performance and security.

**Minimum System Requirements:**
- **RAM:** 4GB (8GB recommended)
- **Storage:** 20GB (50GB+ recommended for marketplace growth)
- **CPU:** 2 cores (4+ cores recommended)
- **OS:** Ubuntu 22.04 LTS or similar Debian-based distribution

**Not Recommended:** Windows, macOS, or other non-Linux operating systems

---

## System Preparation

Begin by updating your system to ensure all packages are current:

```bash
sudo apt update
sudo apt upgrade -y
```

---

## Installing Required Dependencies

### PHP 8.3 Installation

First, we'll install PHP 8.3 along with essential extensions required for the marketplace:

```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.3-fpm php8.3-mysql php8.3-curl php8.3-gd php8.3-mbstring \
php8.3-xml php8.3-zip php8.3-bcmath php8.3-gnupg php8.3-intl php8.3-readline \
php8.3-common php8.3-cli php8.3-gmp php8.3-sodium
```

Install unzip (required for Composer package extraction):

```bash
sudo apt install -y unzip
```

### Composer Installation

Install Composer 2, which is required for managing Laravel dependencies:

```bash
sudo php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
HASH="$(wget -q -O - https://composer.github.io/installer.sig)"
sudo php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
sudo php -r "unlink('composer-setup.php');"
```

### Version Control

Install Git for version control and repository management:

```bash
sudo apt install -y git
```

### Database Server

Install and configure MySQL server:

```bash
sudo apt install -y mysql-server
sudo systemctl start mysql
sudo systemctl enable mysql
```

### Web Server

Install and enable Nginx:

```bash
sudo apt install -y nginx
sudo systemctl start nginx
sudo systemctl enable nginx
```

### Additional System Tools

Install useful utilities:

```bash
sudo apt install -y curl wget htop nano vim netstat-nat
```

---

## Optional: Frontend Development Tools

Erebus Marketplace Script is designed with zero-JavaScript architecture and pure server-side rendering. However, if you plan to extend the marketplace with modern frontend enhancements, you can optionally install Node.js and npm:

```bash
sudo apt install -y nodejs npm
```

After cloning the repository, you can optionally run:

```bash
cd /var/www/Erebus
sudo npm install
```

**Note:** This is completely optional and does not affect core marketplace functionality.

---

## Verification Steps

Verify all installations by checking their versions:

```bash
php -v
composer -V
git --version
mysql --version
nginx -v
```

Expected output (versions may vary):

```
PHP 8.3.15 (cli) (built: Dec 11 2024 14:30:25) (ZTS)
Composer version 2.8.4 2024-12-11 11:57:47
git version 2.34.1
mysql  Ver 8.0.40-0ubuntu0.22.04.1 for Linux on x86_64
nginx version: nginx/1.18.0 (Ubuntu)
```

---

## Database Configuration

### Secure MySQL Setup

Run the MySQL secure installation script:

```bash
sudo mysql_secure_installation
```

When prompted:
1. Enable the VALIDATE PASSWORD COMPONENT (select 'y')
2. Choose password validation level 1
3. Answer 'y' to all subsequent security questions

### Database and User Creation

Access the MySQL prompt:

```bash
sudo mysql
```

Create a new database user (replace placeholders with your own secure values):

```sql
CREATE USER 'ErebusAdmin'@'localhost' IDENTIFIED BY 'Erebus123';
```

Create the database:

```sql
CREATE DATABASE Erebus;
```

Grant necessary privileges:

```sql
GRANT ALL PRIVILEGES ON Erebus.* TO 'ErebusAdmin'@'localhost';
FLUSH PRIVILEGES;
```

Exit the MySQL prompt:

```sql
exit;
```

### Verify Database Access

Test your new database user credentials:

```bash
mysql -u ErebusAdmin -p
```

After entering your password, verify database creation:

```sql
SHOW DATABASES;
```

Your database name should appear in the list. **Securely store your database credentials** - they'll be required for the .env configuration file.

---

## Repository Setup and Laravel Configuration

Navigate to the Erebus Marketplace Script directory:

```bash
cd /var/www/Erebus
```

Clone the Erebus Marketplace Script repository from GitHub:

```bash
sudo git clone https://github.com/AnonymousUser9183/Erebus.git
```

Move the repository to /var/www/ where Nginx will serve it:

```bash
sudo mv Erebus /var/www/
```

Navigate to the project directory:

```bash
cd /var/www/Erebus
```

### Environment Configuration

Create the environment configuration file:

```bash
sudo cp .env.example .env
```

Edit the .env file with administrative privileges:

```bash
sudo nano .env
```

**Critical configuration variables to update:**

```
##ENV Configuration File for Erebus Marketplace Script##

#Database Config
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Erebus
DB_USERNAME=ErebusAdmin
DB_PASSWORD=Erebus123

#Application Config
APP_NAME="Erebus Marketplace Script"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost
APP_TIMEZONE=UTC
APP_LOCALE=en
APP_KEY=

#PoW Captcha Config
POW_DIFFICULTY=4
POW_BATCH_SIZE=10000
POW_MAX_ATTEMPTS=1000000
POW_TOKEN_EXPIRY_MINUTES=30

#Marketplace Config
MARKETPLACE_REQUIRE_REFERENCE=false
MARKETPLACE_SHOW_JS_WARNING=true
MARKETPLACE_COMMISSION_PERCENTAGE=7

#Monero Config
MONERO_RPC_HOST=127.0.0.1
MONERO_RPC_PORT=18082
MONERO_RPC_SSL=false
MONERO_VENDOR_PAYMENT_REQUIRED_AMOUNT=0.4
MONERO_VENDOR_PAYMENT_MINIMUM_AMOUNT=0.04
MONERO_VENDOR_PAYMENT_REFUND_PERCENTAGE=80
MONERO_ADDRESS_EXPIRATION_TIME=1440
MONERO_ADVERTISEMENT_BASE_PRICE=0.10
MONERO_ADVERTISEMENT_MAX_DURATION=30
MONERO_ADVERTISEMENT_MIN_DURATION=1
MONERO_ADVERTISEMENT_MINIMUM_PAYMENT_PERCENTAGE=0.10
MONERO_CANCELLED_ORDER_COMMISSION_PERCENTAGE=1.0
```

Save the file by pressing **CTRL+X**, then **'y'** to confirm, and finally **Enter**.

### Laravel Installation

Install all required packages using Composer:

```bash
sudo composer install --no-dev --optimize-autoloader
```

Generate the application encryption key:

```bash
sudo php artisan key:generate
```

Create database tables through migrations:

```bash
sudo php artisan migrate --force
```

Optionally seed the database with sample data:

```bash
sudo php artisan db:seed (Database Seeder currently broken, see issues)
```

### File Permissions

Set proper file permissions for security:

```bash
sudo chown -R www-data:www-data /var/www/Erebus
sudo find /var/www/Erebus -type f -exec chmod 644 {} \;
sudo find /var/www/Erebus -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/Erebus/storage
sudo chmod -R 775 /var/www/Erebus/bootstrap/cache
sudo chmod 640 /var/www/Erebus/.env
```

---

## Secure Nginx Configuration

Create a new Nginx server block configuration:

```bash
sudo nano /etc/nginx/sites-available/Erebus
```

Copy and paste the following secure Nginx configuration:

```nginx
server {
    listen 80;
    listen [::]:80;

    server_name localhost;

    root /var/www/Erebus/public;
    index index.php index.html index.htm;

    # Error pages
    error_page 503 /maintenance.php;

    # Maintenance mode check
    location / {
        if (-f $document_root/../storage/framework/down) {
            return 503;
        }
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM configuration
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    # Deny access to hidden files
    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }

    # Deny access to backup/lock files
    location ~ ~$ {
        deny all;
        access_log off;
        log_not_found off;
    }

    # Static files caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    # Log files
    access_log /var/log/nginx/erebus-access.log;
    error_log /var/log/nginx/erebus-error.log;
}
```

Save the configuration by pressing **CTRL+X**, then **'y'**, and **Enter**.

### Enable the Site

Create a symbolic link to enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/Erebus /etc/nginx/sites-enabled/
```

Remove the default Nginx configuration:

```bash
sudo rm /etc/nginx/sites-enabled/default
```

### Test Nginx Configuration

Before restarting, test the configuration:

```bash
sudo nginx -t
```

If successful, you'll see:
```
nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
nginx: configuration file /etc/nginx/nginx.conf test is successful
```

Restart Nginx:

```bash
sudo systemctl restart nginx
```

---

## Monero Wallet RPC Configuration

**Important:** Erebus Marketplace Script requires a configured Monero Wallet RPC for payment processing.

### Install Monero

Download and install the latest Monero release:

```bash
cd
wget https://downloads.getmonero.org/linux64
tar xf linux64
sudo mv monero-linux-x64-* /opt/monero
```

### Configure Wallet RPC

Create a dedicated user for Monero:

```bash
sudo useradd -r -s /bin/false monero
```

Create wallet directories:

```bash
sudo mkdir -p /var/lib/monero
sudo chown -R monero:monero /var/lib/monero
sudo chmod 700 /var/lib/monero
```

See [Monero Wallet RPC Configuration Guide](docs/CONNECTING-MONERO-RPC.md) for detailed setup instructions.

---

## Installing and Configuring Tor

**Note:** Your marketplace requires Tor for Anonymous XMR price fetching. We do not provide instructions for setting up a hidden service as using this script on Tor is forbidden and against the licensing terms that you agree to by interacting with this source code in any fashion. By using Tor with this script you will be violating the licensing agreement and you are no longer entitled to use this software and must delete any access to this script immediately.


## Post-Installation Configuration

### Enable Marketplace Features

Generate storage symlink:

```bash
sudo php artisan storage:link
```

### Queue Configuration (Optional)

If using background jobs, configure the queue:

```bash
sudo nano /etc/systemd/system/erebus-queue.service
```

Add:

```ini
[Unit]
Description=Erebus Marketplace Script Queue Worker
After=network.target

[Service]
User=www-data
WorkingDirectory=/var/www/Erebus
ExecStart=/usr/bin/php artisan queue:work --sleep=3
Restart=always

[Install]
WantedBy=multi-user.target
```

Enable the service:

```bash
sudo systemctl daemon-reload
sudo systemctl enable erebus-queue
sudo systemctl start erebus-queue
```

### Setup SSL/TLS (Recommended)

```bash
sudo certbot certonly --manual --preferred-challenges=dns -d address.example.domain
```

Or generate self-signed certificate:

```bash
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
-keyout /etc/ssl/private/erebus.key \
-out /etc/ssl/certs/erebus.crt
```

---

## Testing Your Installation

### Test Access

Visit `http://localhost` in your browser to verify the marketplace is running.

### Test Monero Integration

Check that XMR prices are loading (requires Tor configured):

```bash
curl -x socks5h://127.0.0.1:9050 http://localhost/api/xmr-price
```

### View Logs

Monitor application logs:

```bash
sudo tail -f /var/log/nginx/erebus-error.log
sudo tail -f /var/www/Erebus/storage/logs/laravel.log
```

---

## Security Hardening Checklist

After installation, ensure these security measures:

- [ ] Change default admin credentials
- [ ] Review and test CSP headers: `curl -I http://localhost | grep -i content-security`
- [ ] Verify HSTS is enabled: `curl -I http://localhost | grep -i strict-transport`
- [ ] Test CORS protection
- [ ] Verify rate limiting is active
- [ ] Configure firewall (see Security Guide)
- [ ] Set up log rotation
- [ ] Configure automated backups
- [ ] Review error logging configuration
- [ ] Test PGP 2FA system
- [ ] Verify Monero wallet integration
- [ ] Test dispute resolution system

---

## Troubleshooting

### Monero RPC Connection Failed

Ensure Monero daemon is running and RPC is accessible:

```bash
curl http://127.0.0.1:18081/get_height
```

### Database Connection Errors

Test database connection:

```bash
mysql -u ErebusAdmin -p -h 127.0.0.1 Erebus
```

### Permission Errors

Reset file permissions:

```bash
sudo chown -R www-data:www-data /var/www/Erebus
sudo chmod 775 /var/www/Erebus/storage
```

---

## Next Steps

1. **Review Security Documentation**: See [Security Policy](docs/SECURITY.md)
2. **Configure Marketplace Settings**: Access admin panel at `/admin`
3. **Set Up Content Moderation**: Configure user conduct policies
4. **Test Order Process**: Create test orders and disputes
5. **Monitor Logs**: Establish log monitoring and alerting
6. **Plan Backups**: Set up automated database backups

---

## Support & Documentation

- **Installation Issues**: Check [docs/INSTALLATION.md](docs/INSTALLATION.md)
- **Security Questions**: See [docs/SECURITY.md](docs/SECURITY.md)
- **Monero Setup**: See [docs/CONNECTING-MONERO-RPC.md](docs/CONNECTING-MONERO-RPC.md)
- **Email Support**: anonymoususer9183@protonmail.com

---

**© 2026 The Erebus Development Team, All Rights Reserved.**

**Installation Completed Successfully!**

Your Erebus Marketplace Script installation is now ready for operation.
