"use client";

import { RockBackdrop } from "@/components/visuals/RockBackdrop";
import { ParticleField } from "@/components/visuals/ParticleField";

export function Scene01Hero() {
  return (
    <section
      id="hero"
      aria-label="ورود به غار"
      className="relative flex h-[100svh] min-h-[560px] w-full items-center justify-center overflow-hidden"
    >
      <RockBackdrop glow="center" intensity="dim" />
      <ParticleField variant="dust" count={26} className="absolute inset-0" />

      <div className="relative z-10 mx-auto flex max-w-3xl flex-col items-center gap-8 px-6 text-center">
        <p className="font-display text-xs uppercase tracking-[0.4em] text-turquoise-soft">
          A Living Aquarium Hidden Inside the Earth
        </p>

        <h1 className="text-balance font-display text-4xl font-semibold leading-[1.35] text-foam sm:text-5xl md:text-6xl">
          جایی که سنگ، آب و زندگی
          <br />
          به هم می‌رسند
        </h1>

        <p className="text-balance text-base text-foam-dim sm:text-lg">
          سفری به قلب یک غار زنده
        </p>

        <a
          href="#darkness"
          data-cursor="کشف"
          className="group relative mt-4 inline-flex items-center gap-3 rounded-full border border-foam/25 px-7 py-3 text-sm text-foam transition-colors hover:border-turquoise hover:text-turquoise-soft"
        >
          کشف غار
        </a>
      </div>

      <a
        href="#darkness"
        className="absolute inset-x-0 bottom-8 z-10 mx-auto flex w-fit flex-col items-center gap-2 text-foam-faint"
      >
        <span className="font-display text-[10px] uppercase tracking-[0.35em]">
          SCROLL TO EXPLORE
        </span>
        <span className="scroll-chevron h-8 w-px bg-gradient-to-b from-foam-faint to-transparent" />
      </a>

      <style>{`
        @keyframes scrollHintPulse {
          0%, 100% { opacity: 0.25; transform: scaleY(0.6); }
          50% { opacity: 0.9; transform: scaleY(1); }
        }
        .scroll-chevron { transform-origin: top; animation: scrollHintPulse 2.2s ease-in-out infinite; }
        @media (prefers-reduced-motion: reduce) {
          .scroll-chevron { animation: none; opacity: 0.6; }
        }
      `}</style>
    </section>
  );
}
