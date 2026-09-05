<?php
/**
 * Generic fallback template (search results, standard 'post' entries,
 * anything not covered by front-page.php / archive-cave_article.php /
 * single-cave_article.php / page.php) — the original site had no such
 * content type, so this is a plain, theme-aware reading view rather than
 * a ported component.
 */
get_header();
?>

<div class="mx-auto max-w-3xl px-6 pt-32 pb-20 md:px-10">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article class="mb-16 border-b border-ink/10 pb-16 last:mb-0 last:border-0 last:pb-0">
				<h1 class="font-display text-2xl font-bold text-ink sm:text-3xl">
					<a href="<?php the_permalink(); ?>" class="transition-colors hover:text-accent"><?php the_title(); ?></a>
				</h1>
				<div class="mt-2 text-sm text-ink-faint"><?php echo esc_html( get_the_date() ); ?></div>
				<div class="mt-6 flex flex-col gap-6 text-[15px] leading-8 text-ink-dim sm:text-base sm:leading-9">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>

		<div class="flex items-center justify-between text-sm">
			<?php echo get_previous_posts_link( '→ جدیدتر' ); ?>
			<?php echo get_next_posts_link( 'قدیمی‌تر ←' ); ?>
		</div>
	<?php else : ?>
		<p class="text-center text-ink-dim">نتیجه‌ای یافت نشد.</p>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
