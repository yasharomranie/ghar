export const THEME_STORAGE_KEY = "ghar-theme";

/**
 * Runs synchronously in <head>, before hydration, so the page never flashes
 * the wrong theme. Kept as a plain string (not a .ts file imported as a
 * script) so it can be inlined via dangerouslySetInnerHTML in layout.tsx —
 * this must stay dependency-free and safe to run before React exists.
 */
export const themeInitScript = `(function(){try{var t=localStorage.getItem(${JSON.stringify(
  THEME_STORAGE_KEY,
)});if(t!=="light"&&t!=="dark"){t="dark"}document.documentElement.setAttribute("data-theme",t);}catch(e){}})();`;
