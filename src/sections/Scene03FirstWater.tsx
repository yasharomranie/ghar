import { RockBackdrop } from "@/components/visuals/RockBackdrop";
import { ParticleField } from "@/components/visuals/ParticleField";
import { RevealText } from "@/components/RevealText";

/** SCENE 03 — THE FIRST WATER: the environment lightens a little, dust and
 * droplets drift, foreshadowing the aquarium reveal that follows. */
export function Scene03FirstWater() {
  return (
    <section
      id="water"
      aria-label="نخستین آب"
      className="relative flex h-[90vh] min-h-[520px] w-full items-center justify-center overflow-hidden"
    >
      <RockBackdrop glow="right" intensity="normal" />
      <div
        className="absolute inset-0"
        style={{
          background:
            "radial-gradient(70% 60% at 50% 60%, var(--color-ocean-900) 0%, transparent 70%)",
          opacity: 0.6,
        }}
        aria-hidden="true"
      />
      <ParticleField variant="bubble" count={18} className="absolute inset-0" />
      <ParticleField variant="dust" count={16} className="absolute inset-0" />

      <RevealText className="relative z-10 max-w-xl px-6 text-center">
        <p className="text-balance font-display text-2xl leading-relaxed text-foam sm:text-3xl">
          اما در دل این تاریکی،
          <br />
          زندگی جریان دارد.
        </p>
      </RevealText>
    </section>
  );
}
