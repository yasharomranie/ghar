import type { Metadata } from "next";
import Image from "next/image";
import Link from "next/link";
import { notFound } from "next/navigation";
import {
  articles,
  authorFor,
  getArticleBySlug,
  headingsOf,
  relatedArticles,
} from "@/data/articles";
import { ArticleBody } from "@/components/magazine/ArticleBody";
import { ArticleSidebar } from "@/components/magazine/ArticleSidebar";
import { ShareBar } from "@/components/magazine/ShareBar";
import { RelatedArticles } from "@/components/magazine/RelatedArticles";
import { Footer } from "@/components/Footer";

export function generateStaticParams() {
  return articles.map((a) => ({ slug: a.slug }));
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>;
}): Promise<Metadata> {
  const { slug } = await params;
  const article = getArticleBySlug(slug);
  if (!article) return {};

  return {
    title: `${article.title} — مجله خبری غار زنده`,
    description: article.excerpt,
    openGraph: {
      title: article.title,
      description: article.excerpt,
      type: "article",
      images: [{ url: article.image, width: 2000, height: 1120 }],
    },
    twitter: {
      card: "summary_large_image",
      title: article.title,
      description: article.excerpt,
      images: [article.image],
    },
  };
}

export default async function ArticlePage({
  params,
}: {
  params: Promise<{ slug: string }>;
}) {
  const { slug } = await params;
  const article = getArticleBySlug(slug);
  if (!article) notFound();

  const headings = headingsOf(article.body);
  const related = relatedArticles(article);
  const author = authorFor(article.category);

  return (
    <>
      <article>
        <div className="mx-auto max-w-4xl px-6 pt-32 md:px-10">
          <nav aria-label="مسیر صفحه" className="flex flex-wrap items-center gap-2 text-xs text-foam-faint">
            <Link href="/" className="transition-colors hover:text-foam-dim">
              غار زنده
            </Link>
            <span aria-hidden="true">/</span>
            <Link href="/magazine" className="transition-colors hover:text-foam-dim">
              مجله خبری
            </Link>
            <span aria-hidden="true">/</span>
            <span className="text-foam-dim">{article.category}</span>
          </nav>

          <div className="mt-6">
            <span className="inline-block rounded-full bg-turquoise-dim/20 px-3 py-1 font-display text-xs text-turquoise-soft">
              {article.category}
            </span>
            <h1 className="mt-4 text-balance font-display text-3xl font-bold leading-snug text-foam sm:text-4xl">
              {article.title}
            </h1>
            <div className="mt-5 flex flex-wrap items-center gap-3 text-sm text-foam-faint">
              <span className="text-foam-dim">{author}</span>
              <span aria-hidden="true">·</span>
              <span>{article.date}</span>
              <span aria-hidden="true">·</span>
              <span>{article.readTime} مطالعه</span>
            </div>
          </div>
        </div>

        <div className="mx-auto mt-8 max-w-5xl px-6 md:px-10">
          <div className="relative aspect-[16/9] w-full overflow-hidden rounded-2xl border border-foam/10">
            <Image
              src={article.image}
              alt={article.title}
              fill
              priority
              sizes="(min-width: 1024px) 900px, 100vw"
              className="object-cover"
            />
          </div>
        </div>

        <div className="mx-auto mt-12 grid max-w-5xl grid-cols-1 gap-12 px-6 pb-20 md:px-10 lg:grid-cols-[1fr_300px]">
          <div className="min-w-0">
            <ArticleBody blocks={article.body} />
            <div className="mt-10">
              <ShareBar slug={article.slug} />
            </div>
          </div>

          <aside className="lg:sticky lg:top-28 lg:h-fit">
            <ArticleSidebar headings={headings} article={article} />
          </aside>
        </div>
      </article>

      <RelatedArticles articles={related} />
      <Footer />
    </>
  );
}
