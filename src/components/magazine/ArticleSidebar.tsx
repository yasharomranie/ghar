import Link from "next/link";
import type { Article, Heading } from "@/data/articles";
import { latestArticles } from "@/data/articles";
import { TableOfContents } from "./TableOfContents";

/**
 * The article page's sidebar rail: table of contents, a "آخرین مطالب" widget
 * for discovery, and a CTA back into the site's actual visit-booking flow.
 */
export function ArticleSidebar({ headings, article }: { headings: Heading[]; article: Article }) {
  const recent = latestArticles.filter((a) => a.slug !== article.slug).slice(0, 4);

  return (
    <div className="flex flex-col gap-6">
      <TableOfContents headings={headings} />

      <div className="rounded-2xl border border-foam/10 bg-stone-900/40 p-5">
        <p className="font-display text-xs uppercase tracking-[0.3em] text-turquoise-soft">آخرین مطالب</p>
        <ul className="mt-4 flex flex-col gap-4">
          {recent.map((a) => (
            <li key={a.slug}>
              <Link href={`/magazine/${a.slug}`} className="group flex flex-col gap-1">
                <span className="line-clamp-2 text-sm text-foam-dim transition-colors group-hover:text-foam">
                  {a.title}
                </span>
                <span className="text-xs text-foam-faint">{a.date}</span>
              </Link>
            </li>
          ))}
        </ul>
      </div>

      <div className="rounded-2xl border border-turquoise-dim/40 bg-turquoise-dim/10 p-5">
        <p className="font-display text-sm font-semibold text-foam">دلت می‌خواهد از نزدیک ببینی؟</p>
        <p className="mt-2 text-sm leading-relaxed text-foam-dim">
          برنامه‌ی بازدید از غار و آکواریوم‌های درون‌صخره‌ای را ببین و تور بعدی را رزرو کن.
        </p>
        <Link
          href="/#visit"
          className="mt-4 inline-flex items-center gap-2 rounded-full border border-foam/25 px-4 py-2 text-sm text-foam transition-colors hover:bg-white/5"
        >
          برنامه بازدید
        </Link>
      </div>
    </div>
  );
}
