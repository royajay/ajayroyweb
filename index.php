<?php
/**
 * Ajay Roy - SEO Specialist & Digital Marketer
 * Main Website File
<?php
/**
 * Cleaned index.php — uses shared header/footer and central stylesheet
 */
require_once 'includes/config.php';

// Fetch data from database (kept for compatibility; front-end uses APIs)
$services_query = "SELECT * FROM services WHERE active=1 ORDER BY order_by ASC";
$services_result = $mysqli->query($services_query);

$portfolio_query = "SELECT * FROM portfolio WHERE active=1 ORDER BY order_by ASC LIMIT 6";
$portfolio_result = $mysqli->query($portfolio_query);

$skills_query = "SELECT * FROM skills WHERE active=1 ORDER BY order_by ASC";
$skills_result = $mysqli->query($skills_query);

$stats_query = "SELECT * FROM statistics ORDER BY stat_name ASC";
$stats_result = $mysqli->query($stats_query);

require 'includes/header.php';
?>

    <!-- HOME SECTION -->
    <section id="home">
        <header>
            <h1>I am Ajay Roy</h1>
            <p>Digital Marketer & SEO Specialist</p>
            <a href="services.php" class="cta-btn">Discover My Services ✨</a>
        </header>
    </section>

    <div class="container">
        <section class="intro">
            <div class="intro-text">
                <h2>Welcome</h2>
                <p>I am an experienced SEO Specialist & SEO Expert in Nepal with Digital Marketing skills in driving organic traffic and optimizing online visibility. Proficient in keyword research, on-page/off-page optimization, and collaborative communication.</p>
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
                <a href="contact.php" class="cta-btn">Get Started Today 🚀</a>
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
                <a href="contact.php" class="cta-btn">Schedule a Free Consultation 📞</a>
            </div>
        </div>
    </section>
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

<?php require 'includes/footer.php'; ?>
