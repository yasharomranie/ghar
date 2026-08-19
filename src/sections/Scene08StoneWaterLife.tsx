"use client";

import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { useRef, useState } from "react";
import { AquariumWindow } from "@/components/visuals/AquariumWindow";
import { CaveWindowClipDefs } from "@/components/visuals/CaveWindowClip";
import { useReducedMotion } from "@/hooks/useReducedMotion";
import { storyChapters } from "@/data/geology";

/**
 * SCENE 08 — STONE → WATER → LIFE (brief §10)
 * The rock physically parts as the reader scrolls, revealing the aquarium
 * behind it. One of the most important beats on the page.
 */
export function Scene08StoneWaterLife() {
  const trackRef = useRef<HTMLElement>(null);
  const [progress, setProgress] = useState(0);
  const reducedMotion = useReducedMotion();

  useGSAP(
    () => {
      if (reducedMotion || !trackRef.current) return;
      const trigger = ScrollTrigger.create({
        trigger: trackRef.current,
        start: "top top",
        end: "bottom bottom",
        scrub: 0.6,
        onUpdate: (self) => {
          const step = Math.round(self.progress * 100);
          setProgress((prev) => (Math.round(prev * 100) === step ? prev : step / 100));
        },
      });
      return () => trigger.kill();
    },
    { scope: trackRef, dependencies: [reducedMotion] },
  );

  const p = reducedMotion ? 1 : progress;
  const partOpen = Math.min(1, p / 0.55); // 0 → 1 across the first ~55%
  const chapterIndex = Math.min(
    storyChapters.length - 1,
    Math.floor(p * storyChapters.length),
  );
  const chapter = storyChapters[chapterIndex];

  return (
    <section
      id="story"
      ref={trackRef}
      aria-label="سنگ، آب، زندگی"
      className="relative h-[300vh] w-full bg-void"
    >
      <CaveWindowClipDefs />
      <div className="sticky top-0 flex h-[100svh] w-full items-center justify-center overflow-hidden">
        <AquariumWindow
          reveal={Math.max(0, (p - 0.3) / 0.7)}
          photoSrc="/images/cave/aquarium-window-light.webp"
          photoAlt="آکواریومی درون‌صخره‌ای با پرتوهای نور و حباب‌های آب، پر از ماهی‌های رنگارنگ"
          clipId="cave-window-b"
          className="aspect-[4/3] w-[min(90vw,760px)]"
        />

        {/* rock shutters that part to reveal the window behind them */}
        <div
          className="absolute inset-y-0 start-0 bg-stone-900"
          style={{
            width: "52%",
            transform: `translateX(${reducedMotion ? "-100%" : `${-partOpen * 100}%`})`,
            boxShadow: "8px 0 30px rgba(0,0,0,0.6)",
          }}
          aria-hidden="true"
        />
        <div
          className="absolute inset-y-0 end-0 bg-stone-900"
          style={{
            width: "52%",
            transform: `translateX(${reducedMotion ? "100%" : `${partOpen * 100}%`})`,
            boxShadow: "-8px 0 30px rgba(0,0,0,0.6)",
          }}
          aria-hidden="true"
        />

        <div className="pointer-events-none absolute inset-x-0 top-14 z-10 flex flex-col items-center gap-2 text-center">
          <span className="font-display text-[11px] uppercase tracking-[0.4em] text-turquoise-soft">
            {chapter.title} · {chapter.persian}
          </span>
          <p className="text-balance font-display text-xl text-foam sm:text-2xl">
            {chapter.text}
          </p>
        </div>
      </div>
    </section>
  );
}
