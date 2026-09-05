<?php
/**
 * Single article — ported from src/app/magazine/[slug]/page.tsx +
 * ArticleBody / ArticleSidebar / TableOfContents / RelatedArticles /
 * ShareBar. Heading ids are injected into the_content() by
 * ghar_zende_inject_heading_ids() (inc/helpers.php); ghar_zende_get_toc()
 * must be read right after the_content() runs once, same order the
 * original mounted ArticleBody before the sidebar's TableOfContents.
 */
get_header();
the_post();

$post_id    = get_the_ID();
$categories = get_the_category( $post_id );
$category   = ! empty( $categories ) ? $categories[0]->name : '';
$author     = ghar_zende_author_for_category( $category );
$date_fa    = ghar_zende_jalali_date( get_the_date( 'Y-m-d' ) );
$read_time  = ghar_zende_read_time( $post_id );
$cover      = get_the_post_thumbnail_url( $post_id, 'ghar-hero' );

$content_html = apply_filters( 'the_content', get_the_content() );
$toc          = ghar_zende_get_toc();

$recent_query = new WP_Query(
	array(
		'post_type'      => 'cave_article',
		'posts_per_page' => 4,
		'post__not_in'   => array( $post_id ),
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

$related_same_cat = array();
if ( $category ) {
	$related_same_cat_query = new WP_Query(
		array(
			'post_type'      => 'cave_article',
			'posts_per_page' => 3,
			'post__not_in'   => array( $post_id ),
			'category_name'  => $category,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
	$related_same_cat = $related_same_cat_query->posts;
}
$related = $related_same_cat;
if ( count( $related ) < 3 ) {
	$exclude = array_merge( array( $post_id ), wp_list_pluck( $related, 'ID' ) );
	$fill_query = new WP_Query(
		array(
			'post_type'      => 'cave_article',
			'posts_per_page' => 3 - count( $related ),
			'post__not_in'   => $exclude,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
	$related = array_merge( $related, $fill_query->posts );
}

function ghar_zende_related_card( $p ) {
	$categories = get_the_category( $p->ID );
	?>
	<article dir="rtl" class="group relative flex h-full flex-col overflow-hidden rounded-2xl border border-ink/10 bg-surface-raised/50 transition-colors duration-300 hover:border-accent-dim">
		<a href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>" class="absolute inset-0 z-10 rounded-2xl"><span class="sr-only"><?php echo esc_html( get_the_title( $p->ID ) ); ?></span></a>
		<div class="relative aspect-[4/3] w-full overflow-hidden">
			<img src="<?php echo esc_url( get_the_post_thumbnail_url( $p->ID, 'ghar-card' ) ); ?>" alt="<?php echo esc_attr( get_the_title( $p->ID ) ); ?>" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-110" />
			<div class="absolute inset-0 bg-gradient-to-t from-void/70 via-void/0 to-void/0"></div>
			<span class="absolute start-3 top-3 rounded-full bg-void/70 px-3 py-1 font-display text-[11px] text-turquoise-soft backdrop-blur"><?php echo esc_html( ! empty( $categories ) ? $categories[0]->name : '' ); ?></span>
		</div>
		<div class="flex flex-1 flex-col gap-2 p-5">
			<h3 class="line-clamp-2 font-display text-base font-semibold leading-snug text-ink"><?php echo esc_html( get_the_title( $p->ID ) ); ?></h3>
			<p class="line-clamp-2 flex-1 text-sm leading-relaxed text-ink-dim"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt( $p->ID ) ) ); ?></p>
			<div class="mt-2 flex items-center gap-3 text-xs text-ink-faint">
				<span><?php echo esc_html( ghar_zende_jalali_date( get_the_date( 'Y-m-d', $p->ID ) ) ); ?></span>
				<span aria-hidden="true">·</span>
				<span><?php echo esc_html( ghar_zende_read_time( $p->ID ) ); ?></span>
			</div>
		</div>
	</article>
	<?php
}
?>

<article>
	<div class="mx-auto max-w-4xl px-6 pt-32 md:px-10">
		<nav aria-label="مسیر صفحه" class="flex flex-wrap items-center gap-2 text-xs text-ink-faint">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="transition-colors hover:text-ink-dim">غار زنده</a>
			<span aria-hidden="true">/</span>
			<a href="<?php echo esc_url( home_url( '/magazine' ) ); ?>" class="transition-colors hover:text-ink-dim">مجله خبری</a>
			<span aria-hidden="true">/</span>
			<span class="text-ink-dim"><?php echo esc_html( $category ); ?></span>
		</nav>

		<div class="mt-6">
			<span class="inline-block rounded-full bg-accent-dim/20 px-3 py-1 font-display text-xs text-accent-soft"><?php echo esc_html( $category ); ?></span>
			<h1 class="mt-4 text-balance font-display text-3xl font-bold leading-snug text-ink sm:text-4xl"><?php the_title(); ?></h1>
			<div class="mt-5 flex flex-wrap items-center gap-3 text-sm text-ink-faint">
				<span class="text-ink-dim"><?php echo esc_html( $author ); ?></span>
				<span aria-hidden="true">·</span>
				<span><?php echo esc_html( $date_fa ); ?></span>
				<span aria-hidden="true">·</span>
				<span><?php echo esc_html( $read_time ); ?></span>
			</div>
		</div>
	</div>

	<div class="mx-auto mt-8 max-w-5xl px-6 md:px-10">
		<div class="relative aspect-[16/9] w-full overflow-hidden rounded-2xl border border-ink/10">
			<img src="<?php echo esc_url( $cover ); ?>" alt="<?php the_title_attribute(); ?>" class="absolute inset-0 h-full w-full object-cover" />
		</div>
	</div>

	<div class="mx-auto mt-12 grid max-w-5xl grid-cols-1 gap-12 px-6 pb-20 md:px-10 lg:grid-cols-[1fr_300px]">
		<div class="min-w-0">
			<div class="flex flex-col gap-6 text-[15px] leading-8 text-ink-dim sm:text-base sm:leading-9">
				<?php echo $content_html; // phpcs:ignore -- already sanitized on import + the_content filters ?>
			</div>

			<div class="mt-10 flex flex-wrap items-center justify-between gap-4 border-y border-ink/10 py-5">
				<a href="<?php echo esc_url( home_url( '/magazine' ) ); ?>" class="text-sm text-ink-dim transition-colors hover:text-ink">→ بازگشت به مجله</a>
				<button type="button" id="shareCopyBtn" data-url="<?php echo esc_url( get_permalink() ); ?>" class="rounded-full border border-ink/25 px-4 py-2 text-sm text-ink transition-colors hover:bg-ink/5">کپی لینک مقاله</button>
			</div>
		</div>

		<aside class="lg:sticky lg:top-28 lg:h-fit">
			<div class="flex flex-col gap-6">
				<?php if ( ! empty( $toc ) ) : ?>
				<nav id="articleToc" aria-label="فهرست مطالب" class="rounded-2xl border border-ink/10 bg-surface-raised/40 p-5">
					<p class="font-display text-xs uppercase tracking-[0.3em] text-accent-soft">فهرست مطالب</p>
					<ol class="mt-4 flex flex-col gap-1 text-sm">
						<?php foreach ( $toc as $i => $h ) : ?>
						<li>
							<a href="#<?php echo esc_attr( $h['id'] ); ?>" data-toc-id="<?php echo esc_attr( $h['id'] ); ?>" class="toc-link block border-s-2 py-1.5 ps-4 transition-colors <?php echo 0 === $i ? 'border-accent text-ink' : 'border-ink/10 text-ink-dim hover:border-ink/30 hover:text-ink'; ?>">
								<?php echo esc_html( $h['text'] ); ?>
							</a>
						</li>
						<?php endforeach; ?>
					</ol>
				</nav>
				<?php endif; ?>

				<div class="rounded-2xl border border-ink/10 bg-surface-raised/40 p-5">
					<p class="font-display text-xs uppercase tracking-[0.3em] text-accent-soft">آخرین مطالب</p>
					<ul class="mt-4 flex flex-col gap-4">
						<?php foreach ( $recent_query->posts as $p ) : ?>
						<li>
							<a href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>" class="group flex flex-col gap-1">
								<span class="line-clamp-2 text-sm text-ink-dim transition-colors group-hover:text-ink"><?php echo esc_html( get_the_title( $p->ID ) ); ?></span>
								<span class="text-xs text-ink-faint"><?php echo esc_html( ghar_zende_jalali_date( get_the_date( 'Y-m-d', $p->ID ) ) ); ?></span>
							</a>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>

				<div class="rounded-2xl border border-accent-dim/40 bg-accent-dim/10 p-5">
					<p class="font-display text-sm font-semibold text-ink">دلت می‌خواهد از نزدیک ببینی؟</p>
					<p class="mt-2 text-sm leading-relaxed text-ink-dim">برنامه‌ی بازدید از غار و آکواریوم‌های درون‌صخره‌ای را ببین و تور بعدی را رزرو کن.</p>
					<a href="<?php echo esc_url( home_url( '/#visit' ) ); ?>" class="mt-4 inline-flex items-center gap-2 rounded-full border border-ink/25 px-4 py-2 text-sm text-ink transition-colors hover:bg-ink/5">برنامه بازدید</a>
				</div>
			</div>
		</aside>
	</div>
</article>

<?php if ( ! empty( $related ) ) : ?>
<section aria-label="مطالب مرتبط" class="border-t border-ink/10 bg-surface py-16">
	<div class="mx-auto max-w-7xl px-6 md:px-10">
		<p class="font-display text-xs uppercase tracking-[0.35em] text-accent-soft">RELATED</p>
		<h2 class="mt-2 font-display text-2xl font-semibold text-ink sm:text-3xl">مطالب مرتبط</h2>
		<div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
			<?php foreach ( $related as $p ) : ?>
			<?php ghar_zende_related_card( $p ); ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
