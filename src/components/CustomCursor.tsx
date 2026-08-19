"use client";

import { useEffect, useRef, useState } from "react";
import { useIsCoarsePointer } from "@/hooks/useIsCoarsePointer";
import { useReducedMotion } from "@/hooks/useReducedMotion";

/**
 * Minimal custom cursor — a dot that grows into a labelled circle over
 * `[data-cursor]` elements. Desktop / fine-pointer only (design brief §18);
 * disabled outright under reduced motion since it's purely decorative.
 */
export function CustomCursor() {
  const dotRef = useRef<HTMLDivElement>(null);
  const [label, setLabel] = useState<string | null>(null);
  const [active, setActive] = useState(false);
  const coarsePointer = useIsCoarsePointer();
  const reducedMotion = useReducedMotion();
  const disabled = coarsePointer || reducedMotion;

  useEffect(() => {
    if (disabled) return;

    let raf = 0;
    let x = window.innerWidth / 2;
    let y = window.innerHeight / 2;
    let renderedX = x;
    let renderedY = y;

    const onMove = (e: PointerEvent) => {
      x = e.clientX;
      y = e.clientY;
      const target = e.target as HTMLElement;
      const cursorEl = target.closest<HTMLElement>("[data-cursor]");
      setActive(!!cursorEl);
      setLabel(cursorEl?.dataset.cursor ?? null);
    };

    const loop = () => {
      renderedX += (x - renderedX) * 0.25;
      renderedY += (y - renderedY) * 0.25;
      if (dotRef.current) {
        dotRef.current.style.transform = `translate(${renderedX}px, ${renderedY}px) translate(-50%, -50%)`;
      }
      raf = requestAnimationFrame(loop);
    };

    window.addEventListener("pointermove", onMove);
    raf = requestAnimationFrame(loop);

    return () => {
      window.removeEventListener("pointermove", onMove);
      cancelAnimationFrame(raf);
    };
  }, [disabled]);

  if (disabled) return null;

  return (
    <div
      ref={dotRef}
      className="pointer-events-none fixed left-0 top-0 z-[100] flex items-center justify-center rounded-full mix-blend-difference transition-[width,height] duration-200 ease-out"
      style={{
        width: active ? 72 : 8,
        height: active ? 72 : 8,
        background: "var(--color-foam)",
      }}
      aria-hidden="true"
    >
      {label && (
        <span className="font-display text-[10px] font-medium uppercase tracking-widest text-void">
          {label}
        </span>
      )}
    </div>
  );
}
