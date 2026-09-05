/**
 * Shared site chrome — runs on every page. Ported from:
 *  - src/components/Nav.tsx        (scroll state, mobile menu)
 *  - src/components/ThemeToggle.tsx + src/lib/theme.ts (theme toggle)
 *  - src/components/CustomCursor.tsx
 *  - src/components/ScrollProgress.tsx (front page only)
 */
(function () {
  "use strict";

  var reducedMotionQuery = window.matchMedia("(prefers-reduced-motion: reduce)");
  var coarsePointerQuery = window.matchMedia("(pointer: coarse)");

  function prefersReducedMotion() {
    return reducedMotionQuery.matches;
  }

  /* ---------------------------------------------------------------
   * Theme toggle — localStorage("ghar-theme"), default "dark".
   * ----------------------------------------------------------- */
  function currentTheme() {
    return document.documentElement.getAttribute("data-theme") === "light" ? "light" : "dark";
  }

  function applyTheme(theme) {
    document.documentElement.setAttribute("data-theme", theme);
    try {
      localStorage.setItem("ghar-theme", theme);
    } catch (e) {
      /* private-browsing / storage disabled — theme still applies for this load */
    }
    syncThemeButtons(theme);
  }

  function syncThemeButtons(theme) {
    var isLight = theme === "light";
    document.querySelectorAll("#themeToggle, #themeToggleMobile").forEach(function (btn) {
      btn.setAttribute("aria-pressed", String(isLight));
      btn.setAttribute("aria-label", isLight ? "فعال‌سازی حالت تاریک" : "فعال‌سازی حالت روشن");
    });
    var moon = document.getElementById("themeIconMoon");
    var sun = document.getElementById("themeIconSun");
    if (moon) moon.hidden = isLight;
    if (sun) sun.hidden = !isLight;
  }

  function toggleTheme() {
    applyTheme(currentTheme() === "light" ? "dark" : "light");
  }

  document.querySelectorAll("#themeToggle, #themeToggleMobile").forEach(function (btn) {
    btn.addEventListener("click", toggleTheme);
  });
  syncThemeButtons(currentTheme());

  /* ---------------------------------------------------------------
   * Header scroll state — .glass-nav once scrolled past 40px.
   * ----------------------------------------------------------- */
  var header = document.getElementById("site-header");
  if (header) {
    var onScroll = function () {
      header.classList.toggle("glass-nav", window.scrollY > 40);
      header.classList.toggle("border-transparent", window.scrollY <= 40);
      header.classList.toggle("bg-transparent", window.scrollY <= 40);
    };
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
  }

  /* ---------------------------------------------------------------
   * Mobile nav panel.
   * ----------------------------------------------------------- */
  var navToggle = document.getElementById("navToggle");
  var mobileNav = document.getElementById("mobileNav");
  if (navToggle && mobileNav) {
    var open = false;
    navToggle.addEventListener("click", function () {
      open = !open;
      navToggle.setAttribute("aria-expanded", String(open));
      navToggle.setAttribute("aria-label", open ? "بستن منو" : "باز کردن منو");
      if (open) {
        mobileNav.style.height = mobileNav.scrollHeight + "px";
        mobileNav.style.opacity = "1";
      } else {
        mobileNav.style.height = "0px";
        mobileNav.style.opacity = "0";
      }
      navToggle.querySelectorAll("span > span").forEach(function (bar, i) {
        if (i === 0) bar.style.transform = open ? "translateY(6px) rotate(45deg)" : "";
        if (i === 1) bar.style.transform = open ? "translateY(-6px) rotate(-45deg)" : "";
      });
    });
    mobileNav.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () {
        if (!open) return;
        open = false;
        mobileNav.style.height = "0px";
        mobileNav.style.opacity = "0";
        navToggle.setAttribute("aria-expanded", "false");
      });
    });
  }

  /* ---------------------------------------------------------------
   * Custom cursor — fine pointer + motion-ok only, mirrors
   * CustomCursor.tsx's 0.25 lerp-follow + grow-on-[data-cursor].
   * ----------------------------------------------------------- */
  var cursor = document.getElementById("customCursor");
  var cursorLabel = document.getElementById("customCursorLabel");
  if (cursor && !coarsePointerQuery.matches && !prefersReducedMotion()) {
    cursor.classList.add("is-visible");
    var x = window.innerWidth / 2;
    var y = window.innerHeight / 2;
    var rx = x;
    var ry = y;

    window.addEventListener(
      "pointermove",
      function (e) {
        x = e.clientX;
        y = e.clientY;
        var el = e.target.closest ? e.target.closest("[data-cursor]") : null;
        if (el) {
          cursor.style.width = "72px";
          cursor.style.height = "72px";
          cursorLabel.textContent = el.dataset.cursor || "";
        } else {
          cursor.style.width = "8px";
          cursor.style.height = "8px";
          cursorLabel.textContent = "";
        }
      },
      { passive: true }
    );

    (function loop() {
      rx += (x - rx) * 0.25;
      ry += (y - ry) * 0.25;
      cursor.style.transform = "translate(" + rx + "px, " + ry + "px) translate(-50%, -50%)";
      requestAnimationFrame(loop);
    })();
  }

  /* ---------------------------------------------------------------
   * Scroll progress rail — front page only (element only exists there).
   * ----------------------------------------------------------- */
  var rail = document.getElementById("scrollRail");
  if (rail) {
    var railFill = document.getElementById("railFill");
    var railLabel = document.getElementById("railLabel");
    var scenes = [
      { id: "hero", index: "۰۱", label: "ورود" },
      { id: "darkness", index: "۰۲", label: "تاریکی" },
      { id: "water", index: "۰۳", label: "نخستین آب" },
      { id: "aquarium", index: "۰۴", label: "آکواریوم" },
      { id: "life", index: "۰۵", label: "دنیای زنده" },
      { id: "species", index: "۰۶", label: "گونه‌ها" },
      { id: "geology", index: "۰۷", label: "زمین‌شناسی" },
      { id: "story", index: "۰۸", label: "سنگ، آب، زندگی" },
      { id: "visit", index: "۰۹", label: "بازدید" },
    ];

    var onRailScroll = function () {
      var doc = document.documentElement;
      var max = doc.scrollHeight - doc.clientHeight;
      var progress = max > 0 ? Math.min(1, window.scrollY / max) : 0;
      railFill.style.height = progress * 100 + "%";
    };
    onRailScroll();
    window.addEventListener("scroll", onRailScroll, { passive: true });

    var els = scenes
      .map(function (s) {
        return { s: s, el: document.getElementById(s.id) };
      })
      .filter(function (x) {
        return !!x.el;
      });

    if (els.length && "IntersectionObserver" in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            var match = els.find(function (x) {
              return x.el === entry.target;
            });
            if (match) {
              railLabel.textContent = match.s.index + " / " + match.s.label;
            }
          });
        },
        { rootMargin: "-45% 0px -45% 0px", threshold: 0 }
      );
      els.forEach(function (x) {
        observer.observe(x.el);
      });
    }
  }
})();
