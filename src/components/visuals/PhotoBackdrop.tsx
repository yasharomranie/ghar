import Image from "next/image";
import { cn } from "@/lib/utils";

/**
 * Real cave/aquarium photography as a scene backdrop, with the same
 * legibility scrim + directional glow treatment RockBackdrop used for its
 * generative gradients — so real photos slot into the existing scenes
 * without changing how text sits on top of them.
 */
export function PhotoBackdrop({
  src,
  alt,
  glow = "top",
  intensity = "normal",
  priority = false,
  className,
}: {
  src: string;
  alt: string;
  glow?: "top" | "left" | "right" | "center" | "none";
  intensity?: "normal" | "dim" | "bright";
  priority?: boolean;
  className?: string;
}) {
  const glowPosition: Record<string, string> = {
    top: "50% -10%",
    left: "-10% 40%",
    right: "110% 40%",
    center: "50% 50%",
    none: "50% 50%",
  };

  const glowOpacity = intensity === "bright" ? 0.5 : intensity === "dim" ? 0.12 : 0.24;

  return (
    <div className={cn("absolute inset-0 overflow-hidden", className)} aria-hidden="true">
      <Image
        src={src}
        alt={alt}
        fill
        priority={priority}
        sizes="100vw"
        className="object-cover"
      />
      {/* darken + cool the photo slightly so foam-colored type stays legible */}
      <div className="absolute inset-0 bg-void/55" />
      <div
        className="absolute inset-0"
        style={{
          background:
            "radial-gradient(120% 90% at 20% 15%, transparent 0%, var(--color-void) 92%)",
          opacity: 0.55,
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
          background: "linear-gradient(180deg, rgba(5,7,8,0) 0%, rgba(5,7,8,0.75) 100%)",
        }}
      />
    </div>
  );
}
