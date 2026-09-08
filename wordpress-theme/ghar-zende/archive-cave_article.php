<?php
/**
 * Magazine listing (/magazine/) — ported from src/app/magazine/page.tsx:
 * HeroSlider + FeaturedCarousel (grouped by 4) + ArticleGrid (infinite-
 * scroll via REST after the initial 12).
 *
 * The three sections read from three different places, all real
 * cave_article posts (no hardcoded content):
 *  - Hero slider: posts in the "اسلایدر" (Slider) category — an editor
 *    marks a post for the hero by adding that category to it, same as
 *    any other WP category.
 *  - Featured: posts in the "ویژه" (Featured) category, same idea.
 *  - Latest: the site's actual latest cave_article posts, no category
 *    filter — always current, nothing to maintain.
 * If a post hasn't been tagged into "اسلایدر"/"ویژه" yet (or the editor
 * never uses those categories), that section falls back to the site's
 * latest posts instead of rendering empty.
 */
get_header();

function ghar_zende_magazine_query( $category_slug, $count ) {
	$args = array(
		'post_type'      => 'cave_article',
		'posts_per_page' => $count,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);
	if ( $category_slug ) {
		$args['category_name'] = $category_slug;
	}
	$query = new WP_Query( $args );
	if ( $category_slug && ! $query->have_posts() ) {
		// Nothing tagged into that category yet — fall back to the
		// latest posts overall so the section isn't empty.
		unset( $args['category_name'] );
		$query = new WP_Query( $args );
	}
	return $query;
}

function ghar_zende_card_data( $post_id ) {
	$categories = get_the_category( $post_id );
	// "اسلایدر"/"ویژه" are placement categories — they decide *where* a
	// post shows up on this page (see ghar_zende_magazine_query() above),
	// not what topic badge the card wears. Skip them when picking which
	// category name to display, preferring a real topical one if the
	// post has one.
	$display_category = '';
	foreach ( $categories as $cat ) {
		if ( ! in_array( $cat->slug, array( 'slider', 'featured' ), true ) ) {
			$display_category = $cat->name;
			break;
		}
	}
	return array(
		'slug'     => get_post_field( 'post_name', $post_id ),
		'title'    => get_the_title( $post_id ),
		'excerpt'  => wp_strip_all_tags( get_the_excerpt( $post_id ) ),
		'category' => $display_category,
		'date'     => ghar_zende_jalali_date( get_the_date( 'Y-m-d', $post_id ) ),
		'readTime' => ghar_zende_read_time( $post_id ),
		'image'    => get_the_post_thumbnail_url( $post_id, 'ghar-card' ),
		'permalink' => get_permalink( $post_id ),
	);
}

$hero_query  = ghar_zende_magazine_query( 'slider', 5 );
$hero_slides = array();
foreach ( $hero_query->posts as $p ) {
	$hero_slides[] = ghar_zende_card_data( $p->ID );
}

$featured_query    = ghar_zende_magazine_query( 'featured', 8 );
$featured_articles = array();
foreach ( $featured_query->posts as $p ) {
	$featured_articles[] = ghar_zende_card_data( $p->ID );
}

$latest_query    = ghar_zende_magazine_query( null, 12 );
$latest_articles = array();
foreach ( $latest_query->posts as $p ) {
	$latest_articles[] = ghar_zende_card_data( $p->ID );
}

