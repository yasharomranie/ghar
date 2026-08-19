"use client";

import { useGSAP } from "@gsap/react";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import Image from "next/image";
import { useRef, useState } from "react";
import { ParticleField } from "@/components/visuals/ParticleField";
import { useReducedMotion } from "@/hooks/useReducedMotion";
import { cn } from "@/lib/utils";

const captions = [
  {
    title: "نوری که از دل آب می‌گذرد",
    text: "هر پرتو، مسیر خودش را در تاریکی پیدا می‌کند.",
  },
  {
    title: "هر آکواریوم، دنیای خودش",
    text: "ده‌ها متر سنگ، ده‌ها دنیای زنده‌ی جداگانه.",
  },
  {
    title: "سکوت صخره، همهمه‌ی حیات",
    text: "بیرون سکوت است؛ پشت شیشه، زندگی در جریان است.",
  },
  {
    title: "یک دنیای کامل",
    text: "این تاریکی، حالا خانه‌ی موجوداتی زنده است.",
  },
];

/**
 * SCENE 05 — THE LIVING WORLD (brief §5/§7/§13)
 * A real photograph of the aquarium corridor stays pinned/sticky while
 * short captions step through on top of it — position:sticky storytelling:
 * the "page" feels still, the story inside keeps moving.
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
      <div className="sticky top-0 flex h-[100svh] w-full items-center justify-center overflow-hidden">
        <Image
          src="/images/cave/corridor-panorama-bright.webp"
          alt="راهروی غار با چند آکواریوم نورانی در دل صخره، پر از ماهی‌های رنگارنگ"
          fill
          sizes="100vw"
          className="object-cover"
        />
        <div className="absolute inset-0 bg-void/45" />
        <div
          className="absolute inset-0"
          style={{
            background: "linear-gradient(0deg, rgba(5,7,8,0.85) 0%, rgba(5,7,8,0.25) 45%, rgba(5,7,8,0.55) 100%)",
          }}
        />
        <ParticleField variant="bubble" count={14} className="absolute inset-0" />

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
