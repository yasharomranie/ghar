"use client";

import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { useRef, type ReactNode } from "react";
import { useReducedMotion } from "@/hooks/useReducedMotion";
import { cn } from "@/lib/utils";

/**
 * Simple viewport-triggered fade + rise wrapper (always a <div>; put the
 * real semantic heading/paragraph tag as its child). Renders the final,
 * fully-visible state immediately under reduced motion (brief §7 / §22).
 */
export function RevealText({
  children,
  className,
  delay = 0,
}: {
  children: ReactNode;
  className?: string;
  delay?: number;
}) {
  const ref = useRef<HTMLDivElement>(null);
  const reducedMotion = useReducedMotion();

  useGSAP(
    () => {
      if (reducedMotion || !ref.current) return;
      gsap.from(ref.current, {
        opacity: 0,
        y: 28,
        duration: 0.9,
        delay,
        ease: "power2.out",
        scrollTrigger: {
          trigger: ref.current,
          start: "top 82%",
          toggleActions: "play none none reverse",
        },
      });
    },
    { scope: ref, dependencies: [reducedMotion] },
  );

  return (
    <div ref={ref} className={cn(className)}>
      {children}
    </div>
  );
}
