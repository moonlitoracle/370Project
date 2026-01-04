-- Seed Data for Career Roadmap Application
-- Run this after setting up the database schema

-- ==================== SKILLS ====================
INSERT INTO skills (skill_id, name, description) VALUES
(1, 'HTML/CSS', 'Markup and styling languages for building web pages'),
(2, 'JavaScript', 'Programming language for interactive web functionality'),
(3, 'Python', 'Versatile programming language for web, data science, and automation'),
(4, 'SQL', 'Database query language for managing relational databases'),
(5, 'React', 'JavaScript library for building user interfaces'),
(6, 'Node.js', 'JavaScript runtime for server-side development'),
(7, 'Git', 'Version control system for tracking code changes'),
(8, 'Data Analysis', 'Skills for extracting insights from data'),
(9, 'Machine Learning', 'AI techniques for predictive modeling'),
(10, 'UI/UX Design', 'User interface and experience design principles'),
(11, 'Figma', 'Collaborative design tool for UI/UX'),
(12, 'Photoshop', 'Image editing and graphic design software'),
(13, 'Java', 'Object-oriented programming language for enterprise applications'),
(14, 'Django', 'Python web framework for rapid development'),
(15, 'Flask', 'Lightweight Python web framework'),
(16, 'REST API', 'Architectural style for web services'),
(17, 'Docker', 'Containerization platform for deployment'),
(18, 'AWS', 'Cloud computing platform by Amazon'),
(19, 'Cybersecurity', 'Protecting systems and networks from digital attacks'),
(20, 'Penetration Testing', 'Ethical hacking to find security vulnerabilities'),
(21, 'Data Visualization', 'Presenting data graphically for insights'),
(22, 'Communication', 'Effective verbal and written communication skills'),
(23, 'Project Management', 'Planning and executing projects successfully'),
(24, 'Problem Solving', 'Analytical thinking and troubleshooting');

-- ==================== CAREERS ====================
INSERT INTO careers (career_id, name, overview) VALUES
(1, 'Full-Stack Web Developer', 'Build complete web applications from front-end to back-end. Master both client and server-side technologies to create dynamic, responsive websites.'),
(2, 'Frontend Developer', 'Specialize in creating user interfaces and client-side functionality. Focus on HTML, CSS, JavaScript, and modern frameworks like React.'),
(3, 'Backend Developer', 'Work on server-side logic, databases, and APIs. Build the backbone that powers web applications.'),
(4, 'Data Scientist', 'Analyze complex data sets to extract insights and build predictive models. Combine statistics, programming, and domain knowledge.'),
(5, 'UI/UX Designer', 'Design intuitive and visually appealing user experiences. Focus on user research, wireframing, prototyping, and visual design.'),
(6, 'DevOps Engineer', 'Bridge development and operations teams. Automate deployment, manage infrastructure, and ensure system reliability.'),
(7, 'Mobile App Developer', 'Create applications for iOS and Android platforms. Build native or cross-platform mobile experiences.'),
(8, 'Cybersecurity Analyst', 'Protect organizations from cyber threats. Implement security measures and respond to security incidents.'),
(9, 'Machine Learning Engineer', 'Build and deploy ML models at scale. Apply algorithms to solve real-world problems.'),
(10, 'Product Manager', 'Guide product development from conception to launch. Balance user needs, business goals, and technical constraints.');

-- ==================== CAREER-SKILL MAPPINGS ====================
-- Full-Stack Web Developer
INSERT INTO career_skills (career_id, skill_id, level) VALUES
(1, 1, 'Advanced'),      -- HTML/CSS
(1, 2, 'Advanced'),      -- JavaScript
(1, 5, 'Advanced'),      -- React
(1, 6, 'Advanced'),      -- Node.js
(1, 4, 'Intermediate'),  -- SQL
(1, 7, 'Intermediate'),  -- Git
(1, 16, 'Advanced'),     -- REST API
(1, 17, 'Intermediate'), -- Docker
(1, 24, 'Advanced');     -- Problem Solving

-- Frontend Developer
INSERT INTO career_skills (career_id, skill_id, level) VALUES
(2, 1, 'Expert'),        -- HTML/CSS
(2, 2, 'Expert'),        -- JavaScript
(2, 5, 'Advanced'),      -- React
(2, 10, 'Intermediate'), -- UI/UX Design
(2, 7, 'Intermediate'),  -- Git
(2, 24, 'Advanced');     -- Problem Solving

