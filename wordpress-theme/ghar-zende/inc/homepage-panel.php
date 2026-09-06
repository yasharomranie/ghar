<?php
/**
 * صفحه‌ی مدیریت محتوای صفحه‌ی اصلی — یک پنل اختصاصی در پیشخوان که تمام
 * متن‌ها، لینک‌ها و تصاویر نُه صحنه‌ی front-page.php را بدون دست‌زدن به
 * کد قابل ویرایش می‌کند.
 *
 * چرا یک صفحه‌ی تنظیمات جدا، نه یک متاباکس روی صفحه؟ چون تنظیمات خواندن
 * سایت (Settings → Reading) روی «آخرین نوشته‌ها»ست، نه «یک صفحه‌ی
 * ثابت» — یعنی هیچ پستی از نوع page به‌عنوان صفحه‌ی اصلی وجود ندارد که
 * بشود رویش متاباکس گذاشت؛ front-page.php همیشه مستقل از آن تنظیم روی
 * آدرس اصلی سایت لود می‌شود. پس محل طبیعی این پنل، یک منوی جدید در
 * پیشخوان است، نه صفحه‌ی ویرایش یک «صفحه».
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GHAR_ZENDE_HOME_OPTION', 'ghar_zende_home_content' );

/** ------------------------------------------------------------------
 * Defaults — mirrors, one-for-one, the content that used to be
 * hardcoded directly inside front-page.php (and the `phases` /
 * `storyChapters` arrays inside assets/js/home.js), so adding this
 * panel does not change anything a visitor sees until an admin
 * actually edits a field.
 * ------------------------------------------------------------------ */
