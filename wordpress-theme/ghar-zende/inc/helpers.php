<?php
/**
 * Shared helpers: Jalali date conversion, author-by-category, read-time
 * estimation, and the TOC/heading-id injector for article content.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Department byline per category — matches AUTHOR_BY_CATEGORY in the
 * original src/data/articles.ts exactly (same 5 Persian category names).
 */
function ghar_zende_author_map() {
	return array(
		'گونه‌های جدید' => 'گروه زیست‌شناسی غار زنده',
		'علمی'          => 'گروه علمی غار زنده',
		'رویدادها'      => 'گروه رویدادها و برنامه‌ها',
		'پشت صحنه'      => 'گروه ارتباطات غار زنده',
		'اخبار غار'     => 'تحریریه غار زنده',
	);
}

function ghar_zende_author_for_category( $category_name ) {
	$map = ghar_zende_author_map();
	return isset( $map[ $category_name ] ) ? $map[ $category_name ] : 'تحریریه غار زنده';
}

/**
 * Estimated read time in minutes, ~ 200 fa words/min — same heuristic the
 * React side used (str_word_count is ASCII-only, so we count whitespace
 * runs instead, which works for Persian text).
 */
function ghar_zende_read_time( $post_id ) {
	$content    = get_post_field( 'post_content', $post_id );
	$text       = wp_strip_all_tags( $content );
	$word_count = count( preg_split( '/\s+/u', trim( $text ), -1, PREG_SPLIT_NO_EMPTY ) );
	$minutes    = max( 1, (int) round( $word_count / 200 ) );

	return sprintf( '%d دقیقه مطالعه', $minutes );
}

/**
 * Gregorian → Jalali (Persian solar calendar) conversion.
 *
 * Standard algorithm (jdate-style); accepts a 'Y-m-d' Gregorian string and
 * returns a formatted Jalali date string like "۳ شهریور ۱۴۰۵", matching
 * the date format already used across the site.
 */
function ghar_zende_gregorian_to_jalali( $gy, $gm, $gd ) {
	$g_days_in_month = array( 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 );
	$j_days_in_month = array( 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29 );

	$gy2 = $gy - 1600;
	$gm2 = $gm - 1;
	$gd2 = $gd - 1;

	$g_day_no = 365 * $gy2 + (int) ( ( $gy2 + 3 ) / 4 ) - (int) ( ( $gy2 + 99 ) / 100 ) + (int) ( ( $gy2 + 399 ) / 400 );

	for ( $i = 0; $i < $gm2; $i++ ) {
		$g_day_no += $g_days_in_month[ $i ];
	}
	if ( $gm2 > 1 && ( ( $gy2 % 4 === 0 && $gy2 % 100 !== 0 ) || $gy2 % 400 === 0 ) ) {
		$g_day_no++;
	}
	$g_day_no += $gd2;

	$j_day_no = $g_day_no - 79;

	$j_np = (int) ( $j_day_no / 12053 );
	$j_day_no %= 12053;

	$jy = 979 + 33 * $j_np + 4 * (int) ( $j_day_no / 1461 );
	$j_day_no %= 1461;

	if ( $j_day_no >= 366 ) {
		$jy += (int) ( ( $j_day_no - 1 ) / 365 );
		$j_day_no = ( $j_day_no - 1 ) % 365;
	}

	$i = 0;
	while ( $i < 11 && $j_day_no >= $j_days_in_month[ $i ] ) {
		$j_day_no -= $j_days_in_month[ $i ];
		$i++;
	}
	$jm = $i + 1;
	$jd = $j_day_no + 1;

	return array( $jy, $jm, $jd );
}

function ghar_zende_persian_digits( $string ) {
	$fa = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
	return strtr(
		(string) $string,
		array(
			'0' => $fa[0], '1' => $fa[1], '2' => $fa[2], '3' => $fa[3], '4' => $fa[4],
			'5' => $fa[5], '6' => $fa[6], '7' => $fa[7], '8' => $fa[8], '9' => $fa[9],
		)
	);
}

/**
 * Formats a 'Y-m-d' Gregorian date string as "۳ شهریور ۱۴۰۵".
 */
function ghar_zende_jalali_date( $gregorian_ymd ) {
	$parts = explode( '-', $gregorian_ymd );
	if ( count( $parts ) !== 3 ) {
		return $gregorian_ymd;
	}

	list( $gy, $gm, $gd ) = array_map( 'intval', $parts );
	list( $jy, $jm, $jd ) = ghar_zende_gregorian_to_jalali( $gy, $gm, $gd );

	$month_names = array(
		'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
		'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند',
	);

	$month_name = $month_names[ $jm - 1 ];

	return sprintf(
		'%s %s %s',
		ghar_zende_persian_digits( $jd ),
		$month_name,
		ghar_zende_persian_digits( $jy )
	);
}

/**
 * Jalali → Gregorian, the inverse of ghar_zende_gregorian_to_jalali() —
 * used only by the importer to set each seeded post's real post_date from
 * its original "۳ شهریور ۱۴۰۵"-style string, so ghar_zende_jalali_date()
 * displays the exact same Jalali date back out on the front end.
 * Standard algorithm (same one behind the common PHP jdf/persian-date
 * libraries).
 */