-- Backend Developer
INSERT INTO career_skills (career_id, skill_id, level) VALUES
(3, 6, 'Advanced'),      -- Node.js
(3, 3, 'Advanced'),      -- Python
(3, 13, 'Advanced'),     -- Java
(3, 14, 'Intermediate'), -- Django
(3, 4, 'Advanced'),      -- SQL
(3, 16, 'Expert'),       -- REST API
(3, 7, 'Intermediate'),  -- Git
(3, 17, 'Advanced'),     -- Docker
(3, 24, 'Advanced');     -- Problem Solving

-- Data Scientist
INSERT INTO career_skills (career_id, skill_id, level) VALUES
(4, 3, 'Expert'),        -- Python
(4, 4, 'Advanced'),      -- SQL
(4, 8, 'Expert'),        -- Data Analysis
(4, 9, 'Advanced'),      -- Machine Learning
(4, 21, 'Advanced'),     -- Data Visualization
(4, 24, 'Expert'),       -- Problem Solving
(4, 22, 'Intermediate'); -- Communication

-- UI/UX Designer
INSERT INTO career_skills (career_id, skill_id, level) VALUES
(5, 10, 'Expert'),       -- UI/UX Design
(5, 11, 'Advanced'),     -- Figma
(5, 12, 'Advanced'),     -- Photoshop
(5, 1, 'Intermediate'),  -- HTML/CSS (helpful)
(5, 22, 'Advanced'),     -- Communication
(5, 24, 'Advanced');     -- Problem Solving

-- DevOps Engineer
INSERT INTO career_skills (career_id, skill_id, level) VALUES
(6, 7, 'Advanced'),      -- Git
(6, 17, 'Expert'),       -- Docker
(6, 18, 'Advanced'),     -- AWS
(6, 3, 'Intermediate'),  -- Python (scripting)
(6, 4, 'Intermediate'),  -- SQL
(6, 24, 'Advanced');     -- Problem Solving

-- Mobile App Developer
INSERT INTO career_skills (career_id, skill_id, level) VALUES
(7, 2, 'Advanced'),      -- JavaScript
(7, 5, 'Advanced'),      -- React (React Native)
(7, 13, 'Advanced'),     -- Java (Android)
(7, 7, 'Intermediate'),  -- Git
(7, 24, 'Advanced'),     -- Problem Solving
(7, 10, 'Intermediate'); -- UI/UX Design

-- Cybersecurity Analyst
INSERT INTO career_skills (career_id, skill_id, level) VALUES
(8, 19, 'Expert'),       -- Cybersecurity
(8, 20, 'Advanced'),     -- Penetration Testing
(8, 3, 'Intermediate'),  -- Python (automation)
(8, 24, 'Expert'),       -- Problem Solving
(8, 22, 'Advanced');     -- Communication

-- Machine Learning Engineer
INSERT INTO career_skills (career_id, skill_id, level) VALUES
(9, 3, 'Expert'),        -- Python
(9, 9, 'Expert'),        -- Machine Learning
(9, 8, 'Advanced'),      -- Data Analysis
(9, 4, 'Advanced'),      -- SQL
(9, 17, 'Intermediate'), -- Docker
(9, 24, 'Expert');       -- Problem Solving

-- Product Manager
INSERT INTO career_skills (career_id, skill_id, level) VALUES
(10, 22, 'Expert'),      -- Communication
(10, 23, 'Expert'),      -- Project Management
(10, 10, 'Advanced'),    -- UI/UX Design
(10, 24, 'Expert'),      -- Problem Solving
(10, 8, 'Intermediate'); -- Data Analysis

-- ==================== LEARNING RESOURCES ====================
-- HTML/CSS Resources
INSERT INTO resources (skill_id, name, type, url) VALUES
(1, 'MDN Web Docs - HTML', 'Documentation', 'https://developer.mozilla.org/en-US/docs/Web/HTML'),
(1, 'MDN Web Docs - CSS', 'Documentation', 'https://developer.mozilla.org/en-US/docs/Web/CSS'),
(1, 'CSS-Tricks', 'Blog', 'https://css-tricks.com/'),
(1, 'freeCodeCamp - Responsive Web Design', 'Course', 'https://www.freecodecamp.org/learn/responsive-web-design/');

