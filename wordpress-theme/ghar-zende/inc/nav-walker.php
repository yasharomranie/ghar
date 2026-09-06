<?php
/**
 * Custom nav walker — outputs bare <a> tags with Nav.tsx's own classes,
 * no <ul>/<li> wrapper, so a WordPress-admin-built menu drops straight in
 * as flex siblings of the logo/theme-toggle in header.php's <nav>
 * (desktop) or the mobile panel — exactly like the original component's
 * flat `navScenes.map(...)` output. wp_nav_menu() is still used for
 * everything else (theme_location, admin UI, fallback), only the item
 * markup itself is replaced.
 *
 * A menu item gets the "برنامه بازدید" CTA pill treatment when the admin
 * adds a `nav-cta` CSS class to it under Appearance → Menus → screen
 * options → CSS classes (one item only makes sense, but every item with
 * the class gets the pill style).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ghar_Zende_Nav_Walker extends Walker_Nav_Menu {

	/** @var string 'desktop' or 'mobile' — picks which class set to print. */
	private $context;

	public function __construct( $context = 'desktop' ) {
		$this->context = $context;
	}

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		// No dropdowns in this design — do nothing (top-level items only).
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( $depth > 0 ) {
			return; // flat menu only, matches the original nav's structure
		}

		$is_cta = in_array( 'nav-cta', $item->classes, true );

		if ( 'mobile' === $this->context ) {
			$class = 'py-3 text-base text-[var(--nav-text-dim)]';
		} elseif ( $is_cta ) {
			$class = 'rounded-full border border-[var(--nav-border)]/25 px-4 py-2 text-sm text-[var(--nav-text)] transition-colors hover:bg-[var(--nav-text)]/5';
		} else {
			$class = 'text-sm text-[var(--nav-text-dim)] transition-colors hover:text-[var(--nav-text)]';
		}

		$output .= sprintf(
			'<a href="%1$s" class="%2$s">%3$s</a>',
			esc_url( $item->url ),
			esc_attr( $class ),
			esc_html( $item->title )
		);
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		// no </li> — start_el() already printed a complete <a>...</a>
	}
}
