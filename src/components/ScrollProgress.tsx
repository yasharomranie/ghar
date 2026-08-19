"use client";

import { useEffect, useRef, useState } from "react";
import { scenes } from "@/data/scenes";

/**
 * Vertical progress rail (design brief §16): a thin line that fills with
 * scroll, plus the current Scene's index/label. Hidden on small screens
 * where it would compete with thumb-reach content.
 */
export function ScrollProgress() {
  const [progress, setProgress] = useState(0);
  const [activeIndex, setActiveIndex] = useState(0);
  const observerTargets = useRef<HTMLElement[]>([]);

  useEffect(() => {
    const onScroll = () => {
      const doc = document.documentElement;
      const max = doc.scrollHeight - doc.clientHeight;
      setProgress(max > 0 ? Math.min(1, window.scrollY / max) : 0);
    };
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  useEffect(() => {
    const els = scenes
      .map((s) => document.getElementById(s.id))
      .filter((el): el is HTMLElement => !!el);
    observerTargets.current = els;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const idx = els.indexOf(entry.target as HTMLElement);
            if (idx !== -1) setActiveIndex(idx);
          }
        });
      },
      { rootMargin: "-45% 0px -45% 0px", threshold: 0 },
    );

    els.forEach((el) => observer.observe(el));
    return () => observer.disconnect();
  }, []);

  const active = scenes[activeIndex];

  return (
    <div
      className="pointer-events-none fixed inset-y-0 left-6 z-40 hidden flex-col items-center justify-center gap-4 lg:flex"
      aria-hidden="true"
    >
      <div className="relative h-56 w-px overflow-hidden bg-foam/15">
        <div
          className="absolute inset-x-0 top-0 bg-turquoise transition-[height] duration-150 ease-out"
          style={{ height: `${progress * 100}%` }}
        />
      </div>
      <div className="mt-2 rotate-180 [writing-mode:vertical-rl] font-display text-[11px] tracking-[0.3em] text-foam-dim">
        {active?.index} / {active?.label}
      </div>
    </div>
  );
}
