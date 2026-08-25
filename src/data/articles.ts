// Content for the "مجله خبری غار" (Cave News Magazine) page — real editorial
// copy in the site's voice, no lorem ipsum. Images cycle through the real
// reference photos in /public/images/cave since that's the only photography
// available; swap individual `image` paths once article-specific photos exist.

export interface Article {
  slug: string;
  title: string;
  excerpt: string;
  category: string;
  date: string;
  readTime: string;
  image: string;
}

const IMAGE_POOL = [
  "/images/cave/aquarium-window-clear.webp",
  "/images/cave/aquarium-window-light.webp",
  "/images/cave/aquarium-window-plants.webp",
  "/images/cave/corridor-entrance.webp",
  "/images/cave/corridor-long-walk.webp",
  "/images/cave/corridor-panorama-bright.webp",
  "/images/cave/corridor-panorama-dark.webp",
  "/images/cave/corridor-stalagmites.webp",
  "/images/cave/corridor-warm-glow.webp",
  "/images/cave/darkness-threshold.webp",
  "/images/cave/hero-entrance.webp",
  "/images/cave/species-regal-angelfish.webp",
  "/images/cave/water-corridor.webp",
];

export function imageFor(index: number): string {
  return IMAGE_POOL[index % IMAGE_POOL.length];
}

