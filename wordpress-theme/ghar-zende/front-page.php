<?php
/**
 * Home page — the nine-scene cinematic scroll story, ported 1:1 from
 * src/app/page.tsx + src/sections/Scene01..09*.tsx (classes/markup lifted
 * from the real Tailwind build's rendered output, so assets/css/theme.css
 * — the compiled Tailwind bundle from that same build — styles it
 * identically). Scroll-scrubbed behaviour lives in assets/js/home.js.
 */
get_header();
?>

<div id="scenePulse" aria-hidden="true"></div>
<div id="introCurtain">
  <span class="intro-sub">غار زنده</span>
  <span class="intro-word">جایی که سنگ، آب و <em>زندگی</em> به‌هم می‌رسند</span>
</div>

<?php
// All text/links/images below come from the "صفحه اصلی" admin panel
// (inc/homepage-panel.php), falling back to its built-in defaults —
// which are exactly what used to be hardcoded here — until an admin
// edits something there.
$species       = ghar_zende_home_get( 'species' );
$geology_facts = ghar_zende_home_get( 'geology_facts' );
?>

<section id="hero" aria-label="ورود به غار" class="relative flex h-[100svh] min-h-[560px] w-full items-center justify-center overflow-hidden bg-void">
  <div class="absolute inset-0 overflow-hidden" aria-hidden="true">
    <img alt="نمای تاریک و مرموز از دل یک شکاف صخره‌ای، با نوری بسیار کم‌رنگ در انتهای مسیر" decoding="async" class="scene-photo absolute inset-0 h-full w-full object-cover" style="filter:brightness(1.55) saturate(1.1)" src="<?php echo esc_url( ghar_zende_home_image( 'hero_image', 'hero-entrance.webp' ) ); ?>" />
    <!-- Lightened from the original bg-void/55 + 0.55/0.75 overlay opacities:
         stacked together they made the very first thing a visitor sees
         almost solid black — the photo itself (already a deliberately dark
         "mysterious cave entrance" shot) was barely visible under them.
         Bumped a second time (brightness 1.35->1.55, overlays down again)
         per user feedback that the first pass was still too dark. -->
    <div class="absolute inset-0 bg-void" style="opacity:0.14"></div>
    <div class="absolute inset-0" style="background:radial-gradient(120% 90% at 20% 15%, transparent 0%, var(--color-void) 92%);opacity:0.24"></div>
    <div class="absolute inset-0" style="background:radial-gradient(45% 35% at 50% 50%, var(--color-amber-glow) 0%, transparent 70%);opacity:0.18;mix-blend-mode:screen"></div>
    <div class="noise-overlay"></div>
    <div class="absolute inset-0" style="background:linear-gradient(180deg, rgba(5,7,8,0) 0%, rgba(5,7,8,0.35) 100%)"></div>
  </div>
  <div class="particle-field absolute inset-0" data-variant="dust" data-count="26" aria-hidden="true"><?php ghar_zende_particles( 'dust', 26 ); ?></div>

  <div class="relative z-10 mx-auto flex max-w-3xl flex-col items-center gap-8 px-6 text-center">
    <p class="hero-eyebrow font-display text-xs uppercase tracking-[0.4em] text-turquoise-soft"><?php echo esc_html( ghar_zende_home_get( 'hero_eyebrow' ) ); ?></p>
    <h1 class="text-balance font-display text-4xl font-semibold leading-[1.35] text-foam sm:text-5xl md:text-6xl"><span class="line"><?php echo esc_html( ghar_zende_home_get( 'hero_title_line1' ) ); ?></span><br /><span class="line"><?php echo esc_html( ghar_zende_home_get( 'hero_title_line2' ) ); ?></span></h1>
    <p class="text-balance text-base text-foam-dim sm:text-lg hero-lede"><?php echo esc_html( ghar_zende_home_get( 'hero_lede' ) ); ?></p>
    <?php
    // Uploaded to the WordPress media library directly (not a theme
    // asset file — WPVibe's file tools only push text-editable
    // extensions, not binary audio), so this points at its uploads/
    // URL rather than GHAR_ZENDE_URI. assets/audio/discover-cave.mp3
    // in the repo is the same clip, kept for the git/local history.
    $hero_discover_audio = 'https://gharakvariom.ir/wp-content/uploads/2026/09/ElevenLabs_2026-09-08T06_38_41__s50_v3.mp3';
    ?>
    <a href="<?php echo esc_url( ghar_zende_home_get( 'hero_cta_href' ) ); ?>" data-cursor="کشف" data-magnetic data-audio="<?php echo esc_url( $hero_discover_audio ); ?>" class="hero-cta group relative mt-4 inline-flex items-center gap-3 rounded-full border border-foam/25 px-7 py-3 text-sm text-foam transition-colors hover:border-turquoise hover:text-turquoise-soft"><?php echo esc_html( ghar_zende_home_get( 'hero_cta_text' ) ); ?></a>
  </div>

  <a href="#darkness" class="scroll-hint absolute inset-x-0 bottom-8 z-10 mx-auto flex w-fit flex-col items-center gap-2 text-foam-faint">
    <span class="font-display text-[10px] uppercase tracking-[0.35em]">SCROLL TO EXPLORE</span>
    <span class="scroll-chevron h-8 w-px bg-gradient-to-b from-foam-faint to-transparent"></span>
  </a>
