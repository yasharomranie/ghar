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
  components/visuals/  بصری‌سازهای جایگزین (Rock, Water, Fish, Particles) — قابل تعویض با عکس/ویدیوی واقعی
  sections/       هر «Scene» روایت، یک کامپوننت مستقل (۹ صحنه)
  data/           محتوای گونه‌ها، حقایق زمین‌شناسی، متادیتای صحنه‌ها
  hooks/          useReducedMotion, useIsCoarsePointer
```

## معماری اسکرول (۹ Scene)

۰۱ ورود → ۰۲ تاریکی → ۰۳ نخستین آب → ۰۴ آکواریوم (Image Completion) → ۰۵ دنیای زنده (تعامل ماهی) → ۰۶ گونه‌ها → ۰۷ زمین‌شناسی → ۰۸ سنگ→آب→زندگی → ۰۹ بازدید

## جایگزینی تصاویر واقعی

عکس‌های مرجع در `assets/images/cave-aquarium/` آپلود می‌شوند. کامپوننت‌های `src/components/visuals/*` در حال حاضر با گرادیان/SVG تولیدی جای عکس واقعی را پر می‌کنند؛ وقتی عکس/ویدیوی واقعی آماده شد، همان‌جا (با `next/image` یا `<video>`) جایگزین می‌شود بدون این‌که معماری انیمیشن‌ها تغییر کند.

## دسترسی‌پذیری و عملکرد

- `prefers-reduced-motion` در همه‌ی صحنه‌های اسکرول‌محور رعایت می‌شود (حالت نهایی و خوانا بدون انیمیشن نمایش داده می‌شود).
- تعداد ذرات/ماهی‌ها روی دستگاه‌های لمسی کاهش می‌یابد.
- فوکوس کیبورد و `skip to content` فعال است.

## توسعه

```bash
npm install
npm run dev
```
