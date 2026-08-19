/**
 * Shared SVG <clipPath> defs for the irregular, hand-carved rock openings
 * seen in the reference photos. Rendered once (hidden) and referenced by
 * id from any element via `clip-path: url(#cave-window-*)`.
 * objectBoundingBox units mean each clip scales with its own container.
 */
export function CaveWindowClipDefs() {
  return (
    <svg width="0" height="0" className="absolute" aria-hidden="true">
      <defs>
        <clipPath id="cave-window-a" clipPathUnits="objectBoundingBox">
          <path d="M0.03,0.42 C0.01,0.22 0.09,0.08 0.24,0.05 C0.38,0.01 0.55,0 0.7,0.04 C0.88,0.08 0.98,0.2 0.97,0.4 C0.99,0.58 0.96,0.78 0.85,0.9 C0.72,1.0 0.5,1.0 0.32,0.95 C0.14,0.9 0.02,0.75 0.01,0.58 C0,0.53 0.02,0.47 0.03,0.42 Z" />
        </clipPath>
        <clipPath id="cave-window-b" clipPathUnits="objectBoundingBox">
          <path d="M0.05,0.5 C0.02,0.3 0.12,0.1 0.3,0.06 C0.5,0.02 0.7,0 0.85,0.1 C0.97,0.18 1.0,0.35 0.96,0.52 C1.0,0.68 0.94,0.85 0.78,0.94 C0.6,1.0 0.38,0.98 0.22,0.9 C0.08,0.82 0.02,0.66 0.05,0.5 Z" />
        </clipPath>
      </defs>
    </svg>
  );
}
