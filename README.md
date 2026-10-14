# ✉️ MailCraft - Open-Source Email Template Builder

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Laravel](https://img.shields.io/badge/Laravel-11%2F12-red.svg)](https://laravel.com)
[![Vue 3](https://img.shields.io/badge/Vue-3.x-green.svg)](https://vuejs.org)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-38bdf8.svg)](https://tailwindcss.com)

A modern, fast, responsive drag-and-drop Email Template Builder designed for developers, agencies, freelancers, and businesses. Create professional email designs and export clean, client-safe HTML that renders seamlessly across Gmail, Apple Mail, Outlook, Yahoo, and mobile email apps.

> **Note**: This application does **NOT** send emails. It solely focuses on building templates and exporting rock-solid, production-ready HTML code. You can use the generated HTML with any ESP or framework: Mailchimp, Resend, Brevo, Klaviyo, Postmark, ActiveCampaign, or Laravel Mail.

---

## 🌟 Key Features

- **🎨 Drag & Drop Visual Editor**:
  - Blocks for: **Heading, Text, Button (CTA), Image, Divider, Spacer, Social Links, Multi-column Layouts, and Footer**.
  - Reorder, duplicate, and delete blocks effortlessly.
- **📱 Real-time Responsive Preview**:
  - Instant live rendering in Desktop mode (600px standard) and Mobile mode (375px).
- **🎨 Brand Kit & Global Settings**:
  - Global background colors, typography, canvas width, button border radius, and primary/secondary colors.
- **⚡ Merge Tags / Dynamic Variables**:
  - 1-click insertion of `{{name}}`, `{{email}}`, `{{company}}`, `{{order_id}}`, `{{unsubscribe_url}}`, and `{{current_year}}`.
- **💾 Template Management**:
  - Save, duplicate, delete, and organize templates by categories.
  - Guest mode friendly (no forced login to design and export).
- **📦 Versatile Export Options**:
  - **1-Click Copy HTML**: Direct to clipboard.
  - **Download Single HTML**: Self-contained `.html` file.
  - **Download ZIP Package**: Bundled HTML + JSON design config.
- **⏪ Undo & Redo History**:
  - State management with instant rollback and redo capabilities.
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

- **Backend**: Laravel 11/12, PHP 8.2+
- **Frontend**: Vue 3 (Composition API), Inertia.js, Tailwind CSS
- **Interactivity**: Sortable drag-and-drop (`vuedraggable`), JSZip
- **Rendering Engine**: Email-safe table layout compiler with inline styles and Outlook MSO conditional comments.
- **Database**: SQLite (default zero-config) or MySQL

---

## 🚀 Quick Start / Local Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 18+ & NPM

### Step 1: Clone the repository
```bash
git clone https://github.com/huzaifaazeem869/email-template-builder.git
cd email-template-builder
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

### Step 4: Run Migrations & Seed Predefined Templates
```bash
php artisan migrate --seed --class=EmailTemplateSeeder
```

### Step 5: Start the Development Server
```bash
# In terminal 1 (Laravel dev server)
php artisan serve

# In terminal 2 (Vite dev server)
npm run dev
```

Visit `http://localhost:8000` in your browser!

---

## 🐳 Docker Setup (Optional)

You can run the entire application using Docker Compose:

```bash
docker compose up -d --build
docker compose exec app php artisan migrate --seed --class=EmailTemplateSeeder
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
