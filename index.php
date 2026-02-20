<?php
/**
 * Ajay Roy - SEO Specialist & Digital Marketer
 * Main Website File
 */

require_once 'includes/config.php';

// Fetch data from database
$services_query = "SELECT * FROM services WHERE active=1 ORDER BY order_by ASC";
$services_result = $mysqli->query($services_query);

$portfolio_query = "SELECT * FROM portfolio WHERE active=1 ORDER BY order_by ASC LIMIT 6";
$portfolio_result = $mysqli->query($portfolio_query);

$skills_query = "SELECT * FROM skills WHERE active=1 ORDER BY order_by ASC";
$skills_result = $mysqli->query($skills_query);

$stats_query = "SELECT * FROM statistics ORDER BY stat_name ASC";
$stats_result = $mysqli->query($stats_query);

// Fetch site settings
$settings = array();
$settings_query = "SELECT setting_name, setting_value FROM site_settings";
$settings_result = $mysqli->query($settings_query);
if ($settings_result) {
    while ($row = $settings_result->fetch_assoc()) {
        $settings[$row['setting_name']] = $row['setting_value'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($settings['site_name']) ? htmlspecialchars($settings['site_name']) : 'Ajay Roy - Portfolio'; ?></title>
    <meta name="description" content="<?php echo isset($settings['site_description']) ? htmlspecialchars($settings['site_description']) : 'Professional SEO and Digital Marketing services'; ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background: #f4f4f4;
            color: #222;
            line-height: 1.6;
        }
        nav {
            background: #1a1a1a;
            color: #fff;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
        }
        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }
        .nav-links a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s;
        }
        .nav-links a:hover {
            color: #4CAF50;
        }
        .nav-links a.active {
            color: #4CAF50;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 0.5rem;
        }
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }
        .hamburger span {
            width: 25px;
            height: 3px;
            background: #fff;
            margin: 5px 0;
        }
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 4rem 2rem;
            text-align: center;
        }
        header h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        header p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        .btn:hover {
            background: #45a049;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 2rem;
        }
        .intro {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.8;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        .card {
            background: #fafafa;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            border-top: 4px solid #667eea;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .card h3 {
            color: #667eea;
            margin-bottom: 1rem;
            font-size: 1.4rem;
        }
        .card p {
            color: #555;
            line-height: 1.8;
        }
        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 3rem 2rem;
            text-align: center;
        }
        .section-header h2 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        section {
            scroll-margin-top: 70px;
        }
        #about, #services, #portfolio, #contact {
            background: #fff;
            padding: 3rem 0;
            margin: 2rem 0;
        }
        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: center;
        }
        .about-text h3 {
            color: #667eea;
            margin-bottom: 1rem;
        }
        .about-text p {
            margin-bottom: 1rem;
            line-height: 1.8;
        }
        .skills {
            margin-top: 2rem;
        }
        .skill-bar {
            margin-bottom: 1.5rem;
        }
        .skill-name {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        .progress-bar {
            background: #e0e0e0;
            border-radius: 10px;
            height: 8px;
            overflow: hidden;
        }
        .progress {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            height: 100%;
            border-radius: 10px;
        }
        .about-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            color: #fff;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }
        .stat-item {
            background: #fafafa;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
        }
        .stat-label {
            color: #666;
            margin-top: 0.5rem;
        }
        .contact-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
        }
        .contact-info h3 {
            color: #667eea;
            margin-bottom: 1.5rem;
        }
        .contact-item {
            margin-bottom: 2rem;
        }
        .contact-item h4 {
            color: #333;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .contact-item p {
            color: #666;
            font-size: 1.05rem;
        }
        .contact-item a {
            color: #667eea;
            text-decoration: none;
        }
        .contact-item a:hover {
            text-decoration: underline;
        }
        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: #667eea;
            color: #fff;
            border-radius: 50%;
            text-decoration: none;
            font-size: 1.2rem;
            transition: background 0.3s;
        }
        .social-links a:hover {
            background: #764ba2;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-family: 'Roboto', sans-serif;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        textarea {
            resize: vertical;
            min-height: 150px;
        }
        footer {
            text-align: center;
            padding: 2rem;
            background: #1a1a1a;
            color: #fff;
            margin-top: 3rem;
        }
        footer a {
            color: #4CAF50;
            text-decoration: none;
        }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }
            .nav-links {
                position: absolute;
                top: 60px;
                right: 0;
                background: #1a1a1a;
                flex-direction: column;
                width: 100%;
                text-align: center;
                gap: 1rem;
                padding: 1rem 0;
                display: none;
            }
            .nav-links.active {
                display: flex;
            }
            header h1 {
                font-size: 2rem;
            }
            .about-content {
                grid-template-columns: 1fr;
            }
            .contact-wrapper {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-container">
            <div class="logo">Ajay Roy</div>
            <ul class="nav-links" id="navLinks">
                <li><a href="#home" class="active">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#portfolio">Portfolio</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="admin/" style="background: #667eea; padding: 0.5rem 1rem; border-radius: 4px;">Admin</a></li>
            </ul>
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- HOME SECTION -->
    <section id="home">
        <header>
            <h1>I am Ajay Roy</h1>
            <p>Digital Marketer & SEO Specialist</p>
            <a href="tel:+9779745232233" class="btn">Hire Me Now!</a>
        </header>
    </section>

    <div class="container">
        <section class="intro">
            <h2>Welcome</h2>
            <p>I am an experienced SEO Specialist & SEO Expert in Nepal with Digital Marketing skills in driving organic traffic and optimizing online visibility. Proficient in keyword research, on-page/off-page optimization, and collaborative communication.</p>
            <p>Explore how I can help grow your online presence and achieve your business goals through strategic digital marketing solutions.</p>
        </section>
    </div>

    <!-- ABOUT SECTION -->
    <section id="about">
        <div class="section-header">
            <h2>About Me</h2>
        </div>
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h3>Who Am I?</h3>
                    <p>I'm Ajay Roy, an experienced SEO Specialist & Digital Marketing expert based in Nepal. With proven expertise in driving organic traffic, optimizing online visibility, and implementing strategic digital marketing campaigns, I help businesses achieve their online objectives.</p>
                    <p>My specialization includes comprehensive keyword research, on-page and off-page SEO optimization, digital marketing strategy, and collaborative communication with clients to ensure their business goals are met.</p>
                    <p>I believe in staying updated with the latest SEO trends and algorithm changes. When I'm not optimizing websites, you can find me analyzing market trends, implementing new strategies, or sharing knowledge about digital marketing best practices.</p>
                    
                    <div class="skills">
                        <h3>My Skills</h3>
                        <div id="skillsContainer">
                            <!-- Skills will be loaded here via JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="about-image">
                    <div>
                        <h3>Ajay Roy</h3>
                        <p>SEO Specialist & Digital Marketer</p>
                    </div>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="stats" id="statsContainer" style="margin-top: 3rem;">
                <!-- Stats will be loaded here via JavaScript -->
            </div>
        </div>
    </section>

    <!-- SERVICES SECTION -->
    <section id="services">
        <div class="section-header">
            <h2>My Services</h2>
            <p>Comprehensive solutions for your digital needs</p>
        </div>
        <div class="container">
            <div class="intro">
                <h3>What I Offer</h3>
                <p>I provide comprehensive SEO and digital marketing services tailored to boost your online visibility and drive quality traffic to your website.</p>
            </div>
            <div class="grid" id="servicesContainer">
                <!-- Services will be loaded here via JavaScript -->
            </div>
        </div>
    </section>

    <!-- PORTFOLIO SECTION -->
    <section id="portfolio">
        <div class="section-header">
            <h2>My Portfolio</h2>
            <p>Showcase of my recent projects and work</p>
        </div>
        <div class="container">
            <div class="intro">
                <h3>Featured Projects</h3>
                <p>Explore successful SEO and digital marketing campaigns that have driven results for businesses across various industries.</p>
            </div>
            <div class="grid" id="portfolioContainer">
                <!-- Portfolio items will be loaded here via JavaScript -->
            </div>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section id="contact">
        <div class="section-header">
            <h2>Get In Touch</h2>
            <p>Let's work together on your next project</p>
        </div>
        <div class="container">
            <div class="contact-wrapper">
                <div class="contact-info">
                    <h3>Contact Information</h3>
                    <div class="contact-item">
                        <h4>📧 Email</h4>
                        <p><a href="mailto:<?php echo isset($settings['admin_email']) ? htmlspecialchars($settings['admin_email']) : 'admin@rayajay.com.np'; ?>">
                            <?php echo isset($settings['admin_email']) ? htmlspecialchars($settings['admin_email']) : 'admin@rayajay.com.np'; ?>
                        </a></p>
                    </div>
                    <div class="contact-item">
                        <h4>📞 Phone</h4>
                        <p><a href="tel:<?php echo isset($settings['phone']) ? str_replace(' ', '', htmlspecialchars($settings['phone'])) : '+977 9745232233'; ?>">
                            <?php echo isset($settings['phone']) ? htmlspecialchars($settings['phone']) : '+977 9745232233'; ?>
                        </a></p>
                    </div>
                    <div class="contact-item">
                        <h4>📍 Address</h4>
                        <p><?php echo isset($settings['address']) ? htmlspecialchars($settings['address']) : 'Kathmandu, Nepal'; ?></p>
                    </div>
                    <div class="contact-item">
                        <h4>Connect With Me</h4>
                        <div class="social-links">
                            <?php if(isset($settings['instagram_url'])): ?>
                            <a href="<?php echo htmlspecialchars($settings['instagram_url']); ?>" target="_blank" title="Instagram">📷</a>
                            <?php endif; ?>
                            <?php if(isset($settings['linkedin_url'])): ?>
                            <a href="<?php echo htmlspecialchars($settings['linkedin_url']); ?>" target="_blank" title="LinkedIn">in</a>
                            <?php endif; ?>
                            <?php if(isset($settings['whatsapp'])): ?>
                            <a href="https://wa.me/<?php echo str_replace(['+', ' '], '', htmlspecialchars($settings['whatsapp'])); ?>" target="_blank" title="WhatsApp">💬</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div style="background: #fafafa; padding: 2rem; border-radius: 8px;">
                    <h3 style="color: #667eea; margin-bottom: 1.5rem;">Send Me a Message</h3>
                    <form id="contactForm" method="POST" action="process_contact.php">
                        <div id="formMessage"></div>
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message"></textarea>
                        </div>
                        <button type="submit" class="btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 Ajay Roy | <a href="#contact">Contact Me</a> | All rights reserved</p>
    </footer>

    <script>
        // Load Services
        fetch('api/get_services.php')
            .then(response => response.json())
            .then(data => {
                const servicesContainer = document.getElementById('servicesContainer');
                servicesContainer.innerHTML = data.map(service => `
                    <div class="card">
                        <div class="icon">${service.icon}</div>
                        <h3>${service.title}</h3>
                        <p>${service.description}</p>
                        <a href="#contact" class="btn">Get Started</a>
                    </div>
                `).join('');
            })
            .catch(error => console.error('Error loading services:', error));

        // Load Portfolio
        fetch('api/get_portfolio.php')
            .then(response => response.json())
            .then(data => {
                const portfolioContainer = document.getElementById('portfolioContainer');
                portfolioContainer.innerHTML = data.map(project => `
                    <div class="card">
                        <div class="icon">${project.category === 'SEO' ? '📈' : project.category === 'Digital Marketing' ? '📊' : '🎯'}</div>
                        <h3>${project.title}</h3>
                        <p>${project.description}</p>
                        <a href="#contact" class="btn">Get Similar Results</a>
                    </div>
                `).join('');
            })
            .catch(error => console.error('Error loading portfolio:', error));

        // Load Skills
        fetch('api/get_skills.php')
            .then(response => response.json())
            .then(data => {
                const skillsContainer = document.getElementById('skillsContainer');
                skillsContainer.innerHTML = data.map(skill => `
                    <div class="skill-bar">
                        <div class="skill-name">
                            <span>${skill.skill_name}</span>
                            <span>${skill.proficiency}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress" style="width: ${skill.proficiency}%;"></div>
                        </div>
                    </div>
                `).join('');
            })
            .catch(error => console.error('Error loading skills:', error));

        // Load Statistics
        fetch('api/get_statistics.php')
            .then(response => response.json())
            .then(data => {
                const statsContainer = document.getElementById('statsContainer');
                statsContainer.innerHTML = data.map(stat => `
                    <div class="stat-item">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">${stat.icon}</div>
                        <div class="stat-number">${stat.stat_value}+</div>
                        <div class="stat-label">${stat.stat_name}</div>
                    </div>
                `).join('');
            })
            .catch(error => console.error('Error loading statistics:', error));

        // Contact Form Handling
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('formMessage');

            fetch('process_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    document.getElementById('contactForm').reset();
                } else {
                    messageDiv.innerHTML = `<div class="alert alert-error">${data.message}</div>`;
                }
            })
            .catch(error => {
                messageDiv.innerHTML = `<div class="alert alert-error">Error sending message. Please try again.</div>`;
                console.error('Error:', error);
            });
        });

        // Mobile Navigation
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.getElementById('navLinks');
        
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });

        // Update active nav link based on scroll position
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section[id]');
            const navItems = document.querySelectorAll('.nav-links a');
            
            let current = 'home';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (window.scrollY >= sectionTop - 100) {
                    current = section.getAttribute('id');
                }
            });
            
            navItems.forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') === `#${current}`) {
                    item.classList.add('active');
                }
            });
        });

        // Close mobile menu when a link is clicked
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
            });
        });
    </script>
</body>
</html>
