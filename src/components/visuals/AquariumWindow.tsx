import { FishSchool } from "./FishSchool";
import { ParticleField } from "./ParticleField";
import { cn } from "@/lib/utils";
import type { Species } from "@/data/species";

function mapRange(value: number, inMin: number, inMax: number) {
  return Math.max(0, Math.min(1, (value - inMin) / (inMax - inMin)));
}

/**
 * The "Image Completion" window (design brief §6): a single irregular
 * rock-cut opening that assembles itself in layers — stone → water → light
 * → plants → fish — driven by `reveal` (0 → 1, typically scroll progress).
 */
export function AquariumWindow({
  reveal,
  fish,
  clipId = "cave-window-a",
  className,
}: {
  reveal: number;
  fish: Species[];
  clipId?: "cave-window-a" | "cave-window-b";
  className?: string;
}) {
  const water = mapRange(reveal, 0.0, 0.35);
  const light = mapRange(reveal, 0.35, 0.6);
  const plants = mapRange(reveal, 0.5, 0.75);
  const fishReveal = mapRange(reveal, 0.7, 1.0);

  return (
    <div className={cn("relative", className)}>
      {/* rock frame */}
      <div
        className="absolute inset-0"
        style={{
          background:
            "radial-gradient(120% 100% at 30% 20%, var(--color-stone-700), var(--color-stone-900) 70%)",
          boxShadow: "inset 0 0 60px rgba(0,0,0,0.6)",
        }}
      />

      {/* window opening */}
      <div className="absolute inset-[6%]" style={{ clipPath: `url(#${clipId})` }}>
        {/* water body */}
        <div
          className="absolute inset-0 transition-opacity duration-700"
          style={{
            opacity: Math.max(water, reveal > 0 ? 0.15 : 0),
            background:
              "linear-gradient(200deg, var(--color-ocean-700) 0%, var(--color-ocean-900) 65%, var(--color-void) 100%)",
          }}
        />

        {/* volumetric light shaft + caustics */}
        <div
          className="absolute inset-0 transition-opacity duration-700"
          style={{
            opacity: light,
            background:
              "radial-gradient(38% 60% at 70% 0%, rgba(79,216,196,0.55), transparent 70%)",
            mixBlendMode: "screen",
          }}
        />
        <div
          className="absolute inset-0 transition-opacity duration-700"
          style={{
            opacity: light * 0.7,
            backgroundImage:
              "repeating-linear-gradient(115deg, rgba(143,233,219,0.12) 0px, rgba(143,233,219,0.12) 2px, transparent 2px, transparent 14px)",
          }}
        />

        {/* plants */}
        <svg
          className="absolute bottom-0 inset-x-0 h-[45%] w-full transition-opacity duration-700"
          style={{ opacity: plants }}
          viewBox="0 0 200 100"
          preserveAspectRatio="none"
          aria-hidden="true"
        >
          {[18, 48, 82, 120, 156, 182].map((x, i) => (
            <path
              key={x}
              d={`M${x},100 C ${x - 6},70 ${x + (i % 2 ? 10 : -10)},50 ${x},0`}
              stroke="var(--color-moss-500)"
              strokeWidth={3}
              fill="none"
              opacity={0.75}
            />
          ))}
        </svg>

        {/* fish */}
        <FishSchool fish={fish} reveal={fishReveal} className="absolute inset-0" />

        {/* bubbles */}
        <ParticleField
          variant="bubble"
          count={10}
          className="absolute inset-0"
          key={`bubbles-${Math.round(fishReveal * 10)}`}
        />

        {/* glass sheen */}
        <div
          className="absolute inset-0 pointer-events-none"
          style={{
            background:
              "linear-gradient(115deg, rgba(244,239,227,0.08) 0%, transparent 30%, transparent 70%, rgba(244,239,227,0.05) 100%)",
          }}
        />
      </div>

      {/* rock rim shadow on top of the window edge for depth */}
      <div
        className="absolute inset-[6%] pointer-events-none"
        style={{
          clipPath: `url(#${clipId})`,
          boxShadow: "inset 0 0 24px 10px rgba(0,0,0,0.55)",
        }}
      />
    </div>
  );
}
