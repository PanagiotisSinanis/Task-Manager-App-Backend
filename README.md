<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 📝 To-Do APP

A simple and functional task management application built with Laravel. Users can register, log in, and manage their personal to-do list by adding, editing, or deleting tasks. The app offers a clean UI using Bootstrap and secure authentication via Laravel middleware.

---

## 🚀 Features

- ✅ User registration & login
- 🗂️ Add / Edit / Delete tasks
- 🔍 Search tasks by title or description
- 🎯 Filter tasks by status (pending, completed)
- ⏰ Display task creation time in a human-friendly format (e.g. "2 hours ago")
- 📊 Task list enhanced with DataTables for pagination, sorting, and instant search
- 🎨 Responsive UI with Bootstrap 5
- 🔐 Route protection via Laravel middleware

---

## ⚙️ Technologies

- **PHP 8.x**
- **Laravel 10.x**
- **MySQL**
- **Bootstrap 5**
- **Blade Templating**
- **jQuery & DataTables**

---

## 🔧 Installation Instructions

```bash
git clone https://github.com/PanagiotisSinanis/To-Do-APP.git
cd To-Do-APP
composer install
cp .env.example .env
php artisan key:generate
# Edit your .env with DB credentials
php artisan migrate
npm install
npm run dev
php artisan serve
