"use client";

import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { useRef, useState } from "react";
import { AquariumWindow } from "@/components/visuals/AquariumWindow";
import { CaveWindowClipDefs } from "@/components/visuals/CaveWindowClip";
import { useReducedMotion } from "@/hooks/useReducedMotion";
import { species } from "@/data/species";

const phases = [
  { at: 0, label: "سنگ" },
  { at: 0.22, label: "آب" },
  { at: 0.45, label: "نور" },
  { at: 0.62, label: "گیاهان" },
  { at: 0.8, label: "ماهی‌ها" },
];

function currentPhase(reveal: number) {
  let label = phases[0].label;
  for (const p of phases) {
    if (reveal >= p.at) label = p.label;
  }
  return label;
}

/**
 * SCENE 04 — AQUARIUM REVEAL ("Image Completion", brief §5/§6)
 * A tall pinned track: the AquariumWindow assembles itself — stone → water
 * → light → plants → fish — strictly as a function of scroll progress
 * inside the pin, never on a timer.
 */
export function Scene04AquariumReveal() {
  const trackRef = useRef<HTMLElement>(null);
  const [reveal, setReveal] = useState(0);
  const lastStep = useRef(-1);
  const reducedMotion = useReducedMotion();

  useGSAP(
    () => {
      if (reducedMotion || !trackRef.current) return;

      const trigger = ScrollTrigger.create({
        trigger: trackRef.current,
        start: "top top",
        end: "bottom bottom",
        scrub: 0.5,
        onUpdate: (self) => {
          const step = Math.round(self.progress * 100);
          if (step !== lastStep.current) {
            lastStep.current = step;
            setReveal(step / 100);
          }
        },
      });

      return () => trigger.kill();
    },
    { scope: trackRef, dependencies: [reducedMotion] },
  );

  const effectiveReveal = reducedMotion ? 1 : reveal;

  return (
    <section
      id="aquarium"
      ref={trackRef}
      aria-label="آکواریوم درون صخره"
      className="relative h-[320vh] w-full bg-void"
    >
      <CaveWindowClipDefs />
      <div className="sticky top-0 flex h-[100svh] w-full items-center justify-center overflow-hidden">
        <AquariumWindow
          reveal={effectiveReveal}
          fish={species}
          className="aspect-[4/3] w-[min(88vw,720px)]"
        />

        <div className="pointer-events-none absolute inset-x-0 bottom-10 z-10 flex flex-col items-center gap-3 px-6 text-center">
          <span className="font-display text-[11px] uppercase tracking-[0.4em] text-turquoise-soft">
            {currentPhase(effectiveReveal)}
          </span>
          <p
            className="text-balance font-display text-xl text-foam transition-opacity duration-500 sm:text-2xl"
            style={{ opacity: effectiveReveal > 0.75 ? 1 : 0 }}
          >
            آکواریومی که تصویرش را با اسکرول تو کامل می‌کند.
          </p>
        </div>
      </div>
    </section>
  );
}
