<?php
$pageTitle       = 'Luboš Jeřábek — Engineering Manager';
$pageDescription = 'Engineering Manager with 14 years of experience building high-performing teams across the UK and Czech Republic. Focused on servant leadership, quality engineering, and the AI era.';

require_once __DIR__ . '/includes/header.php';

// Load projects from JSON
$projectsRaw = file_get_contents(__DIR__ . '/data/projects.json');
$projects    = json_decode($projectsRaw, true) ?? [];
?>

<!-- ============================================================
     HERO
     ============================================================ -->
<section class="hero" id="about">
    <div class="hero__inner">
        <div class="hero__content">
            <h1 class="hero__name">Luboš Jeřábek</h1>

            <div class="hero__title" id="typewriter" aria-label="Engineering Manager">
                <span class="cursor"></span>
            </div>

            <p class="hero__bio">
                Engineering Manager with 14 years of experience across the UK and Czech Republic.
                I moved from technical QA into people leadership with a focus on servant leadership
                and team autonomy. Currently helping teams navigate the AI era at SolarWinds&nbsp;&mdash;
                based in Brno, CZ.
            </p>

            <div class="hero__cta">
                <a href="#projects" class="btn btn--primary">
                    <span class="prompt">$</span> view-projects
                </a>
                <a href="/recommendations.php" class="btn btn--outline">
                    <span class="prompt">$</span> recommendations
                </a>
                <a href="#contact" class="btn btn--outline">
                    <span class="prompt">$</span> contact-me
                </a>
                <a href="/blog.php" class="btn btn--outline">
                    <span class="prompt">$</span> read-blog
                </a>
            </div>
        </div>

        <div class="hero__avatar-placeholder" aria-hidden="true">
            &#x1F9D1;&#x200D;&#x1F4BB;
        </div>
    </div>
</section>

<!-- ============================================================
     PROJECTS
     ============================================================ -->
<section class="section" id="projects">
    <div class="container">
        <h2 class="section-title">
            <span class="prompt">~/</span>projects
        </h2>
        <p class="section-subtitle">Things I've built and shipped.</p>

        <div class="projects-grid" id="projectsGrid">
            <?php foreach ($projects as $project): ?>
            <article class="project-card">
                <div class="project-card__header">
                    <span class="project-card__icon"><?= htmlspecialchars($project['icon'] ?? '◯') ?></span>
                    <div class="project-card__links">
                        <?php if (!empty($project['github'])): ?>
                        <a href="<?= htmlspecialchars($project['github']) ?>" target="_blank" rel="noopener noreferrer">GitHub ↗</a>
                        <?php endif; ?>
                        <?php if (!empty($project['live'])): ?>
                        <a href="<?= htmlspecialchars($project['live']) ?>" target="_blank" rel="noopener noreferrer">Live ↗</a>
                        <?php endif; ?>
                    </div>
                </div>
                <h3 class="project-card__name"><?= htmlspecialchars($project['name']) ?></h3>
                <p class="project-card__desc"><?= htmlspecialchars($project['description']) ?></p>
                <div class="project-card__tags">
                    <?php foreach ($project['tags'] as $tag): ?>
                    <span class="tag"><?= htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     BLOG (LinkedIn post preview)
     ============================================================ -->
<section class="section section--alt" id="blog">
    <div class="container">
        <h2 class="section-title">
            <span class="prompt">~/</span>blog
        </h2>
        <p class="section-subtitle">Latest thoughts from LinkedIn.</p>

        <div class="blog-grid" id="blogPreview">
            <p class="blog-status">
                <span class="spinner"></span>&nbsp; Loading posts&hellip;
            </p>
        </div>

        <div class="blog-more">
            <a href="/blog.php" class="btn btn--outline">
                <span class="prompt">$</span> view-all-posts
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     CONTACT
     ============================================================ -->
<section class="section" id="contact">
    <div class="container">
        <h2 class="section-title">
            <span class="prompt">~/</span>contact
        </h2>
        <p class="section-subtitle">Let's talk. I respond to every genuine message.</p>

        <div class="contact-grid">
            <!-- Social / direct links -->
            <div>
                <div class="contact-links">
                    <a class="contact-link" href="mailto:lubos.jerabek@gmail.com">
                        <span class="contact-link__icon">&#9993;</span>
                        lubos.jerabek@gmail.com
                    </a>
                    <a class="contact-link" href="https://linkedin.com/in/lubosjerabek/" target="_blank" rel="noopener noreferrer">
                        <span class="contact-link__icon">&#9670;</span>
                        linkedin.com/in/lubosjerabek
                    </a>
                </div>
            </div>

            <!-- Contact form -->
            <form class="contact-form" id="contactForm" method="post" action="/api/contact.php" novalidate>
                <div class="form-group">
                    <label for="name">// your name</label>
                    <input type="text" id="name" name="name" placeholder="Jane Doe" required autocomplete="name">
                </div>
                <div class="form-group">
                    <label for="email">// your email</label>
                    <input type="email" id="email" name="email" placeholder="jane@example.com" required autocomplete="email">
                </div>
                <div class="form-group">
                    <label for="message">// message</label>
                    <textarea id="message" name="message" placeholder="Hi, I'd like to talk about..." required></textarea>
                </div>
                <div id="formMessage" class="form-message" role="alert"></div>
                <button type="submit" class="btn btn--primary" id="submitBtn">
                    <span class="prompt">$</span> send-message
                </button>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
