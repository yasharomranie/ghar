import Link from "next/link";
import { scenes } from "@/data/scenes";

const navScenes = scenes.filter((s) => s.navLabel);

/**
 * Site-wide footer — shared between the home experience and every other
 * page (e.g. the magazine) so the brand frame stays consistent. Uses the
 * theme-aware tokens (it's plain chrome, never overlaid on a photo), so it
 * flips with the header toggle even on the home page.
 */
export function Footer() {
  return (
    <footer className="relative border-t border-ink/10 bg-surface">
      <div className="mx-auto flex max-w-7xl flex-col gap-10 px-6 py-14 md:px-10">
        <div className="flex flex-col gap-8 md:flex-row md:items-start md:justify-between">
          <div className="max-w-sm">
            <Link href="/" className="font-display text-lg font-semibold text-ink">
              غار <span className="text-accent">زنده</span>
            </Link>
            <p className="mt-3 text-sm leading-relaxed text-ink-dim">
              دنیایی زنده در دل زمین — سفری به قلب یک غار طبیعی با آکواریوم‌های
              درون‌صخره‌ای.
            </p>
          </div>

          <nav className="grid grid-cols-2 gap-x-10 gap-y-3 text-sm sm:grid-cols-3" aria-label="پیمایش فوتر">
            {navScenes.map((s) => (
              <Link
                key={s.id}
                href={`/#${s.id}`}
                className="text-ink-dim transition-colors hover:text-ink"
              >
                {s.navLabel}
              </Link>
            ))}
            <Link href="/magazine" className="text-ink-dim transition-colors hover:text-ink">
              مجله خبری
            </Link>
          </nav>
        </div>

        <div className="flex flex-col items-center justify-between gap-4 border-t border-ink/10 pt-6 text-xs text-ink-faint sm:flex-row">
          <p>© {new Date().getFullYear()} غار زنده — تمام حقوق محفوظ است.</p>
          <p className="font-display">جایی که سنگ، آب و زندگی به هم می‌رسند</p>
        </div>
      </div>
    </footer>
  );
}
