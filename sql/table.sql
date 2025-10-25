USE student_dbms;
-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    id_number VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    role ENUM('student', 'instructor') NOT NULL,
    image LONGBLOB,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Students table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    course VARCHAR(100) NOT NULL,
    year_level INT NOT NULL,
    age INT,
    address VARCHAR(255),
    contact_number VARCHAR(15), -- Fixed the missing parenthesis here
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Instructors table
CREATE TABLE instructors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    department VARCHAR(100) NOT NULL,
    age INT,
    address VARCHAR(255),
    contact_number VARCHAR(15), -- Fixed the missing parenthesis here
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Classes table
CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    instructor_id INT NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    schedule VARCHAR(100) NOT NULL,
    FOREIGN KEY (instructor_id) REFERENCES instructors(id) ON DELETE CASCADE
);

-- Enrollments table
CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
);

-- Assessments table (Quizzes, Activities, Exams)
CREATE TABLE assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    type ENUM('quiz', 'activity', 'exam') NOT NULL,
    total_score INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
);

-- Student Scores table
CREATE TABLE student_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    assessment_id INT NOT NULL,
    score INT NOT NULL,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE CASCADE
);

