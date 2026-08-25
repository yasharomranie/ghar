"use client";

import { useEffect, useMemo, useRef, useState } from "react";
import type { Article } from "@/data/articles";
import { ArticleCard } from "./ArticleCard";

const INITIAL_COUNT = 12; // 3 columns × 4 rows on desktop
const LOAD_BATCH = 6;

/**
 * "آخرین مطالب" — a 12-item grid up front, then more articles load in as
 * the reader scrolls near the bottom (IntersectionObserver on a sentinel).
 * The base dataset is small, so once it's exhausted the feed loops back to
 * the start rather than dead-ending — same real cards, endless scroll.
 */
export function ArticleGrid({ articles }: { articles: Article[] }) {
  const [visibleCount, setVisibleCount] = useState(Math.min(INITIAL_COUNT, articles.length));
  const [loading, setLoading] = useState(false);
  const sentinelRef = useRef<HTMLDivElement>(null);

  const visibleArticles = useMemo(() => {
    if (articles.length === 0) return [];
    return Array.from({ length: visibleCount }, (_, i) => articles[i % articles.length]);
  }, [articles, visibleCount]);

  useEffect(() => {
    const sentinel = sentinelRef.current;
    if (!sentinel || articles.length === 0) return;

    const observer = new IntersectionObserver(
      (entries) => {
        if (!entries[0].isIntersecting) return;
        setLoading(true);
        // brief pause so the loading state is perceptible instead of an instant snap
        window.setTimeout(() => {
          setVisibleCount((c) => c + LOAD_BATCH);
          setLoading(false);
        }, 500);
      },
      { rootMargin: "600px 0px" },
    );

    observer.observe(sentinel);
    return () => observer.disconnect();
  }, [articles.length]);

  return (
    <section id="latest" aria-label="آخرین مطالب" className="relative w-full bg-surface py-20">
      <div className="mx-auto max-w-7xl px-6 md:px-10">
        <div className="mb-10">
          <p className="font-display text-xs uppercase tracking-[0.35em] text-accent-soft">
            LATEST
          </p>
          <h2 className="mt-2 font-display text-2xl font-semibold text-ink sm:text-3xl">
            آخرین مطالب
          </h2>
        </div>

        <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {visibleArticles.map((article, i) => (
            <ArticleCard key={`${article.slug}-${i}`} article={article} />
          ))}
        </div>

        <div ref={sentinelRef} className="flex h-20 items-center justify-center" aria-hidden={!loading}>
          {loading && (
            <span className="flex items-center gap-3 text-sm text-ink-faint">
              <span className="h-4 w-4 animate-spin rounded-full border-2 border-ink/20 border-t-accent" />
              در حال بارگذاری مطالب بیشتر…
            </span>
          )}
        </div>
      </div>
    </section>
  );
}