function ghar_zende_jalali_to_gregorian( $jy, $jm, $jd ) {
	$j_days_in_month = array( 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29 );

	$jy -= 979;
	$jm -= 1;
	$jd -= 1;

	$j_day_no = 365 * $jy + (int) ( $jy / 33 ) * 8 + (int) ( ( ( $jy % 33 ) + 3 ) / 4 );
	for ( $i = 0; $i < $jm; $i++ ) {
		$j_day_no += $j_days_in_month[ $i ];
	}
	$j_day_no += $jd;

	$g_day_no = $j_day_no + 79;

	$gy = 1600 + 400 * (int) ( $g_day_no / 146097 );
	$g_day_no %= 146097;

	$leap = true;
	if ( $g_day_no >= 36525 ) {
		$g_day_no--;
		$gy += 100 * (int) ( $g_day_no / 36524 );
		$g_day_no %= 36524;

		if ( $g_day_no >= 365 ) {
			$g_day_no++;
		} else {
			$leap = false;
		}
	}

	$gy += 4 * (int) ( $g_day_no / 1461 );
	$g_day_no %= 1461;

	if ( $g_day_no >= 366 ) {
		$leap = false;
		$g_day_no--;
		$gy += (int) ( $g_day_no / 365 );
		$g_day_no %= 365;
	}

	$g_days_in_month = array( 31, $leap ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 );

	$gm = 0;
	while ( $gm < 12 && $g_day_no >= $g_days_in_month[ $gm ] ) {
		$g_day_no -= $g_days_in_month[ $gm ];
		$gm++;
	}
	$gm += 1;
	$gd  = $g_day_no + 1;

	return array( $gy, $gm, $gd );
}

function ghar_zende_latin_digits( $string ) {
	$fa = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
	$en = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
	return str_replace( $fa, $en, (string) $string );
}

/**
 * Parses a "۳ شهریور ۱۴۰۵"-style Jalali date string (the exact format used
 * in src/data/articles.ts) into a 'Y-m-d H:i:s' Gregorian string suitable
 * for wp_insert_post's post_date.
 */
function ghar_zende_parse_jalali_string( $fa_date ) {
	$month_names = array(
		'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
		'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند',
	);

	$parts = preg_split( '/\s+/u', trim( $fa_date ) );
	if ( count( $parts ) !== 3 ) {
		return current_time( 'mysql' );
	}

	$jd_str   = ghar_zende_latin_digits( $parts[0] );
	$month_fa = $parts[1];
	$jy_str   = ghar_zende_latin_digits( $parts[2] );

	$jm = array_search( $month_fa, $month_names, true );
	if ( false === $jm ) {
		return current_time( 'mysql' );
	}

	list( $gy, $gm, $gd ) = ghar_zende_jalali_to_gregorian( (int) $jy_str, $jm + 1, (int) $jd_str );

	return sprintf( '%04d-%02d-%02d 09:00:00', $gy, $gm, $gd );
}

/**
 * URL of a theme-bundled photo under assets/images/ (the 13 real cave
 * photos also sideloaded into the Media Library by the importer).
 */
function ghar_zende_img( $filename ) {
	return GHAR_ZENDE_URI . '/assets/images/' . $filename;
}

/**
 * Prints the drifting dust/bubble particle markup — ported from
 * ParticleField.tsx's deterministic layout formula (left/top from the
 * index, not random) so the static markup matches what that component's
 * useMemo produced; assets/js/home.js re-applies the same GSAP drift tween
 * to each `.particle` span found inside the container.
 */
function ghar_zende_particles( $variant, $count ) {
	for ( $i = 0; $i < $count; $i++ ) {
		$left = (int) round( fmod( $i * 137.5, 100 ) );
		$top  = (int) round( fmod( $i * 71.3, 100 ) );
		$size = ( 'bubble' === $variant ) ? 3 + ( $i % 4 ) : 1 + ( $i % 3 );
		$bg   = ( 'bubble' === $variant )
			? 'radial-gradient(circle, rgba(244,239,227,0.8), rgba(244,239,227,0.05))'
			: 'var(--color-turquoise-soft)';

		printf(
			'<span class="particle absolute rounded-full" style="left:%1$d%%;top:%2$d%%;width:%3$dpx;height:%3$dpx;background:%4$s;opacity:0.25"></span>',
			$left,
			$top,
			$size,
			$bg
		);
	}
}

/**
 * Injects `id="section-N"` on every <h2> in rendered post content, where N
 * is the h2's index counted only among h2 tags — the exact scheme
 * headingsOf() used in the original data layer (ids: section-0, section-1…
 * in document order). Also collects the headings for the TOC sidebar via
 * a global, read immediately after the_content() runs.
 */
$GLOBALS['ghar_zende_toc'] = array();

function ghar_zende_inject_heading_ids( $content ) {
	if ( ! is_singular( 'cave_article' ) || ! in_the_loop() ) {
		return $content;
	}

	$GLOBALS['ghar_zende_toc'] = array();
	$index                     = 0;

	$content = preg_replace_callback(
		'/<h2([^>]*)>(.*?)<\/h2>/su',
		function ( $matches ) use ( &$index ) {
			$id    = 'section-' . $index;
			$text  = wp_strip_all_tags( $matches[2] );
			$index++;

			$GLOBALS['ghar_zende_toc'][] = array(
				'id'   => $id,
				'text' => $text,
			);

			$attrs = $matches[1];
			// Strip any pre-existing id attribute before adding ours.
			$attrs = preg_replace( '/\s+id="[^"]*"/i', '', $attrs );

			return '<h2' . $attrs . ' id="' . esc_attr( $id ) . '">' . $matches[2] . '</h2>';
		},
		$content
	);

	return $content;
}
add_filter( 'the_content', 'ghar_zende_inject_heading_ids', 5 );

/**
 * Returns the headings collected by ghar_zende_inject_heading_ids() for the
 * current article — call only after the_content() has already run once in
 * the loop (single-cave_article.php calls the_content() before rendering
 * the sidebar TOC, same order the original ArticleBody/Sidebar mount in).
 */
function ghar_zende_get_toc() {
	return isset( $GLOBALS['ghar_zende_toc'] ) ? $GLOBALS['ghar_zende_toc'] : array();
}
