# Deela LMS: Remote Web Server Deployment Guide
This guide details how to deploy the Web version of the **Deela LMS (BAPS-e-Learning)** application to a production server (VPS, Shared Hosting, Vercel, or AWS).

---

## 1. Production Requirements
Ensure your remote web server meets the following requirements:
*   **Operating System**: Linux (Ubuntu 22.04 LTS or newer recommended) or Windows Server
*   **PHP Version**: `>= 8.5` (Ensure core extensions like `openssl`, `pdo_mysql`, `mbstring`, `xml`, `curl`, `zip` are enabled)
*   **Web Server**: Nginx or Apache (with `mod_rewrite` enabled)
*   **Database**: Relational MySQL or TiDB Cloud (already preconfigured in `.env`)
*   **Composer**: Dependency Manager for PHP

---

## 2. Server Installation Steps

### Step A: Clone and Install Dependencies
Upload the repository files to your server directory (e.g., `/var/www/deela`) and run:
```bash
composer install --no-dev --optimize-autoloader
npm install && npm run build
```

### Step B: Configure the Environment File (.env)
Create a `.env` file on the remote server based on `.env.example` and set production configurations:
```ini
APP_NAME="Deela LMS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Active Database Configuration
DB_CONNECTION=mysql
DB_STATE=online # Set to online to use remote DB (TiDB Cloud) or set up a standard mysql server
DB_ACTIVE_CONNECTION=mysql_online

# Configure your production database connection details:
DB_HOST=your-remote-db-host
DB_PORT=3306
DB_DATABASE=your-production-db-name
DB_USERNAME=your-production-db-user
DB_PASSWORD=your-production-db-password
```

### Step C: Initialize the Database and Application Key
Generate the secure application key and run migrations:
```bash
php artisan key:generate
php artisan migrate --force
```

### Step D: Setup Storage Link
Ensure upload pathways function correctly on production by symbolic linking the storage directory:
```bash
php artisan storage:link
```

---

## 3. Web Server Configurations

### Apache VirtualHost Configuration
Create a virtual host pointing to the `/public` folder of your project:
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/deela/public

    <Directory /var/www/deela/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/deela_error.log
    CustomLog ${APACHE_LOG_DIR}/deela_access.log combined
</VirtualHost>
```

### Nginx Server Configuration
Configure Nginx to route all requests through the Laravel Front Controller:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/deela/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.5-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 4. Production Optimizations
To ensure maximum speed and stability, run the following cache optimization commands on deployment:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 5. Security Checklist
*   Ensure `.env` file permissions are set to read-only for the web server user (`chmod 600 .env`).
*   Disable `APP_DEBUG` (`APP_DEBUG=false`) to prevent displaying stack traces on errors.
*   Configure SSL certificates (Let's Encrypt or similar) and redirect HTTP traffic to HTTPS.
