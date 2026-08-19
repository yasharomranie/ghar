"use client";

import { useSyncExternalStore } from "react";

const QUERY = "(prefers-reduced-motion: reduce)";

function subscribe(callback: () => void) {
  const mql = window.matchMedia(QUERY);
  mql.addEventListener("change", callback);
  return () => mql.removeEventListener("change", callback);
}

function getSnapshot() {
  return window.matchMedia(QUERY).matches;
}

function getServerSnapshot() {
  return false;
}

/**
 * Tracks `prefers-reduced-motion` via useSyncExternalStore — the React-
 * recommended way to read live browser/media-query state without a
 * setState-in-effect cascade or an SSR/client hydration mismatch.
 * Every scroll-driven / GSAP-heavy component must read this and fall back
 * to a static, fully-readable final state (design brief §22, §7).
 */
export function useReducedMotion(): boolean {
  return useSyncExternalStore(subscribe, getSnapshot, getServerSnapshot);
}
