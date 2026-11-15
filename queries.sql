-- Populating table with 2 CVs.

INSERT INTO cvs (id, name, email, password, keyprogramming, profile, education, URLlinks) VALUES
(1, 'John Smith', 'john.smith@example.com', 'password123', 'JavaScript, Python, React, Node.js', 'Full-stack developer with 5+ years of experience building web applications. Passionate about clean code and user-centric design.', 'BSc Computer Science, University of Manchester (2017); MSc Software Engineering, University College London (2019)', 'https://github.com/johnsmith, https://linkedin.com/in/johnsmith, https://johnsmith.dev'),
(2, 'Sarah Johnson', 'sarah.johnson@example.com', 'password456', 'PHP, Laravel, MySQL, Vue.js', 'Backend developer specializing in PHP frameworks and database optimization. Strong focus on security and scalability.', 'BEng Software Engineering, University of Bristol (2018); Full Stack Web Development Certificate, Code Institute (2020)', 'https://github.com/sarahjohnson, https://linkedin.com/in/sarahjohnson, https://sarahjohnson.tech');

ALTER TABLE cvs AUTO_INCREMENT = 3;