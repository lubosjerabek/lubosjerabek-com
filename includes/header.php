<?php
// Allow pages to set $pageTitle and $pageDescription before including this file
$pageTitle = $pageTitle ?? 'Your Name — Software Engineer';
$pageDescription = $pageDescription ?? 'Software engineer specializing in building things for the web.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <nav class="nav" id="nav">
        <div class="nav__inner">
            <a class="nav__logo" href="/">
                <span class="prompt">$</span> lubosjerabek.com
            </a>
            <button class="nav__toggle" id="navToggle" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
            <ul class="nav__links" id="navLinks">
                <li><a href="/#about"><span class="prompt">~/</span>about</a></li>
                <li><a href="/#experience"><span class="prompt">~/</span>experience</a></li>
                <li><a href="/recommendations.php"><span class="prompt">~/</span>recommendations</a></li>
                <li><a href="/#blog"><span class="prompt">~/</span>blog</a></li>
                <li><a href="/#contact"><span class="prompt">~/</span>contact</a></li>
            </ul>
        </div>
    </nav>
