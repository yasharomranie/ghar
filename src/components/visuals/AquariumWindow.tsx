import Image from "next/image";
import { ParticleField } from "./ParticleField";
import { cn } from "@/lib/utils";

function mapRange(value: number, inMin: number, inMax: number) {
  return Math.max(0, Math.min(1, (value - inMin) / (inMax - inMin)));
}

/**
 * The "Image Completion" window (design brief §6): a real photograph of one
 * of the rock-cut aquarium windows, clipped to the same irregular opening
 * seen in the reference photos, and revealed — literally coming into focus
 * out of the dark stone — strictly as a function of `reveal` (0 → 1,
 * typically scroll progress), never on a timer.
 */
export function AquariumWindow({
  reveal,
  photoSrc,
  photoAlt,
  clipId = "cave-window-a",
  priority = false,
  className,
}: {
  reveal: number;
  photoSrc: string;
  photoAlt: string;
  clipId?: "cave-window-a" | "cave-window-b";
  priority?: boolean;
  className?: string;
}) {
  // 0 -> a near-black, fully out-of-focus slab of stone
  // 1 -> the photo, sharp, bright and true to color
  const focus = mapRange(reveal, 0, 1);
  const brightness = 0.1 + focus * 0.9;
  const saturation = 0.15 + focus * 0.85;
  const blurPx = (1 - focus) * 16;
  const lightPulse = mapRange(reveal, 0.3, 0.65);
  const bubbleReveal = mapRange(reveal, 0.55, 1);

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
      <div className="absolute inset-[6%] overflow-hidden" style={{ clipPath: `url(#${clipId})` }}>
        <div
          className="absolute inset-0 scale-110 transition-[filter] duration-150"
          style={{
            filter: `brightness(${brightness}) saturate(${saturation}) blur(${blurPx}px)`,
          }}
        >
          <Image src={photoSrc} alt={photoAlt} fill priority={priority} sizes="90vw" className="object-cover" />
        </div>

        {/* a warm/cool light pulse as the scene "switches on" mid-reveal */}
        <div
          className="absolute inset-0"
          style={{
            opacity: lightPulse * (1 - lightPulse) * 3.2,
            background:
              "radial-gradient(45% 60% at 65% 10%, rgba(79,216,196,0.5), transparent 70%)",
            mixBlendMode: "screen",
          }}
        />

        {bubbleReveal > 0 && (
          <ParticleField
            variant="bubble"
            count={10}
            className="absolute inset-0"
            key={`bubbles-${Math.round(bubbleReveal * 10)}`}
          />
        )}

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
