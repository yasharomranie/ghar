import { RevealText } from "@/components/RevealText";
import { RockBackdrop } from "@/components/visuals/RockBackdrop";
import { ParticleField } from "@/components/visuals/ParticleField";

/** SCENE 09 — VISIT THE CAVE: the exit — camera pulls back out of the
 * cave mouth into daylight, closing the emotional arc (brief §19). */
export function Scene09Visit() {
  return (
    <section
      id="visit"
      aria-label="بازدید از غار"
      className="relative flex min-h-[90vh] w-full items-center justify-center overflow-hidden py-24"
    >
      <RockBackdrop glow="top" intensity="bright" />
      <ParticleField variant="dust" count={20} className="absolute inset-0" />

      <div className="relative z-10 mx-auto flex max-w-2xl flex-col items-center gap-8 px-6 text-center">
        <RevealText>
          <h2 className="text-balance font-display text-3xl font-semibold leading-relaxed text-foam sm:text-4xl">
            حالا نوبت توست که این دنیا را از نزدیک ببینی.
          </h2>
        </RevealText>

        <RevealText delay={0.1} className="flex flex-wrap items-center justify-center gap-4">
          <a
            href="#"
            data-cursor="بازدید"
            className="rounded-full bg-turquoise px-8 py-3 text-sm font-medium text-void transition-transform hover:scale-[1.03]"
          >
            برنامه بازدید
          </a>
          <a
            href="#"
            className="rounded-full border border-foam/25 px-8 py-3 text-sm text-foam transition-colors hover:border-turquoise hover:text-turquoise-soft"
          >
            مسیریابی
          </a>
          <a
            href="#"
            className="rounded-full border border-foam/25 px-8 py-3 text-sm text-foam transition-colors hover:border-turquoise hover:text-turquoise-soft"
          >
            تماس با ما
          </a>
        </RevealText>
      </div>

      <footer className="absolute inset-x-0 bottom-6 z-10 px-6 text-center text-xs text-foam-faint">
        © {new Date().getFullYear()} غار زنده — دنیایی زنده در دل زمین
      </footer>
    </section>
  );
}