</section>

<section id="darkness" aria-label="ورود به تاریکی" class="relative flex h-[130vh] w-full items-center justify-center overflow-hidden bg-void">
  <div class="parallax-layer absolute inset-0 scale-110" aria-hidden="true">
    <img alt="نمای عبور از میان صخره‌ها به سمت ردیفی از آکواریوم‌های نورانی در دوردست" loading="lazy" decoding="async" class="absolute inset-0 h-full w-full object-cover" src="<?php echo esc_url( ghar_zende_home_image( 'darkness_image', 'darkness-threshold.webp' ) ); ?>" />
    <div class="absolute inset-0 bg-void/60"></div>
  </div>
  <div class="parallax-layer absolute inset-x-0 bottom-0 h-[55%]" style="background:linear-gradient(0deg, var(--color-stone-900) 0%, transparent 100%);clip-path:polygon(0% 100%, 0% 30%, 12% 45%, 24% 20%, 38% 50%, 52% 15%, 68% 42%, 82% 10%, 100% 38%, 100% 100%)" aria-hidden="true"></div>
  <div class="parallax-layer absolute inset-x-0 top-0 h-[40%]" style="background:linear-gradient(180deg, var(--color-void) 0%, transparent 100%);clip-path:polygon(0% 0%, 100% 0%, 100% 55%, 84% 30%, 70% 60%, 55% 25%, 40% 58%, 26% 22%, 10% 50%, 0% 35%)" aria-hidden="true"></div>

  <!-- torch/flashlight sweep, painted from the same photo, masked to a small
       circle that follows the cursor (assets/js/home.js sets --mx/--my) -->
  <div id="torchLayer" class="torch-layer" aria-hidden="true">
    <img alt="" src="<?php echo esc_url( ghar_zende_home_image( 'darkness_image', 'darkness-threshold.webp' ) ); ?>" />
  </div>

  <div class="relative z-10 px-6 text-center">
    <p class="text-balance font-display text-2xl leading-relaxed text-foam sm:text-3xl"><?php echo ghar_zende_split_words( ghar_zende_home_get( 'darkness_text' ) ); ?></p>
  </div>
</section>

