<?php
/**
 * گونه‌ها — کارت‌های بخش «ساکنان این تاریکی» (#species) صفحه‌ی اصلی و
 * صفحه‌ی اختصاصی «گونه‌ها» (page-species.php) از نوشته‌های دسته‌ی
 * «گونه‌ها» خوانده می‌شوند:
 *
 * - عکس کارت  = تصویر شاخص نوشته
 * - نام کارت  = فیلد «نام کوتاه گونه»، وگرنه عنوان نوشته
 * - توضیح     = فیلد «توضیح کوتاه کارت»، وگرنه چکیده‌ی نوشته (کوتاه‌شده)
 * - نام علمی / زیستگاه / ویژگی / رنگ آیکون = متاباکس «کارت گونه» روی
 *   صفحه‌ی ویرایش نوشته (همان فیلدهای قبلی پنل «صفحه اصلی → گونه‌ها»)
 * - هر کارت به خود نوشته لینک می‌شود.
 *
 * اگر دسته هیچ نوشته‌ی منتشرشده‌ای نداشته باشد، صفحه‌ی اصلی همان فهرست
 * دستی پنل «صفحه اصلی → گونه‌ها» را نشان می‌دهد؛ آن داده‌ها دست‌نخورده
 * باقی مانده‌اند.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GHAR_ZENDE_SPECIES_META', '_ghar_species' );
define( 'GHAR_ZENDE_SPECIES_PAGE_SLUG', 'species' );

/** شناسه‌ی دسته‌ی «گونه‌ها». */
function ghar_zende_species_cat_id() {
	return (int) apply_filters( 'ghar_zende_species_cat_id', 21 );
}

/** فیلدهای متای کارت گونه — هم‌نام با فیلدهای ریپیتر پنل صفحه‌ی اصلی. */
function ghar_zende_species_fields() {
	return array(
		'name'        => array(
			'label' => 'نام کوتاه گونه',
			'help'  => 'روی کارت‌ها نمایش داده می‌شود. اگر خالی بماند، عنوان نوشته استفاده می‌شود.',
			'type'  => 'text',
		),
		'sci'         => array(
			'label' => 'نام علمی',
			'help'  => '',
			'type'  => 'ltr',
		),
		'habitat'     => array(
			'label' => 'زیستگاه',
			'help'  => '',
			'type'  => 'text',
		),
		'trait'       => array(
			'label' => 'ویژگی',
			'help'  => '',
			'type'  => 'text',
		),
		'description' => array(
			'label' => 'توضیح کوتاه کارت',
			'help'  => 'یکی دو جمله. اگر خالی بماند، چکیده‌ی نوشته (کوتاه‌شده) نمایش داده می‌شود.',
			'type'  => 'textarea',
		),
		'color'       => array(
			'label' => 'رنگ اصلی آیکون',
			'help'  => 'فقط وقتی نوشته تصویر شاخص نداشته باشد، به‌جای عکس یک آیکون ماهی با این رنگ‌ها نمایش داده می‌شود.',
			'type'  => 'color',
		),
		'accent'      => array(
			'label' => 'رنگ تکمیلی آیکون',
			'help'  => '',
			'type'  => 'color',
		),
	);
}

/** پاک‌سازی آرایه‌ی متا؛ مقدارهای خالی حذف می‌شوند. */
function ghar_zende_species_sanitize( $raw ) {
	$raw   = is_array( $raw ) ? $raw : array();
	$clean = array();
	foreach ( ghar_zende_species_fields() as $key => $field ) {
		$value = isset( $raw[ $key ] ) && is_scalar( $raw[ $key ] ) ? (string) $raw[ $key ] : '';
		if ( 'textarea' === $field['type'] ) {
			$value = sanitize_textarea_field( $value );
		} elseif ( 'color' === $field['type'] ) {
			$value = (string) sanitize_hex_color( $value );
		} else {
			$value = sanitize_text_field( $value );
		}
		if ( '' !== $value ) {
			$clean[ $key ] = $value;
		}
	}
	return $clean;
}

/** متای کارت یک نوشته، با همه‌ی کلیدها (خالی = ''). */
function ghar_zende_species_get_meta( $post_id ) {
	$meta = get_post_meta( $post_id, GHAR_ZENDE_SPECIES_META, true );
	$meta = is_array( $meta ) ? $meta : array();
	$out  = array();
	foreach ( array_keys( ghar_zende_species_fields() ) as $key ) {
		$out[ $key ] = isset( $meta[ $key ] ) ? (string) $meta[ $key ] : '';
	}
	return $out;
}

