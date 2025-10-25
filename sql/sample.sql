USE student_dbms;

-- Insert sample users (students and instructors)
INSERT INTO users (name, id_number, password, email, role, image) VALUES
('Jake Marlon Destura', '202311169', '$2y$10$az4r.7ivBLGXlj2dZyO9euhM21HflcqQxT8uq2JLTe41WXqt6RcZu', 'desturajakemarlon@gmail.com', 'student', ''),
('Jae Mojas', 'Mojas123', '$2y$10$az4r.7ivBLGXlj2dZyO9euhM21HflcqQxT8uq2JLTe41WXqt6RcZu', 'jane@example.com', 'instructor', 'jane.jpg'),
('Dhan Belgica', 'Dhan123', '$2y$10$az4r.7ivBLGXlj2dZyO9euhM21HflcqQxT8uq2JLTe41WXqt6RcZu', 'bob@example.com', 'instructor', 'bob.jpg');

-- Insert sample students
INSERT INTO students (user_id, course, year_level, age, address, contact_number) VALUES
(1, 'Computer Science', 2, 20, '123 Main St', '1234567890');

-- Insert sample instructors
INSERT INTO instructors (user_id, department, age, address, contact_number) VALUES
(2, 'Computer Studies', 24, 'Bacoor City', '09123456789'),
(3, 'Computer Studies', 25, 'Bacoor City', '09234567891');

-- Insert sample classes
INSERT INTO classes (instructor_id, subject_name, schedule) VALUES
(2, 'Object Oriented Programming', 'Monday 10:00 AM - 12:00 PM'),
(3, 'Advanced Database Management System', 'Wednesday 2:00 PM - 4:00 PM');

-- Insert sample enrollments
INSERT INTO enrollments (student_id, class_id) VALUES
(1, 1),
(1, 2);

-- Insert sample assessments (quiz, activity, exam)
INSERT INTO assessments (class_id, title, type, total_score) VALUES
(1, 'Exam: Classes And Objects', 'exam', 70),
(1, 'Activity: Laboratory 1', 'activity', 100),
(1, 'Quiz: Quiz 1', 'quiz', 30),
(2, 'Activity: ER Diagram', 'activity', 100),
(2, 'Quiz 1: SQL Basics', 'quiz', 30),
(2, 'Exam: Midterms Exam', 'exam', 70);

-- Insert sample student scores
INSERT INTO student_scores (student_id, assessment_id, score) VALUES
(1, 1, 60),
(1, 2, 87),
(1, 3, 30),
(1, 4, 85),
(1, 5, 24),
(1, 6, 60);
