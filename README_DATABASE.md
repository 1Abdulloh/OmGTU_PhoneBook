# Ma'lumotlar bazasini sozlash

## XAMPP/MAMP/LAMP uchun

### 1-usul: phpMyAdmin orqali

1. Brauzerda oching: `http://localhost/phpmyadmin`
2. "SQL" tabiga o'ting
3. `database.sql` faylini ochib, barcha kodni nusxalang
4. phpMyAdmin SQL oynasiga yopishtiring va "Go" tugmasini bosing

### 2-usul: MySQL command line orqali

```bash
mysql -u root -p < database.sql
```

Yoki parol bo'lmasa:
```bash
mysql -u root < database.sql
```

### 3-usul: MySQL command line interaktiv

```bash
mysql -u root -p
```

Keyin MySQL da:
```sql
source database.sql
```

yoki:
```sql
CREATE DATABASE IF NOT EXISTS `oxirgi` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `oxirgi`;
-- va hokazo...
```

## Tekshirish

Ma'lumotlar bazasi yaratilganini tekshirish:

```sql
SHOW DATABASES;
USE oxirgi;
SHOW TABLES;
SELECT * FROM departments;
SELECT * FROM contacts;
SELECT * FROM users;
```

## Login ma'lumotlari

- **Username:** EgorVikulov
- **Password:** EgorOmGTU
- **Role:** admin

## Muammo bo'lsa

Agar xatolik bo'lsa, quyidagilarni tekshiring:

1. MySQL server ishlamoqdamimi?
2. `root` foydalanuvchisi parolga ega bo'lsa, `config/database.php` da parolni o'zgartiring
3. Ma'lumotlar bazasi allaqachon mavjud bo'lsa, avval o'chirib keyin yarating:

```sql
DROP DATABASE IF EXISTS oxirgi;
```

Keyin `database.sql` ni qayta ishga tushiring.
