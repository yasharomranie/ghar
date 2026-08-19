"use client";

import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import Image from "next/image";
import { useRef } from "react";
import { RevealText } from "@/components/RevealText";
import { useReducedMotion } from "@/hooks/useReducedMotion";

/**
 * SCENE 02 — INTO THE DARKNESS
 * Foreground/background rock layers drift at different scroll speeds
 * (parallax-scroll preset, "Standard" tier) to sell depth as the camera
 * moves inward.
 */
export function Scene02IntoDarkness() {
  const sectionRef = useRef<HTMLElement>(null);
  const reducedMotion = useReducedMotion();

  useGSAP(
    () => {
      if (reducedMotion || !sectionRef.current) return;
      gsap.utils
        .toArray<HTMLElement>(".parallax-layer", sectionRef.current)
        .forEach((layer, i) => {
          gsap.to(layer, {
            yPercent: (i + 1) * -10,
            ease: "none",
            scrollTrigger: {
              trigger: sectionRef.current,
              start: "top bottom",
              end: "bottom top",
              scrub: 0.6,
            },
          });
        });
    },
    { scope: sectionRef, dependencies: [reducedMotion] },
  );

  return (
    <section
      id="darkness"
      ref={sectionRef}
      aria-label="ورود به تاریکی"
      className="relative flex h-[130vh] w-full items-center justify-center overflow-hidden bg-void"
    >
      <div className="parallax-layer absolute inset-0 scale-110" aria-hidden="true">
        <Image
          src="/images/cave/darkness-threshold.webp"
          alt="نمای عبور از میان صخره‌ها به سمت ردیفی از آکواریوم‌های نورانی در دوردست"
          fill
          sizes="100vw"
          className="object-cover"
        />
        <div className="absolute inset-0 bg-void/60" />
      </div>
      <div
        className="parallax-layer absolute inset-x-0 bottom-0 h-[55%]"
        style={{
          background:
            "linear-gradient(0deg, var(--color-stone-900) 0%, transparent 100%)",
          clipPath:
            "polygon(0% 100%, 0% 30%, 12% 45%, 24% 20%, 38% 50%, 52% 15%, 68% 42%, 82% 10%, 100% 38%, 100% 100%)",
        }}
        aria-hidden="true"
      />
      <div
        className="parallax-layer absolute inset-x-0 top-0 h-[40%]"
        style={{
          background: "linear-gradient(180deg, var(--color-void) 0%, transparent 100%)",
          clipPath:
            "polygon(0% 0%, 100% 0%, 100% 55%, 84% 30%, 70% 60%, 55% 25%, 40% 58%, 26% 22%, 10% 50%, 0% 35%)",
        }}
        aria-hidden="true"
      />

      <RevealText className="relative z-10 px-6 text-center">
        <p className="text-balance font-display text-2xl leading-relaxed text-foam sm:text-3xl">
          همه‌چیز از دل سنگ آغاز می‌شود.
        </p>
      </RevealText>
    </section>
  );
}
