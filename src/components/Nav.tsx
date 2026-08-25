"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import { AnimatePresence, motion } from "framer-motion";
import { cn } from "@/lib/utils";
import { scenes } from "@/data/scenes";
import { ThemeToggle } from "@/components/ThemeToggle";

const navScenes = scenes.filter((s) => s.navLabel);

export function Nav() {
  const [scrolled, setScrolled] = useState(false);
  const [open, setOpen] = useState(false);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 40);
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  return (
    <header
      className={cn(
        "fixed inset-x-0 top-0 z-50 transition-[background,border-color] duration-500",
        scrolled ? "glass-nav" : "border-b border-transparent bg-transparent",
      )}
    >
      <div className="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 md:px-10">
        <Link
          href="/"
          className="font-display text-lg font-semibold tracking-wide text-[var(--nav-text)]"
        >
          غار <span className="text-turquoise">زنده</span>
        </Link>

        <nav className="hidden items-center gap-8 md:flex" aria-label="پیمایش اصلی">
          {navScenes.map((s) => (
            <Link
              key={s.id}
              href={`/#${s.id}`}
              className="text-sm text-[var(--nav-text-dim)] transition-colors hover:text-[var(--nav-text)]"
            >
              {s.navLabel}
            </Link>
          ))}
          <Link
            href="/magazine"
            className="text-sm text-[var(--nav-text-dim)] transition-colors hover:text-[var(--nav-text)]"
          >
            مجله خبری
          </Link>
          <Link
            href="/#visit"
            className="rounded-full border border-[var(--nav-border)]/25 px-4 py-2 text-sm text-[var(--nav-text)] transition-colors hover:bg-[var(--nav-text)]/5"
          >
            برنامه بازدید
          </Link>
          <ThemeToggle />
        </nav>

        <div className="flex items-center gap-2 md:hidden">
          <ThemeToggle />
          <button
            type="button"
            onClick={() => setOpen((v) => !v)}
            className="flex h-10 w-10 items-center justify-center rounded-full border border-[var(--nav-border)]/15 text-[var(--nav-text)]"
            aria-expanded={open}
            aria-label={open ? "بستن منو" : "باز کردن منو"}
          >
            <span className="relative block h-3 w-4">
              <span
                className={cn(
                  "absolute inset-x-0 top-0 h-px bg-current transition-transform",
                  open && "translate-y-[6px] rotate-45",
                )}
              />
              <span
                className={cn(
                  "absolute inset-x-0 bottom-0 h-px bg-current transition-transform",
                  open && "-translate-y-[6px] -rotate-45",
                )}
              />
            </span>
          </button>
        </div>
      </div>

      <AnimatePresence>
        {open && (
          <motion.nav
            initial={{ height: 0, opacity: 0 }}
            animate={{ height: "auto", opacity: 1 }}
            exit={{ height: 0, opacity: 0 }}
            transition={{ duration: 0.3, ease: [0.22, 1, 0.36, 1] }}
            className="glass-nav overflow-hidden md:hidden"
            aria-label="پیمایش موبایل"
          >
            <div className="flex flex-col gap-1 px-6 pb-6">
              {navScenes.map((s) => (
                <Link
                  key={s.id}
                  href={`/#${s.id}`}
                  onClick={() => setOpen(false)}
                  className="py-3 text-base text-[var(--nav-text-dim)]"
                >
                  {s.navLabel}
                </Link>
              ))}
              <Link
                href="/magazine"
                onClick={() => setOpen(false)}
                className="py-3 text-base text-[var(--nav-text-dim)]"
              >
                مجله خبری
              </Link>
            </div>
          </motion.nav>
        )}
      </AnimatePresence>
    </header>
  );
}
