(function () {
  var cfg = window.DBIM_LANG || { default: "en", supported: {} };
  var root = document.documentElement;
  var HTML_LANG = { gom: "kok" };

  function store(key, val) {
    try {
      return val === undefined
        ? localStorage.getItem(key)
        : localStorage.setItem(key, val);
    } catch (e) {
      return null;
    }
  }

  function currentLang() {
    var m = document.cookie.match(/(?:^|; )googtrans=\/[^/]*\/([^;]+)/);
    return m && cfg.supported && cfg.supported[m[1]] ? m[1] : cfg.default;
  }

  function writeCookie(value, maxAge) {
    var hostname = location.hostname;
    var attrs = "; path=/; max-age=" + maxAge;
    if (location.protocol === "https:") {
      attrs += "; Secure; SameSite=None";
    }
    // host-only cookie (works for IP/localhost)
    document.cookie = "googtrans=" + value + attrs;
    // also attempt top-level domain cookies for real hostnames
    if (hostname.indexOf(".") > 0 && !/^\d+\.\d+\.\d+\.\d+$/.test(hostname)) {
      try {
        var parts = hostname.split(".");
        var topDomain = "." + parts.slice(-2).join(".");
        document.cookie =
          "googtrans=" + value + attrs + "; domain=" + topDomain;
        document.cookie = "googtrans=" + value + attrs + "; domain=" + hostname;
      } catch (e) {
        document.cookie = "googtrans=" + value + attrs + "; domain=" + hostname;
      }
    }
  }

  function setLanguage(code) {
    if (!cfg || !cfg.default) return;
    if (code === cfg.default) {
      writeCookie("", 0);
    } else {
      writeCookie("/" + cfg.default + "/" + code, 31536000);
    }
    store("dbim-lang-chosen", "1");
    var urlNoHash = location.pathname + location.search;
    location.replace(urlNoHash);
  }

  function loadGoogle() {
    window.googleTranslateInit = function () {
      try {
        new google.translate.TranslateElement(
          {
            pageLanguage: cfg.default,
            includedLanguages: Object.keys(cfg.supported).join(","),
            autoDisplay: false,
          },
          "google_translate_element",
        );
      } catch (e) {}
    };
    if (!document.querySelector('script[src*="translate_a/element.js"]')) {
      var s = document.createElement("script");
      s.src =
        "https://translate.google.com/translate_a/element.js?cb=googleTranslateInit";
      document.head.appendChild(s);
    }
  }

  function markActive(lang) {
    document.querySelectorAll("[data-lang]").forEach(function (a) {
      var on = a.dataset.lang === lang;
      a.classList.toggle("active", on);
      if (on) {
        a.setAttribute("aria-current", "true");
      } else {
        a.removeAttribute("aria-current");
      }
    });
  }

  function showPopupOnFirstVisit() {
    if (store("dbim-lang-chosen") || !window.bootstrap) {
      return;
    }
    try {
      new bootstrap.Modal("#langModal").show();
    } catch (e) {}
  }

  function init() {
    var lang = currentLang();
    if (lang !== cfg.default) {
      loadGoogle();
    }
    markActive(lang);
    showPopupOnFirstVisit();
    document.addEventListener(
      "click",
      function (e) {
        var a = e.target.closest("[data-lang]");
        if (a) {
          e.preventDefault();
          e.stopPropagation();
          setLanguage(a.dataset.lang);
        }
      },
      true,
    );
  }

  document.addEventListener("DOMContentLoaded", init);
})();
