# ✉️ MailCraft - Open-Source Email Template Builder

[![Live Demo](https://img.shields.io/badge/Demo-mailcraft.huzaifaazeem.com-indigo.svg)](https://mailcraft.huzaifaazeem.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Laravel](https://img.shields.io/badge/Laravel-11%2F12-red.svg)](https://laravel.com)
[![Vue 3](https://img.shields.io/badge/Vue-3.x-green.svg)](https://vuejs.org)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-38bdf8.svg)](https://tailwindcss.com)

A modern, fast, responsive drag-and-drop Email Template Builder designed for developers, agencies, freelancers, and businesses. Create professional email designs and export clean, client-safe HTML that renders seamlessly across Gmail, Apple Mail, Outlook, Yahoo, and mobile email apps.

> **Live Demo**: [https://mailcraft.huzaifaazeem.com](https://mailcraft.huzaifaazeem.com)
> 
> **Note**: This application does **NOT** send emails. It solely focuses on building templates and exporting rock-solid, production-ready HTML code. You can use the generated HTML with any ESP or framework: Mailchimp, Resend, Brevo, Klaviyo, Postmark, ActiveCampaign, or Laravel Mail.

---

## 🌟 Key Features

- **🎨 Drag & Drop Visual Editor**:
  - Blocks for: **Brand Logo, Heading, Text, Button (CTA), Image Banner, Divider, Spacer, Social Links, Multi-column Layouts, and Footer**.
  - Reorder, duplicate, and delete blocks effortlessly.
- **🖼️ Smart Image & Logo Engine**:
  - Direct PC file upload (zero-error Base64 data URLs).
  - External image URLs with built-in hotlink protection bypass (`referrerpolicy="no-referrer"`).
  - Instant live thumbnail previews and quick size presets (Small Logo 120px, Medium Logo 160px, Large Logo 240px, Full Banner 100%).
- **📱 Real-time Responsive Preview**:
  - Instant live rendering in Desktop mode (600px standard) and Mobile mode (375px).
- **🎨 Brand Kit & Global Settings**:
  - Global background colors, typography, canvas width, button border radius, and primary/secondary colors.
- **⚡ Merge Tags / Dynamic Variables**:
  - 1-click insertion of `{{name}}`, `{{email}}`, `{{company}}`, `{{order_id}}`, `{{unsubscribe_url}}`, and `{{current_year}}`.
- **🔑 Authentication & User Profiles**:
  - Email/Password Sign Up with verification.
  - One-Click **Sign in with Google** (OAuth 2.0).
  - Guest mode friendly (users can design and export templates without logging in).
- **💾 Template Management**:
  - Save, duplicate, delete, and organize templates by categories.
  - Automatically loads saved custom templates on refresh.
- **📦 Versatile Export Options**:
  - **1-Click Copy HTML**: Direct to clipboard.
  - **Download Single HTML**: Self-contained `.html` file.
  - **Download ZIP Package**: Bundled HTML + JSON design config.
- **💼 10 Ready-to-Use Predefined Templates Included**:
  1. Welcome Email (Onboarding)
  2. Password Reset / OTP Verification
  3. Order Confirmation & Purchase Receipt
  4. Professional Corporate Invoice
  5. Weekly Promotional Newsletter
  6. Account Verification
  7. Abandoned Cart Recovery (10% Discount Offer)
  8. Customer Support Ticket Reply
  9. Subscription Renewal Notice
  10. Security & System Activity Alert

---

## 🛠️ Tech Stack

- **Backend**: Laravel 11/12, PHP 8.2+ (PHP 8.4 recommended)
- **Frontend**: Vue 3 (Composition API), Inertia.js, Tailwind CSS
- **Interactivity**: Sortable drag-and-drop (`vuedraggable`), JSZip
- **Rendering Engine**: Email-safe table layout compiler with inline styles and Outlook MSO conditional comments.
- **Database**: MySQL or SQLite (zero-config)

---

## 🚀 Quick Start / Local Installation

### Prerequisites
- PHP 8.2 or higher (PHP 8.4 recommended)
- Composer
- Node.js 18+ & NPM
- MySQL or SQLite

### Step 1: Clone the repository
```bash
git clone https://github.com/huzaifaazeem869/mailcraft.git
cd mailcraft
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### Step 3: Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

Configure your database connection in `.env`:
```env
DB_CONNECTION=sqlite
# Or for MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=mailcraft
# DB_USERNAME=root
# DB_PASSWORD=your_password
```

### Step 4: Run Migrations & Seed Predefined Templates
```bash
php artisan migrate --seed --class=EmailTemplateSeeder
```

### Step 5: Start the Development Server
```bash
# Terminal 1: Laravel Backend
php artisan serve

# Terminal 2: Vite Frontend
npm run dev
```

Visit `http://localhost:8000` in your browser!

---

## 🌐 Production Server Deployment Guide

### Option A: Deploying on cPanel / Shared Hosting

1. **Upload Files**:
   - Clone via **cPanel Git Version Control** or upload a ZIP of the repo via File Manager into `public_html` (or your subdomain folder, e.g. `public_html/mailcraft.yourdomain.com`).
2. **Document Root**:
   - The subdomain document root should ideally point to the `/public` folder.
   - If your host restricts document root to the project root folder, MailCraft already includes a root `.htaccess` that transparently rewrites all traffic and static assets into `/public`.
3. **PHP Version**:
   - In cPanel **MultiPHP Manager**, set the domain/subdomain PHP version to **PHP 8.2 or PHP 8.4**.
4. **Environment File (`.env`)**:
   - Create `.env` inside the project folder:
     ```env
     APP_NAME="MailCraft"
     APP_ENV=production
     APP_DEBUG=false
     APP_URL=https://mailcraft.yourdomain.com

     DB_CONNECTION=mysql
     DB_HOST=localhost
     DB_DATABASE=your_cpanel_db
     DB_USERNAME=your_cpanel_user
     DB_PASSWORD=your_cpanel_password

     SESSION_DRIVER=database
     CACHE_STORE=database
     ```
   - Run `php artisan key:generate`.
5. **Database Setup**:
   - Create a MySQL database and user in cPanel > **MySQL Databases**.
   - In Terminal / SSH (or via cPanel cron / migration script):
     ```bash
     php artisan migrate --force --seed --class=EmailTemplateSeeder
     ```
6. **Compile Frontend Assets**:
   - On your local PC (or on the server if Node is installed):
     ```bash
     npm run build
     ```
   - Ensure the generated `public/build/` folder is uploaded to `public_html/.../public/build/`.
7. **Set File Permissions**:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

---

### Option B: Deploying on Linux VPS (Ubuntu + Nginx + PHP-FPM)

1. **Clone and Install**:
   ```bash
   cd /var/www
   git clone https://github.com/huzaifaazeem869/mailcraft.git
   cd mailcraft
   composer install --no-dev --optimize-autoloader
   npm install && npm run build
   ```
2. **Permissions**:
   ```bash
   sudo chown -R www-data:www-data /var/www/mailcraft
   sudo chmod -R 775 /var/www/mailcraft/storage /var/www/mailcraft/bootstrap/cache
   ```
3. **Nginx Configuration**:
   ```nginx
   server {
       listen 80;
       server_name mailcraft.yourdomain.com;
       root /var/www/mailcraft/public;

       index index.php index.html;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           include snippets/fastcgi-php.conf;
           fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
       }

       location ~ /\.ht {
           deny all;
       }
   }
   ```
4. **SSL Setup**:
   ```bash
   sudo certbot --nginx -d mailcraft.yourdomain.com
   ```

---

## 🔑 Google OAuth Setup (Optional)

To enable "Sign In with Google":

1. Visit [Google Cloud Console](https://console.cloud.google.com/).
2. Create a project and navigate to **APIs & Services > Credentials**.
3. Create an **OAuth 2.0 Client ID** (Web application).
4. Add your **Authorized redirect URI**:
   - Local: `http://localhost:8000/auth/google/callback`
   - Production: `https://mailcraft.yourdomain.com/auth/google/callback`
5. Copy your Client ID and Client Secret into your `.env`:
   ```env
   GOOGLE_CLIENT_ID=your_client_id_here
   GOOGLE_CLIENT_SECRET=your_client_secret_here
   GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
   ```

---

## 🧪 Running Tests

```bash
php artisan test
```

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!
1. Fork the project.
2. Create your feature branch (`git checkout -b feature/AmazingFeature`).
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the branch (`git push origin feature/AmazingFeature`).
5. Open a Pull Request.

---

## 📄 License

Distributed under the **MIT License**. See `LICENSE` for more information.

---

## 👨‍💻 Author

- **Huzaifa Azeem**
- Portfolio: [huzaifaazeem.com](https://huzaifaazeem.com/)
- GitHub: [@huzaifaazeem869](https://github.com/huzaifaazeem869)
