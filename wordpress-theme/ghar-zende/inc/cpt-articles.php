<?php
/**
 * Custom post type: cave_article — the magazine's articles.
 *
 * has_archive => 'magazine' makes /magazine/ auto-route to
 * archive-cave_article.php with zero rewrite-rule wiring; show_in_rest
 * exposes /wp-json/wp/v2/cave_article for the listing page's infinite
 * scroll. Categories are WordPress's own built-in `category` taxonomy
 * (not a custom one) — the 5 Persian sections map onto real WP categories
 * seeded by the importer, so editors keep normal category-list tooling.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ghar_zende_register_cpt() {
	$labels = array(
		'name'                  => 'مقالات مجله',
		'singular_name'         => 'مقاله مجله',
		'menu_name'             => 'مجله غار زنده',
		'add_new'               => 'افزودن مقاله',
		'add_new_item'          => 'افزودن مقاله جدید',
		'edit_item'             => 'ویرایش مقاله',
		'new_item'              => 'مقاله جدید',
		'view_item'             => 'مشاهده مقاله',
		'view_items'            => 'مشاهده مقالات',
		'search_items'          => 'جستجوی مقالات',
		'not_found'             => 'مقاله‌ای یافت نشد',
		'not_found_in_trash'    => 'مقاله‌ای در زباله‌دان یافت نشد',
		'all_items'             => 'همه مقالات',
		'archives'              => 'بایگانی مقالات',
		'featured_image'        => 'تصویر شاخص مقاله',
		'set_featured_image'    => 'انتخاب تصویر شاخص',
		'remove_featured_image' => 'حذف تصویر شاخص',
	);

	register_post_type(
		'cave_article',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => 'magazine',
			'rewrite'       => array( 'slug' => 'magazine', 'with_front' => false ),
			'menu_icon'     => 'dashicons-book-alt',
			'show_in_rest'  => true,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
			'taxonomies'    => array( 'category' ),
			'menu_position' => 5,
		)
	);
}
add_action( 'init', 'ghar_zende_register_cpt' );

/**
 * Make the built-in `category` taxonomy's admin UI + REST behave for
 * cave_article the same way it does for posts (it's registered against
 * 'post' by core; this just extends its object type).
 */
function ghar_zende_extend_category_taxonomy() {
	register_taxonomy_for_object_type( 'category', 'cave_article' );
}
add_action( 'init', 'ghar_zende_extend_category_taxonomy', 20 );

/**
 * Flush rewrite rules on activation so /magazine/ and /magazine/%postname%/
 * resolve immediately — 'init' (where the CPT is normally registered) can
 * fire before 'after_switch_theme' misses the just-switched CPT, so the
 * type is registered explicitly here before flushing.
 */
function ghar_zende_flush_rewrites() {
	ghar_zende_register_cpt();
	ghar_zende_extend_category_taxonomy();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'ghar_zende_flush_rewrites' );