-- JavaScript Resources
INSERT INTO resources (skill_id, name, type, url) VALUES
(2, 'JavaScript.info', 'Tutorial', 'https://javascript.info/'),
(2, 'MDN JavaScript Guide', 'Documentation', 'https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide'),
(2, 'Eloquent JavaScript (Book)', 'Book', 'https://eloquentjavascript.net/'),
(2, 'freeCodeCamp - JavaScript Algorithms', 'Course', 'https://www.freecodecamp.org/learn/javascript-algorithms-and-data-structures/');

-- Python Resources
INSERT INTO resources (skill_id, name, type, url) VALUES
(3, 'Python Official Tutorial', 'Tutorial', 'https://docs.python.org/3/tutorial/'),
(3, 'Real Python', 'Blog', 'https://realpython.com/'),
(3, 'Automate the Boring Stuff with Python', 'Book', 'https://automatetheboringstuff.com/'),
(3, 'Python for Everybody (Coursera)', 'Course', 'https://www.coursera.org/specializations/python');

-- SQL Resources
INSERT INTO resources (skill_id, name, type, url) VALUES
(4, 'SQLBolt - Interactive Tutorial', 'Tutorial', 'https://sqlbolt.com/'),
(4, 'Mode SQL Tutorial', 'Tutorial', 'https://mode.com/sql-tutorial/'),
(4, 'W3Schools SQL', 'Tutorial', 'https://www.w3schools.com/sql/');

-- React Resources
INSERT INTO resources (skill_id, name, type, url) VALUES
(5, 'Official React Documentation', 'Documentation', 'https://react.dev/'),
(5, 'React Tutorial for Beginners', 'Tutorial', 'https://react.dev/learn'),
(5, 'Full Stack Open - React', 'Course', 'https://fullstackopen.com/en/');

-- Git Resources
INSERT INTO resources (skill_id, name, type, url) VALUES
(7, 'Git Official Documentation', 'Documentation', 'https://git-scm.com/doc'),
(7, 'Learn Git Branching', 'Interactive', 'https://learngitbranching.js.org/'),
(7, 'GitHub Skills', 'Tutorial', 'https://skills.github.com/');

-- Data Science Resources
INSERT INTO resources (skill_id, name, type, url) VALUES
(8, 'Kaggle Learn', 'Course', 'https://www.kaggle.com/learn'),
(8, 'DataCamp - Data Analysis', 'Course', 'https://www.datacamp.com/'),
(9, 'Coursera - Machine Learning (Andrew Ng)', 'Course', 'https://www.coursera.org/learn/machine-learning'),
(9, 'Fast.ai - Practical Deep Learning', 'Course', 'https://course.fast.ai/');

-- UI/UX Design Resources
INSERT INTO resources (skill_id, name, type, url) VALUES
(10, 'Nielsen Norman Group Articles', 'Blog', 'https://www.nngroup.com/articles/'),
(10, 'Google UX Design Certificate', 'Course', 'https://www.coursera.org/professional-certificates/google-ux-design'),
(11, 'Figma Official Tutorials', 'Tutorial', 'https://www.figma.com/resources/learn-design/');

-- DevOps Resources
INSERT INTO resources (skill_id, name, type, url) VALUES
(17, 'Docker Official Docs', 'Documentation', 'https://docs.docker.com/get-started/'),
(17, 'Docker for Beginners', 'Course', 'https://docker-curriculum.com/'),
(18, 'AWS Training and Certification', 'Course', 'https://aws.amazon.com/training/');

-- Cybersecurity Resources
INSERT INTO resources (skill_id, name, type, url) VALUES
(19, 'Cybrary - Free Cybersecurity Training', 'Course', 'https://www.cybrary.it/'),
(19, 'OWASP Top 10', 'Documentation', 'https://owasp.org/www-project-top-ten/'),
(20, 'HackTheBox - Penetration Testing', 'Practice', 'https://www.hackthebox.com/');

-- Soft Skills Resources
INSERT INTO resources (skill_id, name, type, url) VALUES
(22, 'Coursera - Effective Communication', 'Course', 'https://www.coursera.org/learn/wharton-communication-skills'),
(23, 'Google Project Management Certificate', 'Course', 'https://www.coursera.org/professional-certificates/google-project-management');


