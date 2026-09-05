<?php
/**
 * Site header — ported from src/components/Nav.tsx + the shared
 * <html>/<head>/skip-link/<CustomCursor/> chrome in src/app/layout.tsx.
 *
 * Nav text is fixed-light (--nav-text: var(--color-foam)) by default
 * because on the front page / magazine listing it floats transparently
 * over a dark hero photo; .glass-nav (once scrolled) and .theme-nav (on
 * pages with no hero) re-declare those to the theme-aware ink tokens —
 * see assets/css/theme.css and ghar_zende_has_hero() in functions.php.
 */
$ghar_has_hero = ghar_zende_has_hero();
?><!DOCTYPE html>
<html lang="fa" dir="rtl" class="h-full antialiased" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<script>
  // Anti-flash: set data-theme on <html> before first paint, from
  // localStorage — mirrors src/lib/theme.ts exactly, default "dark".
  (function () {
    try {
      var t = localStorage.getItem('ghar-theme');
      if (t !== 'light' && t !== 'dark') t = 'dark';
      document.documentElement.setAttribute('data-theme', t);
    } catch (e) {
      document.documentElement.setAttribute('data-theme', 'dark');
    }
  })();
</script>
<?php wp_head(); ?>
</head>
<body <?php body_class( 'min-h-full bg-surface text-ink' ); ?>>
<?php wp_body_open(); ?>

<a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:start-4 focus:top-4 focus:z-[200] focus:rounded-full focus:bg-foam focus:px-4 focus:py-2 focus:text-void">رفتن به محتوای اصلی</a>

<header id="site-header" class="fixed inset-x-0 top-0 z-50 transition-[background,border-color] duration-500 border-b border-transparent bg-transparent<?php echo $ghar_has_hero ? '' : ' theme-nav'; ?>">
  <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 md:px-10">
    <a class="font-display text-lg font-semibold tracking-wide text-[var(--nav-text)]" href="<?php echo esc_url( home_url( '/' ) ); ?>">غار <span class="text-turquoise">زنده</span></a>

    <nav class="hidden items-center gap-8 md:flex" aria-label="پیمایش اصلی">
      <?php ghar_zende_primary_nav(); ?>
      <button type="button" id="themeToggle" aria-pressed="false" aria-label="فعال‌سازی حالت روشن" class="flex h-9 w-9 items-center justify-center rounded-full border border-[var(--nav-border)]/25 text-[var(--nav-text)] transition-colors hover:border-accent hover:text-accent-soft">
        <svg viewBox="0 0 24 24" class="h-4 w-4" aria-hidden="true" id="themeIconMoon"><path d="M20 14.5A8.5 8.5 0 0 1 9.5 4a8.5 8.5 0 1 0 10.5 10.5Z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <svg viewBox="0 0 24 24" class="h-4 w-4" aria-hidden="true" id="themeIconSun" hidden><path d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.36-6.36-1.42 1.42M7.05 16.95l-1.41 1.41m0-12.72 1.41 1.42m9.9 9.9 1.42 1.41M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
    </nav>

    <div class="flex items-center gap-2 md:hidden">
      <button type="button" id="themeToggleMobile" aria-pressed="false" aria-label="فعال‌سازی حالت روشن" class="flex h-9 w-9 items-center justify-center rounded-full border border-[var(--nav-border)]/25 text-[var(--nav-text)] transition-colors hover:border-accent hover:text-accent-soft">
        <svg viewBox="0 0 24 24" class="h-4 w-4" aria-hidden="true"><path d="M20 14.5A8.5 8.5 0 0 1 9.5 4a8.5 8.5 0 1 0 10.5 10.5Z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
      <button type="button" id="navToggle" class="flex h-10 w-10 items-center justify-center rounded-full border border-[var(--nav-border)]/15 text-[var(--nav-text)]" aria-expanded="false" aria-label="باز کردن منو">
        <span class="relative block h-3 w-4">
          <span class="absolute inset-x-0 top-0 h-px bg-current transition-transform"></span>
          <span class="absolute inset-x-0 bottom-0 h-px bg-current transition-transform"></span>
        </span>
      </button>
    </div>
  </div>

  <nav id="mobileNav" class="glass-nav overflow-hidden md:hidden" style="height:0;opacity:0" aria-label="پیمایش موبایل">
    <div class="flex flex-col gap-1 px-6 pb-6">
      <?php ghar_zende_primary_nav(); ?>
    </div>
  </nav>
</header>

<?php if ( is_front_page() ) : ?>
<div id="scrollRail" class="pointer-events-none fixed inset-y-0 left-6 z-40 hidden flex-col items-center justify-center gap-4 lg:flex" aria-hidden="true">
  <div class="relative h-56 w-px overflow-hidden bg-foam/15">
    <div id="railFill" class="absolute inset-x-0 top-0 bg-turquoise transition-[height] duration-150 ease-out" style="height:0%"></div>
  </div>
  <div id="railLabel" class="mt-2 rotate-180 [writing-mode:vertical-rl] font-display text-[11px] tracking-[0.3em] text-foam-dim">۰۱ / ورود</div>
</div>
<?php endif; ?>

<div id="customCursor" class="pointer-events-none fixed left-0 top-0 z-[100] hidden items-center justify-center rounded-full mix-blend-difference transition-[width,height] duration-200 ease-out" style="width:8px;height:8px;background:var(--color-foam)" aria-hidden="true">
  <span id="customCursorLabel" class="font-display text-[10px] font-medium uppercase tracking-widest text-void"></span>
</div>

<main id="main-content">
