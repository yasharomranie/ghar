</main>

<?php
/**
 * Site-wide footer — ported from src/components/Footer.tsx. Theme-aware
 * (plain chrome, never overlaid on a photo) so it flips with the header
 * toggle even on the front page.
 */
?>
<footer class="relative border-t border-ink/10 bg-surface">
  <div class="mx-auto flex max-w-7xl flex-col gap-10 px-6 py-14 md:px-10">
    <div class="flex flex-col gap-8 md:flex-row md:items-start md:justify-between">
      <div class="max-w-sm">
        <a class="font-display text-lg font-semibold text-ink" href="<?php echo esc_url( home_url( '/' ) ); ?>">غار <span class="text-accent">زنده</span></a>
        <p class="mt-3 text-sm leading-relaxed text-ink-dim">دنیایی زنده در دل زمین — سفری به قلب یک غار طبیعی با آکواریوم‌های درون‌صخره‌ای.</p>
      </div>

      <nav class="grid grid-cols-2 gap-x-10 gap-y-3 text-sm sm:grid-cols-3" aria-label="پیمایش فوتر">
        <a class="text-ink-dim transition-colors hover:text-ink" href="<?php echo esc_url( home_url( '/#hero' ) ); ?>">غار</a>
        <a class="text-ink-dim transition-colors hover:text-ink" href="<?php echo esc_url( home_url( '/#aquarium' ) ); ?>">آکواریوم</a>
        <a class="text-ink-dim transition-colors hover:text-ink" href="<?php echo esc_url( home_url( '/#species' ) ); ?>">گونه‌ها</a>
        <a class="text-ink-dim transition-colors hover:text-ink" href="<?php echo esc_url( home_url( '/#geology' ) ); ?>">درباره غار</a>
        <a class="text-ink-dim transition-colors hover:text-ink" href="<?php echo esc_url( home_url( '/#visit' ) ); ?>">بازدید</a>
        <a class="text-ink-dim transition-colors hover:text-ink" href="<?php echo esc_url( home_url( '/magazine' ) ); ?>">مجله خبری</a>
      </nav>
    </div>

    <div class="flex flex-col items-center justify-between gap-4 border-t border-ink/10 pt-6 text-xs text-ink-faint sm:flex-row">
      <p>© <?php echo esc_html( gmdate( 'Y' ) ); ?> غار زنده — تمام حقوق محفوظ است.</p>
      <p class="font-display">جایی که سنگ، آب و زندگی به هم می‌رسند</p>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
