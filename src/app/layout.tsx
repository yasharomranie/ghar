import type { Metadata, Viewport } from "next";
import type { ReactNode } from "react";
import { Vazirmatn } from "next/font/google";
import "./globals.css";
import { SmoothScrollProvider } from "@/components/SmoothScrollProvider";
import { Nav } from "@/components/Nav";
import { ScrollProgress } from "@/components/ScrollProgress";
import { CustomCursor } from "@/components/CustomCursor";

const vazirmatn = Vazirmatn({
  variable: "--font-vazirmatn",
  subsets: ["arabic", "latin"],
  display: "swap",
});

const siteUrl = "https://ghar-zende.example.com";
const title = "غار زنده — دنیایی زنده در دل زمین";
const description =
  "سفری سینمایی و اسکرول‌محور به دل یک غار طبیعی با آکواریوم‌های زنده در دل سنگ. جایی که سنگ، آب و زندگی به هم می‌رسند.";

export const metadata: Metadata = {
  metadataBase: new URL(siteUrl),
  title,
  description,
  openGraph: {
    title,
    description,
    url: siteUrl,
    siteName: "غار زنده",
    locale: "fa_IR",
    type: "website",
  },
  twitter: {
    card: "summary_large_image",
    title,
    description,
  },
  robots: { index: true, follow: true },
};

export const viewport: Viewport = {
  themeColor: "#050708",
  width: "device-width",
  initialScale: 1,
};

export default function RootLayout({ children }: { children: ReactNode }) {
  return (
    <html lang="fa" dir="rtl" className={`${vazirmatn.variable} h-full antialiased`}>
      <body className="min-h-full bg-void text-foam">
        <SmoothScrollProvider>
          <a
            href="#hero"
            className="sr-only focus:not-sr-only focus:fixed focus:start-4 focus:top-4 focus:z-[200] focus:rounded-full focus:bg-foam focus:px-4 focus:py-2 focus:text-void"
          >
            رفتن به محتوای اصلی
          </a>
          <Nav />
          <ScrollProgress />
          <CustomCursor />
          <main>{children}</main>
        </SmoothScrollProvider>
      </body>
    </html>
  );
}
