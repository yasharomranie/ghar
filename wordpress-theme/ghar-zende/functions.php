<?php
/**
 * غار زنده — Theme bootstrap.
 *
 * Ported from the original Next.js/React implementation. Where a decision
 * mirrors that source directly (design tokens, scroll-cinematic behaviour,
 * the fixed-dark vs theme-aware CSS token split) the comment says so.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GHAR_ZENDE_VERSION', '1.0.0' );
define( 'GHAR_ZENDE_DIR', get_template_directory() );
define( 'GHAR_ZENDE_URI', get_template_directory_uri() );

/**
 * Theme setup: supports, nav menus, image sizes.
 */
function ghar_zende_setup() {
	load_theme_textdomain( 'ghar-zende', GHAR_ZENDE_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 60,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Cover images for the magazine listing / article detail — matches the
	// aspect ratios the original React <ArticleCard>/<HeroSlider> used.
	set_post_thumbnail_size( 1200, 800, true );
	add_image_size( 'ghar-card', 640, 420, true );
	add_image_size( 'ghar-hero', 1600, 900, true );

	register_nav_menus(
		array(
			'primary' => __( 'منوی اصلی', 'ghar-zende' ),
		)
	);
}
add_action( 'after_setup_theme', 'ghar_zende_setup' );

/**
 * Styles & scripts.
 *
 * Load order matters: gsap -> ScrollTrigger -> theme.js (shared: header
 * scroll-state + theme toggle, needed on every template) -> the
 * page-specific script, matching the original app's mount order.
 */
function ghar_zende_assets() {
	wp_enqueue_style(
		'ghar-zende-fonts',
		'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'ghar-zende-theme', GHAR_ZENDE_URI . '/assets/css/theme.css', array(), GHAR_ZENDE_VERSION );

	wp_enqueue_script( 'ghar-zende-theme-js', GHAR_ZENDE_URI . '/assets/js/theme.js', array(), GHAR_ZENDE_VERSION, true );

	if ( is_front_page() ) {
		wp_enqueue_script( 'lenis', GHAR_ZENDE_URI . '/assets/lib/lenis.min.js', array(), GHAR_ZENDE_VERSION, true );
		wp_enqueue_script( 'gsap', GHAR_ZENDE_URI . '/assets/lib/gsap.min.js', array(), GHAR_ZENDE_VERSION, true );
		wp_enqueue_script( 'gsap-scrolltrigger', GHAR_ZENDE_URI . '/assets/lib/ScrollTrigger.min.js', array( 'gsap' ), GHAR_ZENDE_VERSION, true );
		wp_enqueue_script( 'ghar-zende-home', GHAR_ZENDE_URI . '/assets/js/home.js', array( 'lenis', 'gsap', 'gsap-scrolltrigger' ), GHAR_ZENDE_VERSION, true );
	}

	if ( is_post_type_archive( 'cave_article' ) || is_page_template( 'archive-cave_article.php' ) ) {
		wp_enqueue_script( 'ghar-zende-magazine', GHAR_ZENDE_URI . '/assets/js/magazine.js', array( 'ghar-zende-theme-js' ), GHAR_ZENDE_VERSION, true );
		wp_localize_script(
			'ghar-zende-magazine',
			'GHAR_MAGAZINE',
			array(
				'restUrl' => esc_url_raw( rest_url( 'wp/v2/cave_article' ) ),
				'nonce'   => wp_create_nonce( 'wp_rest' ),
			)
		);
	}

	if ( is_singular( 'cave_article' ) ) {
		wp_enqueue_script( 'ghar-zende-article', GHAR_ZENDE_URI . '/assets/js/article.js', array( 'ghar-zende-theme-js' ), GHAR_ZENDE_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'ghar_zende_assets' );

/**
 * Includes.
 */
require_once GHAR_ZENDE_DIR . '/inc/cpt-articles.php';
require_once GHAR_ZENDE_DIR . '/inc/helpers.php';
require_once GHAR_ZENDE_DIR . '/inc/importer.php';
require_once GHAR_ZENDE_DIR . '/inc/nav-walker.php';

/**
 * Fallback primary menu — used until an admin assigns a real one under
 * Appearance → Menus. Same 6 links/order/anchors as the original Nav.tsx
 * (scenes.ts's navLabel entries + مجله خبری + the برنامه بازدید CTA),
 * printed as bare <a> tags — no <ul>/<li> — so they lay out exactly like
 * the WP-menu path below (both are flex siblings of the logo/toggle).
 */
function ghar_zende_fallback_menu( $context = 'desktop' ) {
	$items = array(
		array( home_url( '/#hero' ), 'غار', false ),
		array( home_url( '/#aquarium' ), 'آکواریوم', false ),
		array( home_url( '/#species' ), 'گونه‌ها', false ),
		array( home_url( '/#geology' ), 'درباره غار', false ),
		array( home_url( '/magazine' ), 'مجله خبری', false ),
		array( home_url( '/#visit' ), 'برنامه بازدید', true ),
	);

	foreach ( $items as $item ) {
		list( $url, $label, $is_cta ) = $item;

		if ( 'mobile' === $context ) {
			// The original mobile panel skips the CTA pill — it's a plain
			// link list there, same as every other item.
			$class = 'py-3 text-base text-[var(--nav-text-dim)]';
		} elseif ( $is_cta ) {
			$class = 'rounded-full border border-[var(--nav-border)]/25 px-4 py-2 text-sm text-[var(--nav-text)] transition-colors hover:bg-[var(--nav-text)]/5';
		} else {
			$class = 'text-sm text-[var(--nav-text-dim)] transition-colors hover:text-[var(--nav-text)]';
		}

		printf(
			'<a href="%1$s" class="%2$s">%3$s</a>',
			esc_url( $url ),
			esc_attr( $class ),
			esc_html( $label )
		);
	}
}

/**
 * Prints the primary nav — a real WordPress menu (Appearance → Menus →
 * assign to "منوی اصلی") rendered flat via Ghar_Zende_Nav_Walker, or the
 * hardcoded list above until one is assigned.
 */
function ghar_zende_primary_nav( $context = 'desktop' ) {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'depth'          => 1,
				'walker'         => new Ghar_Zende_Nav_Walker( $context ),
			)
		);
	} else {
		ghar_zende_fallback_menu( $context );
	}
}

/**
 * Whether the current template renders its own full-bleed dark hero photo
 * (front page, magazine listing) — mirrors `hasHero` in the original
 * Nav.tsx. Everything else needs theme-aware nav text from first paint.
 */
function ghar_zende_has_hero() {
	return is_front_page() || is_post_type_archive( 'cave_article' );
}

/**
 * REST: expose category + featured image URL on cave_article responses so
 * the magazine listing's infinite-scroll JS (fetching raw REST data) can
 * render cards without a second round trip per post.
 */
function ghar_zende_rest_article_fields() {
	register_rest_field(
		'cave_article',
		'ghar_meta',
		array(
			'get_callback' => function ( $post ) {
				$post_id    = $post['id'];
				$categories = get_the_category( $post_id );
				$cat_name   = ! empty( $categories ) ? $categories[0]->name : '';
				$thumb      = get_the_post_thumbnail_url( $post_id, 'ghar-card' );

				return array(
					'category'   => $cat_name,
					'image'      => $thumb ? $thumb : '',
					'date_fa'    => ghar_zende_jalali_date( get_the_date( 'Y-m-d', $post_id ) ),
					'readTime'   => ghar_zende_read_time( $post_id ),
					'author'     => ghar_zende_author_for_category( $cat_name ),
					'excerpt'    => wp_strip_all_tags( get_the_excerpt( $post_id ) ),
					'permalink'  => get_permalink( $post_id ),
					'title'      => get_the_title( $post_id ),
				);
			},
			'schema'       => null,
		)
	);
}
add_action( 'rest_api_init', 'ghar_zende_rest_article_fields' );

/**
 * Widen the excerpt length slightly to match the original card copy length.
 */
function ghar_zende_excerpt_length() {
	return 30;
}
add_filter( 'excerpt_length', 'ghar_zende_excerpt_length' );

function ghar_zende_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'ghar_zende_excerpt_more' );