function ghar_zende_home_defaults() {
	return array(
		'hero_eyebrow'     => 'A Living Aquarium Hidden Inside the Earth',
		'hero_title_line1' => 'جایی که سنگ، آب و زندگی',
		'hero_title_line2' => 'به هم می‌رسند',
		'hero_lede'        => 'سفری به قلب یک غار زنده',
		'hero_cta_text'    => 'کشف غار',
		'hero_cta_href'    => '#darkness',
		'hero_image'       => 0,

		'darkness_text'  => 'همه‌چیز از دل سنگ آغاز می‌شود.',
		'darkness_image' => 0,

		'water_text1' => 'اما در دل این تاریکی،',
		'water_text2' => 'زندگی جریان دارد.',
		'water_image' => 0,

		'aquarium_caption'     => 'آکواریومی که تصویرش را با اسکرول تو کامل می‌کند.',
		'aquarium_frame_image' => 0,
		'aquarium_clear_image' => 0,
		'aquarium_phases'      => array(
			array( 'at' => 0, 'label' => 'سنگ' ),
			array( 'at' => 0.25, 'label' => 'سایه' ),
			array( 'at' => 0.45, 'label' => 'نور' ),
			array( 'at' => 0.7, 'label' => 'رنگ' ),
			array( 'at' => 0.9, 'label' => 'زندگی' ),
		),

		'life_image'    => 0,
		'life_captions' => array(
			array(
				'title' => 'نوری که از دل آب می‌گذرد',
				'text'  => 'هر پرتو، مسیر خودش را در تاریکی پیدا می‌کند.',
			),
			array(
				'title' => 'هر آکواریوم، دنیای خودش',
				'text'  => 'ده‌ها متر سنگ، ده‌ها دنیای زنده‌ی جداگانه.',
			),
			array(
				'title' => 'سکوت صخره، همهمه‌ی حیات',
				'text'  => 'بیرون سکوت است؛ پشت شیشه، زندگی در جریان است.',
			),
			array(
				'title' => 'یک دنیای کامل',
				'text'  => 'این تاریکی، حالا خانه‌ی موجوداتی زنده است.',
			),
		),

		'species_eyebrow' => 'SPECIES',
		'species_title'   => 'ساکنان این تاریکی',
		'species'         => array(
			array(
				'name'          => 'فرشته‌ماهی سلطنتی',
				'sci'           => 'Pygoplites diacanthus',
				'habitat'       => 'شکاف‌های صخره‌ای کم‌نور',
				'trait'         => 'رنگ‌های نواری که در نور کم می‌درخشند',
				'description'   => 'در تاریک‌روشنای غار، نوارهای آبی و طلایی‌اش تنها وقتی نور به آن می‌تابد آشکار می‌شوند.',
				'photo'         => 0,
				'photo_default' => 'species-regal-angelfish.webp',
				'color'         => '',
				'accent'        => '',
			),
			array(
				'name'          => 'تانگ آبی پودری',
				'sci'           => 'Acanthurus leucosternon',
				'habitat'       => 'جریان‌های آرام نزدیک سطح آب',
				'trait'         => 'حرکت گروهی و هماهنگ',
				'description'   => 'این‌ها معمولاً به‌صورت دسته‌جمعی شنا می‌کنند؛ حرکتشان مثل موجی آبی در دل تاریکی است.',
				'photo'         => 0,
				'photo_default' => '',
				'color'         => '#3f8fd1',
				'accent'        => '#101820',
			),
			array(
				'name'          => 'سیکلید غاری',
				'sci'           => 'Amphilophus cf. citrinellus',
				'habitat'       => 'بستر سنگی و ریشه‌های فرورفته در آب',
				'trait'         => 'سازگاری کامل با نور بسیار کم',
				'description'   => 'نسل‌هایی از این گونه در همین محیط کم‌نور رشد کرده‌اند و کمتر از هر ماهی دیگری به نور نیاز دارند.',
				'photo'         => 0,
				'photo_default' => '',
				'color'         => '#c97a4a',
				'accent'        => '#5c3a21',
			),
			array(
				'name'          => 'گاوماهی لیمویی',
				'sci'           => 'Gobiodon citrinus',
				'habitat'       => 'لابه‌لای گیاهان آبزی و سنگ‌های مرجانی',
				'trait'         => 'رنگ زرد درخشان، برخلاف محیط تیره اطراف',
				'description'   => 'تنها لکه‌ی روشن این صحنه؛ انگار طبیعت خواسته یک نقطه‌ی امید در دل تاریکی بگذارد.',
				'photo'         => 0,
				'photo_default' => '',
				'color'         => '#e8c93a',
				'accent'        => '#8a6c14',
			),
		),

		'geology_eyebrow' => 'THE GEOLOGY',
		'geology_title'   => 'این غار فقط یک غار نیست.',
		'geology_image'   => 0,
		'geology_facts'   => array(
			array(
				'value' => '۱۸',
				'unit'  => 'متر',
				'label' => 'ارتفاع غار',
				'note'  => 'سقفی که هزاران سال بی‌صدا شکل گرفته است.',
			),
			array(
				'value' => '۴۲۰',
				'unit'  => 'متر',
				'label' => 'عمق مسیر',
				'note'  => 'مسیری که قدم‌به‌قدم به دل زمین نزدیک‌تر می‌شود.',
			),
			array(
				'value' => 'دو میلیون',
				'unit'  => 'سال',
				'label' => 'قدمت تخمینی',
				'note'  => 'پیش از هر جاده و هر شهر، این سنگ‌ها اینجا بودند.',
			),
			array(
				'value' => '۱۹',
				'unit'  => 'درجه',
				'label' => 'دمای آب',
				'note'  => 'ثابت در تمام فصل‌ها؛ زیستگاهی بی‌نوسان.',
			),
			array(
				'value' => '۲۷',
				'unit'  => 'گونه',
				'label' => 'گونه‌های آبزی',
				'note'  => 'هرکدام با سازگاری خاص خود به این محیط.',
			),
			array(
				'value' => '۱۴',
				'unit'  => '',
				'label' => 'آکواریوم‌های درون‌صخره‌ای',
				'note'  => 'هرکدام در دل سنگ، نه در برابر آن.',
			),
		),

		'story_image'    => 0,
		'story_chapters' => array(
			array(
				'title'   => 'THE CAVE',
				'persian' => 'غار',
				'text'    => 'میلیون‌ها سال در سکوت شکل گرفته.',
			),
			array(
				'title'   => 'THE WATER',
				'persian' => 'آب',
				'text'    => 'آب، مسیر تازه‌ای برای زندگی ساخته است.',
			),
			array(
				'title'   => 'THE LIFE',
				'persian' => 'زندگی',
				'text'    => 'حالا این تاریکی، خانه‌ی موجوداتی زنده است.',
			),
		),

		'visit_heading' => 'حالا نوبت توست که این دنیا را از نزدیک ببینی.',
		'visit_image'   => 0,
		'visit_ctas'    => array(
			array( 'text' => 'برنامه بازدید', 'href' => '#', 'primary' => 1 ),
			array( 'text' => 'مسیریابی', 'href' => '#', 'primary' => 0 ),
			array( 'text' => 'تماس با ما', 'href' => '#', 'primary' => 0 ),
		),
	);
}

