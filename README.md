# WP Profile View Logs

Track and display administrator profile view history for WordPress users.

WP Profile View Logs automatically records every administrator (or authorized user) who opens a user's profile page inside the WordPress dashboard. Each visit is securely logged and displayed directly inside the user's profile page.

---

## Features

- Automatically logs profile views
- Records viewer name
- Records viewer User ID
- Records visit date and time
- Stores visitor IP address
- Optional User-Agent logging
- Duplicate view protection
- Automatically keeps the latest 50 records
- Uses native WordPress User Meta
- No custom database tables
- Lightweight and fast
- No configuration required
- WordPress Coding Standards compatible

---

## Requirements

- WordPress 6.0+
- PHP 7.4+

---

## Installation

1. Download or clone this repository.

2. Upload the plugin to:

`/wp-content/plugins/`

3. Activate the plugin from the WordPress Plugins page.

---

## How It Works

Every time an administrator (or another authorized user) opens a user's profile page, the plugin automatically records the visit.

Each log contains:

- Viewer Name
- Viewer User ID
- Visit Date & Time
- IP Address
- User Agent (optional)

Duplicate visits from the same administrator within 60 seconds are ignored automatically.

The newest records always appear at the top of the list.

---

## Data Storage

This plugin stores logs using the native WordPress User Meta API.

**Meta Key**

`_wp_profile_view_logs`

Maximum stored records:

`50`

---

## Development

### Coding Standards

- WordPress Coding Standards (WPCS)
- Secure capability validation
- Sanitized input
- Escaped output
- Native WordPress APIs
- Lightweight architecture

### Hooks

- `load-user-edit.php`
- `show_user_profile`
- `edit_user_profile`

### Future Improvements

- CSV Export
- Search & Filtering
- Activity Dashboard
- Email Notifications
- Automatic Cleanup
- REST API
- Multisite Support

---

## License

GPL-2.0-or-later

---

## Author

**Amirreza Shayesteh Far**

GitHub

https://github.com/amirrezashf

---

# افزونه ثبت تاریخچه بازدید پروفایل کاربران

این افزونه تاریخچه بازدید از پروفایل کاربران را در بخش مدیریت وردپرس ثبت و نمایش می‌دهد.

هر زمان مدیر یا کاربری که دسترسی لازم را داشته باشد صفحه پروفایل یک کاربر را مشاهده کند، اطلاعات بازدید به‌صورت خودکار ذخیره شده و در همان صفحه پروفایل نمایش داده می‌شود.

---

## امکانات

- ثبت خودکار بازدید پروفایل کاربران
- ثبت نام بازدیدکننده
- ثبت شناسه کاربر
- ثبت تاریخ و ساعت بازدید
- ثبت IP بازدیدکننده
- امکان ثبت User Agent
- جلوگیری از ثبت بازدیدهای تکراری
- نگهداری آخرین ۵۰ رکورد
- استفاده از User Meta
- بدون ایجاد جدول جدید در دیتابیس
- سبک و سریع
- بدون نیاز به تنظیمات

---

## پیش‌نیازها

- وردپرس ۶ یا بالاتر
- PHP 7.4 یا بالاتر

---

## نصب

1. مخزن را دانلود یا Clone کنید.

2. پوشه افزونه را در مسیر زیر قرار دهید:

`/wp-content/plugins/`

3. افزونه را از بخش افزونه‌های وردپرس فعال کنید.

---

## نحوه عملکرد

با هر بار مشاهده صفحه پروفایل یک کاربر:

- اطلاعات بازدید ثبت می‌شود.
- بازدیدهای تکراری در کمتر از ۶۰ ثانیه ثبت نمی‌شوند.
- آخرین بازدیدها در ابتدای لیست قرار می‌گیرند.
- تنها ۵۰ رکورد آخر نگهداری می‌شود.

هر لاگ شامل اطلاعات زیر است:

- نام بازدیدکننده
- شناسه کاربر
- تاریخ و ساعت
- IP
- User Agent (اختیاری)

---

## محل ذخیره اطلاعات

این افزونه اطلاعات را با استفاده از User Meta ذخیره می‌کند.

**کلید ذخیره‌سازی**

`_wp_profile_view_logs`

حداکثر تعداد رکورد:

`50`

---

## توسعه

### استانداردهای توسعه

- WordPress Coding Standards
- رعایت اصول امنیتی وردپرس
- استفاده از Hookهای استاندارد
- استفاده از APIهای داخلی وردپرس
- معماری سبک و قابل توسعه

### Hookهای استفاده‌شده

- `load-user-edit.php`
- `show_user_profile`
- `edit_user_profile`

### برنامه‌های آینده

- خروجی CSV
- جستجو و فیلتر
- داشبورد آماری
- اعلان ایمیلی
- پاکسازی خودکار
- REST API
- پشتیبانی از Multisite

---

## مجوز

GPL-2.0-or-later

---

## توسعه‌دهنده

**Amirreza Shayesteh Far**

GitHub

https://github.com/amirrezashf
