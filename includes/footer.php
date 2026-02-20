<?php
// Shared footer - outputs footer and common scripts
// $settings may already be available from header include; attempt load if not
if (!isset($settings)) {
    require_once __DIR__ . '/config.php';
    $settings = [];
    $settings_result = $mysqli->query("SELECT setting_name, setting_value FROM site_settings");
    if ($settings_result) {
        while ($row = $settings_result->fetch_assoc()) {
            $settings[$row['setting_name']] = $row['setting_value'];
        }
    }
}
?>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Ajay Roy | <a href="contact.php">Contact Me</a> | All rights reserved</p>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="javascript:void(0);" id="whatsappButton" class="whatsapp-float" title="Chat with us on WhatsApp" style="display:none;">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004c-3.582 0-6.917-2.536-6.917-5.65C5.13 5.1 8.464 2.563 12.046 2.563c3.582 0 6.917 2.537 6.917 5.65 0 3.114-3.335 5.65-6.917 5.65m5.694-12.973C16.97.027 14.184 0 12.046 0 5.438 0 .149 5.175.149 11.5c0 2.047.529 4.04 1.528 5.783L.645 24l6.868-3.545c1.712.922 3.66 1.408 5.533 1.408 6.61 0 11.898-5.175 11.898-11.5 0-3.176-1.286-6.14-3.622-8.38"/>
        </svg>
    </a>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // WhatsApp button init
            const whatsappButton = document.getElementById('whatsappButton');
            const whatsappNumber = '<?php echo isset($settings["whatsapp"]) ? preg_replace("/[^0-9+]/", "", htmlspecialchars($settings['whatsapp'])) : ""; ?>';
            if (whatsappNumber) {
                whatsappButton.href = 'https://wa.me/' + whatsappNumber.replace(/[^0-9+]/g, '') + '?text=Hello%20Ajay%20Roy!';
                whatsappButton.target = '_blank';
                whatsappButton.style.display = 'flex';
            } else {
                whatsappButton.style.display = 'none';
            }

            // Mobile nav
            const hamburger = document.getElementById('hamburger');
            const navLinks = document.getElementById('navLinks');
            if (hamburger && navLinks) {
                hamburger.addEventListener('click', () => navLinks.classList.toggle('active'));
                document.querySelectorAll('.nav-links a').forEach(link => link.addEventListener('click', () => navLinks.classList.remove('active')));
            }

            // Initialize scroll animations
            initializeScrollAnimations();
        });

        // Scroll Animation Handler used across pages
        function initializeScrollAnimations() {
            const observerOptions = { threshold: 0.15, rootMargin: '0px 0px -50px 0px' };
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('in-view'); });
            }, observerOptions);

            document.querySelectorAll('.container, .card, .stat-item, .review-card, .intro-text, .intro-image, .about-text, .about-image, .skills-image, .contact-info, .cta-section').forEach(el => {
                el.classList.add('animate-on-scroll');
                observer.observe(el);
            });
        }

        // Helper: safe fetch + render if container exists
        function safeFetchJson(url, onData) {
            try {
                fetch(url).then(r => r.json()).then(onData).catch(e => console.error('fetch error', e));
            } catch (e) { console.error(e); }
        }

        // Load dynamic blocks only if present on the page
        if (document.getElementById('servicesContainer')) {
            safeFetchJson('api/get_services.php', data => {
                const el = document.getElementById('servicesContainer');
                if (!el) return;
                el.innerHTML = data.map(service => `\
                    <div class="card">\
                        <div class="icon">${service.icon}</div>\
                        <h3>${service.title}</h3>\
                        <p>${service.description}</p>\
                        <a href="contact.php" class="btn">Get Started</a>\
                    </div>`).join('');
            });
        }

        if (document.getElementById('portfolioContainer')) {
            safeFetchJson('api/get_portfolio.php', data => {
                const el = document.getElementById('portfolioContainer'); if (!el) return;
                el.innerHTML = data.map(project => `\
                    <div class="card">\
                        <div class="icon">${project.category === 'SEO' ? '📈' : project.category === 'Digital Marketing' ? '📊' : '🎯'}</div>\
                        <h3>${project.title}</h3>\
                        <p>${project.description}</p>\
                        <a href="contact.php" class="btn">Get Similar Results</a>\
                    </div>`).join('');
            });
        }

        if (document.getElementById('skillsContainer')) {
            safeFetchJson('api/get_skills.php', data => {
                const el = document.getElementById('skillsContainer'); if (!el) return;
                el.innerHTML = data.map(skill => `\
                    <div class="skill-bar">\
                        <div class="skill-name">\
                            <span>${skill.skill_name}</span>\
                            <span>${skill.proficiency}%</span>\
                        </div>\
                        <div class="progress-bar">\
                            <div class="progress" style="width: ${skill.proficiency}%;"></div>\
                        </div>\
                    </div>`).join('');
            });
        }

        if (document.getElementById('statsContainer')) {
            safeFetchJson('api/get_statistics.php', data => {
                const el = document.getElementById('statsContainer'); if (!el) return;
                el.innerHTML = data.map(stat => `\
                    <div class="stat-item">\
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">${stat.icon}</div>\
                        <div class="stat-number" data-value="${stat.stat_value}">0+</div>\
                        <div class="stat-label">${stat.stat_name}</div>\
                    </div>`).join('');
                initializeCounters();
            });
        }

        if (document.getElementById('reviewsContainer')) {
            safeFetchJson('api/get_google_reviews.php', data => {
                const el = document.getElementById('reviewsContainer'); if (!el) return;
                if (data.error) { el.innerHTML = '<p style="text-align:center;color:#666;">Reviews not available. Please check admin settings.</p>'; return; }
                if (!data.length) { el.innerHTML = '<p style="text-align:center;color:#666;">No reviews yet. Check back soon!</p>'; return; }
                el.innerHTML = data.map(review => `\
                    <div class="review-card" title="${review.source === 'google' ? 'From Google' : 'From local database'}">\
                        <div class="review-stars">${'★'.repeat(review.rating)}${'☆'.repeat(5-review.rating)}</div>\
                        <div class="review-rating">${review.rating}/5</div>\
                        <p class="review-text">"${review.review_text}"</p>\
                        <div class="review-author">${review.reviewer_name}</div>\
                        <div class="review-date">${new Date(review.review_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</div>\
                        ${review.source === 'google' ? '<div style="font-size:0.8rem;color:#0052CC;margin-top:0.5rem;">✓ Verified Google Review</div>' : ''}\
                    </div>`).join('');
            });
        }

        // Counter Animation
        function initializeCounters() {
            const counters = document.querySelectorAll('.stat-number');
            if (!counters.length) return;
            const options = { threshold: 0.5, rootMargin: '0px' };
            const obs = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                        animateCounter(entry.target);
                        entry.target.classList.add('counted');
                    }
                });
            }, options);
            counters.forEach(c => obs.observe(c));
        }

        function animateCounter(element) {
            const targetValue = parseInt(element.getAttribute('data-value')) || 0;
            const duration = 2000;
            const steps = 60;
            const increment = targetValue / steps;
            let currentValue = 0, currentStep = 0;
            const interval = setInterval(() => {
                currentStep++; currentValue += increment;
                if (currentStep >= steps) { element.textContent = targetValue + '+'; clearInterval(interval); }
                else { element.textContent = Math.floor(currentValue) + '+'; }
            }, duration / steps);
        }

        // Contact form handler (for contact.php)
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                const messageDiv = document.getElementById('formMessage');
                fetch('process_contact.php', { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) { messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`; contactForm.reset(); }
                        else { messageDiv.innerHTML = `<div class="alert alert-error">${data.message}</div>`; }
                    }).catch(e => { messageDiv.innerHTML = `<div class="alert alert-error">Error sending message. Please try again.</div>`; console.error(e); });
            });
        }
    </script>
</body>
</html>
