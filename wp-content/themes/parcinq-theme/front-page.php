<?php
/**
 * Front page template.
 *
 * Temporary static homepage content ported from the supplied Parcinq prototype.
 *
 * @package Parcinq_Theme
 */

get_header();
?>
<section class="hero" style="padding:0" id="top">
  <div class="hero-media">
    <div class="hero-monogram">P5</div>
    <div class="hero-grad"></div>
    <div class="wrap hero-content reveal">
      <span class="kicker">The Cover · Boys of Summer 2026</span>
      <h1>Eight Boys, <em>One Endless</em> Summer</h1>
      <p>We took eight of the names defining a new wave of Filipino pop to the shore for three days. The result is our most ambitious cover yet.</p>
      <div class="byline">Art Direction Joe Andy · Photography PARCINQ Studio · Club Laiya, Batangas</div>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn">Enter the Story
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </a>
    </div>
  </div>
</section>

<!-- 2 — COVER STORIES -->
<section id="covers">
  <div class="wrap">
    <div class="sec-head reveal">
      <div><span class="kicker">The Vault</span><h2>Cover Stories</h2></div>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="seeall">All Covers</a>
    </div>
    <div class="cover-grid reveal">
      <a class="cover-card feature" href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <div class="ph g2" data-label="Cover Editorial — Feature"></div>
        <div class="scrim"></div>
        <div class="meta">
          <span class="tag">Cover Story</span>
          <h3>Ron Angeles Is Done Playing It Safe</h3>
        </div>
      </a>
      <div class="cover-col">
        <a class="cover-card" href="<?php echo esc_url( home_url( '/' ) ); ?>">
          <div class="ph g4" data-label="Cover Editorial"></div>
          <div class="scrim"></div>
          <div class="meta"><span class="tag">Exclusive</span><h3>Inside the Mind of Caizer</h3></div>
        </a>
        <a class="cover-card" href="<?php echo esc_url( home_url( '/' ) ); ?>">
          <div class="ph g3" data-label="Cover Editorial"></div>
          <div class="scrim"></div>
          <div class="meta"><span class="tag">Interview</span><h3>Kobie Brown, In Full Bloom</h3></div>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- 3 — CITY BOY FRANCHISE -->
<section class="franchise" style="padding:0" id="cityboy">
  <div class="fr-inner">
    <div class="fr-media">
      <div class="ph g5" data-label="Franchise Cover — City Boy" style="height:100%"></div>
      <div class="scrim"></div>
    </div>
    <div class="fr-text reveal">
      <span class="kicker">A PARCINQ Franchise</span>
      <div class="label">City Boy</div>
      <p>Modern masculinity, after hours. Style, grooming, travel and the creative men shaping the culture of the city.</p>
      <div class="fr-chips">
        <span class="chip">Style</span><span class="chip">Grooming</span>
        <span class="chip">Travel</span><span class="chip">Urban Culture</span><span class="chip">Profiles</span>
      </div>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn ghost" style="align-self:flex-start">Explore City Boy</a>
    </div>
  </div>
</section>

<!-- 4 — WHAT'S NEW -->
<section id="new">
  <div class="wrap">
    <div class="sec-head reveal">
      <div><span class="kicker">Fresh Off the Feed</span><h2>What's New</h2></div>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="seeall">Latest</a>
    </div>
    <div class="new-grid reveal">
      <a class="new-card" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="ph g1" data-label="Article"></div><span class="ck">Music</span><h3>The P-pop Comeback Season Is Officially Here</h3><span class="by">Currie Cator</span></a>
      <a class="new-card" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="ph g6" data-label="Article"></div><span class="ck">Style</span><h3>Six Manila Streetwear Drops You Missed This Week</h3><span class="by">Pete Villalino</span></a>
      <a class="new-card" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="ph g3" data-label="Article"></div><span class="ck">Culture</span><h3>Why Fandom Twitter Won't Let This Trend Die</h3><span class="by">Ethan Carbonilla</span></a>
      <a class="new-card" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="ph g2" data-label="Article"></div><span class="ck">Beauty</span><h3>The Glass Skin Routine, Reconsidered</h3><span class="by">Kryzzle Cailing</span></a>
    </div>
  </div>
</section>

<!-- 5 — MUSIC -->
<section class="alt" id="music">
  <div class="wrap">
    <div class="sec-head reveal">
      <div><span class="kicker">On Repeat</span><h2>Music</h2></div>
      <div class="sub">
        <a class="sublink" href="<?php echo esc_url( home_url( '/' ) ); ?>">P-pop</a><a class="sublink" href="<?php echo esc_url( home_url( '/' ) ); ?>">K-pop</a><a class="sublink" href="<?php echo esc_url( home_url( '/' ) ); ?>">Asian Pop</a>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="seeall">All Music</a>
      </div>
    </div>
    <div class="three-grid reveal">
      <a class="mcard" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="ph g4" data-label="P-pop"></div><div class="scrim"></div><div class="meta"><span class="tag">P-pop</span><h3>The Groups Redefining Filipino Pop in 2026</h3></div></a>
      <a class="mcard" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="ph g3" data-label="K-pop"></div><div class="scrim"></div><div class="meta"><span class="tag">K-pop</span><h3>A Field Guide to This Year's Comebacks</h3></div></a>
      <a class="mcard" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="ph g5" data-label="Asian Pop"></div><div class="scrim"></div><div class="meta"><span class="tag">Asian Pop</span><h3>The Sound Crossing Borders Right Now</h3></div></a>
    </div>
  </div>
</section>

