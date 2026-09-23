<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

$config = cohort_config();
$fee = number_format((int) $config['consultation_rupees']);
$monthly = number_format((int) $config['monthly_rupees']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lifestyle Reversal Cohort | Eat Rrite</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600&family=Lora:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/cohort.css">
</head>
<body class="er-cohort">
  <header class="er-hero">
    <p class="er-pill er-pill-light">10 spots · Women's hormonal health &amp; lifestyle reversal</p>
    <h1>Reverse Your Lifestyle Disease &amp; Hormonal Imbalance — With a Plan Built Around You</h1>
    <p>For women dealing with PCOS, thyroid imbalance, stubborn weight, or early lifestyle disease who are ready to work on the root cause — guided personally by nutritionist Mukta Patil.</p>
    <p class="er-actions">
      <a class="er-btn er-btn-gold" href="apply.php">Apply for this month's cohort</a>
      <a class="er-btn er-btn-ghost" href="#how-it-works">See how it works</a>
    </p>
    <ul class="er-stats">
      <li><strong>8</strong><span>Years of Practice</span></li>
      <li><strong>500+</strong><span>Lives Transformed</span></li>
      <li><strong>4.8</strong><span>Google Rating</span></li>
    </ul>
  </header>

    <main>
      <section class="er-section">
        <div class="er-wrap er-prose">
          <p class="er-pill">Your guide through the program</p>
          <h2>Meet Mukta Patil — Nutritionist &amp; Founder, Eat Rrite</h2>
          <p>Mukta didn't set out to become a nutritionist — she became one because she had to. Eight years on, the mission remains Unchanged - To help people embrace the magic of Eat Rrite</p>
          <ul>
            <li>8 years in practice across Hyderabad &amp; Dehradun</li>
            <li>500+ clients guided through sustainable transformation</li>
            <li>Focus: hormonal health, diabetes reversal, weight &amp; lifestyle</li>
            <li>Science-backed coaching — food you already eat</li>
          </ul>
        </div>
      </section>

      <section class="er-section er-section-mint">
        <div class="er-wrap er-prose">
          <p class="er-pill">Is this you?</p>
          <h2>This cohort is built for women who recognise themselves here</h2>
          <div class="er-chips">
            <span>Irregular or missed periods</span>
            <span>PCOS / PCOD diagnosis</span>
            <span>Thyroid imbalance</span>
            <span>Stubborn weight gain</span>
            <span>Constant fatigue, low energy</span>
            <span>Hair fall or adult acne</span>
            <span>Sugar cravings you can't control</span>
            <span>Disturbed sleep or mood swings</span>
            <span>Pre-diabetes, diabetes, or high BP</span>
          </div>
        </div>
      </section>

      <section class="er-section">
        <div class="er-wrap">
          <p class="er-pill">This is an investment, not an expense</p>
          <h2>Start with a real conversation. The first month is on us.</h2>
          <div class="er-cards">
            <article><span>Consultation</span><strong>₹<?php echo $fee; ?></strong><p>One-time, 30 minutes. Paid at application.</p></article>
            <article><span>First month</span><strong>Free</strong><p>The cohort is ₹<?php echo $monthly; ?> a month. Month one is complimentary.</p></article>
            <article><span>From month two</span><strong>Your call</strong><p>Continue at ₹<?php echo $monthly; ?>, or step away. No lock-in.</p></article>
          </div>
          <p class="er-note">The ₹<?php echo $fee; ?> consultation fee covers your session only and is separate from program fees.</p>
        </div>
      </section>

      <section class="er-section er-section-mint" id="how-it-works">
        <div class="er-wrap">
          <p class="er-pill">How it works</p>
          <h2>From application to a program built for you</h2>
          <div class="er-steps">
            <article><span>01</span><h3>Apply</h3><p>Share your name, email and number. Ten spots only.</p></article>
            <article><span>02</span><h3>Book your consultation</h3><p>Pay ₹<?php echo $fee; ?> once. You are on Mukta's calendar.</p></article>
            <article><span>03</span><h3>Get direction</h3><p>Talk through what is going on and leave with a clear next step.</p></article>
            <article><span>04</span><h3>Start your first month free</h3><p>Begin the cohort at no program fee. Continue or pause next month.</p></article>
          </div>
        </div>
      </section>

      <section class="er-cta">
        <div class="er-wrap">
          <p class="er-pill er-pill-light">Only 10 spots this cohort</p>
          <h2>Apply today and hear back within 48 hours.</h2>
          <p>Your first month is free — only the ₹<?php echo $fee; ?> consultation is due now.</p>
          <a class="er-btn er-btn-gold" href="apply.php">Apply for a spot</a>
        </div>
      </section>
    </main>
</body>
</html>
