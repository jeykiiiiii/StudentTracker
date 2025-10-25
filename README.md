# 🎓 StudentTracker — Student Database Management System

A PHP and MySQL-based web application that allows schools or instructors to manage student information, enrolled subjects, and assessments efficiently.  
It’s built with **Bootstrap 5** for a responsive, modern design and provides CRUD operations for student and subject data.

---

## 🚀 Features

### 👨‍🎓 Student Dashboard
- View and update profile information (course, year level, age, address, contact number)
- Display enrolled subjects in an accordion layout
- View and manage assessments such as quizzes and activities
- CRUD operations for subjects and assessments

### 👩‍🏫 Instructor / Admin Dashboard
- Manage students, subjects, and enrollment
- Add and grade assessments for each subject
- View and track student performance

### ⚙️ Core Functionalities
- Real-time data update using JavaScript (AJAX)
- MySQL-backed dynamic data management
- Session-based user access control
- Clean PHP logic (no PDO)
- Responsive Bootstrap UI

---

## 🗃️ Database Structure

**Database Name:** `student_db`

### 📄 Tables Overview

#### `students`
| Column | Type | Description |
|--------|------|-------------|
| student_id | INT | Primary Key |
| full_name | VARCHAR(100) | Student’s name |
| course | VARCHAR(50) | Course name or code |
| year_level | VARCHAR(10) | e.g., 1st Year, 2nd Year |
| age | INT | Student’s age |
| address | TEXT | Home address |
| contact_number | VARCHAR(20) | Student’s contact info |

#### `subjects`
| Column | Type | Description |
|--------|------|-------------|
| subject_id | INT | Primary Key |
| subject_name | VARCHAR(100) | Name of subject |
| instructor_name | VARCHAR(100) | Assigned instructor |

#### `student_subjects`
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary Key |
| student_id | INT | Foreign Key from `students` |
| subject_id | INT | Foreign Key from `subjects` |

#### `assessments`
| Column | Type | Description |
|--------|------|-------------|
| assessment_id | INT | Primary Key |
| subject_id | INT | Foreign Key from `subjects` |
| title | VARCHAR(100) | Assessment title |
| score | INT | Student’s score |

---

## 🧩 Technologies Used

| Category | Technology |
|-----------|-------------|
| Frontend | HTML5, CSS3, JavaScript, Bootstrap 5 |
| Backend | PHP |
| Database | MySQL |
| Server | XAMPP (Apache) |
| Version Control | Git & GitHub |

---

## ⚙️ Installation Guide

1. **Clone the Repository**
   ```bash
   git clone https://github.com/jeykiiiiii/StudentTracker.git
