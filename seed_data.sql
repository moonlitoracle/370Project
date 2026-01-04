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
