/**
 * Home page cinematic scroll story.
 *
 * Two source lineages meet here:
 *  - The current React source (src/sections/Scene01..09*.tsx, via
 *    @gsap/react) for the base scroll-scrubbed reveals: parallax,
 *    RevealText fades, the aquarium/story window reveal, life captions,
 *    particle drift, Lenis smooth scroll.
 *  - The original preview artifact — an earlier, more elaborate demo of
 *    this same design — for the extra polish layered on top: the intro
 *    curtain, synthesized entrance sound, magnetic cursor + torch +
 *    species-card tilt, word-by-word text reveals, the aquarium's
 *    plankton/light-sweep, and the story scene's crack shards + beam.
 *
 * Every scroll-scrubbed effect reads scroll progress only — nothing here
 * runs on a timer — and every non-essential effect (curtain, sound,
 * cursor extras, decorative particles) is skipped outright under
 * prefers-reduced-motion, landing straight on each scene's fully-revealed
 * final state.
 */
(function () {
  "use strict";

  if (typeof gsap === "undefined") return;
  gsap.registerPlugin(ScrollTrigger);

  var reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var finePointer = window.matchMedia("(pointer: fine)").matches;

  function clamp01(v) {
    return Math.max(0, Math.min(1, v));
  }
  function mapRange(value, inMin, inMax) {
    return clamp01((value - inMin) / (inMax - inMin));
  }
  function easeOutBack(x) {
    var c1 = 1.70158,
      c3 = c1 + 1;
    return 1 + c3 * Math.pow(x - 1, 3) + c1 * Math.pow(x - 1, 2);
  }

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

  /* ---------------------------------------------------------------
   * Intro curtain — a brief title card before the hero. Removed
   * outright (not just hidden) under reduced motion.
   * ----------------------------------------------------------- */
  var curtain = document.getElementById("introCurtain");
  if (curtain) {
    if (reducedMotion) {
      curtain.remove();
    } else {
      gsap
        .timeline({ delay: 0.1 })
        .to("#introCurtain .intro-sub", { opacity: 1, duration: 0.6 })
        .to("#introCurtain .intro-word", { opacity: 1, duration: 0.8, ease: "power2.out" }, "-=0.35")
        .to({}, { duration: 0.5 })
        .to("#introCurtain", {
          opacity: 0,
          scale: 1.06,
          filter: "blur(6px)",
          duration: 0.9,
          ease: "power2.inOut",
          onComplete: function () {
            curtain.remove();
          },
        });
    }
  }

  /* ---------------------------------------------------------------
   * Entrance sound — synthesized on the fly with Web Audio (no audio
   * files, no network request): a soft underwater shimmer bed plus a
   * handful of staggered bubble blips. On by default; browsers require
   * a user gesture before audio can start, so it plays the instant
   * that's allowed — immediately if permitted, otherwise on the first
   * click/key/touch anywhere on the page.
   * ----------------------------------------------------------- */
  (function entranceSound() {
    var toggles = document.querySelectorAll("#soundToggle, #soundToggleMobile");
    if (!toggles.length) return;

    var audioCtx = null;
    var soundOn = true;
    var hasPlayed = false;

    function ensureAudioCtx() {
      if (!audioCtx) {
        var Ctx = window.AudioContext || window.webkitAudioContext;
        if (!Ctx) return null;
        audioCtx = new Ctx();
      }
      if (audioCtx.state === "suspended") audioCtx.resume();
      return audioCtx;
    }

    function makeNoiseBuffer(ctx, duration) {
      var length = Math.floor(ctx.sampleRate * duration);
      var buffer = ctx.createBuffer(1, length, ctx.sampleRate);
      var data = buffer.getChannelData(0);
      var last = 0;
      for (var i = 0; i < length; i++) {
        var white = Math.random() * 2 - 1;
        last = (last + 0.02 * white) / 1.02;
        data[i] = last * 3.2;
      }
      return buffer;
    }

    function playEntranceSound() {
      var ctx = ensureAudioCtx();
      if (!ctx) return;
      var now = ctx.currentTime;

      var shimmer = ctx.createBufferSource();
      shimmer.buffer = makeNoiseBuffer(ctx, 2.6);
      var shimmerFilter = ctx.createBiquadFilter();
      shimmerFilter.type = "bandpass";
      shimmerFilter.frequency.value = 1500;
      shimmerFilter.Q.value = 0.6;
      var shimmerGain = ctx.createGain();
      shimmerGain.gain.setValueAtTime(0, now);
      shimmerGain.gain.linearRampToValueAtTime(0.05, now + 0.5);
      shimmerGain.gain.exponentialRampToValueAtTime(0.001, now + 2.6);
      shimmer.connect(shimmerFilter).connect(shimmerGain).connect(ctx.destination);
      shimmer.start(now);
      shimmer.stop(now + 2.6);

      var bubbleCount = 10;
      for (var i = 0; i < bubbleCount; i++) {
        var startAt = now + 0.05 + Math.random() * 2.0;
        var baseFreq = 360 + Math.random() * 950;
        var dur = 0.09 + Math.random() * 0.15;

        var osc = ctx.createOscillator();
        osc.type = "sine";
        osc.frequency.setValueAtTime(baseFreq, startAt);
        osc.frequency.exponentialRampToValueAtTime(baseFreq * (1.5 + Math.random() * 0.9), startAt + dur);

        var gain = ctx.createGain();
        gain.gain.setValueAtTime(0, startAt);
        gain.gain.linearRampToValueAtTime(0.11 + Math.random() * 0.06, startAt + dur * 0.3);
        gain.gain.exponentialRampToValueAtTime(0.001, startAt + dur);

        if (ctx.createStereoPanner) {
          var pan = ctx.createStereoPanner();
          pan.pan.value = (Math.random() * 2 - 1) * 0.6;
          osc.connect(gain).connect(pan).connect(ctx.destination);
        } else {
          osc.connect(gain).connect(ctx.destination);
        }
        osc.start(startAt);
        osc.stop(startAt + dur + 0.05);
      }
    }

    function tryAutoEntrance() {
      if (hasPlayed || !soundOn) return;
      var ctx = ensureAudioCtx();
      if (ctx && ctx.state === "running") {
        hasPlayed = true;
        playEntranceSound();
      }
    }
    tryAutoEntrance();
    ["pointerdown", "keydown", "touchstart"].forEach(function (evt) {
      document.addEventListener(evt, tryAutoEntrance, { passive: true });
    });

    toggles.forEach(function (btn) {
      btn.addEventListener("click", function () {
        soundOn = !soundOn;
        toggles.forEach(function (b) {
          b.classList.toggle("muted", !soundOn);
          b.setAttribute("aria-pressed", String(soundOn));
          b.setAttribute("aria-label", soundOn ? "قطع صدای ورود" : "فعال‌سازی صدای ورود");
        });
        if (soundOn) {
          hasPlayed = true;
          playEntranceSound();
        }
      });
    });
  })();

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
   * Word-by-word reveals — darkness/water/visit text (server-split
   * into `.word` spans by ghar_zende_split_words()).
   * ----------------------------------------------------------- */
  if (!reducedMotion) {
    [
      { selector: "#darkness p", trigger: "#darkness p" },
      { selector: "#water p", trigger: "#water p" },
      { selector: "#visit h2", trigger: "#visit h2" },
    ].forEach(function (cfg) {
      var el = document.querySelector(cfg.selector);
      if (!el) return;
      gsap.from(cfg.selector + " .word", {
        opacity: 0,
        y: 26,
        rotateX: -35,
        stagger: 0.045,
        duration: 0.6,
        ease: "power2.out",
        scrollTrigger: {
          trigger: cfg.trigger,
          start: "top 82%",
          toggleActions: "play none none reverse",
        },
      });
    });
  }

  /* ---------------------------------------------------------------
   * Custom-cursor extras (site-wide dot itself is theme.js's job):
   * magnetic buttons, aquarium + species-card tilt, darkness torch.
   * Fine pointer + motion-ok only.
   * ----------------------------------------------------------- */
  if (!reducedMotion && finePointer) {
    document.querySelectorAll("[data-magnetic]").forEach(function (btn) {
      btn.addEventListener("pointermove", function (e) {
        var r = btn.getBoundingClientRect();
        var x = e.clientX - r.left - r.width / 2;
        var y = e.clientY - r.top - r.height / 2;
        gsap.to(btn, { x: x * 0.35, y: y * 0.35, duration: 0.3, ease: "power2.out" });
      });
      btn.addEventListener("pointerleave", function () {
        gsap.to(btn, { x: 0, y: 0, duration: 0.6, ease: "elastic.out(1,0.4)" });
      });
    });

    var aqSticky = document.getElementById("aquariumSticky");
    var aqFrameEl = document.getElementById("aquariumWindow");
    if (aqSticky && aqFrameEl) {
      aqSticky.addEventListener("pointermove", function (e) {
        var r = aqFrameEl.getBoundingClientRect();
        var dx = (e.clientX - (r.left + r.width / 2)) / r.width;
        var dy = (e.clientY - (r.top + r.height / 2)) / r.height;
        gsap.to(aqFrameEl, { rotateY: dx * 7, rotateX: -dy * 7, duration: 0.6, ease: "power2.out" });
      });
      aqSticky.addEventListener("pointerleave", function () {
        gsap.to(aqFrameEl, { rotateY: 0, rotateX: 0, duration: 0.8, ease: "power2.out" });
      });
    }

    var torchLayer = document.getElementById("torchLayer");
    var darknessSection = document.getElementById("darkness");
    if (torchLayer && darknessSection) {
      darknessSection.addEventListener("pointermove", function (e) {
        var r = darknessSection.getBoundingClientRect();
        torchLayer.style.setProperty("--mx", ((e.clientX - r.left) / r.width) * 100 + "%");
        torchLayer.style.setProperty("--my", ((e.clientY - r.top) / r.height) * 100 + "%");
        torchLayer.style.opacity = "1";
      });
      darknessSection.addEventListener("pointerleave", function () {
        torchLayer.style.opacity = "0";
      });
    }

    document.querySelectorAll(".species-card").forEach(function (card) {
      card.addEventListener("pointermove", function (e) {
        var r = card.getBoundingClientRect();
        card.style.setProperty("--mx", ((e.clientX - r.left) / r.width) * 100 + "%");
        card.style.setProperty("--my", ((e.clientY - r.top) / r.height) * 100 + "%");
        var dx = (e.clientX - (r.left + r.width / 2)) / r.width;
        var dy = (e.clientY - (r.top + r.height / 2)) / r.height;
        gsap.to(card, { rotateY: dx * 5, rotateX: -dy * 5, y: -6, duration: 0.4, ease: "power2.out" });
      });
      card.addEventListener("pointerleave", function () {
        gsap.to(card, { rotateY: 0, rotateX: 0, y: 0, duration: 0.6, ease: "power2.out" });
      });
    });
  }

  /* ---------------------------------------------------------------
   * Scene-change flash pulse — theme.js already updates the rail
   * label text/fill; this just adds the brief light-pulse overlay each
   * time a scene becomes current, matching the original preview.
   * ----------------------------------------------------------- */
  if (!reducedMotion) {
    var pulse = document.getElementById("scenePulse");
    if (pulse) {
      ["hero", "darkness", "water", "aquarium", "life", "species", "geology", "story", "visit"].forEach(function (id) {
        var el = document.getElementById(id);
        if (!el) return;
        var flash = function () {
          gsap.fromTo(pulse, { opacity: 0 }, { opacity: 1, duration: 0.18, yoyo: true, repeat: 1, ease: "power1.inOut" });
        };
        ScrollTrigger.create({ trigger: el, start: "top 55%", end: "bottom 55%", onEnter: flash, onEnterBack: flash });
      });
    }
  }

  /* ---------------------------------------------------------------
   * Scene 01 — hero entrance timeline + push-through parallax.
   * ----------------------------------------------------------- */
  if (!reducedMotion) {
    gsap
      .timeline({ delay: curtain ? 2.15 : 0.3, defaults: { ease: "power3.out" } })
      .from(".hero-eyebrow", { opacity: 0, y: 16, duration: 0.7 })
      .from("#hero .line", { opacity: 0, y: 46, stagger: 0.14, duration: 0.9 }, "-=0.3")
      .from(".hero-lede", { opacity: 0, y: 16, duration: 0.7 }, "-=0.45")
      .from(".hero-cta", { opacity: 0, y: 16, scale: 0.88, duration: 0.6, ease: "back.out(1.8)" }, "-=0.35")
      .from(".scroll-hint", { opacity: 0, duration: 0.8 }, "-=0.25");

    gsap.to("#hero .scene-photo", {
      yPercent: 10,
      scale: 1.22,
      ease: "none",
      scrollTrigger: { trigger: "#hero", start: "top top", end: "bottom top", scrub: true },
    });
    gsap.to("#hero > .relative.z-10", {
      yPercent: -35,
      opacity: 0,
      ease: "none",
      scrollTrigger: { trigger: "#hero", start: "top top", end: "70% top", scrub: true },
    });
  }

  /* ---------------------------------------------------------------
   * Scene 02 — darkness: layered parallax + dolly zoom.
   * ----------------------------------------------------------- */
  var darkness = document.getElementById("darkness");
  if (darkness) {
    if (!reducedMotion) {
      gsap.utils.toArray(".parallax-layer", darkness).forEach(function (layer, i) {
        gsap.to(layer, {
          yPercent: (i + 1) * -10,
          ease: "none",
          scrollTrigger: { trigger: darkness, start: "top bottom", end: "bottom top", scrub: 0.6 },
        });
      });
      var darknessImg = darkness.querySelector(".parallax-layer img");
      if (darknessImg) {
        gsap.to(darknessImg, {
          scale: 1.2,
          ease: "none",
          scrollTrigger: { trigger: darkness, start: "top bottom", end: "bottom top", scrub: 0.6 },
        });
      }
    }
  }

  /* ---------------------------------------------------------------
   * Scene 03 — water: dolly zoom.
   * ----------------------------------------------------------- */
  if (!reducedMotion) {
    var water = document.getElementById("water");
    if (water) {
      gsap.to("#water .scene-photo", {
        scale: 1.16,
        ease: "none",
        scrollTrigger: { trigger: water, start: "top bottom", end: "bottom top", scrub: 0.6 },
      });
    }
  }

  /* ---------------------------------------------------------------
   * Scene 04 — aquarium reveal ("stone -> shadow -> light -> color ->
   * life"), shared photo-focus look with Scene 08 (setAquariumWindow).
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
    var pulseEl = root.querySelector(".js-aq-pulse, .js-story-pulse");
    if (pulseEl) {
      pulseEl.style.opacity = String(lightPulse * (1 - lightPulse) * 3.2);
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
  var aqPlankton = document.getElementById("aqPlankton");
  var aqSweep = document.getElementById("aqSweep");
  var aqSwept = false;

  // scatter plankton dots (deterministic layout, like the particle helper)
  if (aqPlankton) {
    for (var pi = 0; pi < 16; pi++) {
      var dot = document.createElement("span");
      dot.style.left = 8 + ((pi * 53) % 84) + "%";
      dot.style.top = 10 + ((pi * 37) % 80) + "%";
      dot.style.setProperty("--dx", (-16 + ((pi * 29) % 32)).toFixed(1) + "px");
      dot.style.animationDuration = 5 + (pi % 6) + "s";
      dot.style.animationDelay = "-" + ((pi % 10) * 0.7) + "s";
      aqPlankton.appendChild(dot);
    }
  }

  if (aquariumSection && aquariumWindow) {
    var applyAquarium = function (reveal) {
      var state = setAquariumWindow(aquariumWindow, reveal);
      if (aqPhase) aqPhase.textContent = phaseLabel(reveal);
      if (aqCaption) aqCaption.style.opacity = reveal > 0.75 ? "1" : "0";

      var scale = 0.86 + state.focus * 0.14;
      gsap.set(aquariumWindow, { scale: scale });

      if (aqPlankton) aqPlankton.style.opacity = reveal > 0.65 ? "1" : "0";

      if (aqSweep) {
        if (reveal > 0.92 && !aqSwept) {
          aqSwept = true;
          aqSweep.classList.add("go");
        } else if (reveal < 0.85 && aqSwept) {
          aqSwept = false;
          aqSweep.classList.remove("go");
        }
      }
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
   * Scene 06 — species: blur-in stagger, backdrop dolly zoom.
   * ----------------------------------------------------------- */
  if (!reducedMotion) {
    var speciesGrid = document.querySelector(".species-grid");
    if (speciesGrid) {
      gsap.from(".species-card", {
        opacity: 0,
        y: 40,
        scale: 0.94,
        filter: "blur(8px)",
        stagger: 0.12,
        duration: 0.85,
        ease: "power2.out",
        scrollTrigger: { trigger: speciesGrid, start: "top 82%" },
      });
    }
    var species = document.getElementById("species");
    if (species) {
      gsap.to("#species .scene-photo", {
        scale: 1.15,
        ease: "none",
        scrollTrigger: { trigger: species, start: "top bottom", end: "bottom top", scrub: 0.6 },
      });
    }
  }

  /* ---------------------------------------------------------------
   * Scene 08 — stone -> water -> life: rock shutters part (with a
   * crack-shard burst as they first start moving), aquarium behind
   * them reveals, chapter text crossfades with a back-ease pop.
   * ----------------------------------------------------------- */
  var storyChapters = [
    { title: "THE CAVE", persian: "غار", text: "میلیون‌ها سال در سکوت شکل گرفته." },
    { title: "THE WATER", persian: "آب", text: "آب، مسیر تازه‌ای برای زندگی ساخته است." },
    { title: "THE LIFE", persian: "زندگی", text: "حالا این تاریکی، خانه‌ی موجوداتی زنده است." },
  ];

  var storySection = document.getElementById("story");
  var storyStage = document.getElementById("storyStage");
  var storyWindow = document.getElementById("storyWindow");
  var storyStart = document.getElementById("storyShutterStart");
  var storyEnd = document.getElementById("storyShutterEnd");
  var storyShardsWrap = document.getElementById("storyShards");
  var storyBeam = document.getElementById("storyBeam");
  var storyBeamSoft = document.getElementById("storyBeamSoft");
  var storyLabel = document.getElementById("storyChapterLabel");
  var storyText = document.getElementById("storyChapterText");
  var lastChapter = -1;
  var cracked = false;

  var shardEls = [];
  if (storyShardsWrap) {
    for (var si = 0; si < 9; si++) {
      var sh = document.createElement("div");
      sh.className = "shard";
      sh.style.top = 12 + ((si * 61) % 78) + "%";
      sh.style.left = 47 + (((si % 3) - 1) * 2) + "%";
      sh.style.width = 10 + (si % 4) * 4 + "px";
      sh.style.transform = "rotate(" + ((si * 41) % 360) + "deg)";
      storyShardsWrap.appendChild(sh);
      shardEls.push(sh);
    }
  }

  function triggerCrack() {
    if (cracked || reducedMotion || !shardEls.length) return;
    cracked = true;
    gsap.fromTo(
      shardEls,
      { opacity: 0, y: 0, x: 0, rotate: 0, scale: 0.6 },
      {
        opacity: 1,
        scale: 1,
        y: function () {
          return gsap.utils.random(70, 190);
        },
        x: function () {
          return gsap.utils.random(-50, 50);
        },
        rotate: function () {
          return gsap.utils.random(180, 620);
        },
        duration: 1.1,
        ease: "power1.in",
        stagger: 0.025,
        onComplete: function () {
          gsap.to(shardEls, { opacity: 0, duration: 0.3 });
        },
      }
    );
    if (storyStage) {
      gsap
        .timeline()
        .to(storyStage, { x: -8, duration: 0.05 })
        .to(storyStage, { x: 7, duration: 0.06 })
        .to(storyStage, { x: -5, duration: 0.06 })
        .to(storyStage, { x: 3, duration: 0.06 })
        .to(storyStage, { x: 0, duration: 0.08 });
    }
  }
  function resetCrack() {
    if (!cracked) return;
    cracked = false;
    gsap.set(shardEls, { opacity: 0 });
  }

  function applyStory(p) {
    var partRaw = clamp01(p / 0.55);
    var partOpen = reducedMotion ? partRaw : clamp01(easeOutBack(partRaw));
    if (storyWindow) setAquariumWindow(storyWindow, Math.max(0, (p - 0.3) / 0.7));
    if (storyStart) storyStart.style.transform = "translateX(" + -partOpen * 100 + "%)";
    if (storyEnd) storyEnd.style.transform = "translateX(" + partOpen * 100 + "%)";

    if (p > 0.06) triggerCrack();
    else resetCrack();

    if (storyBeam || storyBeamSoft) {
      var beamPeak = mapRange(p, 0.1, 0.3) * (1 - mapRange(p, 0.4, 0.62));
      if (storyBeam) storyBeam.style.opacity = String(Math.max(0, beamPeak));
      if (storyBeamSoft) storyBeamSoft.style.opacity = String(Math.max(0, beamPeak * 0.7));
    }

    var chapterIndex = Math.min(storyChapters.length - 1, Math.floor(p * storyChapters.length));
    if (chapterIndex !== lastChapter) {
      lastChapter = chapterIndex;
      var chapter = storyChapters[chapterIndex];
      var setText = function () {
        if (storyLabel) storyLabel.textContent = chapter.title + " · " + chapter.persian;
        if (storyText) storyText.textContent = chapter.text;
      };
      if (reducedMotion || !storyLabel) {
        setText();
      } else {
        gsap.to([storyLabel, storyText], {
          opacity: 0,
          scale: 0.9,
          duration: 0.2,
          onComplete: function () {
            setText();
            gsap.to([storyLabel, storyText], { opacity: 1, scale: 1, duration: 0.4, ease: "back.out(1.6)" });
          },
        });
      }
    }
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
   * Scene 09 — visit: pull-back zoom + CTA stagger.
   * ----------------------------------------------------------- */
  if (!reducedMotion) {
    var visit = document.getElementById("visit");
    if (visit) {
      gsap.fromTo(
        "#visit .scene-photo",
        { scale: 1.2 },
        {
          scale: 1,
          ease: "none",
          scrollTrigger: { trigger: visit, start: "top bottom", end: "top 20%", scrub: 0.6 },
        }
      );
    }
    var ctaRow = document.querySelector(".cta-row");
    if (ctaRow) {
      gsap.from(ctaRow.children, {
        opacity: 0,
        y: 18,
        stagger: 0.1,
        duration: 0.6,
        ease: "back.out(1.6)",
        scrollTrigger: { trigger: ctaRow, start: "top 88%", toggleActions: "play none none reverse" },
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
