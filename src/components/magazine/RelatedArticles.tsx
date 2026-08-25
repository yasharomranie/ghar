import type { Article } from "@/data/articles";
import { ArticleCard } from "./ArticleCard";

/** Full-width "مطالب مرتبط" block at the bottom of an article page. */
export function RelatedArticles({ articles }: { articles: Article[] }) {
  if (articles.length === 0) return null;

  return (
    <section aria-label="مطالب مرتبط" className="border-t border-ink/10 bg-surface py-16">
      <div className="mx-auto max-w-7xl px-6 md:px-10">
        <p className="font-display text-xs uppercase tracking-[0.35em] text-accent-soft">RELATED</p>
        <h2 className="mt-2 font-display text-2xl font-semibold text-ink sm:text-3xl">مطالب مرتبط</h2>
        <div className="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {articles.map((a) => (
            <ArticleCard key={a.slug} article={a} />
          ))}
        </div>
      </div>
    </section>
  );
}