const raw: Omit<Article, "image">[] = [
  {
    slug: "regal-angelfish-arrival",
    title: "فرشته‌ماهی سلطنتی به آکواریوم شماره هفت پیوست",
    excerpt:
      "پس از سه هفته قرنطینه و سازگاری با نور کم غار، این گونه‌ی کمیاب حالا در یکی از پنجره‌های اصلی مسیر بازدید قابل مشاهده است.",
    category: "گونه‌های جدید",
    date: "۳ شهریور ۱۴۰۵",
    readTime: "۴ دقیقه",
  },
  {
    slug: "night-tour-launch",
    title: "بازدید شبانه از غار از این هفته آغاز می‌شود",
    excerpt:
      "با نورپردازی ویژه و بدون ازدحام روز، تور شبانه فرصتی تازه برای دیدن رفتار طبیعی ماهی‌ها در ساعات آرام‌تر شبانه‌روز فراهم می‌کند.",
    category: "رویدادها",
    date: "۲۹ مرداد ۱۴۰۵",
    readTime: "۳ دقیقه",
  },
  {
    slug: "water-quality-report",
    title: "گزارش فصلی کیفیت آب: ثبات دمایی در تمام آکواریوم‌ها",
    excerpt:
      "تیم زیست‌محیطی غار نتایج شش ماه پایش مستمر دما، اکسیژن محلول و شفافیت آب را منتشر کرد؛ همه‌ی شاخص‌ها در محدوده‌ی ایمن گزارش شده‌اند.",
    category: "علمی",
    date: "۲۵ مرداد ۱۴۰۵",
    readTime: "۶ دقیقه",
  },
  {
    slug: "behind-the-scenes-maintenance",
    title: "یک روز با تیم نگهداری: پشت پرده‌ی آکواریوم‌های درون‌صخره‌ای",
    excerpt:
      "از تمیزکاری شیشه‌ها در تاریکی کامل تا تنظیم دستی جریان آب پشت هر پنجره — روایتی از کاری که بازدیدکننده‌ها هرگز نمی‌بینند.",
    category: "پشت صحنه",
    date: "۲۰ مرداد ۱۴۰۵",
    readTime: "۵ دقیقه",
  },
  {
    slug: "cave-cichlid-breeding",
    title: "نخستین تخم‌گذاری سیکلید غاری در محیط پرورشی ثبت شد",
    excerpt:
      "زیست‌شناسان غار این رویداد را نشانه‌ی موفقیت کامل سازگاری این گونه با نور بسیار کم می‌دانند؛ نوزادان ظرف دو هفته آینده قابل مشاهده خواهند بود.",
    category: "علمی",
    date: "۱۶ مرداد ۱۴۰۵",
    readTime: "۴ دقیقه",
  },
  {
    slug: "photography-workshop",
    title: "کارگاه عکاسی در نور کم: ثبت تصویر از دل تاریکی",
    excerpt:
      "برای نخستین‌بار، غار میزبان کارگاهی تخصصی برای علاقه‌مندان به عکاسی در شرایط نوری دشوار خواهد بود — با تجهیزات پیشنهادی و تمرین عملی کنار آکواریوم‌ها.",
    category: "رویدادها",
    date: "۱۲ مرداد ۱۴۰۵",
    readTime: "۳ دقیقه",
  },
  {
    slug: "geology-survey",
    title: "بررسی زمین‌شناسی تازه: قدمت غار دو میلیون سال تخمین زده شد",
    excerpt:
      "تیم مشترک زمین‌شناسی دانشگاه با تحلیل لایه‌های رسوبی سقف غار، تصویری دقیق‌تر از شکل‌گیری این فضای طبیعی در اختیار پژوهشگران قرار داد.",
    category: "علمی",
    date: "۸ مرداد ۱۴۰۵",
    readTime: "۷ دقیقه",
  },
  {
    slug: "school-visit-program",
    title: "برنامه‌ی بازدید مدرسه‌ها برای سال تحصیلی جدید اعلام شد",
    excerpt:
      "بسته‌ی آموزشی ویژه‌ی دانش‌آموزان شامل تور راهنمایی‌شده، کارگاه کوتاه زیست‌شناسی آبزیان و کتابچه‌ی فعالیت برای هر گروه سنی طراحی شده است.",
    category: "رویدادها",
    date: "۴ مرداد ۱۴۰۵",
    readTime: "۳ دقیقه",
  },
  {
    slug: "lemon-goby-spotlight",
    title: "چرا گاوماهی لیمویی تنها نقطه‌ی زرد رنگ این تاریکی است؟",
    excerpt:
      "زیست‌شناسان توضیح می‌دهند چگونه رنگدانه‌ی درخشان این گونه، سازگاری تکاملی‌اش با محیط کم‌نور را برخلاف تصور اولیه پیچیده‌تر کرده است.",
    category: "گونه‌های جدید",
    date: "۳۰ تیر ۱۴۰۵",
    readTime: "۴ دقیقه",
  },
  {
    slug: "conservation-partnership",
    title: "همکاری تازه با مرکز حفاظت از آبزیان کمیاب آغاز شد",
    excerpt:
      "این همکاری امکان جابه‌جایی امن گونه‌های در معرض خطر به محیط پایدار غار را فراهم می‌کند و بخشی از برنامه‌ی بلندمدت حفاظت زیستی است.",
    category: "اخبار غار",
    date: "۲۶ تیر ۱۴۰۵",
    readTime: "۵ دقیقه",
  },
  {
    slug: "path-lighting-upgrade",
    title: "بازطراحی نورپردازی مسیر: تجربه‌ای نزدیک‌تر به غار واقعی",
    excerpt:
      "چراغ‌های جدید LED با طیف رنگی گرم‌تر جایگزین نورپردازی قبلی مسیر شدند تا حس ورود تدریجی به تاریکی برای بازدیدکننده طبیعی‌تر باشد.",
    category: "پشت صحنه",
    date: "۲۲ تیر ۱۴۰۵",
    readTime: "۳ دقیقه",
  },
  {
    slug: "powder-blue-tang-school",
    title: "دسته‌ی تانگ آبی پودری، جدیدترین ساکنان راهروی میانی",
    excerpt:
      "حرکت هماهنگ این گونه در دسته‌های هفت تا ده‌تایی، یکی از دیدنی‌ترین صحنه‌های تازه‌ی مسیر بازدید در هفته‌های اخیر بوده است.",
    category: "گونه‌های جدید",
    date: "۱۸ تیر ۱۴۰۵",
    readTime: "۴ دقیقه",
  },
  {
    slug: "acoustic-study",
    title: "پژوهش تازه: چگونه سکوت صخره بر رفتار ماهی‌ها اثر می‌گذارد؟",
    excerpt:
      "نتایج اولیه‌ی این مطالعه نشان می‌دهد کاهش آلودگی صوتی محیط غار، سطح استرس برخی گونه‌ها را نسبت به آکواریوم‌های معمولی به‌طور محسوسی پایین آورده است.",
    category: "علمی",
    date: "۱۴ تیر ۱۴۰۵",
    readTime: "۶ دقیقه",
  },
  {
    slug: "volunteer-program",
    title: "فراخوان جذب داوطلب برای فصل پاییز",
    excerpt:
      "علاقه‌مندان به زیست‌شناسی دریایی و راهنمایی بازدیدکنندگان می‌توانند در برنامه‌ی داوطلبی جدید غار ثبت‌نام کنند؛ آموزش کامل رایگان است.",
    category: "رویدادها",
    date: "۱۰ تیر ۱۴۰۵",
    readTime: "۳ دقیقه",
  },
  {
    slug: "rock-formation-story",
    title: "داستان سنگی که در دل خود آب را جای داد",
    excerpt:
      "روایتی از فرآیند طولانی حفر و آماده‌سازی هر پنجره‌ی آکواریوم در دل صخره، بدون آسیب به ساختار طبیعی غار.",
    category: "پشت صحنه",
    date: "۶ تیر ۱۴۰۵",
    readTime: "۵ دقیقه",
  },
  {
    slug: "visitor-record",
    title: "رکورد بازدید تابستانی: بیش از چهل هزار نفر در یک ماه",
    excerpt:
      "با وجود افزایش چشمگیر بازدیدکننده‌ها، مدیریت غار اعلام کرد ظرفیت‌بندی ساعتی مسیر همچنان کیفیت تجربه‌ی هر گروه را حفظ کرده است.",
    category: "اخبار غار",
    date: "۱ تیر ۱۴۰۵",
    readTime: "۳ دقیقه",
  },
  {
    slug: "coral-substrate-research",
    title: "بستر مرجانی مصنوعی جدید برای آکواریوم شماره سه نصب شد",
    excerpt:
      "این بستر با تقلید از بافت سنگ‌های طبیعی غار طراحی شده تا هم زیستگاه امن‌تری برای ماهی‌های کوچک باشد و هم با محیط اطراف هماهنگ بماند.",
    category: "پشت صحنه",
    date: "۲۸ خرداد ۱۴۰۵",
    readTime: "۴ دقیقه",
  },
  {
    slug: "documentary-filming",
    title: "تیم مستندسازی ملی برای فیلم‌برداری وارد غار شد",
    excerpt:
      "این مستند که قرار است تجربه‌ی زیستن در دل تاریکی را روایت کند، تا پایان پاییز برای پخش عمومی آماده خواهد شد.",
    category: "اخبار غار",
    date: "۲۴ خرداد ۱۴۰۵",
    readTime: "۴ دقیقه",
  },
  {
    slug: "kids-drawing-contest",
    title: "برندگان مسابقه‌ی نقاشی «دنیای زیر تاریکی» معرفی شدند",
    excerpt:
      "بیش از سیصد اثر از کودکان سراسر کشور به این رویداد سالانه ارسال شد؛ آثار برگزیده تا پایان ماه در سالن ورودی غار به نمایش گذاشته می‌شوند.",
    category: "رویدادها",
    date: "۲۰ خرداد ۱۴۰۵",
    readTime: "۲ دقیقه",
  },
  {
    slug: "temperature-stability-tech",
    title: "فناوری تازه‌ی تثبیت دما، مصرف انرژی را ۱۸ درصد کاهش داد",
    excerpt:
      "با استفاده از عایق‌بندی طبیعی خود صخره در کنار سامانه‌ی هوشمند پایش، آکواریوم‌ها اکنون با ثبات بیشتر و انرژی کمتری دما را حفظ می‌کنند.",
    category: "علمی",
    date: "۱۶ خرداد ۱۴۰۵",
    readTime: "۵ دقیقه",
  },
  {
    slug: "guide-team-story",
    title: "راهنمایانی که مسیر غار را در تاریکی کامل حفظ کرده‌اند",
    excerpt:
      "گفت‌وگو با تیم راهنمایان درباره‌ی سال‌ها تجربه‌ی هدایت بازدیدکننده‌ها در مسیری که هر بار، در نور متفاوت، حس تازه‌ای دارد.",
    category: "پشت صحنه",
    date: "۱۲ خرداد ۱۴۰۵",
    readTime: "۶ دقیقه",
  },
  {
    slug: "new-entrance-hours",
    title: "ساعات بازدید فصل پاییز اعلام شد",
    excerpt:
      "با نزدیک شدن به فصل پاییز، غار ساعات کاری جدید خود را برای روزهای عادی و تعطیل منتشر کرد؛ رزرو آنلاین همچنان توصیه می‌شود.",
    category: "اخبار غار",
    date: "۸ خرداد ۱۴۰۵",
    readTime: "۲ دقیقه",
  },
  {
    slug: "species-count-milestone",
    title: "شمار گونه‌های ساکن غار به ۲۷ گونه رسید",
    excerpt:
      "با ورود سه گونه‌ی تازه در بهار امسال، غار اکنون میزبان متنوع‌ترین مجموعه‌ی آبزیان سازگار با نور کم در منطقه است.",
    category: "علمی",
    date: "۴ خرداد ۱۴۰۵",
    readTime: "۴ دقیقه",
  },
  {
    slug: "sound-of-water-exhibit",
    title: "نمایشگاه صوتی «صدای آب در تاریکی» افتتاح شد",
    excerpt:
      "این نمایشگاه با ضبط صداهای واقعی جریان آب و حباب در دل غار، تجربه‌ای شنیداری موازی با تجربه‌ی بصری بازدیدکننده‌ها ارائه می‌دهد.",
    category: "رویدادها",
    date: "۳۰ اردیبهشت ۱۴۰۵",
    readTime: "۳ دقیقه",
  },
];

export const articles: Article[] = raw.map((a, i) => ({
  ...a,
  image: imageFor(i),
}));

export const heroArticles = articles.slice(0, 5);
export const featuredArticles = articles.slice(0, 8);
export const latestArticles = articles;
