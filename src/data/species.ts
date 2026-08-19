// Species catalogue for Scene 06.
// `asset` is the path the real photo/video should be placed at later
// (see /assets/images/cave-aquarium) — until then, `color` / `accent`
// drive the generative placeholder visual so the layout never breaks.
export interface Species {
  id: string;
  name: string;
  scientificName: string;
  habitat: string;
  trait: string;
  description: string;
  color: string; // primary body tone for the placeholder SVG fish
  accent: string; // fin / stripe tone
  asset: string; // future real asset path
}

export const species: Species[] = [
  {
    id: "regal-angelfish",
    name: "فرشته‌ماهی سلطنتی",
    scientificName: "Pygoplites diacanthus",
    habitat: "شکاف‌های صخره‌ای کم‌نور",
    trait: "رنگ‌های نواری که در نور کم می‌درخشند",
    description:
      "در تاریک‌روشنای غار، نوارهای آبی و طلایی‌اش تنها وقتی نور به آن می‌تابد آشکار می‌شوند.",
    color: "#2b6ea8",
    accent: "#e8b93f",
    asset: "/assets/images/cave-aquarium/species/regal-angelfish.jpg",
  },
  {
    id: "powder-blue-tang",
    name: "تانگ آبی پودری",
    scientificName: "Acanthurus leucosternon",
    habitat: "جریان‌های آرام نزدیک سطح آب",
    trait: "حرکت گروهی و هماهنگ",
    description:
      "این‌ها معمولاً به‌صورت دسته‌جمعی شنا می‌کنند؛ حرکتشان مثل موجی آبی در دل تاریکی است.",
    color: "#3f8fd1",
    accent: "#101820",
    asset: "/assets/images/cave-aquarium/species/powder-blue-tang.jpg",
  },
  {
    id: "cave-cichlid",
    name: "سیکلید غاری",
    scientificName: "Amphilophus cf. citrinellus",
    habitat: "بستر سنگی و ریشه‌های فرورفته در آب",
    trait: "سازگاری کامل با نور بسیار کم",
    description:
      "نسل‌هایی از این گونه در همین محیط کم‌نور رشد کرده‌اند و کمتر از هر ماهی دیگری به نور نیاز دارند.",
    color: "#c97a4a",
    accent: "#5c3a21",
    asset: "/assets/images/cave-aquarium/species/cave-cichlid.jpg",
  },
  {
    id: "lemon-goby",
    name: "گاوماهی لیمویی",
    scientificName: "Gobiodon citrinus",
    habitat: "لابه‌لای گیاهان آبزی و سنگ‌های مرجانی",
    trait: "رنگ زرد درخشان، برخلاف محیط تیره اطراف",
    description:
      "تنها لکه‌ی روشن این صحنه؛ انگار طبیعت خواسته یک نقطه‌ی امید در دل تاریکی بگذارد.",
    color: "#e8c93a",
    accent: "#8a6c14",
    asset: "/assets/images/cave-aquarium/species/lemon-goby.jpg",
  },
];
