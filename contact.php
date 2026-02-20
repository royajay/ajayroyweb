<?php require 'includes/header.php'; ?>

<!-- CONTACT PAGE -->
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
                    <p><a href="mailto:<?php echo isset($settings['admin_email']) ? htmlspecialchars($settings['admin_email']) : 'admin@rayajay.com.np'; ?>"><?php echo isset($settings['admin_email']) ? htmlspecialchars($settings['admin_email']) : 'admin@rayajay.com.np'; ?></a></p>
                </div>
                <div class="contact-item">
                    <h4>📞 Phone</h4>
                    <p><a href="tel:<?php echo isset($settings['phone']) ? str_replace(' ', '', htmlspecialchars($settings['phone'])) : '+977 9745232233'; ?>"><?php echo isset($settings['phone']) ? htmlspecialchars($settings['phone']) : '+977 9745232233'; ?></a></p>
                </div>
                <div class="contact-item">
                    <h4>📍 Address</h4>
                    <p><?php echo isset($settings['address']) ? htmlspecialchars($settings['address']) : 'Kathmandu, Nepal'; ?></p>
                </div>
                <div class="contact-item">
                    <h4>Connect With Me</h4>
                    <div class="social-links">
                        <?php if(isset($settings['instagram_url'])): ?><a href="<?php echo htmlspecialchars($settings['instagram_url']); ?>" target="_blank" title="Instagram">📷</a><?php endif; ?>
                        <?php if(isset($settings['linkedin_url'])): ?><a href="<?php echo htmlspecialchars($settings['linkedin_url']); ?>" target="_blank" title="LinkedIn">in</a><?php endif; ?>
                        <?php if(isset($settings['whatsapp'])): ?><a href="https://wa.me/<?php echo str_replace(['+', ' '], '', htmlspecialchars($settings['whatsapp'])); ?>" target="_blank" title="WhatsApp">💬</a><?php endif; ?>
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
