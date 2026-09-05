/**
 * Magazine listing — ported from:
 *  - src/components/magazine/HeroSlider.tsx
 *  - src/components/magazine/FeaturedCarousel.tsx
 *  - src/components/magazine/ArticleGrid.tsx (infinite scroll, here backed
 *    by the real REST API instead of cycling a fixed in-memory array)
 */
(function () {
  "use strict";

  var reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* ---------------------------------------------------------------
   * Hero slider.
   * ----------------------------------------------------------- */
  (function heroSlider() {
    var section = document.getElementById("heroSlider");
    if (!section) return;
    var slides = section.querySelectorAll(".hero-slide");
    var dots = section.querySelectorAll(".hero-dot");
    var prevBtn = document.getElementById("heroPrev");
    var nextBtn = document.getElementById("heroNext");
    var index = 0;
    var paused = false;
    var timer = null;
    var AUTOPLAY_MS = 6000;

    function goTo(i) {
      index = ((i % slides.length) + slides.length) % slides.length;
      slides.forEach(function (slide, si) {
        var active = si === index;
        slide.classList.toggle("opacity-100", active);
        slide.classList.toggle("opacity-0", !active);
        slide.classList.toggle("pointer-events-none", !active);
        slide.setAttribute("aria-hidden", String(!active));
      });
      dots.forEach(function (dot, di) {
        var active = di === index;
        dot.classList.toggle("w-6", active);
        dot.classList.toggle("bg-turquoise", active);
        dot.classList.toggle("w-1.5", !active);
        dot.classList.toggle("bg-foam/30", !active);
        dot.setAttribute("aria-current", String(active));
      });
    }

    function restart() {
      if (timer) clearInterval(timer);
      if (reducedMotion || paused || slides.length <= 1) return;
      timer = setInterval(function () {
        goTo(index + 1);
      }, AUTOPLAY_MS);
    }

    dots.forEach(function (dot) {
      dot.addEventListener("click", function () {
        goTo(parseInt(dot.dataset.index, 10));
        restart();
      });
    });
    if (prevBtn) prevBtn.addEventListener("click", function () { goTo(index - 1); restart(); });
    if (nextBtn) nextBtn.addEventListener("click", function () { goTo(index + 1); restart(); });

    section.addEventListener("mouseenter", function () { paused = true; restart(); });
    section.addEventListener("mouseleave", function () { paused = false; restart(); });
    section.addEventListener("focusin", function () { paused = true; restart(); });
    section.addEventListener("focusout", function () { paused = false; restart(); });

    restart();
  })();

  /* ---------------------------------------------------------------
   * Featured carousel.
   * ----------------------------------------------------------- */
  (function featuredCarousel() {
    var section = document.getElementById("featured");
    var track = document.getElementById("featuredTrack");
    if (!section || !track) return;
    var pages = track.children;
    var dots = document.querySelectorAll(".featured-dot");
    var page = 0;
    var paused = false;
    var timer = null;
    var AUTOPLAY_MS = 5000;

    function goTo(i) {
      page = ((i % pages.length) + pages.length) % pages.length;
      track.style.transform = "translateX(" + -page * 100 + "%)";
      Array.prototype.forEach.call(pages, function (p, pi) {
        p.setAttribute("aria-hidden", String(pi !== page));
      });
      dots.forEach(function (dot, di) {
        var active = di === page;
        dot.classList.toggle("w-6", active);
        dot.classList.toggle("bg-accent", active);
        dot.classList.toggle("w-1.5", !active);
        dot.classList.toggle("bg-ink/25", !active);
        dot.setAttribute("aria-current", String(active));
      });
    }

    function restart() {
      if (timer) clearInterval(timer);
      if (reducedMotion || paused || pages.length <= 1) return;
      timer = setInterval(function () {
        goTo(page + 1);
      }, AUTOPLAY_MS);
    }

    dots.forEach(function (dot) {
      dot.addEventListener("click", function () {
        goTo(parseInt(dot.dataset.index, 10));
        restart();
      });
    });

    section.addEventListener("mouseenter", function () { paused = true; restart(); });
    section.addEventListener("mouseleave", function () { paused = false; restart(); });

    restart();
  })();

  /* ---------------------------------------------------------------
   * Latest grid — infinite scroll against the real REST API, looping
   * back to the first page once the feed is exhausted (same "endless,
   * real cards" behaviour ArticleGrid.tsx had over its fixed array).
   * ----------------------------------------------------------- */
  (function latestGrid() {
    var grid = document.getElementById("latestGrid");
    var sentinel = document.getElementById("latestSentinel");
    var loadingEl = document.getElementById("latestLoading");
    if (!grid || !sentinel || typeof GHAR_MAGAZINE === "undefined") return;

    var page = 2; // page 1 was already rendered server-side
    var loading = false;
    var exhausted = false;

    function cardHtml(a) {
      var meta = a.ghar_meta || {};
      return (
        '<article dir="rtl" class="group relative flex h-full flex-col overflow-hidden rounded-2xl border border-ink/10 bg-surface-raised/50 transition-colors duration-300 hover:border-accent-dim">' +
        '<a href="' + meta.permalink + '" class="absolute inset-0 z-10 rounded-2xl"><span class="sr-only">' + meta.title + "</span></a>" +
        '<div class="relative aspect-[4/3] w-full overflow-hidden">' +
        '<img src="' + meta.image + '" alt="' + meta.title + '" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-110" />' +
        '<div class="absolute inset-0 bg-gradient-to-t from-void/70 via-void/0 to-void/0"></div>' +
        '<span class="absolute start-3 top-3 rounded-full bg-void/70 px-3 py-1 font-display text-[11px] text-turquoise-soft backdrop-blur">' + meta.category + "</span>" +
        "</div>" +
        '<div class="flex flex-1 flex-col gap-2 p-5">' +
        '<h3 class="line-clamp-2 font-display text-base font-semibold leading-snug text-ink">' + meta.title + "</h3>" +
        '<p class="line-clamp-2 flex-1 text-sm leading-relaxed text-ink-dim">' + meta.excerpt + "</p>" +
        '<div class="mt-2 flex items-center gap-3 text-xs text-ink-faint">' +
        "<span>" + meta.date_fa + "</span><span aria-hidden=\"true\">·</span><span>" + meta.readTime + "</span>" +
        "</div></div></article>"
      );
    }

    function loadMore() {
      if (loading || exhausted) return;
      loading = true;
      if (loadingEl) loadingEl.classList.remove("hidden");

      var url = GHAR_MAGAZINE.restUrl + "?per_page=6&page=" + page + "&orderby=date&order=desc&_=" + Date.now();

      fetch(url)
        .then(function (res) {
          if (!res.ok) {
            // Past the last page — loop back to page 1, same "endless,
            // real cards" fallback ArticleGrid.tsx had.
            exhausted = true;
            page = 1;
            return [];
          }
          page += 1;
          return res.json();
        })
        .then(function (posts) {
          window.setTimeout(function () {
            posts.forEach(function (post) {
              var div = document.createElement("div");
              div.innerHTML = cardHtml(post);
              grid.appendChild(div.firstElementChild);
            });
            loading = false;
            exhausted = false; // allow the next scroll to fetch page 1 again
            if (loadingEl) loadingEl.classList.add("hidden");
          }, 500);
        })
        .catch(function () {
          loading = false;
          if (loadingEl) loadingEl.classList.add("hidden");
        });
    }

    if ("IntersectionObserver" in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          if (entries[0].isIntersecting) loadMore();
        },
        { rootMargin: "600px 0px" }
      );
      observer.observe(sentinel);
    }
  })();
})();