/** Prints one ArticleCard — matches src/components/magazine/ArticleCard.tsx. */
function ghar_zende_article_card( $a, $extra_class = '' ) {
	?>
	<article dir="rtl" class="group relative flex h-full flex-col overflow-hidden rounded-2xl border border-ink/10 bg-surface-raised/50 transition-colors duration-300 hover:border-accent-dim <?php echo esc_attr( $extra_class ); ?>">
		<a href="<?php echo esc_url( $a['permalink'] ); ?>" class="absolute inset-0 z-10 rounded-2xl">
			<span class="sr-only"><?php echo esc_html( $a['title'] ); ?></span>
		</a>
		<div class="relative aspect-[4/3] w-full overflow-hidden">
			<img src="<?php echo esc_url( $a['image'] ); ?>" alt="<?php echo esc_attr( $a['title'] ); ?>" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-110" />
			<div class="absolute inset-0 bg-gradient-to-t from-void/70 via-void/0 to-void/0"></div>
			<span class="absolute start-3 top-3 rounded-full bg-void/70 px-3 py-1 font-display text-[11px] text-turquoise-soft backdrop-blur"><?php echo esc_html( $a['category'] ); ?></span>
		</div>
		<div class="flex flex-1 flex-col gap-2 p-5">
			<h3 class="line-clamp-2 font-display text-base font-semibold leading-snug text-ink"><?php echo esc_html( $a['title'] ); ?></h3>
			<p class="line-clamp-2 flex-1 text-sm leading-relaxed text-ink-dim"><?php echo esc_html( $a['excerpt'] ); ?></p>
			<div class="mt-2 flex items-center gap-3 text-xs text-ink-faint">
				<span><?php echo esc_html( $a['date'] ); ?></span>
				<span aria-hidden="true">·</span>
				<span><?php echo esc_html( $a['readTime'] ); ?></span>
			</div>
		</div>
	</article>
	<?php
}
?>

<section id="heroSlider" aria-roledescription="اسلایدر" aria-label="مطالب برگزیده" class="relative h-[78svh] min-h-[440px] w-full overflow-hidden bg-void">
	<?php foreach ( $hero_slides as $i => $slide ) : ?>
	<div class="hero-slide absolute inset-0 transition-opacity duration-700 ease-out <?php echo 0 === $i ? 'opacity-100' : 'pointer-events-none opacity-0'; ?>" aria-hidden="<?php echo 0 === $i ? 'false' : 'true'; ?>">
		<img src="<?php echo esc_url( $slide['image'] ); ?>" alt="" loading="<?php echo 0 === $i ? 'eager' : 'lazy'; ?>" class="absolute inset-0 h-full w-full object-cover" />
		<div class="absolute inset-0 bg-gradient-to-t from-void via-void/40 to-void/10"></div>
		<div class="relative z-10 mx-auto flex h-full max-w-7xl flex-col justify-end px-6 pb-14 md:px-10">
			<span class="font-display text-xs uppercase tracking-[0.35em] text-turquoise-soft"><?php echo esc_html( $slide['category'] ); ?></span>
			<h2 class="mt-3 max-w-2xl text-balance font-display text-2xl font-semibold leading-snug text-foam sm:text-4xl">
				<a href="<?php echo esc_url( $slide['permalink'] ); ?>" class="transition-colors hover:text-turquoise-soft"><?php echo esc_html( $slide['title'] ); ?></a>
			</h2>
			<p class="mt-3 max-w-xl text-sm text-foam-dim sm:text-base"><?php echo esc_html( $slide['excerpt'] ); ?></p>
			<div class="mt-3 flex items-center gap-3 text-xs text-foam-faint">
				<span><?php echo esc_html( $slide['date'] ); ?></span>
				<span aria-hidden="true">·</span>
				<span><?php echo esc_html( $slide['readTime'] ); ?></span>
			</div>
			<a href="<?php echo esc_url( $slide['permalink'] ); ?>" class="mt-4 inline-flex w-fit items-center gap-2 text-sm text-turquoise-soft transition-colors hover:text-turquoise">
				ادامه مطلب
				<svg viewBox="0 0 24 24" class="h-3.5 w-3.5" aria-hidden="true"><path d="M15 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
			</a>
		</div>
	</div>
	<?php endforeach; ?>

	<div class="absolute inset-x-0 bottom-6 z-20 flex items-center justify-center gap-4">
		<button type="button" id="heroPrev" aria-label="اسلاید قبلی" class="flex h-9 w-9 items-center justify-center rounded-full border border-foam/25 text-foam transition-colors hover:border-turquoise hover:text-turquoise-soft">
			<svg viewBox="0 0 24 24" class="h-4 w-4" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
		</button>
		<div id="heroDots" class="flex items-center gap-2">
			<?php foreach ( $hero_slides as $i => $slide ) : ?>
			<button type="button" class="hero-dot h-1.5 rounded-full transition-all <?php echo 0 === $i ? 'w-6 bg-turquoise' : 'w-1.5 bg-foam/30 hover:bg-foam/50'; ?>" data-index="<?php echo (int) $i; ?>" aria-label="رفتن به اسلاید <?php echo (int) $i + 1; ?>" aria-current="<?php echo 0 === $i ? 'true' : 'false'; ?>"></button>
			<?php endforeach; ?>
		</div>
		<button type="button" id="heroNext" aria-label="اسلاید بعدی" class="flex h-9 w-9 items-center justify-center rounded-full border border-foam/25 text-foam transition-colors hover:border-turquoise hover:text-turquoise-soft">
			<svg viewBox="0 0 24 24" class="h-4 w-4" aria-hidden="true"><path d="M15 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
		</button>
	</div>