-- ===================================================
-- PROFICIENCY TESTING SYSTEM - FIXED SCHEMA & SEED DATA  
-- ===================================================
-- Run this AFTER importing your main seed_data.sql

-- Drop existing tables if they exist (to reset)
DROP TABLE IF EXISTS test_attempts;
DROP TABLE IF EXISTS test_questions;
DROP TABLE IF EXISTS proficiency_tests;

-- Table: proficiency_tests
CREATE TABLE proficiency_tests (
    test_id INT PRIMARY KEY AUTO_INCREMENT,
    skill_id INT NOT NULL,
    required_level ENUM('Beginner', 'Intermediate', 'Advanced', 'Expert') NOT NULL,
    test_title VARCHAR(255) NOT NULL,
    passing_score INT DEFAULT 70,
    time_limit_minutes INT DEFAULT 30,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (skill_id) REFERENCES skills(skill_id) ON DELETE CASCADE,
    UNIQUE KEY unique_skill_level (skill_id, required_level)
);

-- Table: test_questions
CREATE TABLE test_questions (
    question_id INT PRIMARY KEY AUTO_INCREMENT,
    test_id INT NOT NULL,
    question_text TEXT NOT NULL,
    option_a VARCHAR(500) NOT NULL,
    option_b VARCHAR(500) NOT NULL,
    option_c VARCHAR(500) NOT NULL,
    option_d VARCHAR(500) NOT NULL,
    correct_answer ENUM('A', 'B', 'C', 'D') NOT NULL,
    points INT DEFAULT 10,
    FOREIGN KEY (test_id) REFERENCES proficiency_tests(test_id) ON DELETE CASCADE
);

-- Table: test_attempts
CREATE TABLE test_attempts (
    attempt_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    test_id INT NOT NULL,
    score INT NOT NULL,
    total_points INT NOT NULL,
    passed BOOLEAN NOT NULL,
    answers JSON,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (test_id) REFERENCES proficiency_tests(test_id) ON DELETE CASCADE
);

-- ===================================================
-- INSERT TESTS FOR ALL SKILLS (using skill names to find IDs)
-- ===================================================

-- Get JavaScript skill_id and create tests
INSERT INTO proficiency_tests (skill_id, required_level, test_title, passing_score)
SELECT skill_id, 'Intermediate', 'JavaScript DOM & Events', 70
FROM skills WHERE name = 'JavaScript';

-- Create variables for the test we just inserted
SET @js_intermediate_test = LAST_INSERT_ID();

