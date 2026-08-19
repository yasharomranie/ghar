"use client";

import { useState } from "react";
import { FishSVG } from "@/components/visuals/FishSVG";
import { RevealText } from "@/components/RevealText";
import { species } from "@/data/species";
import { cn } from "@/lib/utils";

/** SCENE 06 — SPECIES: each entry is a small interactive scene of its own —
 * hover moves the fish, lights it, and reveals its details (brief §9). */
export function Scene06Species() {
  const [activeId, setActiveId] = useState<string | null>(null);

  return (
    <section id="species" aria-label="گونه‌های آبزی" className="relative w-full bg-void py-28">
      <div className="mx-auto max-w-6xl px-6">
        <RevealText className="mb-16 text-center">
          <p className="font-display text-xs uppercase tracking-[0.4em] text-turquoise-soft">
            SPECIES
          </p>
          <h2 className="mt-4 text-balance font-display text-3xl font-semibold text-foam sm:text-4xl">
            ساکنان این تاریکی
          </h2>
        </RevealText>

        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
          {species.map((s) => {
            const active = activeId === s.id;
            return (
              <RevealText key={s.id} delay={0.05}>
                <button
                  type="button"
                  data-cursor="مشاهده"
                  onMouseEnter={() => setActiveId(s.id)}
                  onMouseLeave={() => setActiveId(null)}
                  onFocus={() => setActiveId(s.id)}
                  onBlur={() => setActiveId(null)}
                  className={cn(
                    "group relative flex w-full items-center gap-6 overflow-hidden rounded-2xl border border-foam/10 p-6 text-start transition-colors duration-500",
                    active ? "border-turquoise-dim bg-stone-800/60" : "bg-stone-900/40",
                  )}
                >
                  <div
                    className="absolute inset-0 -z-10 transition-opacity duration-500"
                    style={{
                      background:
                        "radial-gradient(60% 80% at 15% 50%, var(--color-ocean-700), transparent 70%)",
                      opacity: active ? 0.7 : 0,
                    }}
                    aria-hidden="true"
                  />

                  <div
                    className={cn(
                      "w-24 shrink-0 transition-transform duration-700 ease-out",
                      active ? "translate-x-1 scale-110" : "scale-100",
                    )}
                  >
                    <FishSVG color={s.color} accent={s.accent} className="w-full h-auto" />
                  </div>

                  <div className="min-w-0">
                    <h3 className="font-display text-lg font-medium text-foam">{s.name}</h3>
                    <p className="text-xs italic text-foam-faint">{s.scientificName}</p>
                    <p
                      className={cn(
                        "mt-3 max-w-sm text-sm text-foam-dim transition-opacity duration-500",
                        active ? "opacity-100" : "opacity-70",
                      )}
                    >
                      {s.description}
                    </p>
                    <dl className="mt-4 flex flex-wrap gap-x-6 gap-y-1 text-xs text-foam-faint">
                      <div className="flex gap-1">
                        <dt className="text-turquoise-soft">زیستگاه:</dt>
                        <dd>{s.habitat}</dd>
                      </div>
                      <div className="flex gap-1">
                        <dt className="text-turquoise-soft">ویژگی:</dt>
                        <dd>{s.trait}</dd>
                      </div>
                    </dl>
                  </div>
                </button>
              </RevealText>
            );
          })}
        </div>
      </div>
    </section>
  );
}
