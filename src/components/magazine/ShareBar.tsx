"use client";

import { useState } from "react";
import Link from "next/link";

/** Copy-link control + a way back to the listing, below the article body. */
export function ShareBar({ slug }: { slug: string }) {
  const [copied, setCopied] = useState(false);

  async function handleCopy() {
    try {
      const url = `${window.location.origin}/magazine/${slug}`;
      await navigator.clipboard.writeText(url);
      setCopied(true);
      window.setTimeout(() => setCopied(false), 2000);
    } catch {
      // Clipboard API unavailable (permissions, insecure context, …) — the
      // button simply stays a no-op rather than throwing at the reader.
    }
  }

  return (
    <div className="flex flex-wrap items-center justify-between gap-4 border-y border-ink/10 py-5">
      <Link href="/magazine" className="text-sm text-ink-dim transition-colors hover:text-ink">
        → بازگشت به مجله
      </Link>
      <button
        type="button"
        onClick={handleCopy}
        className="rounded-full border border-ink/25 px-4 py-2 text-sm text-ink transition-colors hover:bg-ink/5"
      >
        {copied ? "لینک کپی شد" : "کپی لینک مقاله"}
      </button>
    </div>
  );
}
