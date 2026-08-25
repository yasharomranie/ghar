import Link from "next/link";

export default function ArticleNotFound() {
  return (
    <div className="mx-auto flex min-h-[60vh] max-w-2xl flex-col items-center justify-center px-6 pt-24 text-center">
      <p className="font-display text-xs uppercase tracking-[0.35em] text-accent-soft">۴۰۴</p>
      <h1 className="mt-3 font-display text-2xl font-semibold text-ink sm:text-3xl">
        این مقاله پیدا نشد
      </h1>
      <p className="mt-3 max-w-md text-sm leading-relaxed text-ink-dim">
        ممکن است آدرس اشتباه باشد یا این مطلب جابه‌جا شده باشد. می‌توانید به مجله خبری برگردید و از آنجا ادامه دهید.
      </p>
      <Link
        href="/magazine"
        className="mt-6 rounded-full border border-ink/25 px-5 py-2.5 text-sm text-ink transition-colors hover:bg-ink/5"
      >
        بازگشت به مجله خبری
      </Link>
    </div>
  );
}
