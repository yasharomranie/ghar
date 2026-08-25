"use client";

import { useEffect, useState } from "react";
import type { Heading } from "@/data/articles";
import { cn } from "@/lib/utils";

/**
 * Sticky "فهرست مطالب" widget — highlights whichever section heading is
 * currently nearest the top of the viewport as the reader scrolls.
 */
export function TableOfContents({ headings }: { headings: Heading[] }) {
  const [activeId, setActiveId] = useState<string | null>(headings[0]?.id ?? null);

  useEffect(() => {
    if (headings.length === 0) return;

    const els = headings
      .map((h) => document.getElementById(h.id))
      .filter((el): el is HTMLElement => el !== null);
    if (els.length === 0) return;

    const observer = new IntersectionObserver(
      (entries) => {
        const visible = entries.filter((e) => e.isIntersecting);
        if (visible.length === 0) return;
        const topMost = visible.reduce((a, b) =>
          a.boundingClientRect.top < b.boundingClientRect.top ? a : b,
        );
        setActiveId(topMost.target.id);
      },
      { rootMargin: "-100px 0px -70% 0px", threshold: 0 },
    );

    els.forEach((el) => observer.observe(el));
    return () => observer.disconnect();
  }, [headings]);

  if (headings.length === 0) return null;

  return (
    <nav aria-label="فهرست مطالب" className="rounded-2xl border border-ink/10 bg-surface-raised/40 p-5">
      <p className="font-display text-xs uppercase tracking-[0.3em] text-accent-soft">فهرست مطالب</p>
      <ol className="mt-4 flex flex-col gap-1 text-sm">
        {headings.map((h) => (
          <li key={h.id}>
            <a
              href={`#${h.id}`}
              aria-current={activeId === h.id ? "location" : undefined}
              className={cn(
                "block border-s-2 py-1.5 ps-4 transition-colors",
                activeId === h.id
                  ? "border-accent text-ink"
                  : "border-ink/10 text-ink-dim hover:border-ink/30 hover:text-ink",
              )}
            >
              {h.text}
            </a>
          </li>
        ))}
      </ol>
    </nav>
  );
}
