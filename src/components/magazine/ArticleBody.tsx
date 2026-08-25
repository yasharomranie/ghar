import Image from "next/image";
import type { ArticleBlock } from "@/data/articles";

/**
 * Renders one article's rich-text body. Heading ids must line up with
 * `headingsOf()` in src/data/articles.ts — both count only "h2" blocks, in
 * document order, so the table of contents links resolve to the right spot.
 */
export function ArticleBody({ blocks }: { blocks: ArticleBlock[] }) {
  // Anchor id per block (empty for non-headings), built without mutating any
  // outer-scoped variable during render — h2 blocks count up in document
  // order, matching headingsOf() in src/data/articles.ts exactly.
  const headingIds = blocks.reduce<string[]>((acc, block) => {
    acc.push(block.type === "h2" ? `section-${acc.filter(Boolean).length}` : "");
    return acc;
  }, []);

  return (
    <div className="flex flex-col gap-6 text-[15px] leading-8 text-foam-dim sm:text-base sm:leading-9">
      {blocks.map((block, i) => {
        switch (block.type) {
          case "p":
            return (
              <p key={i}>{block.text}</p>
            );

          case "h2":
            return (
              <h2
                key={i}
                id={headingIds[i]}
                className="scroll-mt-28 font-display text-xl font-semibold text-foam sm:text-2xl"
              >
                {block.text}
              </h2>
            );

          case "quote":
            return (
              <blockquote
                key={i}
                className="border-s-4 border-turquoise-dim bg-stone-900/40 py-4 ps-6 font-display text-lg leading-relaxed text-foam"
              >
                {block.text}
              </blockquote>
            );

          case "list":
            return (
              <ul key={i} className="flex flex-col gap-2.5">
                {block.items?.map((item, ii) => (
                  <li key={ii} className="flex gap-3">
                    <span aria-hidden="true" className="mt-2.5 h-1.5 w-1.5 flex-none rounded-full bg-turquoise" />
                    <span>{item}</span>
                  </li>
                ))}
              </ul>
            );

          case "image":
            if (!block.image) return null;
            return (
              <figure key={i} className="overflow-hidden rounded-2xl border border-foam/10">
                <div className="relative aspect-[16/9] w-full">
                  <Image
                    src={block.image}
                    alt={block.caption ?? ""}
                    fill
                    sizes="(min-width: 1024px) 700px, 100vw"
                    className="object-cover"
                  />
                </div>
                {block.caption && (
                  <figcaption className="bg-stone-900/60 px-4 py-2 text-xs text-foam-faint">
                    {block.caption}
                  </figcaption>
                )}
              </figure>
            );

          default:
            return null;
        }
      })}
    </div>
  );
}
