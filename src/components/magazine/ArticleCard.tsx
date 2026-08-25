import Image from "next/image";
import type { Article } from "@/data/articles";
import { cn } from "@/lib/utils";

export function ArticleCard({
  article,
  priority = false,
  className,
}: {
  article: Article;
  priority?: boolean;
  className?: string;
}) {
  return (
    <article
      dir="rtl"
      className={cn(
        "group relative flex h-full flex-col overflow-hidden rounded-2xl border border-foam/10 bg-stone-900/50 transition-colors duration-300 hover:border-turquoise-dim",
        className,
      )}
    >
      <div className="relative aspect-[4/3] w-full overflow-hidden">
        <Image
          src={article.image}
          alt={article.title}
          fill
          priority={priority}
          sizes="(min-width: 1024px) 25vw, (min-width: 640px) 50vw, 100vw"
          className="object-cover transition-transform duration-700 ease-out group-hover:scale-110"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-void/70 via-void/0 to-void/0" />
        <span className="absolute start-3 top-3 rounded-full bg-void/70 px-3 py-1 font-display text-[11px] text-turquoise-soft backdrop-blur">
          {article.category}
        </span>
      </div>

      <div className="flex flex-1 flex-col gap-2 p-5">
        <h3 className="line-clamp-2 font-display text-base font-semibold leading-snug text-foam">
          {article.title}
        </h3>
        <p className="line-clamp-2 flex-1 text-sm leading-relaxed text-foam-dim">{article.excerpt}</p>
        <div className="mt-2 flex items-center gap-3 text-xs text-foam-faint">
          <span>{article.date}</span>
          <span aria-hidden="true">·</span>
          <span>{article.readTime} مطالعه</span>
        </div>
      </div>
    </article>
  );
}