/**
 * Scalar (non-repeater) fields and their sanitize/render type.
 */
function ghar_zende_home_field_types() {
	return array(
		'hero_eyebrow'         => 'text',
		'hero_title_line1'     => 'text',
		'hero_title_line2'     => 'text',
		'hero_lede'            => 'text',
		'hero_cta_text'        => 'text',
		'hero_cta_href'        => 'url',
		'hero_image'           => 'image',

		'darkness_text'        => 'text',
		'darkness_image'       => 'image',

		'water_text1'          => 'text',
		'water_text2'          => 'text',
		'water_image'          => 'image',

		'aquarium_caption'     => 'text',
		'aquarium_frame_image' => 'image',
		'aquarium_clear_image' => 'image',

		'life_image'           => 'image',

		'species_eyebrow'      => 'text',
		'species_title'        => 'text',

		'geology_eyebrow'      => 'text',
		'geology_title'        => 'text',
		'geology_image'        => 'image',

		'story_image'          => 'image',

		'visit_heading'        => 'text',
		'visit_image'          => 'image',
	);
}

/**
 * Repeater fields: key => { sub-field key => type }.
 */
function ghar_zende_home_repeater_schema() {
	return array(
		'aquarium_phases' => array(
			'at'    => 'float',
			'label' => 'text',
		),
		'life_captions'   => array(
			'title' => 'text',
			'text'  => 'text',
		),
		'species'         => array(
			'name'          => 'text',
			'sci'           => 'text',
			'habitat'       => 'text',
			'trait'         => 'text',
			'description'   => 'textarea',
			'photo'         => 'image',
			'photo_default' => 'raw',
			'color'         => 'text',
			'accent'        => 'text',
		),
		'geology_facts'   => array(
			'value' => 'text',
			'unit'  => 'text',
			'label' => 'text',
			'note'  => 'text',
		),
		'story_chapters'  => array(
			'title'   => 'text',
			'persian' => 'text',
			'text'    => 'text',
		),
		'visit_ctas'      => array(
			'text'    => 'text',
			'href'    => 'url',
			'primary' => 'checkbox',
		),
	);
}

/**
 * Saved option merged over the defaults. Repeaters are taken wholesale
 * from the saved option the moment it exists at all (rather than
 * index-merged with the defaults) — otherwise deleting rows down to
 * fewer than the default count would leave leftover default rows
 * dangling past the end of what was actually saved.
 */
function ghar_zende_home_content() {
	static $content = null;
	if ( null !== $content ) {
		return $content;
	}

	$saved    = get_option( GHAR_ZENDE_HOME_OPTION, array() );
	$saved    = is_array( $saved ) ? $saved : array();
	$defaults = ghar_zende_home_defaults();
	$content  = array_replace_recursive( $defaults, $saved );

	foreach ( ghar_zende_home_repeater_schema() as $key => $fields ) {
		if ( isset( $saved[ $key ] ) && is_array( $saved[ $key ] ) ) {
			$content[ $key ] = $saved[ $key ];
		}
	}

	return $content;
}

function ghar_zende_home_get( $key ) {
	$content = ghar_zende_home_content();
	return isset( $content[ $key ] ) ? $content[ $key ] : '';
}

/**
 * Resolves an image field to a URL: the admin-picked attachment if one
 * was chosen, otherwise the theme's own default image asset.
 */
function ghar_zende_home_image( $key, $fallback_filename ) {
	$id = (int) ghar_zende_home_get( $key );
	if ( $id ) {
		$url = wp_get_attachment_image_url( $id, 'full' );
		if ( $url ) {
			return $url;
		}
	}
	return ghar_zende_img( $fallback_filename );
}

/** ------------------------------------------------------------------
 * Admin menu + save handling.
 * ------------------------------------------------------------------ */
