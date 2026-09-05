<?php
/**
 * Generic WP Page template — theme-aware reading view, same treatment
 * index.php uses for a single entry.
 */
get_header();
the_post();
?>

<div class="mx-auto max-w-3xl px-6 pt-32 pb-20 md:px-10">
	<article>
		<h1 class="font-display text-3xl font-bold text-ink sm:text-4xl"><?php the_title(); ?></h1>
		<div class="mt-8 flex flex-col gap-6 text-[15px] leading-8 text-ink-dim sm:text-base sm:leading-9">
			<?php the_content(); ?>
		</div>
	</article>
</div>

<?php get_footer(); ?>
