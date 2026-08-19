// Scroll architecture — one entry per narrative Scene.
// Used by the progress indicator, the nav, and each section's own id/anchor.
export type SceneId =
  | "hero"
  | "darkness"
  | "water"
  | "aquarium"
  | "life"
  | "species"
  | "geology"
  | "story"
  | "visit";

export interface SceneMeta {
  id: SceneId;
  index: string; // "01", "02", ...
  label: string; // short Persian label shown in the progress rail
  navLabel?: string; // shown in the top nav (subset of scenes)
}

export const scenes: SceneMeta[] = [
  { id: "hero", index: "۰۱", label: "ورود", navLabel: "غار" },
  { id: "darkness", index: "۰۲", label: "تاریکی" },
  { id: "water", index: "۰۳", label: "نخستین آب" },
  { id: "aquarium", index: "۰۴", label: "آکواریوم", navLabel: "آکواریوم" },
  { id: "life", index: "۰۵", label: "دنیای زنده" },
  { id: "species", index: "۰۶", label: "گونه‌ها", navLabel: "گونه‌ها" },
  { id: "geology", index: "۰۷", label: "زمین‌شناسی", navLabel: "درباره غار" },
  { id: "story", index: "۰۸", label: "سنگ، آب، زندگی" },
  { id: "visit", index: "۰۹", label: "بازدید", navLabel: "بازدید" },
];