function ghar_zende_home_admin_menu() {
	$hook = add_menu_page(
		'مدیریت صفحه اصلی — غار زنده',
		'صفحه اصلی',
		'edit_theme_options',
		'ghar-zende-home',
		'ghar_zende_home_render_page',
		'dashicons-images-alt2',
		59
	);
	add_action( 'load-' . $hook, function () {
		add_action( 'admin_enqueue_scripts', 'ghar_zende_home_admin_assets' );
	} );
}
add_action( 'admin_menu', 'ghar_zende_home_admin_menu' );

function ghar_zende_home_admin_assets() {
	wp_enqueue_media();
	wp_enqueue_style( 'ghar-zende-home-admin', GHAR_ZENDE_URI . '/assets/admin/homepage-panel.css', array(), ghar_zende_asset_ver( '/assets/admin/homepage-panel.css' ) );
	wp_enqueue_script( 'ghar-zende-home-admin', GHAR_ZENDE_URI . '/assets/admin/homepage-panel.js', array(), ghar_zende_asset_ver( '/assets/admin/homepage-panel.js' ), true );
}

function ghar_zende_home_sanitize_value( $val, $type ) {
	switch ( $type ) {
		case 'image':
			return absint( $val );
		case 'url':
			return esc_url_raw( $val );
		case 'textarea':
			return sanitize_textarea_field( $val );
		case 'checkbox':
			return $val ? 1 : 0;
		case 'float':
			return max( 0, min( 1, (float) $val ) );
		case 'raw':
			return sanitize_text_field( $val );
		case 'text':
		default:
			return sanitize_text_field( $val );
	}
}

