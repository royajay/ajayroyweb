<?php require 'includes/header.php'; ?>

<!-- GALLERY PAGE -->
<section id="gallery">
    <div class="section-header">
        <h2>Gallery</h2>
        <p>Selected visuals and campaign screenshots</p>
    </div>
    <div class="container">
        <div class="grid" id="galleryContainer">
            <!-- Gallery items will be loaded here via JS (reusing portfolio API images if available) -->
        </div>
        <div class="cta-section" style="margin-top: 3rem;">
            <h3>Like what you see?</h3>
            <p>Contact me and let's plan a strategy that fits your business.</p>
            <a href="contact.php" class="cta-btn">Get a Custom Plan</a>
        </div>
    </div>

    <script>
        // Try to reuse portfolio items as gallery images
        if (document.getElementById('galleryContainer')) {
            fetch('api/get_portfolio.php').then(r => r.json()).then(data => {
                const el = document.getElementById('galleryContainer');
                el.innerHTML = data.map(item => `\
                    <div class="card">\
                        <div style="height:180px;display:flex;align-items:center;justify-content:center;">\
                            <img src="${item.image || 'images/1.webp'}" alt="${item.title}" style="max-width:100%;max-height:160px;border-radius:8px;object-fit:cover;" />\
                        </div>\
                        <h4 style="margin-top:0.8rem;color:#0052CC;">${item.title}</h4>\
                        <p style="color:#555;">${item.description}</p>\
                    </div>`).join('');
            }).catch(e => console.error(e));
        }
    </script>

<?php require 'includes/footer.php'; ?>
