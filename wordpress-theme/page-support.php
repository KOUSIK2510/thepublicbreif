<?php
/**
 * Support Page Template
 *
 * Template Name: Support Page
 */
get_header();
?>

<style>
/* ── Support Page Styles ─────────────────────── */
.sp-wrap {
  font-family: 'Inter', sans-serif;
  color: #0d0d0d;
  background: #fff;
  padding-bottom: 80px;
}
.sp-playfair { font-family: 'Playfair Display', serif !important; }

/* Global Section Styles */
.sp-section {
  padding: 64px 24px;
  max-width: 800px;
  margin: 0 auto;
}
.sp-section-wide {
  max-width: 1000px;
}
.sp-eyebrow {
  display: inline-block;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .25em;
  text-transform: uppercase;
  color: #b90c25;
  padding-bottom: 8px;
  margin-bottom: 32px;
  border-bottom: 2px solid #b90c25;
}
.sp-center { text-align: center; }

/* ── Our Story Section ──────────────────────── */
.sp-story-content p {
  font-size: 16px;
  line-height: 1.8;
  color: #374151;
  margin-bottom: 24px;
}
.sp-story-content strong {
  color: #111827;
  font-weight: 700;
}
.sp-signature {
  margin-top: 40px;
  padding-left: 16px;
  border-left: 3px solid #b90c25;
}
.sp-signature-title {
  font-size: 15px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 4px;
}
.sp-signature-name {
  font-size: 15px;
  font-weight: 700;
  color: #b90c25;
}

/* ── Your Impact Section ────────────────────── */
.sp-impact-section {
  padding: 80px 24px;
  border-top: 1px solid #f3f4f6;
  border-bottom: 1px solid #f3f4f6;
  background: #fafafa;
}
.sp-impact-title {
  font-size: 32px;
  font-weight: 700;
  color: #0d0d0d;
  margin-bottom: 48px;
}
.sp-impact-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
}
.sp-impact-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-top: 3px solid #b90c25;
  padding: 32px 24px;
  text-align: center;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
}
.sp-impact-icon {
  color: #b90c25;
  margin-bottom: 16px;
  display: inline-flex;
}
.sp-impact-card h3 {
  font-family: 'Playfair Display', serif;
  font-size: 18px;
  font-weight: 700;
  color: #0d0d0d;
  margin-bottom: 12px;
}
.sp-impact-card p {
  font-size: 13.5px;
  line-height: 1.6;
  color: #6b7280;
}

/* ── Contribute Section ─────────────────────── */
.sp-contribute-section {
  padding: 80px 24px;
}
.sp-contribute-title {
  font-size: 32px;
  font-weight: 700;
  color: #0d0d0d;
  margin-bottom: 16px;
}
.sp-contribute-subtitle {
  font-size: 15px;
  color: #6b7280;
  margin-bottom: 48px;
}

.sp-amounts-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  max-width: 600px;
  margin: 0 auto 32px;
}
.sp-amount-btn {
  background: #fff;
  border: 1px solid #e5e7eb;
  color: #111827;
  font-weight: 700;
  font-size: 16px;
  padding: 16px;
  cursor: pointer;
  transition: all 0.2s;
  text-align: center;
}
.sp-amount-btn:hover {
  border-color: #b90c25;
  color: #b90c25;
}
.sp-amount-btn.active {
  background: #b90c25;
  border-color: #b90c25;
  color: #fff;
}

.sp-frequency-toggle {
  display: flex;
  justify-content: center;
  gap: 0;
  max-width: 300px;
  margin: 0 auto 40px;
}
.sp-freq-btn {
  flex: 1;
  background: #fff;
  border: 1px solid #e5e7eb;
  color: #6b7280;
  font-weight: 700;
  font-size: 12px;
  letter-spacing: .05em;
  text-transform: uppercase;
  padding: 12px;
  cursor: pointer;
  transition: all 0.2s;
}
.sp-freq-btn:first-child { border-right: none; }
.sp-freq-btn.active {
  background: #0d0d0d;
  border-color: #0d0d0d;
  color: #fff;
}

.sp-submit-btn {
  display: block;
  width: 100%;
  max-width: 600px;
  margin: 0 auto;
  background: #b90c25;
  color: #fff;
  font-weight: 700;
  font-size: 15px;
  letter-spacing: .05em;
  text-transform: uppercase;
  padding: 20px;
  border: none;
  cursor: pointer;
  transition: background 0.2s;
}
.sp-submit-btn:hover {
  background: #9a0a1f;
}

.sp-secure-text {
  font-size: 12px;
  color: #9ca3af;
  text-align: center;
  margin-top: 16px;
}

/* Responsive */
@media (max-width: 768px) {
  .sp-impact-grid { grid-template-columns: repeat(2, 1fr); }
  .sp-amounts-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
  .sp-impact-grid { grid-template-columns: 1fr; }
  .sp-amounts-grid { grid-template-columns: 1fr; }
  .sp-story-content p { font-size: 15px; }
}
</style>

