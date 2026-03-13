<?php
$pageTitle       = 'Blog — Your Name';
$pageDescription = 'Thoughts, insights, and articles from Your Name — software engineer.';

require_once __DIR__ . '/includes/header.php';
?>

<main>
    <!-- Page Hero -->
    <div class="page-hero">
        <div class="container">
            <h1 class="section-title">
                <span class="prompt">~/</span>blog
            </h1>
            <p class="section-subtitle">
                Pulled live from LinkedIn &middot; cached hourly
            </p>
        </div>
    </div>

    <!-- Posts -->
    <div class="container">
        <div class="blog-full-grid" id="blogFull">
            <p class="blog-status">
                <span class="spinner"></span>&nbsp; Fetching posts&hellip;
            </p>
        </div>
    </div>
</main>

<script>
(async function () {
    const container = document.getElementById('blogFull');

    function formatDate(iso) {
        if (!iso) return '';
        try {
            return new Date(iso).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
        } catch { return iso; }
    }

    function truncate(text, limit = 600) {
        return text.length > limit ? text.slice(0, limit) + '…' : text;
    }

    function escapeHtml(str) {
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function renderPosts(posts) {
        if (!posts.length) {
            container.innerHTML = '<p class="blog-status">No posts found. Check back soon.</p>';
            return;
        }

        container.innerHTML = posts.map((post, i) => {
            const fullText  = escapeHtml(post.text);
            const shortText = escapeHtml(truncate(post.text));
            const needsTruncation = post.text.length > 600;
            const id = 'post-' + i;

            const stats = [];
            if (post.likes    != null) stats.push('&#9829; ' + post.likes + ' likes');
            if (post.comments != null) stats.push('&#128172; ' + post.comments + ' comments');

            return `
                <article class="post-card post-card--full">
                    <div class="post-card__date">
                        <span class="prompt">$</span> ${formatDate(post.date_iso)}
                    </div>
                    <div class="post-card__text" id="${id}-text">${needsTruncation ? shortText : fullText}</div>
                    ${needsTruncation ? `<button class="post-card__expand" data-target="${id}-text" data-full="${encodeURIComponent(post.text)}">read more ↓</button>` : ''}
                    ${stats.length ? `<div class="post-card__stats">${stats.join(' &nbsp;·&nbsp; ')}</div>` : ''}
                </article>`;
        }).join('');

        // Expand/collapse buttons
        container.querySelectorAll('.post-card__expand').forEach(btn => {
            btn.addEventListener('click', () => {
                const el   = document.getElementById(btn.dataset.target);
                const full = decodeURIComponent(btn.dataset.full);
                if (btn.textContent.startsWith('read more')) {
                    el.innerHTML   = escapeHtml(full);
                    el.style.maxHeight = 'none';
                    el.style.webkitMaskImage = 'none';
                    el.style.maskImage = 'none';
                    btn.textContent = 'show less ↑';
                } else {
                    el.innerHTML   = escapeHtml(truncate(full));
                    el.style.maxHeight = '';
                    el.style.webkitMaskImage = '';
                    el.style.maskImage = '';
                    btn.textContent = 'read more ↓';
                }
            });
        });
    }

    try {
        const res  = await fetch('/api/posts.php?count=50');
        const data = await res.json();

        if (!res.ok || data.error) {
            container.innerHTML = `<p class="blog-status">
                Could not load posts: ${escapeHtml(data.message || 'Unknown error')}<br>
                <a href="/auth/linkedin-callback.php" style="color:var(--accent)">Check LinkedIn connection ↗</a>
            </p>`;
            return;
        }

        renderPosts(data.posts || []);
    } catch (err) {
        container.innerHTML = '<p class="blog-status">Failed to load posts. Please try again later.</p>';
        console.error(err);
    }
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
