"use client";

import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { useMemo, useRef } from "react";
import { FishSVG } from "./FishSVG";
import { useReducedMotion } from "@/hooks/useReducedMotion";
import { useIsCoarsePointer } from "@/hooks/useIsCoarsePointer";
import type { Species } from "@/data/species";

interface FishSchoolProps {
  fish: Species[];
  /** 0 → 1 how "revealed" the fish are (opacity/scale), driven by scroll */
  reveal?: number;
  className?: string;
}

/**
 * Loose swimming fish, each on its own randomized GSAP timeline —
 * deliberately non-uniform paths/durations so nothing reads as a
 * mechanical loop (design brief §5 / §7 Fish Interaction).
 */
export function FishSchool({ fish, reveal = 1, className }: FishSchoolProps) {
  const containerRef = useRef<HTMLDivElement>(null);
  const reducedMotion = useReducedMotion();
  const coarsePointer = useIsCoarsePointer();

  // Fewer concurrent swimmers on touch devices (§20 Mobile Experience)
  const visibleFish = useMemo(
    () => (coarsePointer ? fish.slice(0, Math.min(3, fish.length)) : fish),
    [fish, coarsePointer],
  );

  useGSAP(
    () => {
      if (reducedMotion || !containerRef.current) return;

      const items = gsap.utils.toArray<HTMLElement>(".swim-fish", containerRef.current);

      items.forEach((el, i) => {
        const startX = gsap.utils.random(-10, 10);
        const startY = gsap.utils.random(-8, 8);
        const depth = gsap.utils.random(0.7, 1.15);
        gsap.set(el, { xPercent: startX, yPercent: startY, scale: depth });

        const tl = gsap.timeline({
          repeat: -1,
          yoyo: true,
          defaults: { ease: "sine.inOut" },
        });

        const travel = gsap.utils.random(18, 34);
        const vertical = gsap.utils.random(6, 16);
        const duration = gsap.utils.random(7, 13);
        const direction = i % 2 === 0 ? 1 : -1;

        tl.to(el, {
          xPercent: startX + travel * direction,
          yPercent: startY - vertical,
          rotate: gsap.utils.random(-4, 4),
          duration,
        }).to(el, {
          xPercent: startX,
          yPercent: startY + vertical * 0.6,
          rotate: gsap.utils.random(-3, 3),
          duration: duration * 0.85,
        });

        // stagger start so timelines never sync up visually
        tl.progress(gsap.utils.random(0, 1));
      });
    },
    { scope: containerRef, dependencies: [reducedMotion, visibleFish.length] },
  );

  return (
    <div ref={containerRef} className={className} aria-hidden="true">
      {visibleFish.map((f, i) => (
        <div
          key={f.id}
          className="swim-fish absolute"
          style={{
            top: `${18 + ((i * 61) % 60)}%`,
            insetInlineStart: `${12 + ((i * 37) % 70)}%`,
            width: `${9 - (i % 3)}%`,
            opacity: reducedMotion ? 0.85 : reveal,
            transition: "opacity 700ms var(--ease-water)",
            filter: "drop-shadow(0 2px 10px rgba(0,0,0,0.35))",
          }}
        >
          <FishSVG color={f.color} accent={f.accent} flip={i % 2 === 0} className="w-full h-auto" />
        </div>
      ))}
    </div>
  );
}
