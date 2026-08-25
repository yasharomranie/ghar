"use client";

import Image from "next/image";
import Link from "next/link";
import { useCallback, useEffect, useRef, useState } from "react";
import type { Article } from "@/data/articles";
import { useReducedMotion } from "@/hooks/useReducedMotion";
import { cn } from "@/lib/utils";

const AUTOPLAY_MS = 6000;

/** Full-bleed hero slider for the magazine's top stories. */
export function HeroSlider({ slides }: { slides: Article[] }) {
  const [index, setIndex] = useState(0);
  const [paused, setPaused] = useState(false);
  const reducedMotion = useReducedMotion();
  const timerRef = useRef<ReturnType<typeof setInterval> | null>(null);

  const goTo = useCallback(
    (i: number) => setIndex(((i % slides.length) + slides.length) % slides.length),
    [slides.length],
  );
  const next = useCallback(() => goTo(index + 1), [goTo, index]);
  const prev = useCallback(() => goTo(index - 1), [goTo, index]);

  useEffect(() => {
    if (reducedMotion || paused) return;
    timerRef.current = setInterval(next, AUTOPLAY_MS);
    return () => {
      if (timerRef.current) clearInterval(timerRef.current);
    };
  }, [next, paused, reducedMotion]);

  return (
    <section
      aria-roledescription="اسلایدر"
      aria-label="مطالب برگزیده"
      className="relative h-[78svh] min-h-[440px] w-full overflow-hidden bg-void"
      onMouseEnter={() => setPaused(true)}
      onMouseLeave={() => setPaused(false)}
      onFocus={() => setPaused(true)}
      onBlur={() => setPaused(false)}
    >
      {slides.map((slide, i) => (
        <div
          key={slide.slug}
          className={cn(
            "absolute inset-0 transition-opacity duration-700 ease-out",
            i === index ? "opacity-100" : "pointer-events-none opacity-0",
          )}
          aria-hidden={i !== index}
        >
          <Image
            src={slide.image}
            alt=""
            fill
            priority={i === 0}
            sizes="100vw"
            className="object-cover"
          />
          <div className="absolute inset-0 bg-gradient-to-t from-void via-void/40 to-void/10" />

          <div className="relative z-10 mx-auto flex h-full max-w-7xl flex-col justify-end px-6 pb-14 md:px-10">
            <span className="font-display text-xs uppercase tracking-[0.35em] text-turquoise-soft">
              {slide.category}
            </span>
            <h2 className="mt-3 max-w-2xl text-balance font-display text-2xl font-semibold leading-snug text-foam sm:text-4xl">
              <Link href={`/magazine/${slide.slug}`} className="transition-colors hover:text-turquoise-soft">
                {slide.title}
              </Link>
            </h2>
            <p className="mt-3 max-w-xl text-sm text-foam-dim sm:text-base">{slide.excerpt}</p>
            <div className="mt-3 flex items-center gap-3 text-xs text-foam-faint">
              <span>{slide.date}</span>
              <span aria-hidden="true">·</span>
              <span>{slide.readTime} مطالعه</span>
            </div>
            <Link
              href={`/magazine/${slide.slug}`}
              className="mt-4 inline-flex w-fit items-center gap-2 text-sm text-turquoise-soft transition-colors hover:text-turquoise"
            >
              ادامه مطلب
              <svg viewBox="0 0 24 24" className="h-3.5 w-3.5" aria-hidden="true">
                <path
                  d="M15 6l-6 6 6 6"
                  fill="none"
                  stroke="currentColor"
                  strokeWidth="2"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                />
              </svg>
            </Link>
          </div>
        </div>
      ))}

      {/* controls */}
      <div className="absolute inset-x-0 bottom-6 z-20 flex items-center justify-center gap-4">
        <button
          type="button"
          onClick={prev}
          aria-label="اسلاید قبلی"
          className="flex h-9 w-9 items-center justify-center rounded-full border border-foam/25 text-foam transition-colors hover:border-turquoise hover:text-turquoise-soft"
        >
          <svg viewBox="0 0 24 24" className="h-4 w-4" aria-hidden="true">
            <path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </button>

        <div className="flex items-center gap-2">
          {slides.map((slide, i) => (
            <button
              key={slide.slug}
              type="button"
              onClick={() => goTo(i)}
              aria-label={`رفتن به اسلاید ${i + 1}`}
              aria-current={i === index}
              className={cn(
                "h-1.5 rounded-full transition-all",
                i === index ? "w-6 bg-turquoise" : "w-1.5 bg-foam/30 hover:bg-foam/50",
              )}
            />
          ))}
        </div>

        <button
          type="button"
          onClick={next}
          aria-label="اسلاید بعدی"
          className="flex h-9 w-9 items-center justify-center rounded-full border border-foam/25 text-foam transition-colors hover:border-turquoise hover:text-turquoise-soft"
        >
          <svg viewBox="0 0 24 24" className="h-4 w-4" aria-hidden="true">
            <path d="M15 6l-6 6 6 6" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </button>
      </div>
    </section>
  );
}
