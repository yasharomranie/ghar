<?php
/**
 * Seeds the 24 real magazine articles as cave_article posts on theme
 * activation, plus sideloads the 13 real cave photos into the Media
 * Library and cycles them as featured images — porting src/data/articles.ts
 * 1:1 (see inc/data/articles.json, generated from that file) so the
 * WordPress site launches with the same real editorial content as the
 * original Next.js build, not placeholder copy.
 *
 * Idempotent: bails immediately if any cave_article posts already exist,
 * so re-activating the theme (or a future update) never duplicates posts.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ghar_zende_image_pool() {
	return array(
		'aquarium-window-clear.webp',
		'aquarium-window-light.webp',
		'aquarium-window-plants.webp',
		'corridor-entrance.webp',
		'corridor-long-walk.webp',
		'corridor-panorama-bright.webp',
		'corridor-panorama-dark.webp',
		'corridor-stalagmites.webp',
		'corridor-warm-glow.webp',
		'darkness-threshold.webp',
		'hero-entrance.webp',
		'species-regal-angelfish.webp',
		'water-corridor.webp',
	);
}

/**
 * Sideloads a theme-bundled image into the Media Library (once) and
 * returns its attachment id. Caches by filename in a static array so the
 * same photo — cycled across many posts, exactly like imageFor() did on
 * the React side — is only uploaded once.
 */
function ghar_zende_get_or_create_attachment( $filename ) {
	static $cache = array();

	if ( isset( $cache[ $filename ] ) ) {
		return $cache[ $filename ];
	}

	$existing = get_posts(
		array(
			'post_type'   => 'attachment',
			'meta_key'    => '_ghar_zende_seed_file',
			'meta_value'  => $filename,
			'numberposts' => 1,
			'post_status' => 'inherit',
		)
	);

	if ( ! empty( $existing ) ) {
		$cache[ $filename ] = $existing[0]->ID;
		return $existing[0]->ID;
	}

	$file_path = GHAR_ZENDE_DIR . '/assets/images/' . $filename;
	if ( ! file_exists( $file_path ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$filetype = wp_check_filetype( $filename, null );
	$upload   = wp_upload_bits( $filename, null, file_get_contents( $file_path ) );

	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$attachment = array(
		'post_mime_type' => $filetype['type'] ? $filetype['type'] : 'image/webp',
		'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$attach_id = wp_insert_attachment( $attachment, $upload['file'] );
	if ( is_wp_error( $attach_id ) || ! $attach_id ) {
		return 0;
	}

	update_post_meta( $attach_id, '_ghar_zende_seed_file', $filename );

	$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
	wp_update_attachment_metadata( $attach_id, $attach_data );

	$cache[ $filename ] = $attach_id;
	return $attach_id;
}

/**
 * Converts one article's `body` blocks (p / h2 / quote / list / image) into
 * HTML — the exact element/class shapes ArticleBody.tsx rendered, so
 * assets/css/theme.css (compiled straight from that component's Tailwind
 * classes) styles imported content identically. The class="" strings below
 * are that component's, copied verbatim, not restyled.
 */
function ghar_zende_body_blocks_to_html( $blocks ) {
	$html = '';

	foreach ( $blocks as $block ) {
		$type = isset( $block['type'] ) ? $block['type'] : '';

		switch ( $type ) {
			case 'p':
				$html .= '<p>' . wp_kses_post( $block['text'] ) . "</p>\n";
				break;

			case 'h2':
				// id is injected later by ghar_zende_inject_heading_ids() on
				// render (the_content filter), which counts h2 tags in
				// document order — same scheme headingsOf() used.
				$html .= '<h2 class="scroll-mt-28 font-display text-xl font-semibold text-ink sm:text-2xl">' . esc_html( $block['text'] ) . "</h2>\n";
				break;

			case 'quote':
				$html .= '<blockquote class="border-s-4 border-accent-dim bg-surface-raised/40 py-4 ps-6 font-display text-lg leading-relaxed text-ink">' . wp_kses_post( $block['text'] ) . "</blockquote>\n";
				break;

			case 'list':
				$html .= '<ul class="flex flex-col gap-2.5">' . "\n";
				foreach ( (array) $block['items'] as $item ) {
					$html .= '<li class="flex gap-3"><span aria-hidden="true" class="mt-2.5 h-1.5 w-1.5 flex-none rounded-full bg-accent"></span><span>' . esc_html( $item ) . "</span></li>\n";
				}
				$html .= "</ul>\n";
				break;

			case 'image':
				if ( ! empty( $block['image'] ) ) {
					$caption = ! empty( $block['caption'] ) ? esc_html( $block['caption'] ) : '';
					$html   .= '<figure class="overflow-hidden rounded-2xl border border-ink/10"><div class="relative aspect-[16/9] w-full"><img class="absolute inset-0 h-full w-full object-cover" src="' . esc_url( $block['image'] ) . '" alt="' . esc_attr( $caption ) . '" /></div>';
					if ( $caption ) {
						$html .= '<figcaption class="bg-surface-raised/60 px-4 py-2 text-xs text-ink-faint">' . $caption . '</figcaption>';
					}
					$html .= "</figure>\n";
				}
				break;
		}
	}

	return $html;
}

function ghar_zende_seed_articles() {
	// Idempotency guard — never run twice.
	$already = get_posts(
		array(
			'post_type'      => 'cave_article',
			'post_status'    => 'any',
			'numberposts'    => 1,
			'fields'         => 'ids',
		)
	);
	if ( ! empty( $already ) ) {
		return;
	}

	$json_path = GHAR_ZENDE_DIR . '/inc/data/articles.json';
	if ( ! file_exists( $json_path ) ) {
		return;
	}

	$articles = json_decode( file_get_contents( $json_path ), true );
	if ( ! is_array( $articles ) ) {
		return;
	}

	$image_pool = ghar_zende_image_pool();
	$pool_count = count( $image_pool );

	foreach ( $articles as $index => $article ) {
		$content   = ghar_zende_body_blocks_to_html( $article['body'] );
		$post_date = ghar_zende_parse_jalali_string( $article['date'] );

		$post_id = wp_insert_post(
			array(
				'post_type'     => 'cave_article',
				'post_title'    => wp_strip_all_tags( $article['title'] ),
				'post_name'     => sanitize_title( $article['slug'] ),
				'post_excerpt'  => wp_strip_all_tags( $article['excerpt'] ),
				'post_content'  => $content,
				'post_status'   => 'publish',
				'post_date'     => $post_date,
				'post_date_gmt' => get_gmt_from_date( $post_date ),
				'comment_status' => 'closed',
			)
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		// Category — reuses (or creates once) a real WP category per the
		// 5 Persian section names, same taxonomy the admin sees under Posts.
		$category = $article['category'];
		$term     = term_exists( $category, 'category' );
		if ( ! $term ) {
			$term = wp_insert_term( $category, 'category' );
		}
		if ( ! is_wp_error( $term ) && isset( $term['term_id'] ) ) {
			wp_set_post_terms( $post_id, array( (int) $term['term_id'] ), 'category' );
		}

		// Featured image — same deterministic cycling as imageFor(index).
		$image_file = $image_pool[ $index % $pool_count ];
		$attach_id  = ghar_zende_get_or_create_attachment( $image_file );
		if ( $attach_id ) {
			set_post_thumbnail( $post_id, $attach_id );
		}
	}
}
add_action( 'after_switch_theme', 'ghar_zende_seed_articles' );
