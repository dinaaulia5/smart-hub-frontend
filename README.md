# Smart Hub Management System

## Summary

Smart Hub Management System adalah aplikasi berbasis web yang digunakan untuk mengelola peminjaman ruangan dan peralatan secara terintegrasi. Aplikasi ini menyediakan fitur autentikasi pengguna, manajemen master data, proses booking, check-in, serta dashboard statistik sehingga memudahkan administrator maupun pengguna dalam mengelola aktivitas peminjaman.

---

# Technology Stack

## Backend

- Laravel 13
- PHP 8.3
- Laravel Sanctum (Token Authentication)
- RESTful API
- UUID

## Frontend

- Vue 3
- Inertia.js
- Vite
- Tailwind CSS
- shadcn-vue
- Axios

## Database

- PostgreSQL
- Supabase

## Tools

- Visual Studio Code
- Git
- GitHub
- Postman

---

# Application Flow

1. Pengguna melakukan login menggunakan email dan password.
2. Sistem melakukan autentikasi menggunakan Laravel Sanctum dan menghasilkan Bearer Token.
3. Token disimpan pada browser (Local Storage).
4. Setelah berhasil login pengguna diarahkan ke halaman Dashboard.
5. Dashboard menampilkan informasi statistik sesuai dengan role pengguna.
6. Administrator dapat mengelola Category, Equipment, Room, dan Booking.
7. User dapat melakukan booking ruangan maupun peralatan.
8. User melakukan proses Check In atau Cancel Booking.
9. Seluruh proses CRUD dilakukan melalui REST API Laravel.
10. Database menggunakan PostgreSQL yang di-host pada Supabase.

---

# System Architecture

```
                +-------------------+
                |   Vue 3 + Inertia |
                |    Tailwind CSS   |
                +---------+---------+
                          |
                     Axios REST API
                          |
                +---------v---------+
                |    Laravel 13     |
                |   RESTful API      |
                | Laravel Sanctum    |
                +---------+---------+
                          |
                     PostgreSQL
                       Supabase
```

---

# Features

## Authentication

- Login
- Logout
- Token Authentication (Laravel Sanctum)

## Dashboard

- Dashboard Admin
- Dashboard User
- Booking Statistics
- Check In Statistics
- Master Data Statistics

## Master Data

### Category

- Create Category
- Edit Category
- Delete Category
- Search Category

### Equipment

- Create Equipment
- Edit Equipment
- Delete Equipment
- Search Equipment

### Room

- Create Room
- Edit Room
- Delete Room
- Search Room

## Booking

- Create Booking
- Edit Booking
- Cancel Booking
- Detail Booking

## Check In

- Check In Booking
- Booking History

---

# REST API

Authentication menggunakan Laravel Sanctum dengan Bearer Token.

Contoh Header

Authorization

```
Bearer {token}
```

Endpoint utama

```
POST   /api/login

POST   /api/logout

GET    /api/categories
POST   /api/categories
GET    /api/categories/{id}
PUT    /api/categories/{id}
DELETE /api/categories/{id}

GET    /api/equipments
POST   /api/equipments
PUT    /api/equipments/{id}
DELETE /api/equipments/{id}

GET    /api/rooms
POST   /api/rooms
PUT    /api/rooms/{id}
DELETE /api/rooms/{id}

GET    /api/bookings
POST   /api/bookings
PUT    /api/bookings/{id}
DELETE /api/bookings/{id}
```

---

# Folder Structure

```
app
├── Http
├── Models
├── Services
├── Supports

resources
├── js
│   ├── Components
│   ├── Layouts
│   ├── Pages
│   ├── Services
│   └── Utils

routes
├── api.php
├── web.php
```

---

# Database

Database menggunakan PostgreSQL yang di-host pada Supabase.

Migration menggunakan UUID sebagai Primary Key.

Contoh tabel

- users
- categories
- equipments
- rooms
- bookings

---

# Installation

Clone repository

```bash
git clone https://github.com/USERNAME/smart-hub-frontend.git
```

Masuk ke project

```bash
cd smart-hub-frontend
```

Install dependency PHP

```bash
composer install
```

Install dependency Node

```bash
npm install
```

Copy environment

```bash
cp .env.example .env
```

Generate key

```bash
php artisan key:generate
```

Migrasi database

```bash
php artisan migrate
```

Menjalankan aplikasi

```bash
php artisan serve
```

Frontend

```bash
npm run dev
```

---

# Environment

Contoh konfigurasi database

```
DB_CONNECTION=pgsql
DB_HOST=your-host
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.xxxxx
DB_PASSWORD=********
```

atau menggunakan

```
DATABASE_URL=postgresql://postgres.xxxxx:password@aws-1-ap-northeast-2.pooler.supabase.com:6543/postgres
```

---

# Validation

Seluruh form menggunakan validasi baik pada sisi frontend maupun backend.

Contoh:

- Required Field
- Unique Category Name
- Numeric Validation
- Booking Validation

---

# UI

Framework UI yang digunakan

- Tailwind CSS
- shadcn-vue
- Tabler Icons

---

# Version Control

Repository menggunakan Git dan GitHub.

Conventional Commit digunakan untuk setiap perubahan fitur.

Contoh

```
feat(category): implement category CRUD

fix(room): resolve validation issue

refactor(dashboard): improve statistics component
```

---

# Author

Dina Nur Auliana

Smart Hub Management System

Universitas Dian Nusantara/ UAS Fullstack Web Development