/** ثبت متا (قابل ویرایش از REST برای ویرایشگرهای مجاز). */
add_action(
	'init',
	function () {
		$props = array();
		foreach ( array_keys( ghar_zende_species_fields() ) as $key ) {
			$props[ $key ] = array( 'type' => 'string' );
		}
		register_post_meta(
			'post',
			GHAR_ZENDE_SPECIES_META,
			array(
				'type'              => 'object',
				'single'            => true,
				'show_in_rest'      => array(
					'schema' => array(
						'type'                 => 'object',
						'properties'           => $props,
						'additionalProperties' => false,
					),
				),
				'sanitize_callback' => 'ghar_zende_species_sanitize',
				'auth_callback'     => function ( $allowed, $meta_key, $post_id ) {
					return current_user_can( 'edit_post', $post_id );
				},
			)
		);
	}
);

/** ------------------------------------------------------------------
 * متاباکس «کارت گونه» روی صفحه‌ی ویرایش نوشته
 * ------------------------------------------------------------------ */
add_action(
	'add_meta_boxes_post',
	function () {
		add_meta_box(
			'ghar-species-card',
			'کارت گونه (صفحه‌ی اصلی و صفحه‌ی گونه‌ها)',
			'ghar_zende_species_render_metabox',
			'post',
			'normal',
			'high'
		);
	}
);

function ghar_zende_species_render_metabox( $post ) {
	$meta = ghar_zende_species_get_meta( $post->ID );
	wp_nonce_field( 'ghar_species_save', 'ghar_species_nonce' );
	echo '<p class="description">این اطلاعات فقط برای نوشته‌های دسته‌ی «گونه‌ها» روی کارت‌های صفحه‌ی اصلی و صفحه‌ی «گونه‌ها» استفاده می‌شود. عکس کارت همان <strong>تصویر شاخص</strong> نوشته است.</p>';
	echo '<table class="form-table" role="presentation"><tbody>';
	foreach ( ghar_zende_species_fields() as $key => $field ) {
		$id    = 'ghar-species-' . $key;
		$name  = 'ghar_species[' . $key . ']';
		$value = $meta[ $key ];
		echo '<tr><th scope="row"><label for="' . esc_attr( $id ) . '">' . esc_html( $field['label'] ) . '</label></th><td>';
		if ( 'textarea' === $field['type'] ) {
			echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" rows="3" class="large-text">' . esc_textarea( $value ) . '</textarea>';
		} elseif ( 'color' === $field['type'] ) {
			echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="ghar-species-color" />';
		} else {
			$dir = 'ltr' === $field['type'] ? ' dir="ltr"' : '';
			echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="regular-text"' . $dir . ' />'; // phpcs:ignore WordPress.Security.EscapeOutput
		}
		if ( '' !== $field['help'] ) {
			echo '<p class="description">' . esc_html( $field['help'] ) . '</p>';
		}
		echo '</td></tr>';
	}
	echo '</tbody></table>';
}

