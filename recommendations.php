<?php
$pageTitle       = 'Recommendations — Luboš Jeřábek';
$pageDescription = 'What colleagues, reports, and peers say about working with Luboš Jeřábek across 14 years in software engineering and leadership.';

require_once __DIR__ . '/includes/header.php';

$recs = json_decode(file_get_contents(__DIR__ . '/data/recommendations.json'), true) ?? [];

// Derive unique companies in order of appearance for the filter
$companies = [];
foreach ($recs as $r) {
    if (!in_array($r['company'], $companies, true)) {
        $companies[] = $r['company'];
    }
}

// Badge colours per relationship type
function rel_class(string $rel): string {
    if (str_contains($rel, 'reported')) return 'badge--green';
    if (str_contains($rel, 'same'))     return 'badge--blue';
    return 'badge--muted';
}

function format_date(string $date): string {
    $ts = strtotime($date);
    return $ts ? date('M Y', $ts) : $date;
}
?>

<main>
    <div class="page-hero">
        <div class="container">
            <h1 class="section-title">
                <span class="prompt">~/</span>recommendations
            </h1>
            <p class="section-subtitle">
                <?= count($recs) ?> colleagues across <?= count($companies) ?> companies &middot; 14 years
            </p>
        </div>
    </div>

    <div class="container rec-container">

        <!-- Company filter tabs -->
        <div class="rec-filters" id="recFilters">
            <button class="rec-filter rec-filter--active" data-company="all">All</button>
            <?php foreach ($companies as $co): ?>
            <button class="rec-filter" data-company="<?= htmlspecialchars($co) ?>">
                <?= htmlspecialchars($co) ?>
            </button>
            <?php endforeach; ?>
        </div>

        <!-- Cards grid -->
        <div class="rec-grid" id="recGrid">
            <?php foreach ($recs as $i => $rec): ?>
            <article class="recommendation-card" data-company="<?= htmlspecialchars($rec['company']) ?>">
                <div class="rec-meta">
                    <div class="rec-meta__left">
                        <strong class="rec-name"><?= htmlspecialchars($rec['name']) ?></strong>
                        <span class="rec-title"><?= htmlspecialchars($rec['title']) ?></span>
                    </div>
                    <div class="rec-meta__right">
                        <span class="rec-date"><?= format_date($rec['date']) ?></span>
                        <span class="badge <?= rel_class($rec['relationship']) ?>">
                            <?= htmlspecialchars($rec['relationship']) ?>
                        </span>
                    </div>
                </div>
                <?php
                    $full  = htmlspecialchars($rec['text']);
                    $short = htmlspecialchars(mb_substr($rec['text'], 0, 300));
                    $needs = mb_strlen($rec['text']) > 300;
                    $id    = 'rec-' . $i;
                ?>
                <blockquote class="rec-quote" id="<?= $id ?>">
                    <?= $needs ? $short . '&hellip;' : $full ?>
                </blockquote>
                <?php if ($needs): ?>
                <button class="post-card__expand"
                        data-target="<?= $id ?>"
                        data-full="<?= htmlspecialchars($rec['text'], ENT_QUOTES) ?>">
                    read more ↓
                </button>
                <?php endif; ?>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<script>
// Filter tabs
(function () {
    const filters = document.querySelectorAll('.rec-filter');
    const cards   = document.querySelectorAll('.recommendation-card');

    filters.forEach(btn => {
        btn.addEventListener('click', () => {
            filters.forEach(b => b.classList.remove('rec-filter--active'));
            btn.classList.add('rec-filter--active');

            const co = btn.dataset.company;
            cards.forEach(card => {
                card.style.display = (co === 'all' || card.dataset.company === co) ? '' : 'none';
            });
        });
    });
})();

// Expand / collapse quotes
(function () {
    document.querySelectorAll('.post-card__expand').forEach(btn => {
        btn.addEventListener('click', () => {
            const el   = document.getElementById(btn.dataset.target);
            const full = btn.dataset.full;
            if (btn.textContent.startsWith('read more')) {
                el.textContent  = full;
                btn.textContent = 'show less ↑';
            } else {
                el.textContent  = full.slice(0, 300) + '…';
                btn.textContent = 'read more ↓';
            }
        });
    });
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
