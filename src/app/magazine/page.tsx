import type { Metadata } from "next";
import { heroArticles, featuredArticles, latestArticles } from "@/data/articles";
import { HeroSlider } from "@/components/magazine/HeroSlider";
import { FeaturedCarousel } from "@/components/magazine/FeaturedCarousel";
import { ArticleGrid } from "@/components/magazine/ArticleGrid";
import { Footer } from "@/components/Footer";

export const metadata: Metadata = {
  title: "مجله خبری غار — غار زنده",
  description:
    "آخرین خبرها، گونه‌های تازه، رویدادها و روایت‌های پشت‌صحنه از غار زنده — دنیایی زنده در دل زمین.",
};

export default function MagazinePage() {
  return (
    <>
      <HeroSlider slides={heroArticles} />
      <FeaturedCarousel articles={featuredArticles} />
      <ArticleGrid articles={latestArticles} />
      <Footer />
    </>
  );
}