function ghar_zende_home_maybe_save() {
	if ( ! isset( $_POST['ghz_save'] ) ) {
		return;
	}
	if ( ! isset( $_POST['ghz_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['ghz_nonce'] ), 'ghar_zende_home_save' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$raw = isset( $_POST['ghz'] ) && is_array( $_POST['ghz'] ) ? wp_unslash( $_POST['ghz'] ) : array();
	$out = array();

	foreach ( ghar_zende_home_field_types() as $key => $type ) {
		$val         = isset( $raw[ $key ] ) ? $raw[ $key ] : '';
		$out[ $key ] = ghar_zende_home_sanitize_value( $val, $type );
	}

	foreach ( ghar_zende_home_repeater_schema() as $key => $fields ) {
		$rows       = isset( $raw[ $key ] ) && is_array( $raw[ $key ] ) ? $raw[ $key ] : array();
		$clean_rows = array();
		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$clean       = array();
			$has_content = false;
			foreach ( $fields as $fkey => $ftype ) {
				$v = isset( $row[ $fkey ] ) ? $row[ $fkey ] : '';
				$v = ghar_zende_home_sanitize_value( $v, $ftype );
				$clean[ $fkey ] = $v;
				if ( ! in_array( $fkey, array( 'primary', 'photo_default' ), true ) && '' !== $v && 0 !== $v ) {
					$has_content = true;
				}
			}
			if ( $has_content ) {
				$clean_rows[] = $clean;
			}
		}
		$out[ $key ] = $clean_rows;
	}

	update_option( GHAR_ZENDE_HOME_OPTION, $out );

	add_action( 'admin_notices', function () {
		echo '<div class="notice notice-success is-dismissible"><p>تغییرات صفحه‌ی اصلی ذخیره شد.</p></div>';
	} );
}
add_action( 'admin_init', 'ghar_zende_home_maybe_save' );

/** ------------------------------------------------------------------
 * Field / repeater renderers.
 * ------------------------------------------------------------------ */
function ghar_zende_home_render_text( $key, $label, $content, $type = 'text' ) {
	$val = isset( $content[ $key ] ) ? $content[ $key ] : '';
	echo '<div class="ghz-field">';
	echo '<label for="ghz-' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label>';
	if ( 'textarea' === $type ) {
		echo '<textarea id="ghz-' . esc_attr( $key ) . '" name="ghz[' . esc_attr( $key ) . ']" rows="3">' . esc_textarea( $val ) . '</textarea>';
	} else {
		echo '<input type="text" id="ghz-' . esc_attr( $key ) . '" name="ghz[' . esc_attr( $key ) . ']" value="' . esc_attr( $val ) . '"' . ( 'url' === $type ? ' dir="ltr"' : '' ) . ' />';
	}
	echo '</div>';
}

function ghar_zende_home_render_image( $key, $label, $content, $fallback_filename ) {
	$id  = isset( $content[ $key ] ) ? (int) $content[ $key ] : 0;
	$src = $id ? wp_get_attachment_image_url( $id, 'medium' ) : '';
	if ( ! $src && $fallback_filename ) {
		$src = ghar_zende_img( $fallback_filename );
	}
	ghar_zende_home_render_image_field( 'ghz[' . $key . ']', $id, $label, $src, $fallback_filename );
}

function ghar_zende_home_render_repeater_image( $name, $id_val, $label ) {
	$src = $id_val ? wp_get_attachment_image_url( (int) $id_val, 'thumbnail' ) : '';
	ghar_zende_home_render_image_field( $name, $id_val, $label, $src, '' );
}

function ghar_zende_home_render_image_field( $name, $id_val, $label, $src, $fallback_filename ) {
	echo '<div class="ghz-field ghz-image-field' . ( $id_val ? ' has-image' : '' ) . '">';
	echo '<label>' . esc_html( $label ) . '</label>';
	echo '<div class="ghz-image-preview">' . ( $src ? '<img src="' . esc_url( $src ) . '" alt="" />' : '' ) . '</div>';
	echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $id_val ) . '" class="ghz-image-input" />';
	echo '<div class="ghz-image-actions">';
	echo '<button type="button" class="button ghz-media-select">انتخاب تصویر</button> ';
	echo '<button type="button" class="button ghz-media-remove">حذف</button>';
	echo '</div>';
	if ( ! $id_val && $fallback_filename ) {
		echo '<p class="description">در صورت خالی‌بودن، تصویر پیش‌فرض تم استفاده می‌شود.</p>';
	}
	echo '</div>';
}

function ghar_zende_home_render_repeater( $key, $rows, $add_label, $row_renderer ) {
	echo '<div class="ghz-repeater" data-key="' . esc_attr( $key ) . '">';
	echo '<div class="ghz-repeater-rows">';
	foreach ( (array) $rows as $i => $row ) {
		echo '<div class="ghz-repeater-row">';
		call_user_func( $row_renderer, $row, $i );
		echo '<button type="button" class="button-link-delete ghz-repeater-remove">حذف این ردیف</button>';
		echo '</div>';
	}
	echo '</div>';
	echo '<script type="text/template" class="ghz-repeater-template">';
	echo '<div class="ghz-repeater-row">';
	call_user_func( $row_renderer, array(), '__INDEX__' );
	echo '<button type="button" class="button-link-delete ghz-repeater-remove">حذف این ردیف</button>';
	echo '</div>';
	echo '</script>';
	echo '<button type="button" class="button ghz-repeater-add">+ ' . esc_html( $add_label ) . '</button>';
	echo '</div>';
}

function ghar_zende_home_row_phase( $row, $i ) {
	$at    = isset( $row['at'] ) ? $row['at'] : '';
	$label = isset( $row['label'] ) ? $row['label'] : '';
	?>
	<div class="ghz-grid-2">
		<p><label>نقطه‌ی شروع (بین ۰ تا ۱)</label><input type="text" dir="ltr" name="ghz[aquarium_phases][<?php echo $i; ?>][at]" value="<?php echo esc_attr( $at ); ?>" /></p>
		<p><label>برچسب مرحله</label><input type="text" name="ghz[aquarium_phases][<?php echo $i; ?>][label]" value="<?php echo esc_attr( $label ); ?>" /></p>
	</div>
	<?php
}

function ghar_zende_home_row_caption( $row, $i ) {
	$title = isset( $row['title'] ) ? $row['title'] : '';
	$text  = isset( $row['text'] ) ? $row['text'] : '';
	?>
	<p><label>عنوان کوتاه</label><input type="text" name="ghz[life_captions][<?php echo $i; ?>][title]" value="<?php echo esc_attr( $title ); ?>" /></p>
	<p><label>متن</label><input type="text" name="ghz[life_captions][<?php echo $i; ?>][text]" value="<?php echo esc_attr( $text ); ?>" /></p>
	<?php
}

function ghar_zende_home_row_species( $row, $i ) {
	$name          = isset( $row['name'] ) ? $row['name'] : '';
	$sci           = isset( $row['sci'] ) ? $row['sci'] : '';
	$habitat       = isset( $row['habitat'] ) ? $row['habitat'] : '';
	$trait         = isset( $row['trait'] ) ? $row['trait'] : '';
	$description   = isset( $row['description'] ) ? $row['description'] : '';
	$photo         = isset( $row['photo'] ) ? (int) $row['photo'] : 0;
	$photo_default = isset( $row['photo_default'] ) ? $row['photo_default'] : '';
	$color         = isset( $row['color'] ) ? $row['color'] : '';
	$accent        = isset( $row['accent'] ) ? $row['accent'] : '';
	?>
	<div class="ghz-grid-2">
		<p><label>نام گونه</label><input type="text" name="ghz[species][<?php echo $i; ?>][name]" value="<?php echo esc_attr( $name ); ?>" /></p>
		<p><label>نام علمی</label><input type="text" dir="ltr" name="ghz[species][<?php echo $i; ?>][sci]" value="<?php echo esc_attr( $sci ); ?>" /></p>
		<p><label>زیستگاه</label><input type="text" name="ghz[species][<?php echo $i; ?>][habitat]" value="<?php echo esc_attr( $habitat ); ?>" /></p>
		<p><label>ویژگی</label><input type="text" name="ghz[species][<?php echo $i; ?>][trait]" value="<?php echo esc_attr( $trait ); ?>" /></p>
	</div>
	<p><label>توضیح</label><textarea rows="2" name="ghz[species][<?php echo $i; ?>][description]"><?php echo esc_textarea( $description ); ?></textarea></p>
	<?php ghar_zende_home_render_repeater_image( 'ghz[species][' . $i . '][photo]', $photo, 'تصویر گونه (اختیاری)' ); ?>
	<input type="hidden" name="ghz[species][<?php echo $i; ?>][photo_default]" value="<?php echo esc_attr( $photo_default ); ?>" />
	<p class="description">اگر تصویری انتخاب نشود، به‌جای آن یک آیکون رنگی نمایش داده می‌شود — رنگ‌هایش را این‌جا تنظیم کنید:</p>
	<div class="ghz-grid-2">
		<p><label>رنگ اصلی آیکون</label><input type="text" class="ghz-color" name="ghz[species][<?php echo $i; ?>][color]" value="<?php echo esc_attr( $color ); ?>" /></p>
		<p><label>رنگ تکمیلی آیکون</label><input type="text" class="ghz-color" name="ghz[species][<?php echo $i; ?>][accent]" value="<?php echo esc_attr( $accent ); ?>" /></p>
	</div>
	<?php
}

function ghar_zende_home_row_geology( $row, $i ) {
	$value = isset( $row['value'] ) ? $row['value'] : '';
	$unit  = isset( $row['unit'] ) ? $row['unit'] : '';
	$label = isset( $row['label'] ) ? $row['label'] : '';
	$note  = isset( $row['note'] ) ? $row['note'] : '';
	?>
	<div class="ghz-grid-3">
		<p><label>عدد</label><input type="text" name="ghz[geology_facts][<?php echo $i; ?>][value]" value="<?php echo esc_attr( $value ); ?>" /></p>
		<p><label>واحد</label><input type="text" name="ghz[geology_facts][<?php echo $i; ?>][unit]" value="<?php echo esc_attr( $unit ); ?>" /></p>
		<p><label>برچسب</label><input type="text" name="ghz[geology_facts][<?php echo $i; ?>][label]" value="<?php echo esc_attr( $label ); ?>" /></p>
	</div>
	<p><label>توضیح</label><input type="text" name="ghz[geology_facts][<?php echo $i; ?>][note]" value="<?php echo esc_attr( $note ); ?>" /></p>
	<?php
}

function ghar_zende_home_row_story( $row, $i ) {
	$title   = isset( $row['title'] ) ? $row['title'] : '';
	$persian = isset( $row['persian'] ) ? $row['persian'] : '';
	$text    = isset( $row['text'] ) ? $row['text'] : '';
	?>
	<div class="ghz-grid-2">
		<p><label>عنوان انگلیسی فصل</label><input type="text" dir="ltr" name="ghz[story_chapters][<?php echo $i; ?>][title]" value="<?php echo esc_attr( $title ); ?>" /></p>
		<p><label>معادل فارسی</label><input type="text" name="ghz[story_chapters][<?php echo $i; ?>][persian]" value="<?php echo esc_attr( $persian ); ?>" /></p>
	</div>
	<p><label>متن فصل</label><input type="text" name="ghz[story_chapters][<?php echo $i; ?>][text]" value="<?php echo esc_attr( $text ); ?>" /></p>
	<?php
}

function ghar_zende_home_row_visit( $row, $i ) {
	$text    = isset( $row['text'] ) ? $row['text'] : '';
	$href    = isset( $row['href'] ) ? $row['href'] : '';
	$primary = ! empty( $row['primary'] );
	?>
	<div class="ghz-grid-2">
		<p><label>متن دکمه</label><input type="text" name="ghz[visit_ctas][<?php echo $i; ?>][text]" value="<?php echo esc_attr( $text ); ?>" /></p>
		<p><label>لینک</label><input type="text" dir="ltr" name="ghz[visit_ctas][<?php echo $i; ?>][href]" value="<?php echo esc_attr( $href ); ?>" /></p>
	</div>
	<label class="ghz-checkbox"><input type="checkbox" name="ghz[visit_ctas][<?php echo $i; ?>][primary]" value="1" <?php checked( $primary ); ?> /> نمایش به‌صورت دکمه‌ی پررنگ (اصلی)</label>
	<?php
}

/** ------------------------------------------------------------------
 * The page itself.
 * ------------------------------------------------------------------ */
function ghar_zende_home_render_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$content = ghar_zende_home_content();
	$tabs    = array(
		'hero'     => 'ورودی غار',
		'darkness' => 'تاریکی',
		'water'    => 'آب',
		'aquarium' => 'آکواریوم',
		'life'     => 'دنیای زنده',
		'species'  => 'گونه‌ها',
		'geology'  => 'زمین‌شناسی',
		'story'    => 'سنگ، آب، زندگی',
		'visit'    => 'بازدید',
	);
	?>
	<div class="wrap ghz-admin">
		<h1>مدیریت صفحه‌ی اصلی — غار زنده</h1>
		<p class="description">هر متن، لینک و تصویری که این‌جا تغییر دهید، مستقیماً روی صفحه‌ی اصلی سایت اعمال می‌شود — نه در کد قالب.</p>

		<div class="ghz-tabs-nav">
			<?php foreach ( $tabs as $tkey => $tlabel ) : ?>
				<button type="button" class="ghz-tab-btn<?php echo 'hero' === $tkey ? ' is-active' : ''; ?>" data-tab="<?php echo esc_attr( $tkey ); ?>"><?php echo esc_html( $tlabel ); ?></button>
			<?php endforeach; ?>
		</div>

		<form method="post" class="ghz-form">
			<?php wp_nonce_field( 'ghar_zende_home_save', 'ghz_nonce' ); ?>

			<div id="ghz-tab-hero" class="ghz-tab-panel is-active">
				<h2>ورودی غار (Hero)</h2>
				<?php
				ghar_zende_home_render_text( 'hero_eyebrow', 'زیرعنوان انگلیسی بالای عنوان', $content );
				ghar_zende_home_render_text( 'hero_title_line1', 'عنوان اصلی — خط اول', $content );
				ghar_zende_home_render_text( 'hero_title_line2', 'عنوان اصلی — خط دوم', $content );
				ghar_zende_home_render_text( 'hero_lede', 'توضیح کوتاه زیر عنوان', $content );
				ghar_zende_home_render_text( 'hero_cta_text', 'متن دکمه', $content );
				ghar_zende_home_render_text( 'hero_cta_href', 'لینک دکمه', $content, 'url' );
				ghar_zende_home_render_image( 'hero_image', 'تصویر پس‌زمینه', $content, 'hero-entrance.webp' );
				?>
			</div>

			<div id="ghz-tab-darkness" class="ghz-tab-panel">
				<h2>تاریکی</h2>
				<?php
				ghar_zende_home_render_text( 'darkness_text', 'متن صحنه', $content );
				ghar_zende_home_render_image( 'darkness_image', 'تصویر پس‌زمینه', $content, 'darkness-threshold.webp' );
				?>
			</div>

			<div id="ghz-tab-water" class="ghz-tab-panel">
				<h2>آب</h2>
				<?php
				ghar_zende_home_render_text( 'water_text1', 'متن — خط اول', $content );
				ghar_zende_home_render_text( 'water_text2', 'متن — خط دوم', $content );
				ghar_zende_home_render_image( 'water_image', 'تصویر پس‌زمینه', $content, 'water-corridor.webp' );
				?>
			</div>

			<div id="ghz-tab-aquarium" class="ghz-tab-panel">
				<h2>آکواریوم</h2>
				<?php
				ghar_zende_home_render_text( 'aquarium_caption', 'کپشن پایانی زیر آکواریوم', $content );
				ghar_zende_home_render_image( 'aquarium_frame_image', 'تصویر قاب سنگی اطراف آکواریوم', $content, 'corridor-panorama-dark.webp' );
				ghar_zende_home_render_image( 'aquarium_clear_image', 'تصویر داخل آکواریوم (روشن)', $content, 'aquarium-window-clear.webp' );
				echo '<h3>مراحل نمایش (برچسبی که هنگام اسکرول بالای آکواریوم عوض می‌شود)</h3>';
				ghar_zende_home_render_repeater( 'aquarium_phases', $content['aquarium_phases'], 'افزودن مرحله', 'ghar_zende_home_row_phase' );
				?>
			</div>

			<div id="ghz-tab-life" class="ghz-tab-panel">
				<h2>دنیای زنده</h2>
				<?php
				ghar_zende_home_render_image( 'life_image', 'تصویر پس‌زمینه', $content, 'corridor-panorama-bright.webp' );
				echo '<h3>کپشن‌های متناوب</h3>';
				ghar_zende_home_render_repeater( 'life_captions', $content['life_captions'], 'افزودن کپشن', 'ghar_zende_home_row_caption' );
				?>
			</div>

			<div id="ghz-tab-species" class="ghz-tab-panel">
				<h2>گونه‌ها</h2>
				<?php
				ghar_zende_home_render_text( 'species_eyebrow', 'زیرعنوان انگلیسی', $content );
				ghar_zende_home_render_text( 'species_title', 'عنوان بخش', $content );
				ghar_zende_home_render_repeater( 'species', $content['species'], 'افزودن گونه‌ی جدید', 'ghar_zende_home_row_species' );
				?>
			</div>

			<div id="ghz-tab-geology" class="ghz-tab-panel">
				<h2>زمین‌شناسی</h2>
				<?php
				ghar_zende_home_render_text( 'geology_eyebrow', 'زیرعنوان انگلیسی', $content );
				ghar_zende_home_render_text( 'geology_title', 'عنوان بخش', $content );
				ghar_zende_home_render_image( 'geology_image', 'تصویر پس‌زمینه', $content, 'corridor-stalagmites.webp' );
				ghar_zende_home_render_repeater( 'geology_facts', $content['geology_facts'], 'افزودن آمار جدید', 'ghar_zende_home_row_geology' );
				?>
			</div>

			<div id="ghz-tab-story" class="ghz-tab-panel">
				<h2>سنگ، آب، زندگی</h2>
				<?php
				ghar_zende_home_render_image( 'story_image', 'تصویر داخل پنجره', $content, 'aquarium-window-light.webp' );
				ghar_zende_home_render_repeater( 'story_chapters', $content['story_chapters'], 'افزودن فصل', 'ghar_zende_home_row_story' );
				?>
			</div>

			<div id="ghz-tab-visit" class="ghz-tab-panel">
				<h2>بازدید</h2>
				<?php
				ghar_zende_home_render_text( 'visit_heading', 'عنوان دعوت به بازدید', $content );
				ghar_zende_home_render_image( 'visit_image', 'تصویر پس‌زمینه', $content, 'corridor-warm-glow.webp' );
				ghar_zende_home_render_repeater( 'visit_ctas', $content['visit_ctas'], 'افزودن دکمه', 'ghar_zende_home_row_visit' );
				?>
			</div>

			<p class="submit">
				<button type="submit" name="ghz_save" value="1" class="button button-primary button-hero">ذخیره‌ی تغییرات</button>
			</p>
		</form>
	</div>
	<?php
}
