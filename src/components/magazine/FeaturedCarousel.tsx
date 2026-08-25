"use client";

import { useEffect, useMemo, useRef, useState } from "react";
import type { Article } from "@/data/articles";
import { useReducedMotion } from "@/hooks/useReducedMotion";
import { ArticleCard } from "./ArticleCard";
import { cn } from "@/lib/utils";

const GROUP_SIZE = 4;
const AUTOPLAY_MS = 5000;

function chunk<T>(items: T[], size: number): T[][] {
  const groups: T[][] = [];
  for (let i = 0; i < items.length; i += size) groups.push(items.slice(i, i + size));
  return groups;
}

/**
 * Auto-advancing carousel of featured articles — four cards per page on
 * every breakpoint (a 4-column row on desktop, a 2×2 grid on mobile).
 */
export function FeaturedCarousel({ articles }: { articles: Article[] }) {
  const groups = useMemo(() => chunk(articles, GROUP_SIZE), [articles]);
  const [page, setPage] = useState(0);
  const [paused, setPaused] = useState(false);
  const reducedMotion = useReducedMotion();
  const timerRef = useRef<ReturnType<typeof setInterval> | null>(null);

  useEffect(() => {
    if (reducedMotion || paused || groups.length <= 1) return;
    timerRef.current = setInterval(() => {
      setPage((p) => (p + 1) % groups.length);
    }, AUTOPLAY_MS);
    return () => {
      if (timerRef.current) clearInterval(timerRef.current);
    };
  }, [groups.length, paused, reducedMotion]);

  return (
    <section
      id="featured"
      aria-label="مطالب ویژه"
      className="relative w-full bg-void py-20"
      onMouseEnter={() => setPaused(true)}
      onMouseLeave={() => setPaused(false)}
    >
      <div className="mx-auto max-w-7xl px-6 md:px-10">
        <div className="mb-10 flex items-end justify-between">
          <div>
            <p className="font-display text-xs uppercase tracking-[0.35em] text-turquoise-soft">
              FEATURED
            </p>
            <h2 className="mt-2 font-display text-2xl font-semibold text-foam sm:text-3xl">
              مطالب ویژه
            </h2>
          </div>

          {groups.length > 1 && (
            <div className="hidden items-center gap-2 sm:flex">
              {groups.map((_, i) => (
                <button
                  key={i}
                  type="button"
                  onClick={() => setPage(i)}
                  aria-label={`صفحه‌ی ${i + 1} مطالب ویژه`}
                  aria-current={i === page}
                  className={cn(
                    "h-1.5 rounded-full transition-all",
                    i === page ? "w-6 bg-turquoise" : "w-1.5 bg-foam/25 hover:bg-foam/45",
                  )}
                />
              ))}
            </div>
          )}
        </div>

        {/*
          The whole track is forced to `dir="ltr"` so the paging math is
          unambiguous (plain translateX, DOM order == physical left-to-right
          order) — mixing `dir="rtl"` with a translated multi-page flex track
          hits an actual RTL grid auto-placement inconsistency in Chromium
          (first page places its first card at the right as expected; later
          pages silently flip it to the left). `flex-row-reverse` inside each
          group is direction-agnostic, so it reliably puts each page's first
          article on the right regardless. ArticleCard re-asserts its own
          `dir="rtl"` so the Persian text itself still reads correctly.
        */}
        <div className="relative overflow-hidden" dir="ltr">
          <div
            className="flex transition-transform duration-700 ease-out"
            style={{ transform: `translateX(${-page * 100}%)` }}
          >
            {groups.map((group, gi) => (
              <div
                key={gi}
                className="flex w-full shrink-0 flex-row-reverse flex-wrap gap-4 sm:gap-5"
                aria-hidden={gi !== page}
              >
                {group.map((article, ai) => (
                  <ArticleCard
                    key={article.slug}
                    article={article}
                    priority={gi === 0 && ai < 2}
                    className="w-[calc(50%-0.5rem)] sm:w-[calc(50%-0.625rem)] lg:w-[calc(25%-0.9375rem)]"
                  />
                ))}
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}
