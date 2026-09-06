/**
 * Home page cinematic scroll story — ported from:
 *  - src/components/SmoothScrollProvider.tsx (Lenis + GSAP ticker bridge)
 *  - src/components/RevealText.tsx           (.js-reveal fade+rise)
 *  - src/sections/Scene02IntoDarkness.tsx    (.parallax-layer)
 *  - src/sections/Scene04AquariumReveal.tsx  (#aquarium)
 *  - src/sections/Scene05LivingWorld.tsx     (#life captions)
 *  - src/sections/Scene08StoneWaterLife.tsx  (#story)
 *  - src/components/visuals/ParticleField.tsx (.particle-field drift)
 *
 * Every scroll-scrubbed effect reads scroll progress only — nothing here
 * runs on a timer — and every one falls back to its fully-revealed final
 * state under prefers-reduced-motion, same as the original components.
 */
(function () {
  "use strict";

  if (typeof gsap === "undefined") return;
  gsap.registerPlugin(ScrollTrigger);

  var reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* ---------------------------------------------------------------
   * Lenis smooth scroll <-> GSAP ticker bridge.
   * ----------------------------------------------------------- */
  if (!reducedMotion && typeof Lenis !== "undefined") {
    var lenis = new Lenis({
      lerp: 0.1,
      duration: 1.2,
      smoothWheel: true,
      touchMultiplier: 1.4,
    });
    gsap.ticker.add(function (time) {
      lenis.raf(time * 1000);
    });
    gsap.ticker.lagSmoothing(0);
    lenis.on("scroll", ScrollTrigger.update);
  }

  function mapRange(value, inMin, inMax) {
    return Math.max(0, Math.min(1, (value - inMin) / (inMax - inMin)));
  }

  /* ---------------------------------------------------------------
   * RevealText — viewport fade + rise, any [.js-reveal].
   * ----------------------------------------------------------- */
  document.querySelectorAll(".js-reveal").forEach(function (el) {
    if (reducedMotion) return;
    var delay = parseFloat(el.dataset.delay || "0");
    gsap.from(el, {
      opacity: 0,
      y: 28,
      duration: 0.9,
      delay: delay,
      ease: "power2.out",
      scrollTrigger: {
        trigger: el,
        start: "top 82%",
        toggleActions: "play none none reverse",
      },
    });
  });

  /* ---------------------------------------------------------------
   * Scene 02 — parallax layers inside #darkness.
   * ----------------------------------------------------------- */
  if (!reducedMotion) {
    var darkness = document.getElementById("darkness");
    if (darkness) {
      gsap.utils.toArray(".parallax-layer", darkness).forEach(function (layer, i) {
        gsap.to(layer, {
          yPercent: (i + 1) * -10,
          ease: "none",
          scrollTrigger: {
            trigger: darkness,
            start: "top bottom",
            end: "bottom top",
            scrub: 0.6,
          },
        });
      });
    }
  }

  /* ---------------------------------------------------------------
   * Scene 04 — aquarium reveal ("stone -> water -> light -> plants ->
   * life") and Scene 08's shared reveal look (photo focus/brightness).
   * ----------------------------------------------------------- */
  var phases = [
    { at: 0, label: "سنگ" },
    { at: 0.25, label: "سایه" },
    { at: 0.45, label: "نور" },
    { at: 0.7, label: "رنگ" },
    { at: 0.9, label: "زندگی" },
  ];

  function phaseLabel(reveal) {
    var label = phases[0].label;
    phases.forEach(function (p) {
      if (reveal >= p.at) label = p.label;
    });
    return label;
  }

  function setAquariumWindow(root, reveal) {
    var focus = mapRange(reveal, 0, 1);
    var brightness = 0.1 + focus * 0.9;
    var saturation = 0.15 + focus * 0.85;
    var blurPx = (1 - focus) * 16;
    var lightPulse = mapRange(reveal, 0.3, 0.65);
    var bubbleReveal = mapRange(reveal, 0.55, 1);

    var photo = root.querySelector(".js-aq-photo, .js-story-photo");
    if (photo) {
      photo.style.filter = "brightness(" + brightness + ") saturate(" + saturation + ") blur(" + blurPx + "px)";
    }
    var pulse = root.querySelector(".js-aq-pulse, .js-story-pulse");
    if (pulse) {
      pulse.style.opacity = String(lightPulse * (1 - lightPulse) * 3.2);
    }
    var bubbles = root.querySelector(".js-aq-bubbles");
    if (bubbles) {
      bubbles.style.opacity = String(bubbleReveal);
    }
    return { focus: focus, bubbleReveal: bubbleReveal };
  }

  var aquariumSection = document.getElementById("aquarium");
  var aquariumWindow = document.getElementById("aquariumWindow");
  var aqPhase = document.getElementById("aqPhase");
  var aqCaption = document.getElementById("aqCaption");

  if (aquariumSection && aquariumWindow) {
    var applyAquarium = function (reveal) {
      setAquariumWindow(aquariumWindow, reveal);
      if (aqPhase) aqPhase.textContent = phaseLabel(reveal);
      if (aqCaption) aqCaption.style.opacity = reveal > 0.75 ? "1" : "0";
    };

    if (reducedMotion) {
      applyAquarium(1);
    } else {
      ScrollTrigger.create({
        trigger: aquariumSection,
        start: "top top",
        end: "bottom bottom",
        scrub: 0.5,
        onUpdate: function (self) {
          applyAquarium(self.progress);
        },
      });
    }
  }

  /* ---------------------------------------------------------------
   * Scene 05 — living world caption crossfade.
   * ----------------------------------------------------------- */
  var lifeSection = document.getElementById("life");
  var lifeCaptions = document.querySelectorAll(".js-life-caption");

  function showLifeCaption(idx) {
    lifeCaptions.forEach(function (el, i) {
      el.classList.toggle("opacity-100", i === idx);
      el.classList.toggle("translate-y-0", i === idx);
      el.classList.toggle("opacity-0", i !== idx);
      el.classList.toggle("translate-y-3", i !== idx);
    });
  }

  if (lifeSection && lifeCaptions.length) {
    if (reducedMotion) {
      showLifeCaption(0);
    } else {
      ScrollTrigger.create({
        trigger: lifeSection,
        start: "top top",
        end: "bottom bottom",
        scrub: 0.5,
        onUpdate: function (self) {
          var idx = Math.min(lifeCaptions.length - 1, Math.floor(self.progress * lifeCaptions.length));
          showLifeCaption(idx);
        },
      });
    }
  }

  /* ---------------------------------------------------------------
   * Scene 08 — stone -> water -> life: rock shutters part, aquarium
   * behind them reveals, chapter text crossfades.
   * ----------------------------------------------------------- */
  var storyChapters = [
    { title: "THE CAVE", persian: "غار", text: "میلیون‌ها سال در سکوت شکل گرفته." },
    { title: "THE WATER", persian: "آب", text: "آب، مسیر تازه‌ای برای زندگی ساخته است." },
    { title: "THE LIFE", persian: "زندگی", text: "حالا این تاریکی، خانه‌ی موجوداتی زنده است." },
  ];

  var storySection = document.getElementById("story");
  var storyWindow = document.getElementById("storyWindow");
  var storyStart = document.getElementById("storyShutterStart");
  var storyEnd = document.getElementById("storyShutterEnd");
  var storyLabel = document.getElementById("storyChapterLabel");
  var storyText = document.getElementById("storyChapterText");

  function applyStory(p) {
    var partOpen = Math.min(1, p / 0.55);
    if (storyWindow) setAquariumWindow(storyWindow, Math.max(0, (p - 0.3) / 0.7));
    if (storyStart) storyStart.style.transform = "translateX(" + -partOpen * 100 + "%)";
    if (storyEnd) storyEnd.style.transform = "translateX(" + partOpen * 100 + "%)";

    var chapterIndex = Math.min(storyChapters.length - 1, Math.floor(p * storyChapters.length));
    var chapter = storyChapters[chapterIndex];
    if (storyLabel) storyLabel.textContent = chapter.title + " · " + chapter.persian;
    if (storyText) storyText.textContent = chapter.text;
  }

  if (storySection && storyWindow) {
    if (reducedMotion) {
      if (storyStart) storyStart.style.transform = "translateX(-100%)";
      if (storyEnd) storyEnd.style.transform = "translateX(100%)";
      applyStory(1);
    } else {
      ScrollTrigger.create({
        trigger: storySection,
        start: "top top",
        end: "bottom bottom",
        scrub: 0.6,
        onUpdate: function (self) {
          applyStory(self.progress);
        },
      });
    }
  }

  /* ---------------------------------------------------------------
   * Particle drift — dust drifts + fades yoyo, bubbles rise and fade.
   * ----------------------------------------------------------- */
  if (!reducedMotion) {
    document.querySelectorAll(".particle-field").forEach(function (field) {
      var variant = field.dataset.variant || "dust";
      var items = field.querySelectorAll(".particle");
      items.forEach(function (el, i) {
        var duration = gsap.utils.random(6, 14);
        var drift = gsap.utils.random(-40, 40);
        gsap.to(el, {
          y: variant === "bubble" ? -gsap.utils.random(120, 260) : drift,
          x: variant === "bubble" ? drift * 0.3 : drift,
          opacity: gsap.utils.random(0.15, 0.55),
          duration: duration,
          repeat: -1,
          yoyo: variant === "dust",
          ease: "sine.inOut",
          delay: i * 0.15,
        });
      });
    });
  }

  /* ---------------------------------------------------------------
   * Re-measure every pinned section once webfonts finish swapping in.
   * ScrollTrigger measures each trigger's start/end once, at load, using
   * whatever font is painted at that moment (the fallback face) — if the
   * real Vazirmatn file (loaded from Google Fonts, not bundled like
   * next/font did) arrives after that and reflows text height anywhere
   * above a given section, that section's trigger points drift out from
   * under it. assets/css/theme.css also restores next/font's own
   * metric-matched fallback font to keep that reflow small in the first
   * place; this is the safety net for whatever's left.
   * ----------------------------------------------------------- */
  if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(function () {
      ScrollTrigger.refresh();
    });
  }
  window.addEventListener("load", function () {
    ScrollTrigger.refresh();
  });
})();
