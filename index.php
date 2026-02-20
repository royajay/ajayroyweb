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
            background: #fff;
            color: #333;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
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
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .logo img {
            height: 50px;
            width: auto;
        }
        .logo-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
        }
        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }
        .nav-links a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s;
        }
        .nav-links a:hover {
            color: #0052CC;
        }
        .nav-links a.active {
            color: #0052CC;
            border-bottom: 2px solid #0052CC;
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
            background: #333;
            margin: 5px 0;
        }
        header {
            background: linear-gradient(135deg, #0052CC 0%, #0078FF 100%);
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
            background: #0052CC;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        .btn:hover {
            background: #003d99;
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
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }
        .intro-text {
            font-size: 1.1rem;
            line-height: 1.8;
        }
        .intro-text h2 {
            margin-bottom: 1.5rem;
            color: #333;
        }
        .intro-text p {
            margin-bottom: 1rem;
        }
        .intro-image {
            border-radius: 8px;
            text-align: center;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .intro-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
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
            border-top: 4px solid #0052CC;
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
            color: #0052CC;
            margin-bottom: 1rem;
            font-size: 1.4rem;
        }
        .card p {
            color: #555;
            line-height: 1.8;
        }
        .section-header {
            background: linear-gradient(135deg, #0052CC 0%, #0066FF 100%);
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
        #about, #services, #portfolio, #contact, #statistics, #skills, #reviews {
            background: #fff;
            padding: 3rem 0;
            margin: 2rem 0;
        }
        #statistics, #skills, #reviews {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            padding: 4rem 0;
        }
        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: start;
            max-height: 600px;
            overflow-y: scroll;
            padding-right: 0.5rem;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .about-content::-webkit-scrollbar {
            display: none;
        }
        .about-text h3 {
            color: #0052CC;
            margin-bottom: 1rem;
        }
        .about-text p {
            margin-bottom: 1rem;
            line-height: 1.8;
        }
        .skills {
            margin-top: 2rem;
        }
        .skills-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: start;
        }
        .skills-image {
            border-radius: 8px;
            text-align: center;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .skills-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            max-height: 400px;
            object-fit: cover;
        }
        #skillsContainer {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
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
            background: linear-gradient(90deg, #0052CC 0%, #0078FF 100%);
            height: 100%;
            border-radius: 10px;
        }
        .about-image {
            border-radius: 8px;
            text-align: center;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: sticky;
            top: 0;
        }
        .about-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            max-height: 400px;
            object-fit: cover;
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
            color: #0052CC;
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stat-label {
            color: #666;
            margin-top: 0.5rem;
        }
        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }
        .review-card {
            background: #fafafa;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .review-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .review-stars {
            color: #FFC107;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            letter-spacing: 0.2rem;
        }
        .review-rating {
            display: inline-block;
            background: #0052CC;
            color: #fff;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }
        .review-text {
            color: #555;
            line-height: 1.6;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }
        .review-author {
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }
        .review-date {
            color: #999;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }
        .contact-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
        }
        .contact-info h3 {
            color: #0052CC;
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
            color: #0052CC;
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
            background: #0052CC;
            color: #fff;
            border-radius: 50%;
            text-decoration: none;
            font-size: 1.2rem;
            transition: background 0.3s;
        }
        .social-links a:hover {
            background: #003d99;
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
            border-color: #0052CC;
        }
        textarea {
            resize: vertical;
            min-height: 150px;
        }
        footer {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(90deg, #0052CC 0%, #0066FF 100%);
            color: #fff;
            margin-top: 3rem;
        }
        footer a {
            color: #66D4FF;
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
        /* WhatsApp Floating Button */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background-color: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 999;
            text-decoration: none;
            transition: all 0.3s ease;
            animation: float 3s ease-in-out infinite;
        }
        .whatsapp-float:hover {
            background-color: #20BA5A;
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
        }
        .whatsapp-float svg {
            width: 30px;
            height: 30px;
            fill: #fff;
        }
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        /* Section Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        @keyframes scaleUp {
            from {
                transform: scale(0.9);
            }
            to {
                transform: scale(1);
            }
        }
        /* Animation Classes */
        .animate-on-scroll {
            opacity: 0;
            animation-fill-mode: forwards;
        }
        .animate-on-scroll.in-view {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        .container.in-view {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        .card {
            transition: all 0.3s ease;
        }
        .card.in-view {
            animation: zoomIn 0.6s ease-out forwards;
        }
        .intro-text.in-view {
            animation: slideInLeft 0.8s ease-out forwards;
        }
        .intro-image.in-view {
            animation: slideInRight 0.8s ease-out forwards;
        }
        .stat-item.in-view {
            animation: scaleUp 0.6s ease-out forwards;
        }
        .review-card.in-view {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .contact-info.in-view {
            animation: slideInLeft 0.8s ease-out forwards;
        }
        .skills-image.in-view {
            animation: slideInLeft 0.8s ease-out forwards;
        }
        .about-text.in-view {
            animation: slideInLeft 0.8s ease-out forwards;
        }
        .about-image.in-view {
            animation: slideInRight 0.8s ease-out forwards;
        }
        /* CTA Button Styles */
        .cta-btn {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: linear-gradient(90deg, #0052CC 0%, #0078FF 100%);
            color: #fff;
            text-decoration: none;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 82, 204, 0.3);
        }
        .cta-btn:hover {
            background: linear-gradient(90deg, #003d99 0%, #0052CC 100%);
            box-shadow: 0 6px 25px rgba(0, 82, 204, 0.5);
            transform: translateY(-2px);
        }
        .cta-section {
            background: linear-gradient(135deg, #0052CC 0%, #0078FF 100%);
            padding: 3rem 2rem;
            text-align: center;
            border-radius: 12px;
            color: #fff;
            margin: 2rem 0;
        }
        .cta-section h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }
        .cta-section p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            opacity: 0.95;
        }
        .section-highlight {
            background: linear-gradient(135deg, #0052CC15 0%, #0066FF15 100%);
            padding: 2rem;
            border-left: 4px solid #0052CC;
            border-radius: 8px;
            margin: 2rem 0;
        }
        @media (max-width: 768px) {
            .whatsapp-float {
                bottom: 20px;
                right: 20px;
                width: 55px;
                height: 55px;
            }
            .whatsapp-float svg {
                width: 28px;
                height: 28px;
            }
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
                max-height: 800px;
            }
            .intro {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .intro-image {
                min-height: 300px;
            }
            .skills-wrapper {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .skills-image {
                min-height: 300px;
            }
            .reviews-grid {
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
            <div class="logo">
                <img src="images/logo.png" alt="Ajay Roy Logo">
                <span class="logo-text">Ajay Roy</span>
            </div>
            <ul class="nav-links" id="navLinks">
                <li><a href="#home" class="active">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#portfolio">Portfolio</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="admin/" style="background: #0052CC; padding: 0.5rem 1rem; border-radius: 4px;">Admin</a></li>
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
            <a href="#services" class="cta-btn">Discover My Services ✨</a>
        </header>
    </section>

    <div class="container">
        <section class="intro">
            <div class="intro-text">
                <h2>Welcome</h2>
                <p>I am an experienced SEO Specialist & SEO Expert in Nepal with Digital Marketing skills in driving organic traffic and optimizing online visibility. Proficient in keyword research, on-page/off-page optimization, and collaborative communication.</p>
                <p>Explore how I can help grow your online presence and achieve your business goals through strategic digital marketing solutions.</p>
                <p>Explore how I can help grow your online presence and achieve your business goals through strategic digital marketing solutions.</p>
                <p>Explore how I can help grow your online presence and achieve your business goals through strategic digital marketing solutions.</p>
                <p>Explore how I can help grow your online presence and achieve your business goals through strategic digital marketing solutions.</p>
                <p>Explore how I can help grow your online presence and achieve your business goals through strategic digital marketing solutions.</p>
                <p>Explore how I can help grow your online presence and achieve your business goals through strategic digital marketing solutions.</p>
            </div>
            <div class="intro-image">
                <img src="images/1.webp" alt="SEO and Digital Marketing">
            </div>
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
                </div>
                <div class="about-image">
                    <img src="images/2.webp" alt="Ajay Roy">
                </img>
            </div>
            </div>
            <div class="section-highlight" style="margin-top: 2rem;">
                <h4 style="color: #0052CC; margin-bottom: 1rem;">🎯 Let's Partner Together</h4>
                <p>I'm committed to delivering measurable results and helping your business thrive in the digital landscape. Whether you're looking to improve your SEO, scale your marketing, or develop a comprehensive digital strategy, I'm here to help.</p>
            </div>
        </div>
    </section>

    <!-- SKILLS SECTION -->
    <section id="skills">
        <div class="section-header">
            <h2>My Skills</h2>
        </div>
        <div class="container">
            <div class="skills-wrapper">
                <div class="skills-image">
                    <img src="images/skill.png" alt="Skills">
                </div>
                <div id="skillsContainer">
                    <!-- Skills will be loaded here via JavaScript -->
                </div>
            </div>
        </div>
    </section>

    <!-- STATISTICS SECTION -->
    <section id="statistics">
        <div class="container">
            <div class="stats" id="statsContainer">
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
            <div class="cta-section" style="margin-top: 3rem;">
                <h3>Ready to Boost Your Online Presence?</h3>
                <p>Let's work together to drive organic traffic and increase your conversions</p>
                <a href="#contact" class="cta-btn">Get Started Today 🚀</a>
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
            <div class="cta-section" style="margin-top: 3rem;">
                <h3>See What I Can Do For Your Business</h3>
                <p>I have a proven track record of delivering results. Let's discuss your project today!</p>
                <a href="#contact" class="cta-btn">Schedule a Free Consultation 📞</a>
            </div>
        </div>
    </section>

    <!-- REVIEWS SECTION -->
    <section id="reviews">
        <div class="section-header">
            <h2>Google Reviews</h2>
            <p>What my clients say about me</p>
        </div>
        <div class="container">
            <div class="reviews-grid" id="reviewsContainer">
                <!-- Reviews will be loaded here via JavaScript -->
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
                    <h3 style="color: #0052CC; margin-bottom: 1.5rem;">Send Me a Message</h3>
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

    <!-- WhatsApp Floating Button -->
    <a href="javascript:void(0);" id="whatsappButton" class="whatsapp-float" title="Chat with us on WhatsApp">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004c-3.582 0-6.917-2.536-6.917-5.65C5.13 5.1 8.464 2.563 12.046 2.563c3.582 0 6.917 2.537 6.917 5.65 0 3.114-3.335 5.65-6.917 5.65m5.694-12.973C16.97.027 14.184 0 12.046 0 5.438 0 .149 5.175.149 11.5c0 2.047.529 4.04 1.528 5.783L.645 24l6.868-3.545c1.712.922 3.66 1.408 5.533 1.408 6.61 0 11.898-5.175 11.898-11.5 0-3.176-1.286-6.14-3.622-8.38"/>
        </svg>
    </a>

    <script>
        // WhatsApp Floating Button
        document.addEventListener('DOMContentLoaded', function() {
            const whatsappButton = document.getElementById('whatsappButton');
            const whatsappNumber = '<?php echo isset($settings["whatsapp"]) ? preg_replace("/[^0-9+]/", "", htmlspecialchars($settings["whatsapp"])) : "+977 9745232233"; ?>';
            
            if (whatsappNumber) {
                whatsappButton.href = 'https://wa.me/' + whatsappNumber.replace(/[^0-9+]/g, '') + '?text=Hello%20Ajay%20Roy!';
                whatsappButton.target = '_blank';
                whatsappButton.style.display = 'flex';
            } else {
                whatsappButton.style.display = 'none';
            }

            // Initialize Scroll Animations
            initializeScrollAnimations();
        });

        // Scroll Animation Handler
        function initializeScrollAnimations() {
            const observerOptions = {
                threshold: 0.15,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in-view');
                    }
                });
            }, observerOptions);

            // Observe all animatable elements
            document.querySelectorAll('.container, .card, .stat-item, .review-card, .intro-text, .intro-image, .about-text, .about-image, .skills-image, .contact-info, .cta-section').forEach(el => {
                el.classList.add('animate-on-scroll');
                observer.observe(el);
            });
        }
    </script>

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
                        <div class="stat-number" data-value="${stat.stat_value}">0+</div>
                        <div class="stat-label">${stat.stat_name}</div>
                    </div>
                `).join('');
                
                // Initialize counter animations
                initializeCounters();
            })
            .catch(error => console.error('Error loading statistics:', error));

        // Counter Animation Function
        function initializeCounters() {
            const counters = document.querySelectorAll('.stat-number');
            
            const observerOptions = {
                threshold: 0.5,
                rootMargin: '0px'
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                        animateCounter(entry.target);
                        entry.target.classList.add('counted');
                    }
                });
            }, observerOptions);
            
            counters.forEach(counter => observer.observe(counter));
        }
        
        function animateCounter(element) {
            const targetValue = parseInt(element.getAttribute('data-value'));
            const duration = 2000; // 2 seconds
            const steps = 60;
            const increment = targetValue / steps;
            let currentValue = 0;
            let currentStep = 0;
            
            const interval = setInterval(() => {
                currentStep++;
                currentValue += increment;
                
                if (currentStep >= steps) {
                    element.textContent = targetValue + '+';
                    clearInterval(interval);
                } else {
                    element.textContent = Math.floor(currentValue) + '+';
                }
            }, duration / steps);
        }

        // Load Reviews from Google My Business
        fetch('api/get_google_reviews.php')
            .then(response => response.json())
            .then(data => {
                const reviewsContainer = document.getElementById('reviewsContainer');
                if (data.error) {
                    reviewsContainer.innerHTML = '<p style="text-align: center; color: #666;">Reviews not available. Please check admin settings.</p>';
                } else if (data.length === 0) {
                    reviewsContainer.innerHTML = '<p style="text-align: center; color: #666;">No reviews yet. Check back soon!</p>';
                } else {
                    reviewsContainer.innerHTML = data.map(review => `
                        <div class="review-card" title="${review.source === 'google' ? 'From Google' : 'From local database'}">
                            <div class="review-stars">${'★'.repeat(review.rating)}${'☆'.repeat(5-review.rating)}</div>
                            <div class="review-rating">${review.rating}/5</div>
                            <p class="review-text">"${review.review_text}"</p>
                            <div class="review-author">${review.reviewer_name}</div>
                            <div class="review-date">${new Date(review.review_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</div>
                            ${review.source === 'google' ? '<div style="font-size: 0.8rem; color: #0052CC; margin-top: 0.5rem;">✓ Verified Google Review</div>' : ''}
                        </div>
                    `).join('');
                }
            })
            .catch(error => {
                console.error('Error loading reviews:', error);
                document.getElementById('reviewsContainer').innerHTML = '<p style="text-align: center; color: #d9534f;">Error loading reviews</p>';
            });

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
