export function FishSVG({
  color,
  accent,
  flip = false,
  className,
}: {
  color: string;
  accent: string;
  flip?: boolean;
  className?: string;
}) {
  return (
    <svg
      viewBox="0 0 100 48"
      className={className}
      style={{ transform: flip ? "scaleX(-1)" : undefined }}
      aria-hidden="true"
    >
      <path
        d="M4 24 C 16 4, 46 2, 62 12 C 74 4, 92 10, 98 24 C 92 38, 74 44, 62 36 C 46 46, 16 44, 4 24 Z"
        fill={color}
        opacity="0.92"
      />
      <path d="M62 12 L98 24 L62 36 Z" fill={accent} opacity="0.85" />
      <path d="M18 8 C 10 2, 4 6, 2 14 C 10 14, 16 12, 18 8 Z" fill={accent} opacity="0.7" />
      <circle cx="20" cy="21" r="2.4" fill="var(--color-void)" />
      <circle cx="20.7" cy="20.3" r="0.9" fill="var(--color-foam)" />
    </svg>
  );
}
