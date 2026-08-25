"use client";

import { useSyncExternalStore } from "react";
import { THEME_STORAGE_KEY } from "@/lib/theme";
import { cn } from "@/lib/utils";

type Theme = "dark" | "light";

function subscribe(callback: () => void) {
  const observer = new MutationObserver(callback);
  observer.observe(document.documentElement, { attributes: true, attributeFilter: ["data-theme"] });
  return () => observer.disconnect();
}

function getSnapshot(): Theme {
  return document.documentElement.getAttribute("data-theme") === "light" ? "light" : "dark";
}

function getServerSnapshot(): Theme {
  return "dark";
}

/**
 * Light/dark toggle for the site chrome (nav, footer, magazine + article
 * pages) — the home page's cinematic scenes stay permanently dark on
 * purpose (see globals.css), so this only ever flips `data-theme` on
 * <html>, which the theme-aware tokens react to.
 *
 * Reads the live `data-theme` attribute via useSyncExternalStore (same
 * pattern as useReducedMotion) rather than mirroring it into local state —
 * that keeps the server/first-client-paint render identical (both assume
 * "dark", matching the anti-flash script's default in src/lib/theme.ts)
 * with no synchronous setState-in-effect.
 */
export function ThemeToggle({ className }: { className?: string }) {
  const theme = useSyncExternalStore(subscribe, getSnapshot, getServerSnapshot);
  const isLight = theme === "light";

  function toggle() {
    const next: Theme = isLight ? "dark" : "light";
    document.documentElement.setAttribute("data-theme", next);
    try {
      localStorage.setItem(THEME_STORAGE_KEY, next);
    } catch {
      // localStorage unavailable (private mode, blocked storage, …) — the
      // toggle still works for this page view, it just won't persist.
    }
    const meta = document.querySelector('meta[name="theme-color"]');
    meta?.setAttribute("content", next === "light" ? "#f2f5f4" : "#050708");
  }

  return (
    <button
      type="button"
      onClick={toggle}
      aria-pressed={isLight}
      aria-label={isLight ? "فعال‌سازی حالت تاریک" : "فعال‌سازی حالت روشن"}
      className={cn(
        "flex h-9 w-9 items-center justify-center rounded-full border border-[var(--nav-border)]/25 text-[var(--nav-text)] transition-colors hover:border-accent hover:text-accent-soft",
        className,
      )}
    >
      {isLight ? (
        <svg viewBox="0 0 24 24" className="h-4 w-4" aria-hidden="true">
          <path
            d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.36-6.36-1.42 1.42M7.05 16.95l-1.41 1.41m0-12.72 1.41 1.42m9.9 9.9 1.42 1.41M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"
            fill="none"
            stroke="currentColor"
            strokeWidth="1.7"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
        </svg>
      ) : (
        <svg viewBox="0 0 24 24" className="h-4 w-4" aria-hidden="true">
          <path
            d="M20 14.5A8.5 8.5 0 0 1 9.5 4a8.5 8.5 0 1 0 10.5 10.5Z"
            fill="none"
            stroke="currentColor"
            strokeWidth="1.7"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
        </svg>
      )}
    </button>
  );
}
