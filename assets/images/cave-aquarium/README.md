# Cave Aquarium — Reference Photos

عکس‌های واقعی غار/آکواریوم اینجا به‌صورت آرشیو (کیفیت اصلی) نگه‌داری می‌شوند.

نسخه‌های بهینه‌شده (WebP، تغییر اندازه‌یافته) که واقعاً توسط سایت سرو می‌شوند در
`public/images/cave/` قرار دارند — همان‌هایی که کامپوننت‌های `PhotoBackdrop` و
`AquariumWindow` رفرنس می‌دهند.

## عکس بیشتر می‌خوای اضافه کنی؟

1. فایل اصلی رو همین‌جا (در `assets/images/cave-aquarium/`) آپلود کن.
2. یک نسخه‌ی بهینه‌شده (حداکثر عرض ~2000px، فرمت WebP) در `public/images/cave/` بساز.
3. مسیر جدید رو در `src/data/species.ts` (فیلد `photo`) یا مستقیم در کامپوننت
   Scene مربوطه (`PhotoBackdrop` / `AquariumWindow`) رفرنس بده.

فرمت‌های مجاز برای آپلود: `.jpg`, `.jpeg`, `.png`, `.webp`
