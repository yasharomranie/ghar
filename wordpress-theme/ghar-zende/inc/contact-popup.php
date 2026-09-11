<?php
/**
 * Contact-us popup («تماس با ما»).
 *
 * Same trigger pattern as inc/visit-popup.php: any link/button whose text
 * is «تماس با ما» (currently the visit-section CTA — see the
 * `visit_ctas` default in inc/homepage-panel.php), any element with the
 * class `js-contact-us`, or any link pointing to `#contact-us` opens this
 * modal instead of navigating. To change the phone numbers or address,
 * edit the constants right below.
 *
 * @package ghar-zende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'GHAR_ZENDE_CONTACT_PHONE' ) ) {
	define( 'GHAR_ZENDE_CONTACT_PHONE', '08138303011' );
}
if ( ! defined( 'GHAR_ZENDE_CONTACT_MOBILE' ) ) {
	define( 'GHAR_ZENDE_CONTACT_MOBILE', '09182223313' );
}
if ( ! defined( 'GHAR_ZENDE_CONTACT_ADDRESS' ) ) {
	define( 'GHAR_ZENDE_CONTACT_ADDRESS', 'همدان، ضلع شرقی میدان گنجنامه، دهکده تفریحی توریستی گنجنامه' );
}

if ( ! function_exists( 'ghar_zende_contact_popup' ) ) {
	function ghar_zende_contact_popup() {
		if ( is_admin() ) {
			return;
		}

		$phone_tel  = preg_replace( '/[^0-9+]/', '', GHAR_ZENDE_CONTACT_PHONE );
		$mobile_tel = preg_replace( '/[^0-9+]/', '', GHAR_ZENDE_CONTACT_MOBILE );
		$map_href   = 'https://maps.google.com/?q=' . rawurlencode( GHAR_ZENDE_CONTACT_ADDRESS );
		?>
<style id="gzcp-css">
.gzcp{position:fixed;inset:0;z-index:100000;display:flex;align-items:center;justify-content:center;padding:20px;visibility:hidden;opacity:0;transition:opacity .35s ease,visibility 0s linear .35s;direction:rtl;font-family:inherit}
.gzcp.is-open{visibility:visible;opacity:1;transition:opacity .35s ease}
.gzcp *{box-sizing:border-box}
.gzcp__backdrop{position:absolute;inset:0;background:rgba(3,8,14,.74);-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px)}
.gzcp__card{position:relative;width:100%;max-width:440px;max-height:calc(100vh - 40px);overflow:auto;border-radius:28px;padding:34px 28px 28px;color:#e8f4f6;text-align:center;background:radial-gradient(120% 80% at 50% -10%,rgba(45,212,191,.2),transparent 60%),linear-gradient(180deg,#0d1b24 0%,#070f15 100%);border:1px solid rgba(125,211,252,.16);box-shadow:0 30px 80px -20px rgba(0,0,0,.85),inset 0 0 0 1px rgba(255,255,255,.03),0 0 60px -10px rgba(45,212,191,.28);transform:translateY(24px) scale(.96);transition:transform .45s cubic-bezier(.2,.9,.25,1.12);outline:none}
.gzcp.is-open .gzcp__card{transform:none}
.gzcp__close{position:absolute;top:14px;left:14px;width:38px;height:38px;padding:0;margin:0;border-radius:50%;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.04);color:#cfe3e7;display:grid;place-items:center;cursor:pointer;transition:background .2s,transform .25s}
.gzcp__close:hover{background:rgba(255,255,255,.1);transform:rotate(90deg)}
.gzcp__close:focus-visible,.gzcp__row:focus-visible{outline:2px solid #5eead4;outline-offset:3px}
.gzcp__icon{width:64px;height:64px;margin:0 auto 16px;border-radius:50%;display:grid;place-items:center;color:#5eead4;background:rgba(45,212,191,.1);box-shadow:0 0 0 8px rgba(45,212,191,.05),0 0 30px rgba(45,212,191,.25)}
.gzcp__eyebrow{display:block;font-size:12.5px;color:#7dd3c8;margin:0 0 6px}
.gzcp__title{margin:0 0 10px;font-size:24px;font-weight:800;line-height:1.5;color:#fff}
.gzcp__lead{margin:0 auto 24px;max-width:350px;font-size:14.5px;line-height:2;color:#a9c1c7}
.gzcp__list{display:flex;flex-direction:column;gap:10px;margin:0 0 6px;text-align:right}
.gzcp__row{display:flex;align-items:center;gap:14px;padding:14px 16px;border-radius:18px;background:rgba(255,255,255,.035);border:1px solid rgba(255,255,255,.07);color:inherit;text-decoration:none;transition:background .25s,border-color .25s,transform .25s}
a.gzcp__row:hover,a.gzcp__row:focus-visible{background:rgba(45,212,191,.09);border-color:rgba(45,212,191,.35);transform:translateY(-2px)}
.gzcp__row-icon{flex:0 0 42px;width:42px;height:42px;border-radius:12px;display:grid;place-items:center;color:#062a26;background:linear-gradient(135deg,#5eead4,#2dd4bf);box-shadow:0 4px 14px -6px rgba(45,212,191,.7)}
.gzcp__row-body{flex:1;min-width:0}
.gzcp__row-label{display:block;font-size:12px;color:#8aa5ab;margin-bottom:3px}
.gzcp__row-value{display:block;font-size:16px;font-weight:700;color:#fff;direction:ltr;text-align:right;unicode-bidi:plain-text}
.gzcp__row-value.is-address{font-size:13.5px;font-weight:500;line-height:1.9;direction:rtl;color:#dbeeef}
.gzcp__row-go{flex:0 0 auto;color:#5eead4;transition:transform .3s}
a.gzcp__row:hover .gzcp__row-go,a.gzcp__row:focus-visible .gzcp__row-go{transform:translateX(-4px)}
.gzcp__note{margin:16px 0 0;font-size:12px;color:#7f9aa0}
html.gzcp-lock,html.gzcp-lock body{overflow:hidden}
@media (prefers-reduced-motion:reduce){.gzcp,.gzcp__card,.gzcp__close,.gzcp__row,.gzcp__row-go{transition:none}}
@media (max-width:420px){.gzcp__card{padding:30px 20px 24px;border-radius:24px}.gzcp__title{font-size:21px}.gzcp__row-value{font-size:15px}}
</style>

<div class="gzcp" id="gz-contact-popup" aria-hidden="true">
	<div class="gzcp__backdrop" data-gzcp-close></div>
	<div class="gzcp__card" role="dialog" aria-modal="true" aria-labelledby="gzcp-title" aria-describedby="gzcp-lead" tabindex="-1">
		<button type="button" class="gzcp__close" data-gzcp-close aria-label="بستن">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
		</button>

		<div class="gzcp__icon" aria-hidden="true">
			<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
		</div>

		<span class="gzcp__eyebrow">تماس با ما</span>
		<h2 class="gzcp__title" id="gzcp-title">در تماس باشید</h2>
		<p class="gzcp__lead" id="gzcp-lead">برای هماهنگی بازدید، تور گروهی یا هر پرسشی، از راه‌های زیر با غار زنده در ارتباط باشید.</p>

		<div class="gzcp__list">
			<a class="gzcp__row" href="tel:<?php echo esc_attr( $phone_tel ); ?>" data-cursor="تماس">
				<span class="gzcp__row-icon" aria-hidden="true">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
				</span>
				<span class="gzcp__row-body">
					<span class="gzcp__row-label">تلفن تماس</span>
					<span class="gzcp__row-value"><?php echo esc_html( GHAR_ZENDE_CONTACT_PHONE ); ?></span>
				</span>
				<span class="gzcp__row-go" aria-hidden="true">←</span>
			</a>

			<a class="gzcp__row" href="tel:<?php echo esc_attr( $mobile_tel ); ?>" data-cursor="تماس">
				<span class="gzcp__row-icon" aria-hidden="true">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="2" width="12" height="20" rx="2.5"/><path d="M11 18h2"/></svg>
				</span>
				<span class="gzcp__row-body">
					<span class="gzcp__row-label">همراه</span>
					<span class="gzcp__row-value"><?php echo esc_html( GHAR_ZENDE_CONTACT_MOBILE ); ?></span>
				</span>
				<span class="gzcp__row-go" aria-hidden="true">←</span>
			</a>

			<a class="gzcp__row" href="<?php echo esc_url( $map_href ); ?>" target="_blank" rel="noopener noreferrer" data-cursor="مسیریابی">
				<span class="gzcp__row-icon" aria-hidden="true">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
				</span>
				<span class="gzcp__row-body">
					<span class="gzcp__row-label">آدرس</span>
					<span class="gzcp__row-value is-address"><?php echo esc_html( GHAR_ZENDE_CONTACT_ADDRESS ); ?></span>
				</span>
				<span class="gzcp__row-go" aria-hidden="true">←</span>
			</a>
		</div>

		<p class="gzcp__note">برای تماس یا مسیریابی، روی هرکدام از موارد بالا بزنید.</p>
	</div>
</div>

<script id="gzcp-js">
(function () {
	var root = document.getElementById('gz-contact-popup');
	if (!root) { return; }
	var lastFocus = null;

	function isTrigger(el) {
		if (!el || root.contains(el)) { return false; }
		if (el.classList && el.classList.contains('js-contact-us')) { return true; }
		var href = el.getAttribute('href') || '';
		if (href.indexOf('#contact-us') !== -1) { return true; }
		var txt = (el.textContent || '').replace(/[\s‌‎‏]+/g, '');
		return txt === 'تماسباما';
	}

	function openPopup() {
		lastFocus = document.activeElement;
		root.classList.add('is-open');
		root.setAttribute('aria-hidden', 'false');
		document.documentElement.classList.add('gzcp-lock');
		setTimeout(function () {
			var c = root.querySelector('.gzcp__close');
			if (c) { c.focus(); }
		}, 60);
	}

	function closePopup() {
		root.classList.remove('is-open');
		root.setAttribute('aria-hidden', 'true');
		document.documentElement.classList.remove('gzcp-lock');
		if (lastFocus && lastFocus.focus) { lastFocus.focus(); }
	}

	document.addEventListener('click', function (e) {
		if (!e.target || !e.target.closest) { return; }
		var el = e.target.closest('a, button, [role="button"]');
		if (el && isTrigger(el)) {
			e.preventDefault();
			e.stopPropagation();
			openPopup();
			return;
		}
		if (root.contains(e.target) && e.target.closest('[data-gzcp-close]')) {
			e.preventDefault();
			closePopup();
		}
	}, true);

	document.addEventListener('keydown', function (e) {
		if (!root.classList.contains('is-open')) { return; }
		if (e.key === 'Escape') { closePopup(); return; }
		if (e.key === 'Tab') {
			var f = root.querySelectorAll('a[href], button');
			if (!f.length) { return; }
			var first = f[0], last = f[f.length - 1];
			if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
			else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
		}
	});

	if (window.location.hash === '#contact-us') { openPopup(); }
})();
</script>
		<?php
	}
}
add_action( 'wp_footer', 'ghar_zende_contact_popup', 51 );
