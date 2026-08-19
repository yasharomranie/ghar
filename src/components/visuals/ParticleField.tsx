"use client";

import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { useMemo, useRef } from "react";
import { useReducedMotion } from "@/hooks/useReducedMotion";
import { useIsCoarsePointer } from "@/hooks/useIsCoarsePointer";

/**
 * Slow-drifting dust / bubble particles suspended in the water or cave air.
 * Count is capped hard on touch devices and skipped under reduced-motion
 * (design brief §20 Mobile Experience, §21 Performance).
 */
export function ParticleField({
  count = 24,
  variant = "dust",
  className,
}: {
  count?: number;
  variant?: "dust" | "bubble";
  className?: string;
}) {
  const ref = useRef<HTMLDivElement>(null);
  const reducedMotion = useReducedMotion();
  const coarsePointer = useIsCoarsePointer();

  const total = coarsePointer ? Math.round(count * 0.4) : count;

  const particles = useMemo(
    () =>
      Array.from({ length: total }, (_, i) => ({
        id: i,
        left: Math.round((i * 137.5) % 100),
        top: Math.round((i * 71.3) % 100),
        size: variant === "bubble" ? 3 + (i % 4) : 1 + (i % 3),
        delay: (i % 10) * 0.4,
      })),
    [total, variant],
  );

  useGSAP(
    () => {
      if (reducedMotion || !ref.current) return;
      const items = gsap.utils.toArray<HTMLElement>(".particle", ref.current);
      items.forEach((el, i) => {
        const duration = gsap.utils.random(6, 14);
        const drift = gsap.utils.random(-40, 40);
        gsap.to(el, {
          y: variant === "bubble" ? -gsap.utils.random(120, 260) : drift,
          x: variant === "bubble" ? drift * 0.3 : drift,
          opacity: gsap.utils.random(0.15, 0.55),
          duration,
          repeat: -1,
          yoyo: variant === "dust",
          ease: "sine.inOut",
          delay: i * 0.15,
        });
      });
    },
    { scope: ref, dependencies: [reducedMotion, total, variant] },
  );

  return (
    <div ref={ref} className={className} aria-hidden="true">
      {particles.map((p) => (
        <span
          key={p.id}
          className="particle absolute rounded-full"
          style={{
            left: `${p.left}%`,
            top: `${p.top}%`,
            width: p.size,
            height: p.size,
            background:
              variant === "bubble"
                ? "radial-gradient(circle, rgba(244,239,227,0.8), rgba(244,239,227,0.05))"
                : "var(--color-turquoise-soft)",
            opacity: 0.25,
          }}
        />
      ))}
    </div>
  );
}
