# Welcome to the ReLoop Recycling Website Repository

Welcome to the official repository for the **ReLoop Recycling Website**, developed as part of the Work-Integrated Learning (WIL) 2026 project by **The Avengers** team.

This project is a PHP and MySQL-based recycling management website designed for **ReLoop Technologies SA**. The system supports user registration, recycling pickup scheduling, reward tracking, recycling guidance, and admin management features.

---

## Project Overview

ReLoop Technologies SA is a recycling-focused organisation that aims to improve recycling participation through digital technology. This website provides a platform where users can register, schedule recycling pickups, view their pickup history, track reward points, and access recycling information.

The system also includes an admin dashboard that allows administrators to manage users, pickups, reward items, and system reports.

---

## Main Features

### User Features

- User registration and login
- Secure password hashing
- User dashboard
- Schedule recycling pickups
- View pickup history
- View recycling guidance
- Track reward points
- Redeem available rewards
- Receive system notifications

### Admin Features

- Admin login
- Admin dashboard
- Manage users
- Manage pickup requests
- Update pickup statuses
- Manage reward items
- View system reports
- Track recycling activity

---

## Technologies Used

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- WampServer
- phpMyAdmin

---

## Project Folder Structure

```text
reloop/
│
├── index.php
├── about.php
├── services.php
├── contact.php
├── login.php
├── register.php
├── logout.php
├── dashboard.php
├── schedule_pickup.php
├── pickup_history.php
├── rewards.php
├── recycling_guidance.php
│
├── admin/
│   ├── admin_dashboard.php
│   ├── manage_users.php
│   ├── manage_pickups.php
│   ├── manage_rewards.php
│   └── reports.php
│
├── includes/
│   ├── db.php
│   ├── header.php
│   ├── footer.php
│   └── auth.php
│
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── script.js
│   └── images/
│
└── database/
    └── reloop_database.sql
