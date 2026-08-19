import { cn } from "@/lib/utils";

/**
 * Generative rock-wall backdrop — deep stone gradients + a faint directional
 * light source, standing in for a real cave photograph/video plate.
 * Swap-ready: drop a real photo/video in the same absolutely-positioned
 * slot and this component becomes unnecessary (see brief §29).
 */
export function RockBackdrop({
  className,
  glow = "top",
  intensity = "normal",
}: {
  className?: string;
  glow?: "top" | "left" | "right" | "center" | "none";
  intensity?: "normal" | "dim" | "bright";
}) {
  const glowPosition: Record<string, string> = {
    top: "50% -10%",
    left: "-10% 40%",
    right: "110% 40%",
    center: "50% 50%",
    none: "50% 50%",
  };

  const glowOpacity = intensity === "bright" ? 0.55 : intensity === "dim" ? 0.14 : 0.28;

  return (
    <div className={cn("absolute inset-0 overflow-hidden", className)} aria-hidden="true">
      <div
        className="absolute inset-0"
        style={{
          background:
            "radial-gradient(120% 90% at 20% 15%, var(--color-stone-700) 0%, var(--color-stone-900) 45%, var(--color-void) 100%)",
        }}
      />
      <div
        className="absolute inset-0"
        style={{
          background:
            "radial-gradient(80% 60% at 80% 85%, var(--color-moss-900) 0%, transparent 60%)",
          mixBlendMode: "screen",
          opacity: 0.5,
        }}
      />
      {glow !== "none" && (
        <div
          className="absolute inset-0"
          style={{
            background: `radial-gradient(45% 35% at ${glowPosition[glow]}, var(--color-amber-glow) 0%, transparent 70%)`,
            opacity: glowOpacity,
            mixBlendMode: "screen",
          }}
        />
      )}
      <div className="noise-overlay" />
      <div
        className="absolute inset-0"
        style={{
          background:
            "linear-gradient(180deg, rgba(5,7,8,0) 0%, rgba(5,7,8,0.65) 100%)",
        }}
      />
    </div>
  );
}
