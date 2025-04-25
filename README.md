# 🎓 Nova Dev Student Coaching App

A local web application that helps manage student sign-ups, logins, admin-controlled course entries, and student course selections — all powered with PHP and MySQL.

## 📁 Features

### 👨‍🎓 User Panel
- Login / Sign-Up forms with client-side validation
- User homepage includes:
  - Ders seçimi (Course Selection)
  - Seçilen dersleri görme
  - Ders silme
  - Duyurular & Mesaj atma (placeholders)

### 🛠️ Admin Panel
- Admin-only login via a fixed admin email
- Add courses with:
  - Course Code
  - Course Name
  - Department
  - Teacher
- View & delete all courses via popup
- Responsive UI with real-time feedback messages

### 📊 Database Tables
- `users`: Stores student and admin credentials
- `courses`: Stores added course details by the admin
- `selected_courses`: Tracks which user selected which course

## 💻 Tech Stack

| Frontend         | Backend        | Database    |
|------------------|----------------|-------------|
| HTML + CSS + JS  | PHP (XAMPP)    | MySQL       |

## 🚀 Getting Started

### 🔧 Requirements
- [XAMPP](https://www.apachefriends.org/index.html)
- Modern browser (Chrome, Edge, etc.)

### 🧪 Setup Instructions

1. ✅ Clone the repository or copy the project folder to: C:\xampp\htdocs\student-coaching-app
 
2. ✅ Start **Apache** and **MySQL** via XAMPP Control Panel.

3. ✅ Go to `http://localhost/phpmyadmin`  
- Create a database: `student_db`
- Import or create the following tables:
  - `users`
  - `courses`
  - `selected_courses`

4. ✅ Access the app in your browser: http://localhost/student-coaching-app/index.html

### ⚙️ Admin Credentials (Hardcoded)
- Email: `admin@novadev.com`
- Password: (set manually in phpMyAdmin)


## 🧠 Future Ideas

- Session-based authentication
- Upload syllabus/grades
- Messaging system for students & teachers
- Mobile-responsive improvements

---

© 2025 Nova Dev | Made with ❤️ in Turkey