<div class="sp-wrap">

  <!-- OUR STORY -->
  <section class="sp-section">
    <div class="sp-eyebrow">Our Story</div>
    
    <div class="sp-story-content">
      <p>
        The Public Brief was established with one goal: to ensure that journalism is <strong>accessible, credible and meaningful</strong>. As a remote-first digital newsroom, The Public Brief has been created through the combined efforts of journalists, researchers, editors, and contributors who are united by a shared commitment to transparent, credible, impactful and factual journalism.
      </p>
      <p>
        We believe that all serious stories should be supported by background information, different angles, and ethical reporting, rather than sensationalism. Alongside covering news, we are establishing a community where young writers and journalists can hone their skills and express their opinions.
      </p>
      <p>
        <strong>Independent journalism is sustained by readers who support truth, openness, and accountability.</strong> Your contributions can help us enhance our reporting, promote factual journalism, provide opportunities for young writers, and maintain our freedom.
      </p>
      <p>
        With any contribution, regardless of its size, we will be able to stay independent and focus on what is important: <strong>serving the public interest.</strong>
      </p>
      <p>
        Thank you for your support and trust in our mission.
      </p>

      <div class="sp-signature">
        <div class="sp-signature-title">Warm Regards,</div>
        <div class="sp-signature-name">Team The Public Brief</div>
      </div>
    </div>
  </section>

  <!-- YOUR IMPACT -->
  <section class="sp-impact-section">
    <div class="sp-section sp-section-wide sp-center" style="padding: 0;">
      <div class="sp-eyebrow">Your Impact</div>
      <h2 class="sp-impact-title sp-playfair">What Your Support Enables</h2>
      
      <div class="sp-impact-grid">
        <!-- Card 1 -->
        <div class="sp-impact-card">
          <div class="sp-impact-icon">
            <span class="material-symbols-outlined" style="font-size: 32px;">fact_check</span>
          </div>
          <h3>Better Reporting</h3>
          <p>Enhancing our investigative capacity and editorial depth on every story we publish.</p>
        </div>
        
        <!-- Card 2 -->
        <div class="sp-impact-card">
          <div class="sp-impact-icon">
            <span class="material-symbols-outlined" style="font-size: 32px;">school</span>
          </div>
          <h3>Young Writers</h3>
          <p>Providing meaningful opportunities for emerging journalists to grow their craft.</p>
        </div>
        
        <!-- Card 3 -->
        <div class="sp-impact-card">
          <div class="sp-impact-icon">
            <span class="material-symbols-outlined" style="font-size: 32px;">shield_person</span>
          </div>
          <h3>Independence</h3>
          <p>Maintaining editorial freedom — no advertisers, no agendas, only public interest.</p>
        </div>
        
        <!-- Card 4 -->
        <div class="sp-impact-card">
          <div class="sp-impact-icon">
            <span class="material-symbols-outlined" style="font-size: 32px;">public</span>
          </div>
          <h3>Access for All</h3>
          <p>Keeping credible journalism free and accessible to every Indian reader.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CONTRIBUTE -->
  <section class="sp-contribute-section">
    <div class="sp-section sp-center" style="padding: 0;">
      <div class="sp-eyebrow">Contribute</div>
      <h2 class="sp-contribute-title sp-playfair">Choose Your Contribution</h2>
      <p class="sp-contribute-subtitle">Every rupee helps us stay independent. Pick an amount below or enter your own.</p>
      
      <!-- Amount Grid -->
      <div class="sp-amounts-grid">
        <div class="sp-amount-btn">₹50</div>
        <div class="sp-amount-btn active">₹100</div>
        <div class="sp-amount-btn">₹200</div>
        <div class="sp-amount-btn">₹500</div>
        <div class="sp-amount-btn">₹1,000</div>
        <div class="sp-amount-btn">Custom</div>
      </div>

      <!-- Frequency Toggle -->
      <div class="sp-frequency-toggle">
        <div class="sp-freq-btn active">One-Time</div>
        <div class="sp-freq-btn">Monthly</div>
      </div>

      <!-- Submit -->
      <button class="sp-submit-btn">Support The Public Brief</button>
      <div class="sp-secure-text">Secure & encrypted. Cancel monthly support anytime.</div>

    </div>
  </section>

</div>

<script>
  // Simple interaction script for contribution buttons
  document.addEventListener('DOMContentLoaded', function() {
    const amountBtns = document.querySelectorAll('.sp-amount-btn');
    const freqBtns = document.querySelectorAll('.sp-freq-btn');

    amountBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        amountBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
      });
    });

    freqBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        freqBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
      });
    });
  });
</script>

<?php get_footer(); ?>