-- Insert questions for JavaScript Intermediate
INSERT INTO test_questions (test_id, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES
(@js_intermediate_test, 'What method is used to select an element by ID?', 'getElementById()', 'querySelector()', 'selectById()', 'getElement()', 'A'),
(@js_intermediate_test, 'Which event occurs when a user clicks on an HTML element?', 'onclick', 'onmouseclick', 'onpress', 'onhit', 'A'),
(@js_intermediate_test, 'What does addEventListener() do?', 'Attaches an event handler to an element', 'Adds a new element', 'Creates an event', 'Removes an element', 'A'),
(@js_intermediate_test, 'What is the purpose of preventDefault()?', 'Stops the default action of an event', 'Prevents errors', 'Stops propagation', 'Validates data', 'A'),
(@js_intermediate_test, 'How do you access the value of an input field?', 'element.value', 'element.text', 'element.content', 'element.data', 'A');

-- Python tests
INSERT INTO proficiency_tests (skill_id, required_level, test_title, passing_score)
SELECT skill_id, 'Intermediate', 'Python OOP & Modules', 70
FROM skills WHERE name = 'Python';

SET @py_intermediate_test = LAST_INSERT_ID();

INSERT INTO test_questions (test_id, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES
(@py_intermediate_test, 'How do you define a class in Python?', 'class MyClass:', 'def MyClass:', 'new MyClass:', 'create MyClass:', 'A'),
(@py_intermediate_test, 'What is __init__ used for?', 'Constructor method', 'Destructor', 'Static method', 'Class method', 'A'),
(@py_intermediate_test, 'How do you import a module?', 'import module_name', 'include module_name', 'require module_name', 'load module_name', 'A'),
(@py_intermediate_test, 'What does self refer to?', 'Current instance of the class', 'The class itself', 'Parent class', 'Global variable', 'A'),
(@py_intermediate_test, 'How do you inherit from a parent class?', 'class Child(Parent):', 'class Child extends Parent:', 'class Child inherits Parent:', 'class Child : Parent:', 'A');

-- SQL tests  
INSERT INTO proficiency_tests (skill_id, required_level, test_title, passing_score)
SELECT skill_id, 'Intermediate', 'SQL Joins & Subqueries', 70
FROM skills WHERE name = 'SQL';

SET @sql_intermediate_test = LAST_INSERT_ID();

INSERT INTO test_questions (test_id, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES
(@sql_intermediate_test, 'What type of JOIN returns all rows from both tables?', 'FULL OUTER JOIN', 'INNER JOIN', 'LEFT JOIN', 'RIGHT JOIN', 'A'),
(@sql_intermediate_test, 'What is a subquery?', 'Query nested inside another query', 'Query with multiple tables', 'Query with GROUP BY', 'Query with ORDER BY', 'A'),
(@sql_intermediate_test, 'Which JOIN returns only matching rows?', 'INNER JOIN', 'LEFT JOIN', 'RIGHT JOIN', 'CROSS JOIN', 'A'),
(@sql_intermediate_test, 'What does LEFT JOIN do?', 'Returns all rows from left table', 'Returns all rows from right table', 'Returns only matching rows', 'Returns random rows', 'A'),
(@sql_intermediate_test, 'Where can a subquery be placed?', 'SELECT, FROM, or WHERE clause', 'Only in SELECT', 'Only in WHERE', 'Only in FROM', 'A');

-- HTML/CSS tests
INSERT INTO proficiency_tests (skill_id, required_level, test_title, passing_score)
SELECT skill_id, 'Intermediate', 'HTML/CSS Layout & Styling', 70
FROM skills WHERE name = 'HTML/CSS';

SET @html_intermediate_test = LAST_INSERT_ID();

INSERT INTO test_questions (test_id, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES
(@html_intermediate_test, 'What CSS property creates flexbox layout?', 'display: flex', 'display: block', 'display: inline', 'display: grid', 'A'),
(@html_intermediate_test, 'How do you center an element horizontally with margin?', 'margin: 0 auto', 'margin: auto 0', 'margin: center', 'margin: middle', 'A'),
(@html_intermediate_test, 'What is the box model order from inside out?', 'Content, Padding, Border, Margin', 'Margin, Border, Padding, Content', 'Border, Padding, Content, Margin', 'Padding, Content, Border, Margin', 'A'),
(@html_intermediate_test, 'Which position value removes element from document flow?', 'absolute', 'relative', 'static', 'sticky', 'A'),
(@html_intermediate_test, 'What property controls stacking order?', 'z-index', 'stack-order', 'layer', 'depth', 'A');

-- Cybersecurity tests
INSERT INTO proficiency_tests (skill_id, required_level, test_title, passing_score)
SELECT skill_id, 'Intermediate', 'Cybersecurity Fundamentals', 70
FROM skills WHERE name = 'Cybersecurity';

SET @cyber_intermediate_test = LAST_INSERT_ID();

INSERT INTO test_questions (test_id, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES
(@cyber_intermediate_test, 'What is SQL injection?', 'Inserting malicious SQL into queries', 'A database backup method', 'A type of encryption', 'A network protocol', 'A'),
(@cyber_intermediate_test, 'What does HTTPS provide?', 'Encrypted communication', 'Faster loading', 'Better SEO', 'More storage', 'A'),
(@cyber_intermediate_test, 'What is a firewall?', 'Network security system', 'Antivirus software', 'Password manager', 'Backup system', 'A'),
(@cyber_intermediate_test, 'What is phishing?', 'Fraudulent attempt to obtain sensitive info', 'A type of malware', 'Network scanning', 'Data encryption', 'A'),
(@cyber_intermediate_test, 'What does XSS stand for?', 'Cross-Site Scripting', 'Extra Secure System', 'External Security Service', 'Extended Session Storage', 'A');

SELECT 'Proficiency tests schema and data imported successfully!' AS Status;
