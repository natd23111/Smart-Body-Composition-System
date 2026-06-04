# Smart Body Composition & AI-Based Health Tracking Web Application

A web-based body composition tracking and health recommendation platform developed as a Final Year Project at Universiti Malaysia Sarawak (UNIMAS).

The system allows users to log body composition measurements (weight, body fat, muscle mass, visceral fat, bone mass, and more), track progress over time, set fitness goals, and receive AI-driven personalised health recommendations based on trend analysis.

---

## Table of Contents

1. [Features](#features)
2. [Installation Kit](#installation-kit)
   - [Method A: Docker Compose (Recommended)](#method-a-docker-compose-recommended)
   - [Method B: Manual Setup](#method-b-manual-setup)
3. [User Manual](#user-manual)
4. [Quick Start / Basic Usage](#quick-start--basic-usage)
5. [Demo Accounts](#demo-accounts)
6. [Troubleshooting / FAQs](#troubleshooting--faqs)
7. [Project Structure](#project-structure)
8. [Credits & License](#credits--license)

---

## Features

- **10 Body Metrics** — track weight, body fat %, body fat kg, muscle mass, bone mass, body water %, visceral fat, , Calories kcal, body age, and physical rating.
- **Trend Analysis** — visualise changes over 7, 14, 30, or 90-day periods with interactive charts
- **AI Rule-Based Recommendations** — automatic health insights generated from your measurement history, adjusted by age and gender
- **Goal Tracking** — set targets for weight, body fat, muscle mass, and visceral fat
- **Notification System** — weekly reports, measurement reminders, and goal-achievement alerts
- **Admin Panel** — manage users, view records, configure system settings, and customise recommendation templates
- **Unit Conversion** — toggle between metric (kg/cm) and imperial (lb/in) units

---

## Installation Kit

### Prerequisites

| Requirement | Version | Notes |
|---|---|---|---|
| **Docker Desktop** | 20.x+ | Required for Method A (Docker Compose) |
| PHP | 8.2 or higher | Required for Method B only |
| Composer | 2.x | Required for Method B only |
| Node.js | 20.x or higher | Required for Method B only |
| MySQL / MariaDB | 5.7+ | Required for Method B only (Docker includes MySQL) |
| Git | Any | For cloning the repository |

> **Windows users:** Run PowerShell or Command Prompt **as Administrator** when executing installation commands that require system-wide changes (e.g., Docker, PHP path setup).

---

### Method A: Docker Compose (Recommended)

This method runs both the app **and MySQL** in containers — no need to install PHP, Composer, Node.js, or MySQL locally. Just Docker.

```bash
# 1. Clone the repository
git clone https://github.com/natd23111/Smart-Body-Composition-System.git
cd Smart-Body-Composition-System

# 2. Build and start all services
docker-compose up --build
```

What happens:
- A **MySQL 8.0 container** starts on port 3306 (with healthcheck)
- The **app container** builds from the Dockerfile and waits for MySQL to be ready
- `entrypoint.sh` runs database migrations and seeds demo data automatically

After starting, open **http://localhost:8080** in your browser.

To stop:
```bash
docker-compose down
```

> **Note:** Each `docker-compose up` re-runs `migrate:fresh --seed` (fresh demo data). Database data persists in a Docker volume between runs unless you run `docker-compose down -v`.

---

### Method B: Manual Setup

Use this method for local development without Docker — requires PHP, Composer, Node.js, and MySQL installed on your machine.

```bash
# 1. Clone the repository
git clone https://github.com/natd23111/Smart-Body-Composition-System.git
cd Smart-Body-Composition-System

# 2. Install PHP dependencies
composer install

# 3. Create environment file
copy .env.example .env
```

**Edit `.env`** with your database details:

```ini
DB_CONNECTION=mysql          # or pgsql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartbodycomposition
DB_USERNAME=root
DB_PASSWORD=your_password
```

Then continue:

```bash
# 4. Generate application key
php artisan key:generate

# 5. Create the database in MySQL/MariaDB first, then run:
php artisan migrate --seed

# 6. Install frontend dependencies and build assets
npm install
npm run build

# 7. Start the development server
php artisan serve
```

Open **http://127.0.0.1:8000** in your browser.

> **Alternative:** Use the bundled composer script to automate steps 2–6:
> ```bash
> composer setup
> ```
> This runs `composer install`, copies `.env`, generates the key, migrates, seeds, installs npm packages, and builds frontend assets in one command. You still need to edit `.env` with your database credentials beforehand.

---

## User Manual

A comprehensive user manual is available at:

📄 **`docs/user_manual.pdf`**

The manual covers:

1. **Account Registration & Setup** — creating an account, setting up your profile (age, gender, height)
2. **Entering Measurements** — how to log body composition readings from your smart scale
3. **Dashboard Overview** — understanding the dashboard widgets and key metrics
4. **Trends & Charts** — interpreting trend graphs across different time periods
5. **Goals** — setting, tracking, and achieving fitness goals
6. **Health Recommendations** — understanding AI-generated insights and acting on them
7. **Notifications** — managing weekly reports, reminders, and alerts
8. **Admin Panel** — user management, system settings, recommendation templates
9. **Troubleshooting** — common issues and their solutions

> If the manual is not yet included with your download, check the latest release on the [GitHub repository](https://github.com/natd23111/Smart-Body-Composition-System).

---

## Quick Start / Basic Usage

### Step 1 — Create an Account

Navigate to http://127.0.0.1:8000 and click **Register**. Fill in your name, email, and password.

### Step 2 — Complete Your Profile

On first login, you will be prompted to set up your profile:
- **Age**
- **Gender** (Male / Female)
- **Height** (cm or in, depending on your unit preference)

### Step 3 — Enter Body Composition Data

Go to **Body Composition** from the sidebar and click **Add New Record**. Enter the values from your smart scale:
- Weight (kg)
- Body Fat %
- Body Fat (kg)
- Muscle Mass (kg)
- Bone Mass (kg)
- Body Water %
- Visceral Fat Level
- BMR / Daily Caloric Intake (kcal)
- Body Age
- Physical Rating (1–9)

> **Tip:** Take measurements at the same time each day (e.g., morning after waking) for consistent trend data.

### Step 4 — Explore the System

| Page | What you can do |
|---|---|
| **Dashboard** | View summary cards, latest measurement, and recent trends |
| **Trends** | Interactive line charts for any metric over 7/14/30/90 days |
| **Recommendations** | AI-generated health insights based on your data changes |
| **Goals** | Set weight/fat/muscle/visceral-fat targets with deadlines |
| **Notifications** | View weekly reports, reminders, and goal alerts |
| **Settings** | Change password, unit preferences, notification toggles |
| **AI Tips** | Quick health tips generated from your profile |

### Step 5 — Generate Recommendations

After entering at least **two measurements** (so the system can detect a trend), go to **Recommendations** and click **Generate**. The system analyses your measurement history and produces personalised advice cards.

---

## Demo Accounts

The database seeder creates the following accounts for testing:

| Role | Email | Password |
|---|---|---|
| **Demo User** | `ahmad.fadhil@example.com` | `password` |
| **Admin** | `admin.demo@example.com` | `password` |

The demo user account includes **8 weeks of sample body composition data** (Jan–Apr 2026), active goals, and pre-generated recommendations — ideal for exploring the system's features immediately.

> **Security note:** Change these passwords in production or delete the demo accounts after testing.

---

## Troubleshooting / FAQs

### "Database connection refused" on `php artisan migrate`

- Make sure MySQL is running.
- Verify `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in your `.env` file.
- Ensure the database exists. Create it manually if needed:
  ```sql
  CREATE DATABASE smartbodycomposition;
  ```

### "Vite manifest not found" or blank page

The frontend assets have not been built. Run:
```bash
npm run build
```
For development with hot-reload, use:
```bash
npm run dev
```

### File permission errors (Linux / macOS)

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Port 8000 already in use

Use a different port:
```bash
php artisan serve --port=8080
```

### "Specified key was too long" error (MySQL < 5.7.7)

Add the following to `app/Providers/AppServiceProvider.php` inside the `boot()` method:
```php
Schema::defaultStringLength(191);
```

### Application shows "500 Server Error" after setup

Check the Laravel log file:
```bash
# Windows (PowerShell)
Get-Content storage\logs\laravel.log -Tail 50

# Linux / macOS
tail -50 storage/logs/laravel.log
```

### Docker container exits immediately

Check the container logs:
```bash
docker logs smartbodycomposition-app
```

### Can't log in after registration

Ensure your database is seeded (`php artisan migrate --seed`). If you registered a new account, check that `email_verified_at` is set, or check `storage/logs/laravel.log` for any authentication errors.

---

## Project Structure

```
Smart-Body-Composition-System/
│
├── app/
│   ├── Enums/                  # Role definitions (user, admin)
│   ├── Http/
│   │   ├── Controllers/        # API & auth controllers
│   │   └── Middleware/         # Session timeout, admin gate
│   ├── Models/                 # User, BodyComposition, Goal, etc.
│   ├── Services/               # Recommendation engine, goal progress, notifications
│   └── Support/                # Admin security settings helper
│
├── config/
│   ├── recommendations.php     # Reference ranges for all body metrics
│   └── ...                     # App, auth, database, sanctum, etc.
│
├── database/
│   ├── migrations/             # Database schema definitions
│   ├── seeders/                # Demo data (users, measurements, templates)
│   └── factories/              # Test data factories
│
├── resources/
│   ├── js/                     # Vue 3 frontend source
│   │   ├── components/         # Reusable components (charts, tabs)
│   │   ├── layouts/            # Admin & user page layouts
│   │   ├── pages/              # Page components (Dashboard, Trends, etc.)
│   │   ├── router/             # Vue Router (auth/admin guards)
│   │   ├── services/           # Axios API client & auth service
│   │   └── stores/             # Pinia state stores (auth, unit conversion)
│   ├── css/                    # Tailwind CSS entry
│   └── views/                  # Laravel Blade templates (SPA mount)
│
├── routes/
│   ├── api.php                 # REST API endpoints
│   ├── web.php                 # SPA catch-all route
│   └── console.php             # Scheduled tasks (daily notifications)
│
├── public/                     # Web root (index.php, built assets)
├── storage/                    # Logs, cache, framework files
├── tests/                      # PHPUnit test suite
│
├── dockerfile                  # Docker build configuration
├── entrypoint.sh               # Container startup script
├── artisan                     # Laravel CLI
├── composer.json               # PHP dependencies
├── package.json                # Node.js dependencies
└── vite.config.js              # Vite bundler configuration
```

---

## Credits & License

**Developer:** Nathanael Anak Chulif @ Douglas Chulip  
**Supervisor** Miss Tay Lee Chee
**Institution:** Universiti Malaysia Sarawak (UNIMAS)  
**Project Type:** Final Year Project (FYP)  

---

**License:** All rights reserved. This project is submitted as part of an academic programme. Redistribution, modification, or commercial use requires prior written permission from the author.

---

*For questions or support, please open an issue on the [GitHub repository](https://github.com/natd23111/Smart-Body-Composition-System).*
