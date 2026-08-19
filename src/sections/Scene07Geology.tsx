import { RevealText } from "@/components/RevealText";
import { RockBackdrop } from "@/components/visuals/RockBackdrop";
import { geologyFacts } from "@/data/geology";
import { cn } from "@/lib/utils";

/**
 * SCENE 07 — THE GEOLOGY: editorial layout, not a card grid (brief §8).
 * Each fact lands at its own point on the page as the reader scrolls past.
 */
export function Scene07Geology() {
  return (
    <section id="geology" aria-label="درباره غار" className="relative w-full overflow-hidden bg-void py-32">
      <RockBackdrop glow="none" intensity="dim" className="opacity-60" />

      <div className="relative mx-auto max-w-4xl px-6">
        <RevealText className="mb-24 text-center">
          <p className="font-display text-xs uppercase tracking-[0.4em] text-turquoise-soft">
            THE GEOLOGY
          </p>
          <h2 className="mt-4 text-balance font-display text-3xl font-semibold text-foam sm:text-4xl">
            این غار فقط یک غار نیست.
          </h2>
        </RevealText>

        <div className="flex flex-col gap-20">
          {geologyFacts.map((fact, i) => (
            <RevealText
              key={fact.id}
              className={cn(
                "flex max-w-md flex-col gap-2",
                i % 2 === 0 ? "self-start text-start" : "self-end text-end items-end",
              )}
            >
              <span className="font-display text-4xl font-semibold text-foam sm:text-5xl">
                {fact.value}
                {fact.unit && (
                  <span className="ms-2 text-lg font-normal text-turquoise-soft">{fact.unit}</span>
                )}
              </span>
              <span className="font-display text-sm uppercase tracking-[0.25em] text-foam-faint">
                {fact.label}
              </span>
              <p className="mt-1 text-sm text-foam-dim">{fact.note}</p>
            </RevealText>
          ))}
        </div>
      </div>
    </section>
  );
}
