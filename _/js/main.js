/**
 * NUNDÌA – front-end behaviour (vanilla JS, no dependencies).
 */
(function () {
  'use strict';

  var cfg = window.nundia_ajax || {};
  var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
  var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };
  var swap = function (el, remove, add) {
    (remove || '').split(/\s+/).filter(Boolean).forEach(function (c) { el.classList.remove(c); });
    (add || '').split(/\s+/).filter(Boolean).forEach(function (c) { el.classList.add(c); });
  };
  var post = function (action, data) {
    var fd = data instanceof FormData ? data : new FormData();
    if (!(data instanceof FormData) && data) Object.keys(data).forEach(function (k) { fd.append(k, data[k]); });
    fd.append('action', action);
    fd.append('nonce', cfg.nonce || '');
    return fetch(cfg.ajax_url, { method: 'POST', body: fd, credentials: 'same-origin' }).then(function (r) { return r.json(); });
  };

  /* 1. Header: transparent on top of the home hero, solid after 30px or when the menu is open */
  var header = $('.nd-header');
  var SOLID = 'border-b border-border bg-background/92 text-foreground backdrop-blur-xl';
  var menuOpen = false;
  var paintHeader = function () {
    if (!header) return;
    var isScrolled = window.scrollY > 30;
    header.classList.toggle('is-scrolled', isScrolled);

    if (header.dataset.transparent === 'true') {
      var solid = isScrolled || menuOpen;
      if (solid) swap(header, 'text-white', SOLID); else swap(header, SOLID, 'text-white');
    }
  };
  window.addEventListener('scroll', paintHeader, { passive: true });
  paintHeader();

  /* 2. Mobile menu */
  var toggle = $('.js-menu-toggle');
  var panel = $('#nd-mobile-menu');
  var setMenu = function (open) {
    if (!header || !panel) return;
    menuOpen = open;
    header.classList.toggle('is-menu-open', open);
    panel.setAttribute('aria-hidden', open ? 'false' : 'true');
    if (toggle) toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    document.body.style.overflow = open ? 'hidden' : '';
    paintHeader();
  };
  if (toggle) toggle.addEventListener('click', function () { setMenu(!menuOpen); });
  if (panel) $$('a', panel).forEach(function (a) { a.addEventListener('click', function () { setMenu(false); }); });
  window.addEventListener('resize', function () { if (window.innerWidth >= 1024 && menuOpen) setMenu(false); });

  /* 3. Reveal on scroll */
  var reveals = $$('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add('is-visible'); });
  }

  /* 4. Swiper JS Initializations (Hero, Bestsellers, News, Testimonials, Banner) */
  if (typeof Swiper !== 'undefined') {
    /* 4a. Home Hero Swiper (crossfade with automatic advance) */
    var heroEl = $('.hero-swiper');
    if (heroEl) {
      var heroSlides = $$('.swiper-slide', heroEl);
      if (heroSlides.length > 1) {
        var heroInterval = parseInt(heroEl.dataset.interval, 10) || 9000;
        new Swiper('.hero-swiper', {
          effect: 'fade',
          fadeEffect: {
            crossFade: true,
          },
          speed: 1200,
          loop: true,
          autoplay: {
            delay: heroInterval,
            disableOnInteraction: false,
          },
          allowTouchMove: true,
        });
      }
    }

    /* 4b. Bestsellers Product Swiper (Desktop: 4, Tablet: 2, Mobile: 1, Gap: 20px) */
    if ($('.bestsellers-swiper')) {
      new Swiper('.bestsellers-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
          prevEl: '.nd-bestsellers-prev',
          nextEl: '.nd-bestsellers-next',
        },
        mousewheel: {
          forceToAxis: true,
          releaseOnEdges: true,
        },
        breakpoints: {
          640: {
            slidesPerView: 2,
            spaceBetween: 20,
          },
          1024: {
            slidesPerView: 4,
            spaceBetween: 20,
          },
        },
      });
    }

    /* 4c. Notizie / News Swiper (Desktop: 4, Tablet: 2, Mobile: 1, Gap: 20px) */
    if ($('.news-swiper')) {
      window.newsSwiperInstance = new Swiper('.news-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
          prevEl: '.nd-news-prev',
          nextEl: '.nd-news-next',
        },
        mousewheel: {
          forceToAxis: true,
          releaseOnEdges: true,
        },
        breakpoints: {
          640: {
            slidesPerView: 2,
            spaceBetween: 20,
          },
          1024: {
            slidesPerView: 4,
            spaceBetween: 20,
          },
        },
      });
    }

    /* 4d. Testimonials Swiper */
    if ($('.testimonial-swiper')) {
      new Swiper('.testimonial-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
          delay: 6000,
          disableOnInteraction: false,
        },
        pagination: {
          el: '.testimonial-swiper .swiper-pagination',
          clickable: true,
        },
        breakpoints: {
          640: {
            slidesPerView: 2,
            spaceBetween: 20,
          },
        },
      });
    }

    /* 4e. Banner Slider Module Swiper */
    if ($('.bannerSlider')) {
      new Swiper('.bannerSlider', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },
        pagination: {
          el: '.bannerSlider .swiper-pagination',
          clickable: true,
        },
      });
    }
  }

  /* 5. Fallback for any non-Swiper carousel tracks */
  $$('.nd-carousel-track').forEach(function (track) {
    var c = track.closest('.nd-carousel');
    if (!track) return;

    if (c) {
      $$('.nd-carousel-btn', c).forEach(function (b) {
        b.addEventListener('click', function () {
          var card = track.firstElementChild;
          var gap = parseFloat(window.getComputedStyle(track).gap) || 20;
          var step = card ? (card.getBoundingClientRect().width + gap) : track.clientWidth * 0.8;
          track.scrollBy({ left: step * parseInt(b.dataset.dir, 10), behavior: 'smooth' });
        });
      });
    }

    /* Mouse wheel scroll: rolling wheel up/down slides the carousel horizontally */
    track.addEventListener('wheel', function (e) {
      if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
        var maxScroll = track.scrollWidth - track.clientWidth;
        if (maxScroll > 4) {
          var atStart = track.scrollLeft <= 4 && e.deltaY < 0;
          var atEnd = track.scrollLeft >= maxScroll - 4 && e.deltaY > 0;
          if (!atStart && !atEnd) {
            e.preventDefault();
            track.scrollBy({ left: e.deltaY * 1.3, behavior: 'auto' });
          }
        }
      }
    }, { passive: false });
  });

  /* 6. Cart: add to cart, badge, quantity selector, cart page updates */
  var setCount = function (n) {
    $$('.js-cart-count').forEach(function (b) { b.textContent = n; b.classList.toggle('hidden', !n); });
  };
  $$('.js-qty').forEach(function (b) {
    b.addEventListener('click', function () {
      var wrap = b.closest('.js-qty-wrap');
      var val = $('.js-qty-value', wrap);
      var q = Math.max(1, (parseInt(val.textContent, 10) || 1) + parseInt(b.dataset.step, 10));
      val.textContent = q;
      var add = $('.js-add-to-cart', wrap);
      if (add) add.dataset.quantity = q;

      // Dynamically update product price when quantity changes
      var priceEl = $('.js-single-price');
      if (priceEl && priceEl.dataset.unitPrice) {
        var unitPrice = parseFloat(priceEl.dataset.unitPrice) || 0;
        var total = unitPrice * q;
        try {
          priceEl.textContent = new Intl.NumberFormat('it-IT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total) + ' €';
        } catch (err) {
          priceEl.textContent = total.toFixed(2).replace('.', ',') + ' €';
        }
      }
    });
  });
  var CHECK = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check h-4 w-4" aria-hidden="true"><path d="M20 6 9 17l-5-5"></path></svg>';
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.js-add-to-cart');
    if (!btn || btn.disabled) return;
    e.preventDefault();
    btn.classList.add('is-loading');
    post('nundia_add_to_cart', { product_id: btn.dataset.productId, quantity: btn.dataset.quantity || 1 })
      .then(function (res) {
        btn.classList.remove('is-loading');
        if (!res.success) return;
        setCount(res.data.count);
        btn.innerHTML = (btn.dataset.check ? CHECK : '') + btn.dataset.done;
        clearTimeout(btn._t);
        btn._t = setTimeout(function () { btn.textContent = btn.dataset.label; }, 2200);
      })
      .catch(function () { btn.classList.remove('is-loading'); });
  });
  $$('.js-cart-set').forEach(function (b) {
    b.addEventListener('click', function () {
      var line = b.closest('.js-cart-line');
      line.style.opacity = '.5';
      post('nundia_update_cart_item', { cart_item_key: line.dataset.key, quantity: b.dataset.qty })
        .then(function () { window.location.reload(); });
    });
  });

  /* 7. Filters, search and sort (shop, news, recipes, FAQ) */
  var applyFilter = function (target) {
    var grid = $(target);
    if (!grid) return;
    var cat = grid.dataset.filter || '';
    var q = (grid.dataset.query || '').trim().toLowerCase();
    var visible = 0;
    $$('[data-item], .nd-faq', grid).forEach(function (it) {
      var cats = (it.dataset.cat || '').split(' ');
      var ok = (!cat || cats.indexOf(cat) !== -1) && (!q || (it.dataset.text || it.textContent.toLowerCase()).indexOf(q) !== -1);
      it.style.display = ok ? '' : 'none';
      if (ok) visible++;
    });
    var section = grid.closest('section');
    var empty = section && $('.js-empty', section);
    if (empty) empty.classList.toggle('hidden', visible > 0);
    if (target === '#news-list' && window.newsSwiperInstance) {
      window.newsSwiperInstance.update();
      window.newsSwiperInstance.slideTo(0);
    }
    return visible;
  };
  $$('.js-filter').forEach(function (bar) {
    var target = bar.dataset.target;
    var grid = $(target);
    var buttons = $$('button[data-filter]', bar);
    var select = function (btn) {
      buttons.forEach(function (b) { swap(b, bar.dataset.active, bar.dataset.inactive); });
      swap(btn, bar.dataset.inactive, bar.dataset.active);
      grid.dataset.filter = btn.dataset.filter;
      var n = applyFilter(target);
      if (bar.dataset.count && $(bar.dataset.count)) $(bar.dataset.count).textContent = n;
    };
    buttons.forEach(function (b) { b.addEventListener('click', function () { select(b); }); });
    var initial = grid && grid.dataset.initialFilter;
    if (initial) {
      var b = buttons.filter(function (x) { return x.dataset.filter === initial; })[0];
      if (b) select(b);
    }
  });
  $$('input[data-search]').forEach(function (input) {
    input.addEventListener('input', function () {
      var grid = $(input.dataset.search);
      grid.dataset.query = input.value;
      var n = applyFilter(input.dataset.search);
      var count = $('#product-count');
      if (count && input.dataset.search === '#product-grid') count.textContent = n;
    });
  });
  $$('select[data-sort]').forEach(function (sel) {
    sel.addEventListener('change', function () {
      var grid = $(sel.dataset.sort);
      var key = sel.value.replace('-', '');
      var dir = sel.value.charAt(0) === '-' ? -1 : 1;
      $$('[data-item]', grid)
        .sort(function (a, b) { return (parseFloat(a.dataset[key]) - parseFloat(b.dataset[key])) * dir; })
        .forEach(function (el) { grid.appendChild(el); });
    });
  });

  /* 8. FAQ accordion */
  $$('.nd-faq').forEach(function (item) {
    var btn = $('button', item);
    btn.addEventListener('click', function () {
      var open = !item.classList.contains('is-open');
      item.classList.toggle('is-open', open);
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });

  /* 9. AJAX forms (newsletter, contact, B2B) */
  $$('.js-ajax-form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var msg = $('.js-form-msg', form) || $('.js-form-msg', form.parentNode);
      if (!form.checkValidity()) { form.reportValidity(); return; }
      var btn = $('button[type="submit"]', form);
      btn.disabled = true;
      post(form.dataset.action, new FormData(form))
        .then(function (res) {
          btn.disabled = false;
          if (msg) {
            msg.hidden = false;
            msg.textContent = (res.data && res.data.message) || '';
            msg.style.color = res.success ? '' : 'hsl(var(--primary))';
          }
          if (res.success) form.reset();
        })
        .catch(function () { btn.disabled = false; });
    });
  });

  /* 10. Cookie consent */
  var KEY = 'nundia_cookie_consent';
  var banner = $('.nd-cookie');
  var store = {
    get: function () { try { return localStorage.getItem(KEY); } catch (e) { return 'x'; } },
    set: function (v) { try { localStorage.setItem(KEY, JSON.stringify(v)); } catch (e) {} }
  };
  if (banner) {
    var prefs = $('.nd-cookie-prefs', banner);
    var close = function () { banner.hidden = true; };
    if (!store.get()) banner.hidden = false;
    $$('[data-consent]', banner).forEach(function (b) {
      b.addEventListener('click', function () {
        var kind = b.dataset.consent;
        if (kind === 'custom' && prefs.hidden) { prefs.hidden = false; b.textContent = 'Salva preferenze'; return; }
        var v = { necessary: true, analytics: kind === 'all', marketing: kind === 'all', decided: true };
        if (kind === 'custom') { v.analytics = $('[name="analytics"]', prefs).checked; v.marketing = $('[name="marketing"]', prefs).checked; }
        store.set(v);
        close();
      });
    });
    $('.js-cookie-close', banner).addEventListener('click', function () { store.set({ necessary: true, analytics: false, marketing: false, decided: true }); close(); });
    $$('.js-cookie-prefs').forEach(function (b) { b.addEventListener('click', function () { banner.hidden = false; }); });
  }

  /* 11. Language dropdown (Google Translate, Italian is the source language) */
  var setCookie = function (v) {
    var exp = v ? '' : '; expires=Thu, 01 Jan 1970 00:00:00 GMT';
    document.cookie = 'googtrans=' + v + '; path=/' + exp;
    document.cookie = 'googtrans=' + v + '; path=/; domain=' + location.hostname + exp;
    var parts = location.hostname.split('.');
    if (parts.length > 1) document.cookie = 'googtrans=' + v + '; path=/; domain=.' + parts.slice(-2).join('.') + exp;
  };
  $$('[data-lang-switcher]').forEach(function (sw) {
    var btn = $('.nd-lang-btn', sw);
    var menu = $('.nd-lang-menu', sw);
    var open = function (o) { menu.hidden = !o; sw.classList.toggle('is-open', o); btn.setAttribute('aria-expanded', o ? 'true' : 'false'); };
    btn.addEventListener('click', function (e) { e.stopPropagation(); open(menu.hidden); });
    document.addEventListener('click', function (e) { if (!sw.contains(e.target)) open(false); });
    $$('[data-lang]', menu).forEach(function (item) {
      item.addEventListener('click', function () {
        var lang = item.dataset.lang;
        open(false);
        if (lang === (cfg.lang || 'it')) return;
        setCookie(lang === 'it' ? '' : '/it/' + lang);
        window.location.reload();
      });
    });
  });
  if (cfg.lang && cfg.lang !== 'it') {
    document.documentElement.setAttribute('lang', cfg.lang);
    if (cfg.lang === 'ar') document.documentElement.setAttribute('dir', 'rtl');
    window.googleTranslateElementInit = function () {
      /* global google */
      new google.translate.TranslateElement({ pageLanguage: 'it', autoDisplay: false }, 'google_translate_element');
    };
    var s = document.createElement('script');
    s.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
    s.async = true;
    document.body.appendChild(s);
  }

  /* 12. Keyboard + misc */
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      if (menuOpen) setMenu(false);
      $$('[data-lang-switcher]').forEach(function (sw) { var m = $('.nd-lang-menu', sw); if (m) m.hidden = true; sw.classList.remove('is-open'); });
    }
  });
  $$('.js-copy-link').forEach(function (b) {
    b.addEventListener('click', function () {
      if (navigator.clipboard) navigator.clipboard.writeText(b.dataset.url);
    });
  });
})();
