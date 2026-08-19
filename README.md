# غار زنده — A Living Aquarium Hidden Inside the Earth

سایت سینمایی و اسکرول‌محور برای یک غار طبیعی با آکواریوم‌های درون‌صخره‌ای. کاربر با اسکرول کردن، مرحله‌به‌مرحله وارد دل غار می‌شود و دنیای زنده‌ی آن را کشف می‌کند.

## Stack

- **Next.js 16** (App Router, TypeScript)
- **Tailwind CSS v4**
- **GSAP + ScrollTrigger** — سکانس‌های اسکرول‌محور، پارالاکس، pin/sticky
- **Lenis** — اسکرول نرم، هماهنگ‌شده با GSAP ticker
- **Framer Motion** — میکرو-اینترکشن‌های UI (منو، کرسر)
- فونت **Vazirmatn** (RTL, فارسی)

## ساختار پروژه

```
src/
  app/            layout, page, globals.css (توکن‌های طراحی)
  components/     Nav, ScrollProgress, CustomCursor, SmoothScrollProvider, RevealText
  components/visuals/  PhotoBackdrop (عکس واقعی + گرادیان خوانایی)، AquariumWindow، Particles، Fish icon
  sections/       هر «Scene» روایت، یک کامپوننت مستقل (۹ صحنه)
  data/           محتوای گونه‌ها، حقایق زمین‌شناسی، متادیتای صحنه‌ها
  hooks/          useReducedMotion, useIsCoarsePointer
```

## معماری اسکرول (۹ Scene)

۰۱ ورود → ۰۲ تاریکی → ۰۳ نخستین آب → ۰۴ آکواریوم (Image Completion) → ۰۵ دنیای زنده (تعامل ماهی) → ۰۶ گونه‌ها → ۰۷ زمین‌شناسی → ۰۸ سنگ→آب→زندگی → ۰۹ بازدید

## عکس‌های واقعی

سایت از همان ابتدای ورود (Hero) با عکس‌های واقعی غار/آکواریوم ساخته شده — نه پلیس‌هولدر تولیدی. عکس‌های بهینه‌شده (WebP) که واقعاً توسط اپ سرو می‌شوند در `public/images/cave/` قرار دارند؛ نسخه‌ی آرشیوی/کیفیت اصلی همان فایل‌ها هم در `assets/images/cave-aquarium/` نگه‌داری می‌شود.

| فایل | صحنه‌ای که استفاده شده |
|---|---|
| `hero-entrance` | ۰۱ ورود |
| `darkness-threshold` | ۰۲ تاریکی |
| `water-corridor` | ۰۳ نخستین آب |
| `aquarium-window-clear` | ۰۴ آکواریوم Reveal |
| `corridor-panorama-bright` | ۰۵ دنیای زنده |
| `aquarium-window-plants` + `species-regal-angelfish` | ۰۶ گونه‌ها |
| `corridor-stalagmites` | ۰۷ زمین‌شناسی |
| `aquarium-window-light` | ۰۸ سنگ→آب→زندگی |
| `corridor-warm-glow` | ۰۹ بازدید |

عکس بیشتری لازم شد (مثلاً پرتره‌ی گونه‌های دیگر)، همان الگوی `photo?` در `src/data/species.ts` و `PhotoBackdrop`/`AquariumWindow` را دنبال کن — بدون نیاز به تغییر معماری انیمیشن‌ها.

## دسترسی‌پذیری و عملکرد

- `prefers-reduced-motion` در همه‌ی صحنه‌های اسکرول‌محور رعایت می‌شود (حالت نهایی و خوانا بدون انیمیشن نمایش داده می‌شود).
- تعداد ذرات/ماهی‌ها روی دستگاه‌های لمسی کاهش می‌یابد.
- فوکوس کیبورد و `skip to content` فعال است.

## توسعه

```bash
npm install
npm run dev
```
