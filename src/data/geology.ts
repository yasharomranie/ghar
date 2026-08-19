// Editorial facts for Scene 07 — each stat appears at its own point on the
// page rather than inside a generic card grid (see design brief §8).
export interface GeologyFact {
  id: string;
  label: string;
  value: string;
  unit?: string;
  note: string;
}

export const geologyFacts: GeologyFact[] = [
  {
    id: "height",
    label: "ارتفاع غار",
    value: "۱۸",
    unit: "متر",
    note: "سقفی که هزاران سال بی‌صدا شکل گرفته است.",
  },
  {
    id: "depth",
    label: "عمق مسیر",
    value: "۴۲۰",
    unit: "متر",
    note: "مسیری که قدم‌به‌قدم به دل زمین نزدیک‌تر می‌شود.",
  },
  {
    id: "age",
    label: "قدمت تخمینی",
    value: "دو میلیون",
    unit: "سال",
    note: "پیش از هر جاده و هر شهر، این سنگ‌ها اینجا بودند.",
  },
  {
    id: "temperature",
    label: "دمای آب",
    value: "۱۹",
    unit: "درجه",
    note: "ثابت در تمام فصل‌ها؛ زیستگاهی بی‌نوسان.",
  },
  {
    id: "species",
    label: "گونه‌های آبزی",
    value: "۲۷",
    unit: "گونه",
    note: "هرکدام با سازگاری خاص خود به این محیط.",
  },
  {
    id: "tanks",
    label: "آکواریوم‌های درون‌صخره‌ای",
    value: "۱۴",
    note: "هرکدام در دل سنگ، نه در برابر آن.",
  },
];

export const storyChapters = [
  {
    id: "the-cave",
    index: "۰۱",
    title: "THE CAVE",
    persian: "غار",
    text: "میلیون‌ها سال در سکوت شکل گرفته.",
  },
  {
    id: "the-water",
    index: "۰۲",
    title: "THE WATER",
    persian: "آب",
    text: "آب، مسیر تازه‌ای برای زندگی ساخته است.",
  },
  {
    id: "the-life",
    index: "۰۳",
    title: "THE LIFE",
    persian: "زندگی",
    text: "حالا این تاریکی، خانه‌ی موجوداتی زنده است.",
  },
];