<!-- 6 — STYLE -->
<section id="style">
  <div class="wrap">
    <div class="sec-head reveal">
      <div><span class="kicker">The Edit</span><h2>Style</h2></div>
      <div class="sub">
        <a class="sublink" href="<?php echo esc_url( home_url( '/' ) ); ?>">Fashion</a><a class="sublink" href="<?php echo esc_url( home_url( '/' ) ); ?>">Beauty</a>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="seeall">All Style</a>
      </div>
    </div>
    <div class="style-grid reveal">
      <a class="style-card" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="ph g6" data-label="Fashion Editorial"></div><div class="scrim"></div><div class="meta"><span class="tag">Fashion</span><h3>Soft Power Dressing</h3><p>An editorial study in tailoring, texture and quiet confidence.</p></div></a>
      <a class="style-card" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="ph g4" data-label="Beauty Editorial"></div><div class="scrim"></div><div class="meta"><span class="tag">Beauty</span><h3>Faces of the Season</h3><p>The looks, the products and the artistry behind them.</p></div></a>
    </div>
  </div>
</section>

<!-- 7 — CULTURE -->
<section class="alt" id="culture">
  <div class="wrap">
    <div class="sec-head reveal">
      <div><span class="kicker">Everything Else We Love</span><h2>Culture</h2></div>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="seeall">All Culture</a>
    </div>
    <div class="culture-tabs reveal">
      <span class="ctab active">All</span><span class="ctab">Sports</span><span class="ctab">Gaming</span>
      <span class="ctab">Food</span><span class="ctab">Travel</span><span class="ctab">Film</span>
    </div>
    <div class="culture-grid reveal">
      <a class="new-card" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="ph g3" data-label="Travel"></div><span class="ck">Travel</span><h3>A Long Weekend in Batangas, the PARCINQ Way</h3><span class="by">Editorial Team</span></a>
      <a class="new-card" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="ph g1" data-label="Film"></div><span class="ck">Film</span><h3>The Coming-of-Age Films Defining a Generation</h3><span class="by">Ethan Carbonilla</span></a>
      <a class="new-card" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="ph g5" data-label="Gaming"></div><span class="ck">Gaming</span><h3>When Idols and Gaming Collide</h3><span class="by">Pete Villalino</span></a>
    </div>
  </div>
</section>

<!-- 8 — VIDEOS -->
<section class="videos" id="videos">
  <div class="wrap">
    <div class="sec-head reveal">
      <div><span class="kicker light">Press Play</span><h2 style="color:#fff">Videos</h2></div>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="seeall" style="color:#fff">All Videos</a>
    </div>
    <div class="vid-grid reveal">
      <a class="vcard lead" href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <div class="ph g2" data-label="Video — Featured" style="position:absolute;inset:0;height:100%"></div>
        <div class="scrim"></div>
        <div class="play"><svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></div>
        <div class="meta"><span class="vseries">CINQ</span><h3 style="color:#fff">Boys of Summer: Behind the Cover</h3></div>
      </a>
      <div class="vid-col">
        <a class="vcard small" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="ph g4" data-label="Clip"></div><div><span class="ck">Word on the Street</span><h3 style="color:#fff">Manila Reacts to the New Drop</h3></div></a>
        <a class="vcard small" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="ph g3" data-label="Clip"></div><div><span class="ck">B-Side</span><h3 style="color:#fff">Unreleased, Unfiltered</h3></div></a>
        <a class="vcard small" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="ph g5" data-label="Clip"></div><div><span class="ck">In Conversation</span><h3 style="color:#fff">Five Minutes with Caizer</h3></div></a>
      </div>
    </div>
  </div>
</section>

<!-- 9 — SHOP -->
<section id="shop">
  <div class="wrap">
    <div class="shop-head reveal">
      <div><span class="kicker">PARCINQ Marketplace</span><h2 style="font-family:var(--serif);font-weight:500;font-size:clamp(30px,4.4vw,52px);letter-spacing:-.02em;line-height:1">Shop</h2></div>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="seeall">Visit the Store</a>
    </div>
    <p class="shop-note reveal">Collectible print objects, photocards and editorial merch. Powered by our Shopify store, lives right here in the PARCINQ world.</p>
    <div class="shop-grid reveal">
      <div class="product"><div class="ph" data-label="Product"></div><h3>Boys of Summer Photocard Set</h3><div class="price">₱450.00</div><div class="add">+ Add to Cart</div></div>
      <div class="product"><div class="ph" data-label="Product"></div><h3>PARCINQ Issue No. 12 — Print</h3><div class="price">₱650.00</div><div class="add">+ Add to Cart</div></div>
      <div class="product"><div class="ph" data-label="Product"></div><h3>City Boy Sticker Pack</h3><div class="price">₱180.00</div><div class="add">+ Add to Cart</div></div>
      <div class="product"><div class="ph" data-label="Product"></div><h3>Collector's Cover Print</h3><div class="price">₱350.00</div><div class="add">+ Add to Cart</div></div>
    </div>
  </div>
</section>

<!-- 10 — NEWSLETTER -->
<section class="news">
  <div class="wrap reveal">
    <span class="kicker" style="color:#111">Don't Miss a Cover</span>
    <h2>Join the PARCINQ List</h2>
    <p>Covers, culture and the occasional secret drop, straight to your inbox. No noise.</p>
    <div class="news-form">
      <input type="email" placeholder="your@email.com" aria-label="Email address">
      <button>Subscribe</button>
    </div>
  </div>
</section>

<!-- footer -->
<?php
get_footer();