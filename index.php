<?php
$pageTitle       = 'Luboš Jeřábek — Engineering Manager';
$pageDescription = 'Engineering Manager with 14 years of experience building high-performing teams across the UK and Czech Republic. Focused on servant leadership, quality engineering, and the AI era.';

require_once __DIR__ . '/includes/header.php';
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
                <a href="/recommendations.php" class="btn btn--primary">
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
     EXPERIENCE
     ============================================================ -->
<section class="section section--alt" id="experience">
    <div class="container">
        <h2 class="section-title">
            <span class="prompt">~/</span>experience
        </h2>
        <p class="section-subtitle">14 years building engineering teams across the UK and Czech Republic.</p>

        <div class="exp-stack">

            <article class="exp-card exp-card--open" style="--brand: #F97316">
                <button class="exp-header" aria-expanded="true">
                    <div class="exp-logo-wrap">
                        <span class="exp-logo-init" aria-hidden="true">SW</span>
                        <img class="exp-logo" src="https://logo.clearbit.com/solarwinds.com" alt="" loading="lazy" onerror="this.style.opacity='0'">
                    </div>
                    <div class="exp-meta">
                        <span class="exp-role">Software Engineering Manager</span>
                        <span class="exp-company">SolarWinds &mdash; Brno, CZ</span>
                    </div>
                    <span class="exp-period exp-period--current">2025 &ndash; present</span>
                    <svg class="exp-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="exp-body">
                    <ul class="exp-list">
                        <li>Leading a team of <strong>10 engineers</strong> building <strong>interactive product demos</strong> for a global <strong>observability platform</strong>.</li>
                        <li>Driving <strong>AI adoption</strong> — guiding the team through <strong>AI coding tools</strong> and experimental workflows.</li>
                        <li>Lead of the internal <strong>Agile community</strong> and co-lead of the <strong>Young Managers community</strong>.</li>
                        <li>Public speaker and workshop facilitator on <strong>AI coding</strong>, <strong>Agile delivery</strong>, and <strong>Quality Engineering</strong>.</li>
                        <li>Internal advocate for <strong>Mental Health</strong> and <strong>Neurodivergence</strong> awareness, implementing training for people leaders.</li>
                    </ul>
                </div>
            </article>

            <article class="exp-card" style="--brand: #00803C">
                <button class="exp-header" aria-expanded="false">
                    <div class="exp-logo-wrap">
                        <span class="exp-logo-init" aria-hidden="true">HO</span>
                        <img class="exp-logo" src="https://logo.clearbit.com/howdens.com" alt="" loading="lazy" onerror="this.style.opacity='0'">
                    </div>
                    <div class="exp-meta">
                        <span class="exp-role">Senior Digital QA Manager</span>
                        <span class="exp-company">Howdens &mdash; Raunds, UK</span>
                    </div>
                    <span class="exp-period">2024 &ndash; 2025</span>
                    <svg class="exp-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="exp-body">
                    <ul class="exp-list">
                        <li>Defined a multi-year <strong>Quality Strategy</strong> to modernise the automation stack using <strong>Python, Playwright, and Appium</strong>.</li>
                        <li>Managed <strong>9 direct reports</strong>; introduced a <strong>career development matrix</strong> to improve retention and skill growth.</li>
                        <li>Negotiated vendor contracts and budget assessments resulting in significant <strong>annual cost savings</strong>.</li>
                        <li>Assessed <strong>engineering maturity</strong> and redefined hiring strategies to align with modern delivery standards.</li>
                    </ul>
                </div>
            </article>

            <article class="exp-card" style="--brand: #7B3FE4">
                <button class="exp-header" aria-expanded="false">
                    <div class="exp-logo-wrap">
                        <span class="exp-logo-init" aria-hidden="true">ZO</span>
                        <img class="exp-logo" src="https://logo.clearbit.com/zopa.com" alt="" loading="lazy" onerror="this.style.opacity='0'">
                    </div>
                    <div class="exp-meta">
                        <span class="exp-role">Quality Engineering Manager</span>
                        <span class="exp-company">Zopa Bank &mdash; London, UK</span>
                    </div>
                    <span class="exp-period">2023 &ndash; 2024</span>
                    <svg class="exp-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="exp-body">
                    <ul class="exp-list">
                        <li>Managed a <strong>team of 6 across three tribes</strong>, establishing <strong>Quality KPIs</strong> and participating in the EM guild.</li>
                        <li>Championed the migration from <strong>Selenium to Playwright</strong> to improve execution speed and reliability.</li>
                        <li>Advocated for <strong>shift-left testing</strong> and optimised incident management efficiency.</li>
                        <li>Led <strong>Quality Engineering workshops</strong> within the QA Chapter to standardise practices across teams.</li>
                    </ul>
                </div>
            </article>

            <article class="exp-card" style="--brand: #2563EB">
                <button class="exp-header" aria-expanded="false">
                    <div class="exp-logo-wrap">
                        <span class="exp-logo-init" aria-hidden="true">JJ</span>
                        <img class="exp-logo" src="https://logo.clearbit.com/jandjglobalfulfilment.com" alt="" loading="lazy" onerror="this.style.opacity='0'">
                    </div>
                    <div class="exp-meta">
                        <span class="exp-role">Lead Test Engineer</span>
                        <span class="exp-company">J&amp;J Global Fulfilment &mdash; Northampton, UK</span>
                    </div>
                    <span class="exp-period">2021 &ndash; 2023</span>
                    <svg class="exp-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="exp-body">
                    <ul class="exp-list">
                        <li>Founded the <strong>QA department from scratch</strong>, including <strong>CI/CD pipelines</strong> and <strong>Python/Robot Framework</strong> test packs.</li>
                        <li>Acted as de-facto <strong>Engineering Manager</strong>, leading resourcing for QA and supporting Dev/BA hiring.</li>
                        <li>Implemented <strong>Agile delivery workflows</strong> in Jira and tracked department-wide <strong>performance KPIs</strong>.</li>
                    </ul>
                </div>
            </article>

            <article class="exp-card" style="--brand: #CC0000">
                <button class="exp-header" aria-expanded="false">
                    <div class="exp-logo-wrap">
                        <span class="exp-logo-init" aria-hidden="true">VM</span>
                        <img class="exp-logo" src="https://logo.clearbit.com/virginmedia.com" alt="" loading="lazy" onerror="this.style.opacity='0'">
                    </div>
                    <div class="exp-meta">
                        <span class="exp-role">Technical Test Lead</span>
                        <span class="exp-company">Virgin Media &mdash; Hemel Hempstead, UK</span>
                    </div>
                    <span class="exp-period">2019 &ndash; 2021</span>
                    <svg class="exp-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="exp-body">
                    <ul class="exp-list">
                        <li>Key contributor to the organisation's <strong>Agile transformation</strong> while managing a <strong>team of 5</strong>.</li>
                        <li>Introduced <strong>visual regression testing</strong> and advanced estimation techniques.</li>
                        <li>Acted as <strong>Scrum Master</strong> for high-priority projects and managed internal and external resourcing.</li>
                    </ul>
                </div>
            </article>

            <article class="exp-card" style="--brand: #003DA5">
                <button class="exp-header" aria-expanded="false">
                    <div class="exp-logo-wrap">
                        <span class="exp-logo-init" aria-hidden="true">AG</span>
                        <img class="exp-logo" src="https://logo.clearbit.com/theaccessgroup.com" alt="" loading="lazy" onerror="this.style.opacity='0'">
                    </div>
                    <div class="exp-meta">
                        <span class="exp-role">Technical QA Analyst</span>
                        <span class="exp-company">Access Group &mdash; Harpenden, UK</span>
                    </div>
                    <span class="exp-period">2016 &ndash; 2019</span>
                    <svg class="exp-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="exp-body">
                    <ul class="exp-list">
                        <li>Company go-to person for <strong>Selenium</strong> and <strong>REST API automation</strong>.</li>
                        <li>Coached junior team members on <strong>technical skills</strong> and <strong>Agile best practices</strong>.</li>
                    </ul>
                </div>
            </article>

        </div>
    </div>
</section>

<!-- ============================================================
     BLOG (LinkedIn post preview)
     ============================================================ -->
<section class="section" id="blog">
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
