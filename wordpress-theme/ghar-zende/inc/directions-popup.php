<?php
/**
 * Directions popup («مسیریابی»).
 *
 * Same trigger pattern as inc/visit-popup.php / inc/contact-popup.php: any
 * link/button whose text is «مسیریابی» (currently the visit-section CTA —
 * see the `visit_ctas` default in inc/homepage-panel.php), any element
 * with the class `js-directions`, or any link pointing to `#directions`
 * opens this modal instead of navigating, offering a choice of navigation
 * app. To change the links, edit the constants right below.
 *
 * The نشان/Google Maps marks are recreated inline (SVG) in each brand's
 * own colors rather than hot-linked bitmaps — this repo has no network
 * access to fetch the original assets, and inline SVG also means no
 * broken-image risk and no extra HTTP request on the live site.
 *
 * @package ghar-zende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'GHAR_ZENDE_NESHAN_URL' ) ) {
	define( 'GHAR_ZENDE_NESHAN_URL', 'https://nshn.ir/95rbsNdhG53qw-' );
}
if ( ! defined( 'GHAR_ZENDE_GMAPS_URL' ) ) {
	define( 'GHAR_ZENDE_GMAPS_URL', 'https://maps.app.goo.gl/hwbWp4ogycRcA66M9' );
}

if ( ! function_exists( 'ghar_zende_directions_popup' ) ) {
	function ghar_zende_directions_popup() {
		if ( is_admin() ) {
			return;
		}
		?>
<style id="gzdp-css">
.gzdp{position:fixed;inset:0;z-index:100000;display:flex;align-items:center;justify-content:center;padding:20px;visibility:hidden;opacity:0;transition:opacity .35s ease,visibility 0s linear .35s;direction:rtl;font-family:inherit}
.gzdp.is-open{visibility:visible;opacity:1;transition:opacity .35s ease}
.gzdp *{box-sizing:border-box}
.gzdp__backdrop{position:absolute;inset:0;background:rgba(3,8,14,.74);-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px)}
.gzdp__card{position:relative;width:100%;max-width:440px;max-height:calc(100vh - 40px);overflow:auto;border-radius:28px;padding:34px 28px 28px;color:#e8f4f6;text-align:center;background:radial-gradient(120% 80% at 50% -10%,rgba(45,212,191,.2),transparent 60%),linear-gradient(180deg,#0d1b24 0%,#070f15 100%);border:1px solid rgba(125,211,252,.16);box-shadow:0 30px 80px -20px rgba(0,0,0,.85),inset 0 0 0 1px rgba(255,255,255,.03),0 0 60px -10px rgba(45,212,191,.28);transform:translateY(24px) scale(.96);transition:transform .45s cubic-bezier(.2,.9,.25,1.12);outline:none}
.gzdp.is-open .gzdp__card{transform:none}
.gzdp__close{position:absolute;top:14px;left:14px;width:38px;height:38px;padding:0;margin:0;border-radius:50%;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.04);color:#cfe3e7;display:grid;place-items:center;cursor:pointer;transition:background .2s,transform .25s}
.gzdp__close:hover{background:rgba(255,255,255,.1);transform:rotate(90deg)}
.gzdp__close:focus-visible,.gzdp__app:focus-visible{outline:2px solid #5eead4;outline-offset:3px}
.gzdp__icon{width:64px;height:64px;margin:0 auto 16px;border-radius:50%;display:grid;place-items:center;color:#5eead4;background:rgba(45,212,191,.1);box-shadow:0 0 0 8px rgba(45,212,191,.05),0 0 30px rgba(45,212,191,.25)}
.gzdp__eyebrow{display:block;font-size:12.5px;color:#7dd3c8;margin:0 0 6px}
.gzdp__title{margin:0 0 10px;font-size:22px;font-weight:800;line-height:1.6;color:#fff}
.gzdp__lead{margin:0 auto 24px;max-width:350px;font-size:14.5px;line-height:2;color:#a9c1c7}
.gzdp__apps{display:flex;flex-direction:column;gap:10px;margin:0 0 6px;text-align:right}
.gzdp__app{display:flex;align-items:center;gap:14px;padding:13px 16px;border-radius:18px;background:rgba(255,255,255,.035);border:1px solid rgba(255,255,255,.07);color:inherit;text-decoration:none;transition:background .25s,border-color .25s,transform .25s}
.gzdp__app:hover,.gzdp__app:focus-visible{background:rgba(45,212,191,.09);border-color:rgba(45,212,191,.35);transform:translateY(-2px)}
.gzdp__app-icon{flex:0 0 44px;width:44px;height:44px;border-radius:13px;display:grid;place-items:center;background:#fff;box-shadow:0 4px 14px -6px rgba(0,0,0,.5)}
.gzdp__app-body{flex:1;min-width:0}
.gzdp__app-name{display:block;font-size:15.5px;font-weight:700;color:#fff}
.gzdp__app-hint{display:block;font-size:12px;color:#8aa5ab;margin-top:2px}
.gzdp__app-go{flex:0 0 auto;color:#5eead4;transition:transform .3s}
.gzdp__app:hover .gzdp__app-go,.gzdp__app:focus-visible .gzdp__app-go{transform:translateX(-4px)}
.gzdp__note{margin:16px 0 0;font-size:12px;color:#7f9aa0}
html.gzdp-lock,html.gzdp-lock body{overflow:hidden}
@media (prefers-reduced-motion:reduce){.gzdp,.gzdp__card,.gzdp__close,.gzdp__app,.gzdp__app-go{transition:none}}
@media (max-width:420px){.gzdp__card{padding:30px 20px 24px;border-radius:24px}.gzdp__title{font-size:19px}}
</style>

<div class="gzdp" id="gz-directions-popup" aria-hidden="true">
	<div class="gzdp__backdrop" data-gzdp-close></div>
	<div class="gzdp__card" role="dialog" aria-modal="true" aria-labelledby="gzdp-title" aria-describedby="gzdp-lead" tabindex="-1">
		<button type="button" class="gzdp__close" data-gzdp-close aria-label="بستن">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
		</button>

		<div class="gzdp__icon" aria-hidden="true">
			<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-8-8 18-2-8-8-2Z"/></svg>
		</div>

		<span class="gzdp__eyebrow">مسیریابی</span>
		<h2 class="gzdp__title" id="gzdp-title">غار آکواریوم گنجنامه را پیدا کنید</h2>
		<p class="gzdp__lead" id="gzdp-lead">با اپلیکیشن مسیریاب موردعلاقه‌ی خودتان، مسیر رسیدن به غار زنده را باز کنید.</p>

		<div class="gzdp__apps">
			<a class="gzdp__app" href="<?php echo esc_url( GHAR_ZENDE_NESHAN_URL ); ?>" target="_blank" rel="noopener noreferrer" data-cursor="مسیریابی">
				<span class="gzdp__app-icon" aria-hidden="true">
					<svg width="26" height="26" viewBox="0 0 36 36">
						<defs>
							<linearGradient id="gzdpNeshanGrad" x1="4" y1="4" x2="32" y2="32" gradientUnits="userSpaceOnUse">
								<stop offset="0" stop-color="#FF7A18"/>
								<stop offset="1" stop-color="#F0342D"/>
							</linearGradient>
						</defs>
						<rect x="2" y="2" width="32" height="32" rx="9" fill="url(#gzdpNeshanGrad)"/>
						<path d="M18 8c-4.4 0-8 3.4-8 8 0 5.6 8 12 8 12s8-6.4 8-12c0-4.6-3.6-8-8-8Z" fill="#fff"/>
						<circle cx="18" cy="16" r="3.4" fill="#F0342D"/>
					</svg>
				</span>
				<span class="gzdp__app-body">
					<span class="gzdp__app-name">نشان</span>
					<span class="gzdp__app-hint">باز کردن مسیر در Neshan</span>
				</span>
				<span class="gzdp__app-go" aria-hidden="true">←</span>
			</a>

			<a class="gzdp__app" href="<?php echo esc_url( GHAR_ZENDE_GMAPS_URL ); ?>" target="_blank" rel="noopener noreferrer" data-cursor="مسیریابی">
				<span class="gzdp__app-icon" aria-hidden="true">
					<svg width="26" height="26" viewBox="0 0 36 36">
						<defs>
							<clipPath id="gzdpGmPinClip">
								<path d="M18 2C10.8 2 5 7.8 5 15c0 10.2 13 19.6 13 19.6S31 25.2 31 15C31 7.8 25.2 2 18 2z"/>
							</clipPath>
							<mask id="gzdpGmHoleMask" maskUnits="userSpaceOnUse" x="0" y="0" width="36" height="36">
								<rect x="0" y="0" width="36" height="36" fill="#fff"/>
								<circle cx="18" cy="14.5" r="5.2" fill="#000"/>
							</mask>
						</defs>
						<g clip-path="url(#gzdpGmPinClip)" mask="url(#gzdpGmHoleMask)">
							<rect x="0" y="0" width="36" height="36" fill="#4285F4"/>
							<polygon points="6,19 12,19 18,34.6" fill="#EA4335"/>
							<polygon points="12,19 18,19 18,34.6" fill="#FBBC04"/>
							<polygon points="18,19 24,19 18,34.6" fill="#34A853"/>
						</g>
					</svg>
				</span>
				<span class="gzdp__app-body">
					<span class="gzdp__app-name">Google Maps</span>
					<span class="gzdp__app-hint">باز کردن مسیر در گوگل‌مپ</span>
				</span>
				<span class="gzdp__app-go" aria-hidden="true">←</span>
			</a>
		</div>

		<p class="gzdp__note">با انتخاب هرکدام، اپلیکیشن مربوطه در تب جدید باز می‌شود.</p>
	</div>
</div>

<script id="gzdp-js">
(function () {
	var root = document.getElementById('gz-directions-popup');
	if (!root) { return; }
	var lastFocus = null;

	function isTrigger(el) {
		if (!el || root.contains(el)) { return false; }
		if (el.classList && el.classList.contains('js-directions')) { return true; }
		var href = el.getAttribute('href') || '';
		if (href.indexOf('#directions') !== -1) { return true; }
		var txt = (el.textContent || '').replace(/[\s‌‎‏]+/g, '');
		return txt === 'مسیریابی';
	}

	function openPopup() {
		lastFocus = document.activeElement;
		root.classList.add('is-open');
		root.setAttribute('aria-hidden', 'false');
		document.documentElement.classList.add('gzdp-lock');
		setTimeout(function () {
			var c = root.querySelector('.gzdp__close');
			if (c) { c.focus(); }
		}, 60);
	}

	function closePopup() {
		root.classList.remove('is-open');
		root.setAttribute('aria-hidden', 'true');
		document.documentElement.classList.remove('gzdp-lock');
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
		if (root.contains(e.target) && e.target.closest('[data-gzdp-close]')) {
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

	if (window.location.hash === '#directions') { openPopup(); }
})();
</script>
		<?php
	}
}
add_action( 'wp_footer', 'ghar_zende_directions_popup', 52 );
