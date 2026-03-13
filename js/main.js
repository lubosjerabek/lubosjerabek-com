/* ==========================================================
   main.js — Personal Website JS
   ========================================================== */

// -------------------------------------------------------
// Typewriter effect in hero
// -------------------------------------------------------
(function typewriter() {
    const el = document.getElementById('typewriter');
    if (!el) return;

    const phrases = [
        'Engineering Manager',
        'Quality Leader',
        'Community Advocate',
        'Servant Leader',
        'AI Era Navigator',
    ];

    let phraseIdx = 0;
    let charIdx   = 0;
    let deleting  = false;
    let paused    = false;

    function tick() {
        const current = phrases[phraseIdx];

        if (paused) {
            paused = false;
            setTimeout(tick, 1400);
            return;
        }

        if (!deleting && charIdx <= current.length) {
            el.innerHTML = current.slice(0, charIdx) + '<span class="cursor"></span>';
            charIdx++;
            if (charIdx > current.length) {
                paused    = true;
                deleting  = true;
            }
            setTimeout(tick, deleting && paused ? 80 : 80);
        } else if (deleting && charIdx >= 0) {
            el.innerHTML = current.slice(0, charIdx) + '<span class="cursor"></span>';
            charIdx--;
            if (charIdx < 0) {
                deleting  = false;
                phraseIdx = (phraseIdx + 1) % phrases.length;
                charIdx   = 0;
            }
            setTimeout(tick, 40);
        }
    }

    tick();
})();

// -------------------------------------------------------
// Mobile nav toggle
// -------------------------------------------------------
(function mobileNav() {
    const toggle = document.getElementById('navToggle');
    const links  = document.getElementById('navLinks');
    if (!toggle || !links) return;

    toggle.addEventListener('click', () => {
        const open = links.classList.toggle('open');
        toggle.setAttribute('aria-expanded', open);
    });

    // Close on nav link click
    links.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => links.classList.remove('open'));
    });
})();

// -------------------------------------------------------
// Nav scroll shadow
// -------------------------------------------------------
(function navShadow() {
    const nav = document.getElementById('nav');
    if (!nav) return;
    const handler = () => {
        nav.style.borderBottomColor = window.scrollY > 20 ? 'var(--accent)' : 'var(--border)';
    };
    window.addEventListener('scroll', handler, { passive: true });
})();

// -------------------------------------------------------
// Blog preview on homepage
// -------------------------------------------------------
(function blogPreview() {
    const container = document.getElementById('blogPreview');
    if (!container) return;

    function formatDate(iso) {
        if (!iso) return '';
        try {
            return new Date(iso).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
        } catch { return iso; }
    }

    function escapeHtml(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    fetch('/api/posts.php?count=3')
        .then(r => r.json())
        .then(data => {
            if (!data.posts || !data.posts.length) {
                container.innerHTML = '<p class="blog-status">No posts yet. Check back soon.</p>';
                return;
            }

            container.innerHTML = data.posts.map(post => {
                const preview = escapeHtml(post.text.slice(0, 280)) + (post.text.length > 280 ? '&hellip;' : '');
                const stats = [];
                if (post.likes    != null) stats.push('&#9829; ' + post.likes);
                if (post.comments != null) stats.push('&#128172; ' + post.comments);

                return `
                    <article class="post-card">
                        <div class="post-card__date">
                            <span style="color:var(--green);font-family:var(--font-mono)">$</span>
                            ${formatDate(post.date_iso)}
                        </div>
                        <div class="post-card__text">${preview}</div>
                        ${stats.length ? `<div class="post-card__stats">${stats.join(' &nbsp;&middot;&nbsp; ')}</div>` : ''}
                    </article>`;
            }).join('');
        })
        .catch(() => {
            container.innerHTML = '<p class="blog-status">Could not load posts right now.</p>';
        });
})();

// -------------------------------------------------------
// Contact form — async submit
// -------------------------------------------------------
(function contactForm() {
    const form    = document.getElementById('contactForm');
    const message = document.getElementById('formMessage');
    const btn     = document.getElementById('submitBtn');
    if (!form) return;

    form.addEventListener('submit', async e => {
        e.preventDefault();

        // Basic client-side validation
        const name  = form.name.value.trim();
        const email = form.email.value.trim();
        const body  = form.message.value.trim();

        if (!name || !email || !body) {
            showMessage('Please fill in all fields.', 'error');
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showMessage('Please enter a valid email address.', 'error');
            return;
        }

        btn.disabled     = true;
        btn.textContent  = '$ sending…';

        try {
            const res  = await fetch(form.action, {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    new URLSearchParams({ name, email, message: body }),
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showMessage('Message sent! I\'ll get back to you soon.', 'success');
                form.reset();
            } else {
                showMessage(data.message || 'Something went wrong. Try emailing directly.', 'error');
            }
        } catch {
            showMessage('Network error. Please try emailing directly.', 'error');
        } finally {
            btn.disabled    = false;
            btn.innerHTML   = '<span class="prompt">$</span> send-message';
        }
    });

    function showMessage(text, type) {
        message.textContent  = text;
        message.className    = 'form-message ' + type;
    }
})();

// -------------------------------------------------------
// Experience accordion
// -------------------------------------------------------
(function expAccordion() {
    document.querySelectorAll('.exp-header').forEach(btn => {
        btn.addEventListener('click', () => {
            const card   = btn.closest('.exp-card');
            const isOpen = card.classList.toggle('exp-card--open');
            btn.setAttribute('aria-expanded', isOpen);
        });
    });
})();

// -------------------------------------------------------
// Scroll-reveal for cards (IntersectionObserver)
// -------------------------------------------------------
(function scrollReveal() {
    const els = document.querySelectorAll('.exp-card, .post-card, .contact-link');
    if (!els.length || !('IntersectionObserver' in window)) return;

    const style = document.createElement('style');
    style.textContent = `
        .reveal { opacity: 0; transform: translateY(16px); transition: opacity 0.4s ease, transform 0.4s ease; }
        .reveal.visible { opacity: 1; transform: none; }
    `;
    document.head.appendChild(style);

    els.forEach(el => el.classList.add('reveal'));

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    els.forEach(el => observer.observe(el));
})();