<section id="water" aria-label="نخستین آب" class="relative flex h-[90vh] min-h-[520px] w-full items-center justify-center overflow-hidden bg-void">
  <div class="absolute inset-0 overflow-hidden" aria-hidden="true">
    <img alt="ردیفی از آکواریوم‌های نورانی در دل صخره، دیده‌شده از فاصله‌ای نزدیک‌تر" loading="lazy" decoding="async" class="scene-photo absolute inset-0 h-full w-full object-cover" src="<?php echo esc_url( ghar_zende_home_image( 'water_image', 'water-corridor.webp' ) ); ?>" />
    <div class="absolute inset-0 bg-void/55"></div>
    <div class="absolute inset-0" style="background:radial-gradient(120% 90% at 20% 15%, transparent 0%, var(--color-void) 92%);opacity:0.55"></div>
    <div class="absolute inset-0" style="background:radial-gradient(45% 35% at 110% 40%, var(--color-amber-glow) 0%, transparent 70%);opacity:0.24;mix-blend-mode:screen"></div>
    <div class="noise-overlay"></div>
    <div class="absolute inset-0" style="background:linear-gradient(180deg, rgba(5,7,8,0) 0%, rgba(5,7,8,0.75) 100%)"></div>
  </div>
  <div class="absolute inset-0" style="background:radial-gradient(70% 60% at 50% 60%, var(--color-ocean-900) 0%, transparent 70%);opacity:0.6" aria-hidden="true"></div>
  <div class="particle-field absolute inset-0" data-variant="bubble" data-count="18" aria-hidden="true"><?php ghar_zende_particles( 'bubble', 18 ); ?></div>
  <div class="particle-field absolute inset-0" data-variant="dust" data-count="16" aria-hidden="true"><?php ghar_zende_particles( 'dust', 16 ); ?></div>

  <div class="relative z-10 max-w-xl px-6 text-center">
    <p class="text-balance font-display text-2xl leading-relaxed text-foam sm:text-3xl"><?php echo ghar_zende_split_words( ghar_zende_home_get( 'water_text1' ) ); ?><br /><?php echo ghar_zende_split_words( ghar_zende_home_get( 'water_text2' ) ); ?></p>
  </div>
</section>

