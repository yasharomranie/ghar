"use client";

import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { useRef, useState } from "react";
import { AquariumWindow } from "@/components/visuals/AquariumWindow";
import { CaveWindowClipDefs } from "@/components/visuals/CaveWindowClip";
import { useReducedMotion } from "@/hooks/useReducedMotion";
import { species } from "@/data/species";
import { cn } from "@/lib/utils";

const captions = [
  {
    title: "از چپ به راست",
    text: "بعضی از این موجودات مسیر ثابتی ندارند؛ هر بار جور دیگری شنا می‌کنند.",
  },
  {
    title: "از عمق به سطح",
    text: "حرکتشان تصادفی و طبیعی است، نه یک انیمیشن تکراری.",
  },
  {
    title: "نور، آب را دنبال می‌کند",
    text: "با هر تغییر نور، رنگ بدن ماهی‌ها کمی تغییر می‌کند.",
  },
  {
    title: "یک دنیای کامل",
    text: "این تاریکی، حالا خانه‌ی موجوداتی زنده است.",
  },
];

/**
 * SCENE 05 — THE LIVING WORLD (Fish Interaction, brief §5/§7/§13)
 * The aquarium stays pinned/sticky while short captions step through on
 * top of it — position:sticky storytelling: the "page" feels still, the
 * story inside keeps moving.
 */
export function Scene05LivingWorld() {
  const trackRef = useRef<HTMLElement>(null);
  const [step, setStep] = useState(0);
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
          const idx = Math.min(
            captions.length - 1,
            Math.floor(self.progress * captions.length),
          );
          setStep((prev) => (prev === idx ? prev : idx));
        },
      });
      return () => trigger.kill();
    },
    { scope: trackRef, dependencies: [reducedMotion] },
  );

  return (
    <section
      id="life"
      ref={trackRef}
      aria-label="دنیای زنده"
      className="relative h-[280vh] w-full bg-void"
    >
      <CaveWindowClipDefs />
      <div className="sticky top-0 flex h-[100svh] w-full items-center justify-center overflow-hidden">
        <AquariumWindow
          reveal={1}
          fish={species}
          clipId="cave-window-b"
          className="aspect-[4/3] w-[min(92vw,780px)] opacity-90"
        />

        <div className="pointer-events-none absolute inset-x-0 top-1/2 z-10 mx-auto max-w-md -translate-y-1/2 px-6">
          {reducedMotion ? (
            <div className="flex flex-col gap-6 text-center">
              {captions.map((c) => (
                <div key={c.title}>
                  <p className="font-display text-sm text-turquoise-soft">{c.title}</p>
                  <p className="mt-1 text-foam-dim">{c.text}</p>
                </div>
              ))}
            </div>
          ) : (
            captions.map((c, i) => (
              <div
                key={c.title}
                className={cn(
                  "absolute inset-x-6 text-center transition-all duration-500",
                  step === i ? "opacity-100 translate-y-0" : "opacity-0 translate-y-3",
                )}
              >
                <p className="font-display text-sm text-turquoise-soft">{c.title}</p>
                <p className="mt-2 text-lg text-foam">{c.text}</p>
              </div>
            ))
          )}
        </div>
      </div>
    </section>
  );
}
