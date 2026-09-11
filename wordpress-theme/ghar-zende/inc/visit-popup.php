<?php
/**
 * Visit-hours popup («برنامه بازدید»).
 *
 * Any link/button whose text is «برنامه بازدید» (header CTA, visit-section
 * CTA, article sidebar), any element with the class `js-visit-hours`, or any
 * link pointing to `#visit-hours` opens this modal instead of navigating.
 * To change the hours, edit the markup below and GZVP_OPEN / GZVP_CLOSE in JS.
 *
 * @package ghar-zende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'ghar_zende_visit_hours_popup' ) ) {
	function ghar_zende_visit_hours_popup() {
		if ( is_admin() ) {
			return;
		}
		?>
<style id="gzvp-css">
.gzvp{position:fixed;inset:0;z-index:100000;display:flex;align-items:center;justify-content:center;padding:20px;visibility:hidden;opacity:0;transition:opacity .35s ease,visibility 0s linear .35s;direction:rtl;font-family:inherit}
.gzvp.is-open{visibility:visible;opacity:1;transition:opacity .35s ease}
.gzvp *{box-sizing:border-box}
.gzvp__backdrop{position:absolute;inset:0;background:rgba(3,8,14,.74);-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px)}
.gzvp__card{position:relative;width:100%;max-width:440px;max-height:calc(100vh - 40px);overflow:auto;border-radius:28px;padding:34px 28px 26px;color:#e8f4f6;text-align:center;background:radial-gradient(120% 80% at 50% -10%,rgba(45,212,191,.2),transparent 60%),linear-gradient(180deg,#0d1b24 0%,#070f15 100%);border:1px solid rgba(125,211,252,.16);box-shadow:0 30px 80px -20px rgba(0,0,0,.85),inset 0 0 0 1px rgba(255,255,255,.03),0 0 60px -10px rgba(45,212,191,.28);transform:translateY(24px) scale(.96);transition:transform .45s cubic-bezier(.2,.9,.25,1.12);outline:none}
.gzvp.is-open .gzvp__card{transform:none}
.gzvp__close{position:absolute;top:14px;left:14px;width:38px;height:38px;padding:0;margin:0;border-radius:50%;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.04);color:#cfe3e7;display:grid;place-items:center;cursor:pointer;transition:background .2s,transform .25s}
.gzvp__close:hover{background:rgba(255,255,255,.1);transform:rotate(90deg)}
.gzvp__close:focus-visible,.gzvp__btn:focus-visible{outline:2px solid #5eead4;outline-offset:3px}
.gzvp__icon{width:64px;height:64px;margin:0 auto 16px;border-radius:50%;display:grid;place-items:center;color:#5eead4;background:rgba(45,212,191,.1);box-shadow:0 0 0 8px rgba(45,212,191,.05),0 0 30px rgba(45,212,191,.25)}
.gzvp__eyebrow{display:block;font-size:12.5px;color:#7dd3c8;margin:0 0 6px}
.gzvp__title{margin:0 0 10px;font-size:24px;font-weight:800;line-height:1.5;color:#fff}
.gzvp__lead{margin:0 auto 22px;max-width:350px;font-size:14.5px;line-height:2;color:#a9c1c7}
.gzvp__hours{display:flex;align-items:center;justify-content:center;gap:14px;padding:18px 16px;border-radius:20px;background:rgba(255,255,255,.035);border:1px solid rgba(255,255,255,.07);margin:0 0 18px}
.gzvp__time{flex:1}
.gzvp__time small{display:block;font-size:12px;color:#8aa5ab;margin-bottom:4px}
.gzvp__time strong{display:block;font-size:28px;font-weight:800;color:#fff;line-height:1.3}
.gzvp__time span{display:block;font-size:13px;color:#5eead4;margin-top:2px}
.gzvp__sep{flex:0 0 34px;height:2px;border-radius:2px;background:linear-gradient(90deg,transparent,#5eead4,transparent)}
.gzvp__days{display:flex;justify-content:center;gap:6px;flex-wrap:wrap;margin:0 0 8px;padding:0;list-style:none}
.gzvp__days li{width:36px;height:36px;border-radius:50%;display:grid;place-items:center;font-size:13px;font-weight:700;color:#062a26;background:linear-gradient(135deg,#5eead4,#2dd4bf);box-shadow:0 4px 14px -6px rgba(45,212,191,.7)}
.gzvp__days-note{display:block;font-size:12.5px;color:#8aa5ab;margin:0 0 18px}
.gzvp__status{display:flex;align-items:center;justify-content:center;gap:8px;font-size:13.5px;line-height:1.8;color:#cfe3e7;margin:0 0 22px;min-height:24px}
.gzvp__dot{width:9px;height:9px;border-radius:50%;background:#94a3b8;flex:0 0 9px}
.gzvp__status.is-now-open .gzvp__dot{background:#34d399;animation:gzvpPulse 1.8s infinite}
.gzvp__status.is-now-closed .gzvp__dot{background:#f59e0b}
.gzvp__btn{display:block;width:100%;margin:0;padding:14px 18px;border:0;border-radius:999px;font:inherit;font-size:15px;font-weight:700;color:#04201d;background:linear-gradient(135deg,#5eead4,#22d3ee);cursor:pointer;box-shadow:0 10px 30px -10px rgba(34,211,238,.6);transition:filter .2s}
.gzvp__btn:hover{filter:brightness(1.07)}
html.gzvp-lock,html.gzvp-lock body{overflow:hidden}
@keyframes gzvpPulse{0%{box-shadow:0 0 0 0 rgba(52,211,153,.55)}70%{box-shadow:0 0 0 9px rgba(52,211,153,0)}100%{box-shadow:0 0 0 0 rgba(52,211,153,0)}}
@media (prefers-reduced-motion:reduce){.gzvp,.gzvp__card,.gzvp__close{transition:none}.gzvp__status.is-now-open .gzvp__dot{animation:none}}
@media (max-width:420px){.gzvp__card{padding:30px 20px 22px;border-radius:24px}.gzvp__title{font-size:21px}.gzvp__time strong{font-size:24px}.gzvp__days li{width:32px;height:32px;font-size:12px}}
</style>

<div class="gzvp" id="gz-visit-popup" aria-hidden="true">
	<div class="gzvp__backdrop" data-gzvp-close></div>
	<div class="gzvp__card" role="dialog" aria-modal="true" aria-labelledby="gzvp-title" aria-describedby="gzvp-lead" tabindex="-1">
		<button type="button" class="gzvp__close" data-gzvp-close aria-label="بستن">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
		</button>

		<div class="gzvp__icon" aria-hidden="true">
			<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.2 2"/></svg>
		</div>

		<span class="gzvp__eyebrow">برنامه بازدید · فصل تابستان</span>
		<h2 class="gzvp__title" id="gzvp-title">ساعات بازدید غار آکواریوم</h2>
		<p class="gzvp__lead" id="gzvp-lead">در فصل تابستان، درهای غار آکواریوم همه‌روزه و بی‌وقفه به روی شما گشوده است؛ فرصتی برای سفر به دل تاریکی و دیدار با ساکنان آبی غار.</p>

		<div class="gzvp__hours">
			<div class="gzvp__time"><small>از ساعت</small><strong>۱۰:۰۰</strong><span>صبح</span></div>
			<i class="gzvp__sep" aria-hidden="true"></i>
			<div class="gzvp__time"><small>تا ساعت</small><strong>۱۰:۰۰</strong><span>شب</span></div>
		</div>

		<ul class="gzvp__days" aria-label="همه روزهای هفته">
			<li title="شنبه">ش</li><li title="یکشنبه">ی</li><li title="دوشنبه">د</li><li title="سه‌شنبه">س</li><li title="چهارشنبه">چ</li><li title="پنجشنبه">پ</li><li title="جمعه">ج</li>
		</ul>
		<span class="gzvp__days-note">همه روزهای هفته، از شنبه تا جمعه</span>

		<p class="gzvp__status" data-gzvp-status aria-live="polite"><i class="gzvp__dot" aria-hidden="true"></i><span></span></p>

		<button type="button" class="gzvp__btn" data-gzvp-close>متوجه شدم</button>
	</div>
</div>

<script id="gzvp-js">
(function () {
	var root = document.getElementById('gz-visit-popup');
	if (!root) { return; }
	var GZVP_OPEN = 10, GZVP_CLOSE = 22, lastFocus = null;

	function isTrigger(el) {
		if (!el || root.contains(el)) { return false; }
		if (el.classList && el.classList.contains('js-visit-hours')) { return true; }
		var href = el.getAttribute('href') || '';
		if (href.indexOf('#visit-hours') !== -1) { return true; }
		var txt = (el.textContent || '').replace(/[\s‌‎‏]+/g, '');
		return txt === 'برنامهبازدید';
	}

	function updateStatus() {
		var box = root.querySelector('[data-gzvp-status]');
		if (!box) { return; }
		try {
			var parts = new Intl.DateTimeFormat('en-US', { timeZone: 'Asia/Tehran', hour: 'numeric', minute: 'numeric', hourCycle: 'h23' }).formatToParts(new Date());
			var h = 0, m = 0;
			parts.forEach(function (p) {
				if (p.type === 'hour') { h = parseInt(p.value, 10) % 24; }
				if (p.type === 'minute') { m = parseInt(p.value, 10); }
			});
			var mins = h * 60 + m;
			var isOpen = mins >= GZVP_OPEN * 60 && mins < GZVP_CLOSE * 60;
			box.className = 'gzvp__status ' + (isOpen ? 'is-now-open' : 'is-now-closed');
			box.querySelector('span').textContent = isOpen
				? 'هم‌اکنون باز هستیم؛ منتظر دیدار شما هستیم'
				: (mins < GZVP_OPEN * 60
					? 'هم‌اکنون بسته‌ایم؛ امروز از ساعت ۱۰ صبح در خدمت شما هستیم'
					: 'هم‌اکنون بسته‌ایم؛ فردا از ساعت ۱۰ صبح در خدمت شما هستیم');
		} catch (e) {
			box.style.display = 'none';
		}
	}

	function openPopup() {
		lastFocus = document.activeElement;
		updateStatus();
		root.classList.add('is-open');
		root.setAttribute('aria-hidden', 'false');
		document.documentElement.classList.add('gzvp-lock');
		setTimeout(function () {
			var c = root.querySelector('.gzvp__close');
			if (c) { c.focus(); }
		}, 60);
	}

	function closePopup() {
		root.classList.remove('is-open');
		root.setAttribute('aria-hidden', 'true');
		document.documentElement.classList.remove('gzvp-lock');
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
		if (root.contains(e.target) && e.target.closest('[data-gzvp-close]')) {
			e.preventDefault();
			closePopup();
		}
	}, true);

	document.addEventListener('keydown', function (e) {
		if (!root.classList.contains('is-open')) { return; }
		if (e.key === 'Escape') { closePopup(); return; }
		if (e.key === 'Tab') {
			var f = root.querySelectorAll('button, [href]');
			if (!f.length) { return; }
			var first = f[0], last = f[f.length - 1];
			if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
			else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
		}
	});

	if (window.location.hash === '#visit-hours') { openPopup(); }
})();
</script>
		<?php
	}
}
add_action( 'wp_footer', 'ghar_zende_visit_hours_popup', 50 );