<section id="aquarium" aria-label="آکواریوم درون صخره" class="relative h-[320vh] w-full bg-void">
  <svg width="0" height="0" class="absolute" aria-hidden="true">
    <defs>
      <clipPath id="cave-window-a" clipPathUnits="objectBoundingBox">
        <path d="M0.03,0.42 C0.01,0.22 0.09,0.08 0.24,0.05 C0.38,0.01 0.55,0 0.7,0.04 C0.88,0.08 0.98,0.2 0.97,0.4 C0.99,0.58 0.96,0.78 0.85,0.9 C0.72,1.0 0.5,1.0 0.32,0.95 C0.14,0.9 0.02,0.75 0.01,0.58 C0,0.53 0.02,0.47 0.03,0.42 Z" />
      </clipPath>
      <clipPath id="cave-window-b" clipPathUnits="objectBoundingBox">
        <path d="M0.05,0.5 C0.02,0.3 0.12,0.1 0.3,0.06 C0.5,0.02 0.7,0 0.85,0.1 C0.97,0.18 1.0,0.35 0.96,0.52 C1.0,0.68 0.94,0.85 0.78,0.94 C0.6,1.0 0.38,0.98 0.22,0.9 C0.08,0.82 0.02,0.66 0.05,0.5 Z" />
      </clipPath>
    </defs>
  </svg>
  <div id="aquariumSticky" class="sticky top-0 flex h-[100svh] w-full items-center justify-center overflow-hidden bg-void">
    <div id="aquariumWindow" class="relative aspect-[4/3] w-[min(88vw,720px)]">
      <div class="absolute inset-0 overflow-hidden">
        <img alt="سنگ خام دیواره‌ی غار، اطراف پنجره‌ی آکواریوم" loading="lazy" decoding="async" class="absolute inset-0 h-full w-full object-cover" src="<?php echo esc_url( ghar_zende_home_image( 'aquarium_frame_image', 'corridor-panorama-dark.webp' ) ); ?>" />
        <div class="absolute inset-0" style="background:radial-gradient(120% 100% at 30% 20%, rgba(26,23,19,0.35) 0%, rgba(5,7,8,0.88) 75%);box-shadow:inset 0 0 60px rgba(0,0,0,0.6)"></div>
      </div>
      <div class="absolute inset-[6%] overflow-hidden" style="clip-path:url(#cave-window-a)">
        <div class="js-aq-photo absolute inset-0 scale-110 transition-[filter] duration-150" style="filter:brightness(0.1) saturate(0.15) blur(16px)">
          <img alt="آکواریومی درون‌صخره‌ای با نور آبی، گیاهان آبزی و چند ماهی رنگارنگ" decoding="async" class="absolute inset-0 h-full w-full object-cover" src="<?php echo esc_url( ghar_zende_home_image( 'aquarium_clear_image', 'aquarium-window-clear.webp' ) ); ?>" />
        </div>
        <div class="js-aq-pulse absolute inset-0" style="opacity:0;background:radial-gradient(45% 60% at 65% 10%, rgba(79,216,196,0.5), transparent 70%);mix-blend-mode:screen"></div>
        <div class="js-aq-bubbles particle-field absolute inset-0" data-variant="bubble" data-count="10" style="opacity:0" aria-hidden="true"><?php ghar_zende_particles( 'bubble', 10 ); ?></div>
        <div id="aqPlankton" class="aq-plankton" aria-hidden="true"></div>
        <div class="absolute inset-0 pointer-events-none" style="background:linear-gradient(115deg, rgba(244,239,227,0.08) 0%, transparent 30%, transparent 70%, rgba(244,239,227,0.05) 100%)"></div>
        <div id="aqSweep" class="aq-sweep" aria-hidden="true"></div>
      </div>
      <div class="absolute inset-[6%] pointer-events-none" style="clip-path:url(#cave-window-a);box-shadow:inset 0 0 24px 10px rgba(0,0,0,0.55)"></div>
    </div>

    <div class="pointer-events-none absolute inset-x-0 bottom-10 z-10 flex flex-col items-center gap-3 px-6 text-center">
      <?php $aquarium_phases = ghar_zende_home_get( 'aquarium_phases' ); ?>
      <span id="aqPhase" class="font-display text-[11px] uppercase tracking-[0.4em] text-turquoise-soft"><?php echo esc_html( ! empty( $aquarium_phases[0]['label'] ) ? $aquarium_phases[0]['label'] : 'سنگ' ); ?></span>
      <p id="aqCaption" class="text-balance font-display text-xl text-foam transition-opacity duration-500 sm:text-2xl" style="opacity:0"><?php echo esc_html( ghar_zende_home_get( 'aquarium_caption' ) ); ?></p>
    </div>
  </div>
</section>

