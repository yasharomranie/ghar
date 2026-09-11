<?php
/**
 * صفحه‌ی اختصاصی «گونه‌ها» (نامک: species) — همه‌ی نوشته‌های منتشرشده‌ی
 * دسته‌ی «گونه‌ها» را به‌صورت کارت نمایش می‌دهد. داده‌ی کارت‌ها از
 * inc/species-posts.php می‌آید (تصویر شاخص + متاباکس «کارت گونه»).
 * متن معرفی بالای صفحه = محتوای همین برگه در پیشخوان.
 */
get_header();
the_post();

$gsp_items = ghar_zende_species_from_posts( -1, 'medium_large' );
$gsp_count = strtr( (string) count( $gsp_items ), array( '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹' ) );
$gsp_intro = trim( get_the_content() );
?>

<style>
.gsp{background:var(--color-void,#050708);color:var(--color-foam,#f4efe3)}
.gsp-hero{position:relative;isolation:isolate;overflow:hidden;min-height:60vh;display:flex;align-items:flex-end;justify-content:center;padding:10rem 1.5rem 4.5rem;text-align:center}
.gsp-hero-bg{position:absolute;inset:0;z-index:-2;width:100%;height:100%;object-fit:cover;opacity:.55;transform:scale(1.06);animation:gspDrift 20s ease-in-out infinite alternate}
.gsp-hero::after{content:"";position:absolute;inset:0;z-index:-1;background:radial-gradient(120% 90% at 50% 0%,transparent 0%,var(--color-void,#050708) 85%),linear-gradient(180deg,rgba(5,7,8,.15) 0%,var(--color-void,#050708) 100%)}
.gsp-eyebrow{font-size:.75rem;letter-spacing:.4em;text-transform:uppercase;color:var(--color-turquoise-soft,#7fe3d3)}
.gsp-title{margin-top:1rem;font-size:clamp(2rem,5vw,3.4rem);font-weight:600;line-height:1.35;color:var(--color-foam,#f4efe3)}
.gsp-intro{max-width:40rem;margin:1.25rem auto 0;line-height:2.1;color:rgba(244,239,227,.72)}
.gsp-intro p{margin:0}
.gsp-count{display:inline-flex;align-items:center;gap:.5rem;margin-top:1.75rem;padding:.5rem 1.15rem;border:1px solid rgba(244,239,227,.18);border-radius:999px;background:rgba(5,7,8,.35);backdrop-filter:blur(6px);font-size:.82rem;color:rgba(244,239,227,.8)}
.gsp-count b{font-weight:600;color:var(--color-turquoise,#4fd8c4)}
.gsp-wrap{max-width:74rem;margin:0 auto;padding:1rem 1.5rem 7rem}
.gsp-grid{display:grid;gap:1.75rem;grid-template-columns:repeat(auto-fill,minmax(min(100%,300px),1fr))}
.gsp-card{opacity:0;transform:translateY(26px);animation:gspIn .85s cubic-bezier(.2,.7,.2,1) forwards;animation-delay:calc(var(--i,0) * 90ms)}
.gsp-link{position:relative;display:flex;flex-direction:column;height:100%;overflow:hidden;border:1px solid rgba(244,239,227,.1);border-radius:1.25rem;background:linear-gradient(180deg,rgba(26,23,19,.6),rgba(5,7,8,.92));color:inherit;text-decoration:none;transition:border-color .5s,transform .5s,box-shadow .5s}
.gsp-link::before{content:"";position:absolute;inset:0;z-index:1;pointer-events:none;background:radial-gradient(280px circle at var(--mx,50%) var(--my,0%),rgba(79,216,196,.16),transparent 70%);opacity:0;transition:opacity .5s}
.gsp-link:hover,.gsp-link:focus-visible{border-color:rgba(79,216,196,.45);transform:translateY(-6px);box-shadow:0 26px 60px -26px rgba(79,216,196,.4)}
.gsp-link:hover::before,.gsp-link:focus-visible::before{opacity:1}
.gsp-media{position:relative;aspect-ratio:4/3;overflow:hidden;background:var(--color-stone-900,#14110e)}
.gsp-media img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;filter:saturate(.9) brightness(.88);transition:transform 1.1s cubic-bezier(.2,.7,.2,1),filter .6s}
.gsp-link:hover .gsp-media img,.gsp-link:focus-visible .gsp-media img{transform:scale(1.08);filter:saturate(1.1) brightness(1)}
.gsp-media::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,transparent 50%,rgba(5,7,8,.9) 100%)}
.gsp-icon{position:absolute;inset:0;display:grid;place-items:center;padding:0 18%}
.gsp-body{position:relative;z-index:2;display:flex;flex:1;flex-direction:column;gap:.45rem;margin-top:-2rem;padding:0 1.5rem 1.5rem}
.gsp-name{font-size:1.3rem;font-weight:600;line-height:1.6;color:var(--color-foam,#f4efe3)}
.gsp-sci{font-size:.78rem;font-style:italic;text-align:right;color:rgba(244,239,227,.5)}
.gsp-desc{margin-top:.35rem;font-size:.9rem;line-height:1.95;color:rgba(244,239,227,.72)}
.gsp-tags{display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.6rem}
.gsp-tag{padding:.3rem .75rem;border:1px solid rgba(79,216,196,.25);border-radius:999px;background:rgba(79,216,196,.06);font-size:.72rem;line-height:1.7;color:rgba(244,239,227,.82)}
.gsp-tag span{color:var(--color-turquoise-soft,#7fe3d3)}
.gsp-more{display:inline-flex;align-items:center;gap:.45rem;margin-top:auto;padding-top:1.1rem;font-size:.82rem;color:var(--color-turquoise,#4fd8c4)}
.gsp-more i{font-style:normal;transition:transform .4s}
.gsp-link:hover .gsp-more i{transform:translateX(-6px)}
.gsp-empty{padding:4rem 0;text-align:center;color:rgba(244,239,227,.6)}
.gsp-back{display:flex;justify-content:center;margin-top:4.5rem}
.gsp-back a{display:inline-flex;align-items:center;gap:.6rem;padding:.75rem 1.75rem;border:1px solid rgba(244,239,227,.25);border-radius:999px;font-size:.88rem;color:var(--color-foam,#f4efe3);text-decoration:none;transition:border-color .4s,color .4s}
.gsp-back a:hover{border-color:var(--color-turquoise,#4fd8c4);color:var(--color-turquoise-soft,#7fe3d3)}
@keyframes gspIn{to{opacity:1;transform:none}}
@keyframes gspDrift{to{transform:scale(1.14) translate(-1.5%,1%)}}
@media (prefers-reduced-motion:reduce){.gsp-card{opacity:1;transform:none;animation:none}.gsp-hero-bg{animation:none}.gsp-link,.gsp-media img,.gsp-more i{transition:none}}
</style>

<div class="gsp">
	<section class="gsp-hero" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
		<img class="gsp-hero-bg" src="<?php echo esc_url( ghar_zende_img( 'aquarium-window-plants.webp' ) ); ?>" alt="" aria-hidden="true" decoding="async" fetchpriority="high" />
		<div>
			<p class="gsp-eyebrow font-display">SPECIES</p>
			<h1 class="gsp-title font-display"><?php the_title(); ?></h1>
			<?php if ( '' !== $gsp_intro ) : ?>
			<div class="gsp-intro"><?php the_content(); ?></div>
			<?php endif; ?>
			<?php if ( $gsp_items ) : ?>
			<p class="gsp-count"><b><?php echo esc_html( $gsp_count ); ?></b> گونه‌ی زنده در دل غار</p>
			<?php endif; ?>
		</div>
	</section>

	<div class="gsp-wrap">
		<?php if ( $gsp_items ) : ?>
		<div class="gsp-grid">
			<?php foreach ( $gsp_items as $i => $s ) : ?>
			<article class="gsp-card" style="--i:<?php echo (int) min( $i, 12 ); ?>">
				<a class="gsp-link" href="<?php echo esc_url( $s['url'] ); ?>" data-cursor="مشاهده" aria-label="<?php echo esc_attr( $s['name'] ); ?>">
					<div class="gsp-media">
						<?php
						if ( $s['photo_id'] ) {
							echo wp_get_attachment_image(
								$s['photo_id'],
								'medium_large',
								false,
								array(
									'alt'      => $s['alt'],
									'loading'  => $i < 3 ? 'eager' : 'lazy',
									'decoding' => 'async',
									'sizes'    => '(min-width: 1024px) 380px, (min-width: 640px) 50vw, 100vw',
								)
							);
						} else {
							echo '<div class="gsp-icon">' . ghar_zende_species_icon( $s['color'], $s['accent'] ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
						}
						?>
					</div>
					<div class="gsp-body">
						<h2 class="gsp-name font-display"><?php echo esc_html( $s['name'] ); ?></h2>
						<?php if ( '' !== $s['sci'] ) : ?>
						<p class="gsp-sci" dir="ltr"><?php echo esc_html( $s['sci'] ); ?></p>
						<?php endif; ?>
						<p class="gsp-desc"><?php echo esc_html( $s['description'] ); ?></p>
						<?php if ( '' !== $s['habitat'] || '' !== $s['trait'] ) : ?>
						<div class="gsp-tags">
							<?php if ( '' !== $s['habitat'] ) : ?>
							<span class="gsp-tag"><span>زیستگاه:</span> <?php echo esc_html( $s['habitat'] ); ?></span>
							<?php endif; ?>
							<?php if ( '' !== $s['trait'] ) : ?>
							<span class="gsp-tag"><span>ویژگی:</span> <?php echo esc_html( $s['trait'] ); ?></span>
							<?php endif; ?>
						</div>
						<?php endif; ?>
						<span class="gsp-more">آشنایی کامل با این گونه <i aria-hidden="true">←</i></span>
					</div>
				</a>
			</article>
			<?php endforeach; ?>
		</div>
		<?php else : ?>
		<p class="gsp-empty">هنوز گونه‌ای در این بخش منتشر نشده است.</p>
		<?php endif; ?>

		<div class="gsp-back">
			<a href="<?php echo esc_url( home_url( '/#species' ) ); ?>" data-magnetic><span aria-hidden="true">→</span> بازگشت به غار</a>
		</div>
	</div>
</div>

<script>
document.querySelectorAll('.gsp-link').forEach(function (card) {
	card.addEventListener('pointermove', function (e) {
		var r = card.getBoundingClientRect();
		card.style.setProperty('--mx', (e.clientX - r.left) + 'px');
		card.style.setProperty('--my', (e.clientY - r.top) + 'px');
	});
});
</script>

<?php get_footer(); ?>
