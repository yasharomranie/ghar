/**
 * Single article page — ported from TableOfContents.tsx (scroll-spy) and
 * ShareBar.tsx (copy-link).
 */
(function () {
  "use strict";

  /* ---------------------------------------------------------------
   * TOC scroll-spy.
   * ----------------------------------------------------------- */
  var toc = document.getElementById("articleToc");
  if (toc && "IntersectionObserver" in window) {
    var links = toc.querySelectorAll(".toc-link");
    var targets = Array.prototype.map
      .call(links, function (link) {
        var id = link.dataset.tocId;
        return { link: link, el: document.getElementById(id) };
      })
      .filter(function (t) {
        return !!t.el;
      });

    function setActive(id) {
      links.forEach(function (link) {
        var active = link.dataset.tocId === id;
        link.classList.toggle("border-accent", active);
        link.classList.toggle("text-ink", active);
        link.classList.toggle("border-ink/10", !active);
        link.classList.toggle("text-ink-dim", !active);
        if (active) {
          link.setAttribute("aria-current", "location");
        } else {
          link.removeAttribute("aria-current");
        }
      });
    }

    var observer = new IntersectionObserver(
      function (entries) {
        var visible = entries.filter(function (e) {
          return e.isIntersecting;
        });
        if (!visible.length) return;
        var topMost = visible.reduce(function (a, b) {
          return a.boundingClientRect.top < b.boundingClientRect.top ? a : b;
        });
        setActive(topMost.target.id);
      },
      { rootMargin: "-100px 0px -70% 0px", threshold: 0 }
    );

    targets.forEach(function (t) {
      observer.observe(t.el);
    });
  }

  /* ---------------------------------------------------------------
   * Copy-link button.
   * ----------------------------------------------------------- */
  var shareBtn = document.getElementById("shareCopyBtn");
  if (shareBtn) {
    var originalLabel = shareBtn.textContent;
    shareBtn.addEventListener("click", function () {
      var url = shareBtn.dataset.url || window.location.href;
      if (!navigator.clipboard) return;
      navigator.clipboard
        .writeText(url)
        .then(function () {
          shareBtn.textContent = "لینک کپی شد";
          window.setTimeout(function () {
            shareBtn.textContent = originalLabel;
          }, 2000);
        })
        .catch(function () {
          /* clipboard unavailable — no-op, same as the original */
        });
    });
  }
})();