<section id="life" aria-label="دنیای زنده" class="relative h-[280vh] w-full bg-void">
  <div class="sticky top-0 flex h-[100svh] w-full items-center justify-center overflow-hidden">
    <img alt="راهروی غار با چند آکواریوم نورانی در دل صخره، پر از ماهی‌های رنگارنگ" loading="lazy" decoding="async" class="absolute inset-0 h-full w-full object-cover" src="<?php echo esc_url( ghar_zende_home_image( 'life_image', 'corridor-panorama-bright.webp' ) ); ?>" />
    <div class="absolute inset-0 bg-void/45"></div>
    <div class="absolute inset-0" style="background:linear-gradient(0deg, rgba(5,7,8,0.85) 0%, rgba(5,7,8,0.25) 45%, rgba(5,7,8,0.55) 100%)"></div>
    <div class="particle-field absolute inset-0" data-variant="bubble" data-count="14" aria-hidden="true"><?php ghar_zende_particles( 'bubble', 14 ); ?></div>

    <div id="lifeCaptions" class="pointer-events-none absolute inset-x-0 top-1/2 z-10 mx-auto max-w-md -translate-y-1/2 px-6">
      <?php
      $captions = ghar_zende_home_get( 'life_captions' );
      foreach ( $captions as $i => $c ) :
			$is_first = 0 === $i;
			?>
      <div class="js-life-caption absolute inset-x-6 text-center transition-all duration-500 <?php echo $is_first ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-3'; ?>">
        <p class="font-display text-sm text-turquoise-soft"><?php echo esc_html( $c['title'] ); ?></p>
        <p class="mt-2 text-lg text-foam"><?php echo esc_html( $c['text'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section id="species" aria-label="گونه‌های آبزی" class="relative w-full overflow-hidden bg-void py-28">
  <div class="absolute inset-0 overflow-hidden opacity-50" aria-hidden="true">
    <img alt="نمای نزدیک یک آکواریوم درون‌صخره‌ای با گیاهان آبزی و چند ماهی" loading="lazy" decoding="async" class="scene-photo absolute inset-0 h-full w-full object-cover" src="<?php echo esc_url( ghar_zende_img( 'aquarium-window-plants.webp' ) ); ?>" />
    <div class="absolute inset-0 bg-void/55"></div>
    <div class="absolute inset-0" style="background:radial-gradient(120% 90% at 20% 15%, transparent 0%, var(--color-void) 92%);opacity:0.55"></div>
    <div class="noise-overlay"></div>
    <div class="absolute inset-0" style="background:linear-gradient(180deg, rgba(5,7,8,0) 0%, rgba(5,7,8,0.75) 100%)"></div>
  </div>
  <div class="relative mx-auto max-w-6xl px-6">
    <div class="js-reveal mb-16 text-center">
      <p class="font-display text-xs uppercase tracking-[0.4em] text-turquoise-soft"><?php echo esc_html( ghar_zende_home_get( 'species_eyebrow' ) ); ?></p>
      <h2 class="mt-4 text-balance font-display text-3xl font-semibold text-foam sm:text-4xl"><?php echo esc_html( ghar_zende_home_get( 'species_title' ) ); ?></h2>
    </div>

    <div class="species-grid grid grid-cols-1 gap-6 sm:grid-cols-2">
      <?php
      foreach ( $species as $s ) :
			// A species can show an admin-uploaded photo, the theme's
			// built-in default photo (only the first seeded species has
			// one), or — for the rest — fall back to a two-tone SVG icon
			// tinted with the color/accent fields.
			$photo_id  = ! empty( $s['photo'] ) ? (int) $s['photo'] : 0;
			$photo_url = $photo_id ? wp_get_attachment_image_url( $photo_id, 'full' ) : '';
			if ( ! $photo_url && ! empty( $s['photo_default'] ) ) {
				$photo_url = ghar_zende_img( $s['photo_default'] );
			}
			$icon_color  = ! empty( $s['color'] ) ? $s['color'] : '#3f8fd1';
			$icon_accent = ! empty( $s['accent'] ) ? $s['accent'] : '#101820';
			?>
      <div>
        <button type="button" data-cursor="مشاهده" class="species-card group relative flex w-full items-center gap-6 overflow-hidden rounded-2xl border border-foam/10 p-6 text-start transition-colors duration-500 bg-stone-900/40">
          <div class="species-glow absolute inset-0 -z-10 transition-opacity duration-500" style="background:radial-gradient(60% 80% at 15% 50%, var(--color-ocean-700), transparent 70%);opacity:0" aria-hidden="true"></div>
          <div class="species-photo w-24 shrink-0 transition-transform duration-700 ease-out scale-100">
            <?php if ( $photo_url ) : ?>
            <div class="relative aspect-square w-full overflow-hidden rounded-xl">
              <img alt="<?php echo esc_attr( $s['name'] . ' در آکواریوم غار' ); ?>" loading="lazy" decoding="async" class="absolute inset-0 h-full w-full object-cover" src="<?php echo esc_url( $photo_url ); ?>" />
            </div>
            <?php else : ?>
            <svg viewBox="0 0 100 48" class="w-full h-auto" aria-hidden="true">
              <path d="M4 24 C 16 4, 46 2, 62 12 C 74 4, 92 10, 98 24 C 92 38, 74 44, 62 36 C 46 46, 16 44, 4 24 Z" fill="<?php echo esc_attr( $icon_color ); ?>" opacity="0.92" />
              <path d="M62 12 L98 24 L62 36 Z" fill="<?php echo esc_attr( $icon_accent ); ?>" opacity="0.85" />
              <path d="M18 8 C 10 2, 4 6, 2 14 C 10 14, 16 12, 18 8 Z" fill="<?php echo esc_attr( $icon_accent ); ?>" opacity="0.7" />
              <circle cx="20" cy="21" r="2.4" fill="var(--color-void)" />
              <circle cx="20.7" cy="20.3" r="0.9" fill="var(--color-foam)" />
            </svg>
            <?php endif; ?>
          </div>
          <div class="min-w-0">
            <h3 class="font-display text-lg font-medium text-foam"><?php echo esc_html( $s['name'] ); ?></h3>
            <p class="text-xs italic text-foam-faint"><?php echo esc_html( $s['sci'] ); ?></p>
            <p class="species-desc mt-3 max-w-sm text-sm text-foam-dim transition-opacity duration-500 opacity-70"><?php echo esc_html( $s['description'] ); ?></p>
            <dl class="mt-4 flex flex-wrap gap-x-6 gap-y-1 text-xs text-foam-faint">
              <div class="flex gap-1">
                <dt class="text-turquoise-soft">زیستگاه:</dt>
                <dd><?php echo esc_html( $s['habitat'] ); ?></dd>
              </div>
              <div class="flex gap-1">
                <dt class="text-turquoise-soft">ویژگی:</dt>
                <dd><?php echo esc_html( $s['trait'] ); ?></dd>
              </div>
            </dl>
          </div>
        </button>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section id="geology" aria-label="درباره غار" class="relative w-full overflow-hidden bg-void py-32">
  <div class="absolute inset-0 overflow-hidden opacity-70" aria-hidden="true">
    <img alt="ردیفی از آکواریوم‌ها دیده‌شده از میان قندیل‌های سنگی در پیش‌زمینه" loading="lazy" decoding="async" class="absolute inset-0 h-full w-full object-cover" src="<?php echo esc_url( ghar_zende_home_image( 'geology_image', 'corridor-stalagmites.webp' ) ); ?>" />
    <div class="absolute inset-0 bg-void/55"></div>
    <div class="absolute inset-0" style="background:radial-gradient(120% 90% at 20% 15%, transparent 0%, var(--color-void) 92%);opacity:0.55"></div>
    <div class="noise-overlay"></div>
    <div class="absolute inset-0" style="background:linear-gradient(180deg, rgba(5,7,8,0) 0%, rgba(5,7,8,0.75) 100%)"></div>
  </div>

  <div class="relative mx-auto max-w-4xl px-6">
    <div class="js-reveal mb-24 text-center">
      <p class="font-display text-xs uppercase tracking-[0.4em] text-turquoise-soft"><?php echo esc_html( ghar_zende_home_get( 'geology_eyebrow' ) ); ?></p>
      <h2 class="mt-4 text-balance font-display text-3xl font-semibold text-foam sm:text-4xl"><?php echo esc_html( ghar_zende_home_get( 'geology_title' ) ); ?></h2>
    </div>

    <div class="flex flex-col gap-20">
      <?php foreach ( $geology_facts as $i => $fact ) :
				$align = ( 0 === $i % 2 ) ? 'self-start text-start' : 'self-end text-end items-end';
				?>
      <div class="js-reveal flex max-w-md flex-col gap-2 <?php echo esc_attr( $align ); ?>">
        <span class="font-display text-4xl font-semibold text-foam sm:text-5xl"><?php echo esc_html( $fact['value'] ); ?><?php if ( ! empty( $fact['unit'] ) ) : ?><span class="ms-2 text-lg font-normal text-turquoise-soft"><?php echo esc_html( $fact['unit'] ); ?></span><?php endif; ?></span>
        <span class="font-display text-sm uppercase tracking-[0.25em] text-foam-faint"><?php echo esc_html( $fact['label'] ); ?></span>
        <p class="mt-1 text-sm text-foam-dim"><?php echo esc_html( $fact['note'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section id="story" aria-label="سنگ، آب، زندگی" class="relative h-[300vh] w-full bg-void">
  <div id="storyStage" class="sticky top-0 flex h-[100svh] w-full items-center justify-center overflow-hidden">
    <div id="storyWindow" class="relative aspect-[4/3] w-[min(90vw,760px)]">
      <div class="absolute inset-0" style="background:radial-gradient(120% 100% at 30% 20%, var(--color-stone-700), var(--color-stone-900) 70%);box-shadow:inset 0 0 60px rgba(0,0,0,0.6)"></div>
      <div class="absolute inset-[6%] overflow-hidden" style="clip-path:url(#cave-window-b)">
        <div class="js-story-photo absolute inset-0 scale-110 transition-[filter] duration-150" style="filter:brightness(0.1) saturate(0.15) blur(16px)">
          <img alt="آکواریومی درون‌صخره‌ای با پرتوهای نور و حباب‌های آب، پر از ماهی‌های رنگارنگ" loading="lazy" decoding="async" class="absolute inset-0 h-full w-full object-cover" src="<?php echo esc_url( ghar_zende_home_image( 'story_image', 'aquarium-window-light.webp' ) ); ?>" />
        </div>
        <div class="js-story-pulse absolute inset-0" style="opacity:0;background:radial-gradient(45% 60% at 65% 10%, rgba(79,216,196,0.5), transparent 70%);mix-blend-mode:screen"></div>
        <div class="absolute inset-0 pointer-events-none" style="background:linear-gradient(115deg, rgba(244,239,227,0.08) 0%, transparent 30%, transparent 70%, rgba(244,239,227,0.05) 100%)"></div>
      </div>
      <div class="absolute inset-[6%] pointer-events-none" style="clip-path:url(#cave-window-b);box-shadow:inset 0 0 24px 10px rgba(0,0,0,0.55)"></div>
    </div>

    <!-- Physical left-0/right-0 on purpose, NOT the logical start-0/end-0:
         the page is dir="rtl", where start/end resolve to right/left — the
         opposite of what applyStory()'s translateX signs and the shadow
         offsets below assume (Start = left panel retracting further left,
         End = right panel retracting further right). Under the logical
         classes the two shutters swapped sides across the center instead
         of opening outward, leaving only a sliver visible at full scroll. -->
    <div id="storyShutterStart" class="absolute inset-y-0 left-0 bg-stone-900" style="width:52%;transform:translateX(0%);box-shadow:8px 0 30px rgba(0,0,0,0.6)" aria-hidden="true"></div>
    <div id="storyShutterEnd" class="absolute inset-y-0 right-0 bg-stone-900" style="width:52%;transform:translateX(0%);box-shadow:-8px 0 30px rgba(0,0,0,0.6)" aria-hidden="true"></div>
    <div id="storyShards" class="shards" aria-hidden="true"></div>
    <div id="storyBeam" class="story-beam" aria-hidden="true"></div>
    <div id="storyBeamSoft" class="story-beam soft" aria-hidden="true"></div>

    <div class="pointer-events-none absolute inset-x-0 top-14 z-10 flex flex-col items-center gap-2 text-center">
      <?php $story_chapters = ghar_zende_home_get( 'story_chapters' ); $story_first = isset( $story_chapters[0] ) ? $story_chapters[0] : array(); ?>
      <span id="storyChapterLabel" class="font-display text-[11px] uppercase tracking-[0.4em] text-turquoise-soft"><?php echo esc_html( ( ! empty( $story_first['title'] ) ? $story_first['title'] : 'THE CAVE' ) . ' · ' . ( ! empty( $story_first['persian'] ) ? $story_first['persian'] : 'غار' ) ); ?></span>
      <p id="storyChapterText" class="text-balance font-display text-xl text-foam sm:text-2xl"><?php echo esc_html( ! empty( $story_first['text'] ) ? $story_first['text'] : 'میلیون‌ها سال در سکوت شکل گرفته.' ); ?></p>
    </div>
  </div>
</section>

<section id="visit" aria-label="بازدید از غار" class="relative flex min-h-[90vh] w-full items-center justify-center overflow-hidden py-24 bg-void">
  <div class="absolute inset-0 overflow-hidden" aria-hidden="true">
    <img alt="راهرو غار با ترکیبی از نور گرم و آبی، در انتهای مسیر بازدید" loading="lazy" decoding="async" class="scene-photo absolute inset-0 h-full w-full object-cover" src="<?php echo esc_url( ghar_zende_home_image( 'visit_image', 'corridor-warm-glow.webp' ) ); ?>" />
    <div class="absolute inset-0 bg-void/55"></div>
    <div class="absolute inset-0" style="background:radial-gradient(120% 90% at 20% 15%, transparent 0%, var(--color-void) 92%);opacity:0.55"></div>
    <div class="absolute inset-0" style="background:radial-gradient(45% 35% at 50% -10%, var(--color-amber-glow) 0%, transparent 70%);opacity:0.5;mix-blend-mode:screen"></div>
    <div class="noise-overlay"></div>
    <div class="absolute inset-0" style="background:linear-gradient(180deg, rgba(5,7,8,0) 0%, rgba(5,7,8,0.75) 100%)"></div>
  </div>
  <div class="particle-field absolute inset-0" data-variant="dust" data-count="20" aria-hidden="true"><?php ghar_zende_particles( 'dust', 20 ); ?></div>

  <div class="relative z-10 mx-auto flex max-w-2xl flex-col items-center gap-8 px-6 text-center">
    <div>
      <h2 class="text-balance font-display text-3xl font-semibold leading-relaxed text-foam sm:text-4xl"><?php echo ghar_zende_split_words( ghar_zende_home_get( 'visit_heading' ) ); ?></h2>
    </div>
    <div class="cta-row flex flex-wrap items-center justify-center gap-4">
      <?php foreach ( ghar_zende_home_get( 'visit_ctas' ) as $cta ) : ?>
      <?php if ( ! empty( $cta['primary'] ) ) : ?>
      <?php // No hover:scale/transition-transform here on purpose: this
      // element also gets a live inline "transform" from the magnetic
      // pointer-follow effect (assets/js/home.js), and a CSS transition
      // on the same property fought it — the button visibly grew AND
      // drifted toward the cursor on hover, far more than the plain
      // color-only hover the other two CTAs have, which read as broken/
      // jarring. Matches the sibling buttons' transition-colors pattern
      // instead, so hover here is just a color shift, consistent with them. ?>
      <a href="<?php echo esc_url( $cta['href'] ); ?>" data-cursor="بازدید" data-magnetic class="visit-cta-primary rounded-full bg-turquoise px-8 py-3 text-sm font-medium text-void transition-colors"><?php echo esc_html( $cta['text'] ); ?></a>
      <?php else : ?>
      <a href="<?php echo esc_url( $cta['href'] ); ?>" data-magnetic class="rounded-full border border-foam/25 px-8 py-3 text-sm text-foam transition-colors hover:border-turquoise hover:text-turquoise-soft"><?php echo esc_html( $cta['text'] ); ?></a>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>

  <footer class="absolute inset-x-0 bottom-6 z-10 px-6 text-center text-xs text-foam-faint">© <?php echo esc_html( gmdate( 'Y' ) ); ?> غار زنده — دنیایی زنده در دل زمین</footer>
</section>

<?php get_footer(); ?>