add_action(
	'save_post_post',
	function ( $post_id ) {
		if ( ! isset( $_POST['ghar_species_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['ghar_species_nonce'] ) ), 'ghar_species_save' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$raw   = isset( $_POST['ghar_species'] ) ? wp_unslash( (array) $_POST['ghar_species'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$clean = ghar_zende_species_sanitize( $raw );
		if ( empty( $clean ) ) {
			delete_post_meta( $post_id, GHAR_ZENDE_SPECIES_META );
		} else {
			update_post_meta( $post_id, GHAR_ZENDE_SPECIES_META, wp_slash( $clean ) );
		}
	}
);

add_action(
	'admin_enqueue_scripts',
	function ( $hook ) {
		if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
			return;
		}
		$screen = get_current_screen();
		if ( ! $screen || 'post' !== $screen->post_type ) {
			return;
		}
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_add_inline_script( 'wp-color-picker', 'jQuery(function($){$(".ghar-species-color").wpColorPicker();});' );
	}
);

/** ------------------------------------------------------------------
 * داده‌ی کارت‌ها
 * ------------------------------------------------------------------ */

/**
 * @param int|null $limit  تعداد کارت‌ها؛ null = پیش‌فرض صفحه‌ی اصلی (۶)، -1 = همه.
 * @param string   $size   اندازه‌ی تصویر شاخص.
 */
function ghar_zende_species_from_posts( $limit = null, $size = 'medium' ) {
	$cat = ghar_zende_species_cat_id();
	if ( ! $cat ) {
		return array();
	}
	if ( null === $limit ) {
		$limit = (int) apply_filters( 'ghar_zende_species_limit', 6 );
	}
	$query = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'cat'                 => $cat,
			'posts_per_page'      => (int) $limit,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	$items = array();
	foreach ( $query->posts as $post ) {
		$meta = ghar_zende_species_get_meta( $post->ID );
		$name = '' !== $meta['name'] ? $meta['name'] : wp_strip_all_tags( get_the_title( $post ) );
		$desc = $meta['description'];
		if ( '' === $desc ) {
			$source = has_excerpt( $post ) ? $post->post_excerpt : strip_shortcodes( $post->post_content );
			$desc   = wp_trim_words( wp_strip_all_tags( $source ), 26, '…' );
		}
		$thumb_id  = (int) get_post_thumbnail_id( $post );
		$photo_url = $thumb_id ? (string) wp_get_attachment_image_url( $thumb_id, $size ) : '';
		$alt       = $thumb_id ? trim( (string) get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) ) : '';
		$items[]   = array(
			'post_id'       => $post->ID,
			'name'          => $name,
			'title'         => wp_strip_all_tags( get_the_title( $post ) ),
			'sci'           => $meta['sci'],
			'habitat'       => $meta['habitat'],
			'trait'         => $meta['trait'],
			'description'   => $desc,
			'photo'         => 0,
			'photo_id'      => $thumb_id,
			'photo_url'     => $photo_url,
			'photo_default' => '',
			'alt'           => '' !== $alt ? $alt : $name . ' در آکواریوم غار',
			'color'         => $meta['color'],
			'accent'        => $meta['accent'],
			'url'           => get_permalink( $post ),
		);
	}
	return $items;
}

/** کارت‌های صفحه‌ی اصلی؛ اگر دسته خالی بود، فهرست دستی پنل صفحه‌ی اصلی. */
function ghar_zende_species_items() {
	$items = ghar_zende_species_from_posts();
	return ! empty( $items ) ? $items : ghar_zende_home_get( 'species' );
}

/** آدرس صفحه‌ی اختصاصی «گونه‌ها»؛ اگر نبود، آرشیو دسته. */
function ghar_zende_species_page_url() {
	$page = get_page_by_path( GHAR_ZENDE_SPECIES_PAGE_SLUG );
	if ( $page && 'publish' === $page->post_status ) {
		return get_permalink( $page );
	}
	$link = get_category_link( ghar_zende_species_cat_id() );
	return ( $link && ! is_wp_error( $link ) ) ? $link : home_url( '/#species' );
}

/** لینک دکمه‌ی «ساکنان بیشتر»: اگر در پنل تنظیم نشده، صفحه‌ی گونه‌ها. */
function ghar_zende_species_more_href() {
	$href = (string) ghar_zende_home_get( 'species_more_href' );
	if ( '' === $href || '#' === $href ) {
		return ghar_zende_species_page_url();
	}
	return $href;
}

/** آیکون SVG ماهی برای کارت بدون تصویر شاخص. */
function ghar_zende_species_icon( $color, $accent ) {
	$color  = $color ? $color : '#3f8fd1';
	$accent = $accent ? $accent : '#101820';
	return '<svg viewBox="0 0 100 48" class="w-full h-auto" aria-hidden="true">'
		. '<path d="M4 24 C 16 4, 46 2, 62 12 C 74 4, 92 10, 98 24 C 92 38, 74 44, 62 36 C 46 46, 16 44, 4 24 Z" fill="' . esc_attr( $color ) . '" opacity="0.92" />'
		. '<path d="M62 12 L98 24 L62 36 Z" fill="' . esc_attr( $accent ) . '" opacity="0.85" />'
		. '<path d="M18 8 C 10 2, 4 6, 2 14 C 10 14, 16 12, 18 8 Z" fill="' . esc_attr( $accent ) . '" opacity="0.7" />'
		. '<circle cx="20" cy="21" r="2.4" fill="var(--color-void)" />'
		. '<circle cx="20.7" cy="20.3" r="0.9" fill="var(--color-foam)" />'
		. '</svg>';
}