</section>

<?php
$featured_groups = array_chunk( $featured_articles, 4 );
?>
<section id="featured" aria-label="مطالب ویژه" class="relative w-full bg-surface py-20">
	<div class="mx-auto max-w-7xl px-6 md:px-10">
		<div class="mb-10 flex items-end justify-between">
			<div>
				<p class="font-display text-xs uppercase tracking-[0.35em] text-accent-soft">FEATURED</p>
				<h2 class="mt-2 font-display text-2xl font-semibold text-ink sm:text-3xl">مطالب ویژه</h2>
			</div>
			<?php if ( count( $featured_groups ) > 1 ) : ?>
			<div id="featuredDots" class="hidden items-center gap-2 sm:flex">
				<?php foreach ( $featured_groups as $gi => $group ) : ?>
				<button type="button" class="featured-dot h-1.5 rounded-full transition-all <?php echo 0 === $gi ? 'w-6 bg-accent' : 'w-1.5 bg-ink/25 hover:bg-ink/45'; ?>" data-index="<?php echo (int) $gi; ?>" aria-label="صفحه‌ی <?php echo (int) $gi + 1; ?> مطالب ویژه" aria-current="<?php echo 0 === $gi ? 'true' : 'false'; ?>"></button>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>

		<div class="relative overflow-hidden" dir="ltr">
			<div id="featuredTrack" class="flex transition-transform duration-700 ease-out" style="transform:translateX(0%)">
				<?php foreach ( $featured_groups as $gi => $group ) : ?>
				<div class="flex w-full shrink-0 flex-row-reverse flex-wrap gap-4 sm:gap-5" aria-hidden="<?php echo 0 === $gi ? 'false' : 'true'; ?>">
					<?php foreach ( $group as $a ) : ?>
					<?php ghar_zende_article_card( $a, 'w-[calc(50%-0.5rem)] sm:w-[calc(50%-0.625rem)] lg:w-[calc(25%-0.9375rem)]' ); ?>
					<?php endforeach; ?>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<section id="latest" aria-label="آخرین مطالب" class="relative w-full bg-surface py-20">
	<div class="mx-auto max-w-7xl px-6 md:px-10">
		<div class="mb-10">
			<p class="font-display text-xs uppercase tracking-[0.35em] text-accent-soft">LATEST</p>
			<h2 class="mt-2 font-display text-2xl font-semibold text-ink sm:text-3xl">آخرین مطالب</h2>
		</div>

		<div id="latestGrid" class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
			<?php foreach ( $latest_articles as $a ) : ?>
			<?php ghar_zende_article_card( $a ); ?>
			<?php endforeach; ?>
		</div>

		<div id="latestSentinel" class="flex h-20 items-center justify-center" aria-hidden="true">
			<span id="latestLoading" class="hidden items-center gap-3 text-sm text-ink-faint">
				<span class="h-4 w-4 animate-spin rounded-full border-2 border-ink/20 border-t-accent"></span>
				در حال بارگذاری مطالب بیشتر…
			</span>
		</div>
	</div>
</section>

<?php get_footer(); ?>
