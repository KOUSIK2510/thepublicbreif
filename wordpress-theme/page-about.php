<?php
/**
 * About Page Template — The Public Brief
 *
 * Template Name: About
 */
get_header();
?>

<style>
  /* ── About Page Scoped Styles ─────────────────────────── */
  .tpb-about { font-family: 'Inter', sans-serif; }
  .tpb-playfair { font-family: 'Playfair Display', serif !important; }

  /* HERO */
  .tpb-hero {
    background: #0d0d0d;
    padding: 96px 24px 104px;
    text-align: center;
  }
  .tpb-hero-inner { max-width: 720px; margin: 0 auto; }
  .tpb-hero-eyebrow {
    display: inline-block;
    font-size: 11px; font-weight: 700;
    letter-spacing: .35em; text-transform: uppercase;
    color: #e05a6a;
    border: 1px solid #6b2030;
    padding: 6px 16px;
    margin-bottom: 32px;
  }
  .tpb-hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.4rem, 5vw, 3.8rem);
    font-weight: 800; line-height: 1.12;
    color: #ffffff; margin-bottom: 36px;
  }
  .tpb-hero-title span { color: #e05a6a; }
  .tpb-hero-body { max-width: 620px; margin: 0 auto; }
  .tpb-hero-lead {
    font-size: 17px; line-height: 1.8;
    color: rgba(255,255,255,.75); margin-bottom: 20px;
  }
  .tpb-hero-lead strong { color: #fff; font-weight: 600; }
  .tpb-hero-sub {
    font-size: 16px; line-height: 1.85;
    color: rgba(255,255,255,.60); margin-bottom: 18px;
  }

  /* IMPACT BANNER */
  .tpb-banner { background: #b90c25; padding: 22px 24px; text-align: center; }
  .tpb-banner p {
    font-family: 'Playfair Display', serif;
    font-style: italic;
    font-size: clamp(1.1rem, 2.8vw, 1.55rem);
    font-weight: 600; color: #fff;
    letter-spacing: .01em; line-height: 1.4;
  }

  /* PURPOSE */
  .tpb-purpose { background: #fff; padding: 88px 24px; }
  .tpb-section-hd { text-align: center; margin-bottom: 64px; }
  .tpb-eyebrow {
    display: inline-block;
    font-size: 11px; font-weight: 700;
    letter-spacing: .35em; text-transform: uppercase;
    color: #b90c25;
    padding-bottom: 10px;
    border-bottom: 2px solid #b90c25;
    margin-bottom: 16px;
  }
  .tpb-sub { font-size: 15px; color: #6b7280; line-height: 1.6; }
  .tpb-cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
    max-width: 960px; margin: 0 auto;
  }
  .tpb-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-top: 4px solid #b90c25;
    padding: 40px 36px;
    transition: box-shadow .25s, transform .25s;
  }
  .tpb-card:hover { box-shadow: 0 12px 40px rgba(0,0,0,.08); transform: translateY(-3px); }
  .tpb-card-lbl-wrap { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
  .tpb-card-icon {
    width: 38px; height: 38px;
    background: #fff0f2; border: 1px solid #fcc;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .tpb-card-icon .material-symbols-outlined { font-size: 20px; color: #b90c25; }
  .tpb-card-lbl { font-size: 11px; font-weight: 700; letter-spacing: .3em; text-transform: uppercase; color: #b90c25; }
  .tpb-card-title {
    font-family: 'Playfair Display', serif;
    font-size: 21px; font-weight: 700; line-height: 1.3;
    color: #1a1a1a; margin-bottom: 16px;
  }
  .tpb-card-body { font-size: 15.5px; line-height: 1.75; color: #4a4a4a; }

  /* FOUNDER */
  .tpb-founder { background: #0d0d0d; padding: 88px 24px; }
  .tpb-founder-inner { max-width: 720px; margin: 0 auto; }
  .tpb-founder-eyebrow { text-align: center; margin-bottom: 52px; }
  .tpb-founder-eyebrow span {
    font-size: 11px; font-weight: 700; letter-spacing: .35em;
    text-transform: uppercase; color: #e05a6a;
    padding-bottom: 10px; border-bottom: 2px solid #b90c25;
  }
  .tpb-quote {
    border-left: 4px solid #b90c25;
    padding-left: 28px; margin-bottom: 48px;
  }
  .tpb-quote-text {
    font-family: 'Playfair Display', serif; font-style: italic;
    font-size: clamp(1.1rem, 2.5vw, 1.45rem);
    line-height: 1.65; color: rgba(255,255,255,.90);
    margin-bottom: 14px;
  }
  .tpb-quote-attr {
    font-size: 12px; font-weight: 700;
    letter-spacing: .25em; text-transform: uppercase; color: #e05a6a;
  }
  .tpb-letter {
    font-size: 17px; line-height: 1.9;
    color: rgba(255,255,255,.68); margin-bottom: 48px;
  }
  .tpb-letter p + p { margin-top: 20px; }
  .tpb-letter strong { color: #fff; font-weight: 600; }
  .tpb-letter em { font-style: normal; color: #fff; font-weight: 500; }
  .tpb-divider { border: none; border-top: 1px solid rgba(255,255,255,.12); margin-bottom: 32px; }
  .tpb-sig { display: flex; align-items: center; gap: 18px; }
  .tpb-avatar {
    width: 60px; height: 60px; border-radius: 50%;
    background: #b90c25;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Playfair Display', serif;
    font-weight: 700; font-size: 20px; color: #fff;
    flex-shrink: 0; border: 2px solid rgba(255,255,255,.15);
  }
  .tpb-sig-name { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 20px; color: #fff; margin-bottom: 4px; }
  .tpb-sig-role { font-size: 11px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #e05a6a; margin-bottom: 3px; }
  .tpb-sig-org { font-size: 13px; color: rgba(255,255,255,.40); }

  /* CTA */
  .tpb-cta { background: #f7f7f7; border-top: 1px solid #e5e7eb; padding: 72px 24px; text-align: center; }
  .tpb-cta-icon .material-symbols-outlined { font-size: 38px; color: #b90c25; display: block; margin-bottom: 16px; }
  .tpb-cta-title { font-family: 'Playfair Display', serif; font-size: 26px; font-weight: 700; color: #1a1a1a; margin-bottom: 12px; }
  .tpb-cta-desc { font-size: 15px; line-height: 1.7; color: #6b7280; max-width: 420px; margin: 0 auto 32px; }
  .tpb-cta-btn {
    display: inline-block; background: #b90c25; color: #fff;
    font-size: 12px; font-weight: 700; letter-spacing: .2em;
    text-transform: uppercase; padding: 14px 40px;
    text-decoration: none; transition: background .2s;
  }
  .tpb-cta-btn:hover { background: #8e0a1c; color: #fff; }

  @media (max-width: 768px) {
    .tpb-hero { padding: 64px 20px 72px; }
    .tpb-purpose { padding: 60px 20px; }
    .tpb-cards { grid-template-columns: 1fr; gap: 20px; }
    .tpb-founder { padding: 60px 20px; }
    .tpb-cta { padding: 56px 20px; }
  }
</style>

<div class="tpb-about">

  <!-- ── HERO ──────────────────────────────────────────── -->
  <section class="tpb-hero">
    <div class="tpb-hero-inner">
      <span class="tpb-hero-eyebrow">About The Public Brief</span>
      <h1 class="tpb-hero-title tpb-playfair">
        Why The Public Brief<br>
        <span>Exists</span>
      </h1>
      <div class="tpb-hero-body">
        <p class="tpb-hero-lead">
          The Public Brief is more than a news platform, it is a
          <strong>movement to strengthen Indian journalism.</strong>
        </p>
        <p class="tpb-hero-sub">
          In an era of misinformation and sensationalism, we stand for journalism that is credible,
          authentic, and trustworthy. We believe every fact matters, every story deserves context,
          and every voice deserves to be heard.
        </p>
        <p class="tpb-hero-sub">
          By creating a platform for independent journalists, researchers, writers, and storytellers,
          we aim to strengthen India's journalistic landscape and ensure that truth remains at the
          heart of public discourse.
        </p>
      </div>
    </div>
  </section>

  <!-- ── IMPACT BANNER ─────────────────────────────────── -->
  <div class="tpb-banner">
    <p class="tpb-playfair">
      Strengthening Indian Journalism.&nbsp;&nbsp; Empowering New Voices.&nbsp;&nbsp; Serving the Public.
    </p>
  </div>

  <!-- ── PURPOSE / MISSION + VISION ───────────────────── -->
  <section class="tpb-purpose">
    <div class="tpb-section-hd">
      <span class="tpb-eyebrow">Our Purpose</span>
      <p class="tpb-sub">What drives every story we publish and every voice we platform.</p>
    </div>
    <div class="tpb-cards">

      <!-- Mission -->
      <div class="tpb-card">
        <div class="tpb-card-lbl-wrap">
          <div class="tpb-card-icon">
            <span class="material-symbols-outlined">track_changes</span>
          </div>
          <span class="tpb-card-lbl">Mission</span>
        </div>
        <p class="tpb-card-title tpb-playfair">Fostering a well-informed public discussion.</p>
        <p class="tpb-card-body">
          To foster a well-informed public discussion on a platform where young writers, researchers,
          and journalists can discuss politics and society through fact-based journalism.
        </p>
      </div>

      <!-- Vision -->
      <div class="tpb-card">
        <div class="tpb-card-lbl-wrap">
          <div class="tpb-card-icon">
            <span class="material-symbols-outlined">visibility</span>
          </div>
          <span class="tpb-card-lbl">Vision</span>
        </div>
        <p class="tpb-card-title tpb-playfair">Building India's most trusted journalism community.</p>
        <p class="tpb-card-body">
          To build India's most reliable, trusted community of young journalists and writers that will
          help create credible, ethical, impactful, and factual journalism with well-informed political
          and public discussions.
        </p>
      </div>

    </div>
  </section>

  <!-- ── FOUNDER'S MESSAGE ─────────────────────────────── -->
  <section class="tpb-founder">
    <div class="tpb-founder-inner">

      <div class="tpb-founder-eyebrow">
        <span>Founder's Message</span>
      </div>

      <!-- Pull Quote -->
      <div class="tpb-quote">
        <p class="tpb-quote-text tpb-playfair">
          "Without facts, there is no truth. Without truth, there is no trust."
        </p>
        <p class="tpb-quote-attr">— Maria Ressa</p>
      </div>

      <!-- Letter -->
      <div class="tpb-letter">
        <p>
          I founded <em>The Public Brief</em> with a simple purpose: to pursue the truth without
          hesitation and create a platform where young journalists, writers, researchers, and
          independent voices can share their ideas, perspectives, and stories without fear.
        </p>
        <p>
          This is more than a news platform—it is a <strong>movement to strengthen Indian
          journalism</strong> through trust, credibility, and fact-based reporting. It is a place
          where voices are heard, facts matter, and public interest always comes first.
        </p>
        <p>
          I invite you to be a part of this movement. Together, let us build a future where
          journalism serves the public with honesty, courage, and purpose.
        </p>
      </div>

      <hr class="tpb-divider"/>

      <!-- Signature -->
      <div class="tpb-sig">
        <div class="tpb-avatar tpb-playfair">KK</div>
        <div>
          <p class="tpb-sig-name tpb-playfair">Kaifullah Khan</p>
          <p class="tpb-sig-role">Founder &amp; Editor-in-Chief</p>
          <p class="tpb-sig-org">The Public Brief</p>
        </div>
      </div>

    </div>
  </section>

  <!-- ── CTA ───────────────────────────────────────────── -->
  <section class="tpb-cta">
    <div class="tpb-cta-icon">
      <span class="material-symbols-outlined">newspaper</span>
    </div>
    <h2 class="tpb-cta-title tpb-playfair">Be Part of the Movement</h2>
    <p class="tpb-cta-desc">
      Join a growing community of readers and writers committed to honest, independent journalism.
    </p>
    <a href="<?php echo esc_url( home_url('/subscribe') ); ?>" class="tpb-cta-btn">
      Join The Brief
    </a>
  </section>

</div><!-- /.tpb-about -->

<?php get_footer(); ?>
