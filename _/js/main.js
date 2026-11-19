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
    post('nundia_add_to_cart', {
      product_id: btn.dataset.productId,
      quantity: btn.dataset.quantity || 1,
      format: btn.dataset.format || '',
      price: btn.dataset.price || ''
    })
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

  /* Format selector on single product */
  $$('.js-format-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (btn.disabled) return;
      var selector = btn.closest('.js-format-selector');
      if (selector) {
        $$('.js-format-btn', selector).forEach(function (b) {
          b.classList.remove('border-primary', 'bg-primary/5', 'text-primary', 'ring-1', 'ring-primary');
          b.classList.add('border-border', 'bg-white', 'text-foreground');
        });
      }
      btn.classList.remove('border-border', 'bg-white', 'text-foreground');
      btn.classList.add('border-primary', 'bg-primary/5', 'text-primary', 'ring-1', 'ring-primary');

      var price = parseFloat(btn.dataset.price) || 0;
      var netWeight = btn.dataset.netWeight || btn.dataset.format || '';
      var inStock = btn.dataset.inStock === '1';

      var priceEl = $('.js-single-price');
      var wrap = $('.js-qty-wrap');
      var qtyVal = wrap ? ($('.js-qty-value', wrap) ? parseInt($('.js-qty-value', wrap).textContent, 10) : 1) : 1;
      if (priceEl) {
        priceEl.dataset.unitPrice = price;
        var total = price * qtyVal;
        try {
          priceEl.textContent = new Intl.NumberFormat('it-IT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total) + ' €';
        } catch (err) {
          priceEl.textContent = total.toFixed(2).replace('.', ',') + ' €';
        }
      }

      var stockLabel = $('.js-stock-label');
      var stockDot = $('.js-stock-dot');
      if (stockLabel) stockLabel.textContent = inStock ? 'Disponibile' : 'Non disponibile';
      if (stockDot) {
        stockDot.classList.toggle('bg-green-600', inStock);
        stockDot.classList.toggle('bg-primary', !inStock);
      }

      $$('.js-eyebrow-weight, .js-tab-net-weight').forEach(function (el) {
        el.textContent = netWeight;
      });

      var addBtn = $('.js-add-to-cart');
      if (addBtn) {
        addBtn.dataset.format = btn.dataset.format;
        addBtn.dataset.price = price;
        addBtn.disabled = !inStock;
      }
    });
  });

  /* Product tabs on single product */
  $$('.js-tab-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var tabId = btn.dataset.tab;
      var nav = btn.closest('.js-tabs-nav');
      if (nav) {
        $$('.js-tab-btn', nav).forEach(function (b) {
          b.classList.remove('border-primary', 'text-primary');
          b.classList.add('border-transparent', 'text-muted-foreground');
        });
      }
      btn.classList.remove('border-transparent', 'text-muted-foreground');
      btn.classList.add('border-primary', 'text-primary');

      var contentWrap = nav ? nav.closest('div.border-t') : null;
      var scope = contentWrap || document;
      $$('.js-tab-pane', scope).forEach(function (pane) {
        if (pane.dataset.pane === tabId) {
          pane.classList.remove('hidden');
        } else {
          pane.classList.add('hidden');
        }
      });
    });
  });

  /* Cart Shipping Destination & Live Totals */
  var initCartShipping = function () {
    var countrySelect = $('#js-shipping-country');
    if (!countrySelect) return;

    var updateCartTotals = function () {
      var selected = countrySelect.options[countrySelect.selectedIndex];
      var baseCost = parseFloat(selected.dataset.cost) || 0;
      var subtotal = parseFloat(countrySelect.dataset.subtotal) || 0;
      var freeThreshold = parseFloat(countrySelect.dataset.threshold) || 0;

      var shippingCost = (freeThreshold > 0 && subtotal >= freeThreshold) ? 0 : baseCost;
      var grandTotal = subtotal + shippingCost;

      var costEl = $('.js-shipping-cost');
      if (costEl) {
        if (shippingCost === 0) {
          costEl.textContent = 'GRATUITA (0,00 €)';
          costEl.classList.add('text-green-600');
        } else {
          costEl.textContent = new Intl.NumberFormat('it-IT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(shippingCost) + ' €';
          costEl.classList.remove('text-green-600');
        }
      }

      var grandEl = $('.js-grand-total');
      if (grandEl) {
        grandEl.textContent = new Intl.NumberFormat('it-IT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(grandTotal) + ' €';
      }

      try {
        localStorage.setItem('nundia_shipping_country', selected.value);
        localStorage.setItem('nundia_shipping_cost', shippingCost);
      } catch (e) {}
    };

    try {
      var saved = localStorage.getItem('nundia_shipping_country');
      if (saved) {
        for (var i = 0; i < countrySelect.options.length; i++) {
          if (countrySelect.options[i].value === saved) {
            countrySelect.selectedIndex = i;
            break;
          }
        }
      }
    } catch (e) {}

    countrySelect.addEventListener('change', updateCartTotals);
    updateCartTotals();
  };
  initCartShipping();

  /* 6b. Checkout Shipping Destination & Totals Live Sync */
  var initCheckoutShipping = function () {
    var countryInput = $('#js-checkout-country-input');
    if (!countryInput) return;

    try {
      var savedCountry = localStorage.getItem('nundia_shipping_country');
      var savedCost = parseFloat(localStorage.getItem('nundia_shipping_cost'));
      if (savedCountry) {
        countryInput.value = savedCountry;
        var addressCountry = $('[name="nd_country"]');
        if (addressCountry && (!addressCountry.value || addressCountry.value === 'Italia')) {
          var nameMap = { IT: 'Italia', DE: 'Germania', FR: 'Francia', ES: 'Spagna', US: 'Stati Uniti', GB: 'Regno Unito', AT: 'Austria', BE: 'Belgio', CH: 'Svizzera' };
          if (nameMap[savedCountry]) addressCountry.value = nameMap[savedCountry];
        }
      }
      if (!isNaN(savedCost)) {
        var costInput = $('#js-checkout-shipping-input');
        if (costInput) costInput.value = savedCost;

        var costLabel = $('.js-checkout-shipping');
        if (costLabel) {
          if (savedCost === 0) {
            costLabel.textContent = 'GRATUITA (0,00 €)';
            costLabel.classList.add('text-green-600');
          } else {
            costLabel.textContent = new Intl.NumberFormat('it-IT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(savedCost) + ' €';
            costLabel.classList.remove('text-green-600');
          }
        }

        var totalLabel = $('.js-checkout-total');
        if (totalLabel) {
          var subtotalBox = costLabel ? costLabel.closest('.space-y-2.5') : null;
          var subtotalEl = subtotalBox ? subtotalBox.querySelector('.tabular-nums') : null;
          if (subtotalEl) {
            var raw = subtotalEl.textContent.replace(/[^0-9,.]/g, '').replace(',', '.');
            var subVal = parseFloat(raw) || 0;
            var grand = subVal + savedCost;
            totalLabel.textContent = new Intl.NumberFormat('it-IT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(grand) + ' €';
          }
        }
      }
    } catch (e) {}
  };
  initCheckoutShipping();

  $$('.js-cart-set').forEach(function (b) {
    b.addEventListener('click', function () {
      var line = b.closest('.js-cart-line');
      if (!line) return;
      line.style.opacity = '.5';
      post('nundia_update_cart_item', { cart_item_key: line.dataset.key, quantity: b.dataset.qty })
        .then(function () { window.location.reload(); });
    });
  });

  /* 7. Filters, search and sort (shop, news, recipes, FAQ) */
  var applyFilter = function (target) {
    var grid = $(target);
    if (!grid) return 0;
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
      try {
        window.newsSwiperInstance.update();
        window.newsSwiperInstance.slideTo(0);
      } catch (err) {}
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
      if (grid) grid.dataset.filter = btn.dataset.filter;
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
      if (grid) grid.dataset.query = input.value;
      var n = applyFilter(input.dataset.search);
      var count = $('#product-count');
      if (count && input.dataset.search === '#product-grid') count.textContent = n;
    });
  });
  $$('select[data-sort]').forEach(function (sel) {
    sel.addEventListener('change', function () {
      var grid = $(sel.dataset.sort);
      if (!grid) return;
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
    if (!btn) return;
    btn.addEventListener('click', function () {
      var open = !item.classList.contains('is-open');
      item.classList.toggle('is-open', open);
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });

  /* 9. AJAX forms (newsletter, contact, B2B, partner) */
  $$('.js-ajax-form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var msg = $('.js-form-msg', form) || $('.js-form-msg', form.parentNode);
      if (!form.checkValidity()) { form.reportValidity(); return; }
      var btn = $('button[type="submit"]', form);
      if (btn) btn.disabled = true;
      post(form.dataset.action, new FormData(form))
        .then(function (res) {
          if (btn) btn.disabled = false;
          if (msg) {
            msg.hidden = false;
            msg.textContent = (res.data && res.data.message) || '';
            msg.style.color = res.success ? '' : 'hsl(var(--primary))';
          }
          if (res.success) form.reset();
        })
        .catch(function () { if (btn) btn.disabled = false; });
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
        if (kind === 'custom' && prefs && prefs.hidden) { prefs.hidden = false; b.textContent = 'Salva preferenze'; return; }
        var v = { necessary: true, analytics: kind === 'all', marketing: kind === 'all', decided: true };
        if (kind === 'custom' && prefs) {
          var an = $('[name="analytics"]', prefs);
          var mk = $('[name="marketing"]', prefs);
          v.analytics = an ? an.checked : false;
          v.marketing = mk ? mk.checked : false;
        }
        store.set(v);
        close();
      });
    });
    var cookieClose = $('.js-cookie-close', banner);
    if (cookieClose) {
      cookieClose.addEventListener('click', function () { store.set({ necessary: true, analytics: false, marketing: false, decided: true }); close(); });
    }
    $$('.js-cookie-prefs').forEach(function (b) {
      b.addEventListener('click', function () {
        banner.hidden = false;
        if (prefs) {
          prefs.hidden = false;
          var customBtn = $('[data-consent="custom"]', banner);
          if (customBtn) customBtn.textContent = 'Salva preferenze';
          try {
            var saved = JSON.parse(store.get());
            if (saved) {
              var an = $('[name="analytics"]', prefs);
              var mk = $('[name="marketing"]', prefs);
              if (an) an.checked = !!saved.analytics;
              if (mk) mk.checked = !!saved.marketing;
            }
          } catch (e) {}
        }
      });
    });
  }

  /* 11. Language dropdown – Powered by GTranslate (Instant, Zero Delay, No Page Reload) */
  var setCookie = function (name, value, days) {
    var exp = '';
    if (days !== undefined) {
      var d = new Date();
      d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
      exp = '; expires=' + d.toUTCString();
    }
    var val = value || '';
    document.cookie = name + '=' + val + '; path=/' + exp + '; SameSite=Lax';
    document.cookie = name + '=' + val + '; path=/; domain=' + location.hostname + exp + '; SameSite=Lax';
    document.cookie = name + '=' + val + '; path=/; domain=.' + location.hostname + exp + '; SameSite=Lax';
    var parts = location.hostname.split('.');
    if (parts.length > 2) {
      document.cookie = name + '=' + val + '; path=/; domain=.' + parts.slice(-2).join('.') + exp + '; SameSite=Lax';
    }
  };

  var clearCookie = function (name) {
    var past = '; expires=Thu, 01 Jan 1970 00:00:00 GMT; Max-Age=0; path=/';
    document.cookie = name + '=' + past;
    document.cookie = name + '=' + past + '; domain=' + location.hostname;
    document.cookie = name + '=' + past + '; domain=.' + location.hostname;
    var parts = location.hostname.split('.');
    if (parts.length > 2) {
      document.cookie = name + '=' + past + '; domain=.' + parts.slice(-2).join('.');
    }
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 GMT; Max-Age=0';
  };

  var revealPage = function () {
    if (typeof window.__gt_unveil === 'function') {
      window.__gt_unveil();
    } else {
      document.documentElement.classList.remove('gt-translating');
    }
  };

  // Helper to ensure GTranslate lib is loaded and executed
  var ensureGTranslate = function (callback) {
    if (window.__GT && window.__GT.translator && window.__GT.translator.libReady) {
      if (callback) callback();
      return;
    }
    var existing = document.querySelector('script[src*="cdn.gtranslate.net/widgets/latest/lib.min.js"]');
    if (!existing) {
      existing = document.createElement('script');
      existing.src = 'https://cdn.gtranslate.net/widgets/latest/lib.min.js';
      existing.defer = true;
      document.body.appendChild(existing);
    }
    window.gt_translate_script = existing;
    if (callback) {
      if (window.__GT && window.__GT.translator && window.__GT.translator.libReady) {
        callback();
      } else {
        existing.addEventListener('load', function () {
          callback();
        }, { once: true });
      }
    }
  };

  // Primary GTranslate caller
  var triggerGTranslate = function (lang) {
    lang = (lang || 'it').toLowerCase();
    ensureGTranslate(function () {
      try {
        if (window.__GT && window.__GT.translator) {
          window.__GT.translator.resultCallback = revealPage;
          window.__GT.translator.translate('it', lang);
          if (window.__GT.translator.finished) {
            revealPage();
          }
        } else if (typeof window.doGTranslate === 'function') {
          window.doGTranslate('it|' + lang);
          revealPage();
        } else {
          revealPage();
        }
      } catch (err) {
        console.warn('GTranslate translation error:', err);
        revealPage();
      }
    });
  };

  // Bridge for standard GTranslate plugin compatibility
  if (typeof window.doGTranslate !== 'function') {
    window.doGTranslate = function (lang_pair) {
      if (!lang_pair) return;
      if (lang_pair.value) lang_pair = lang_pair.value;
      var parts = lang_pair.split('|');
      var target = parts[1] || parts[0] || 'it';
      triggerGTranslate(target);
    };
  }

  // Update switcher UI flags and active states immediately (desktop + mobile)
  var updateSwitcherUI = function (targetLang, flagUrl) {
    targetLang = (targetLang || 'it').toLowerCase();
    $$('[data-lang-switcher]').forEach(function (sw) {
      var flagImg = $('.js-lang-flag', sw);
      var codeSpan = $('.js-lang-code', sw);
      if (flagImg && flagUrl) {
        flagImg.src = flagUrl;
      }
      if (codeSpan) {
        codeSpan.textContent = targetLang.toUpperCase();
      }
      $$('.nd-lang-item', sw).forEach(function (btn) {
        var isCurrent = btn.dataset.lang === targetLang;
        btn.setAttribute('aria-selected', isCurrent ? 'true' : 'false');
        if (isCurrent && flagImg && !flagUrl && btn.dataset.flag) {
          flagImg.src = btn.dataset.flag;
        }
      });
    });
  };

  var changeLanguage = function (targetLang, flagUrl) {
    targetLang = (targetLang || 'it').toLowerCase();

    // 1. Instantly update UI without any delay
    updateSwitcherUI(targetLang, flagUrl);

    // 2. Set document language and direction
    document.documentElement.setAttribute('lang', targetLang);
    if (targetLang === 'ar') {
      document.documentElement.setAttribute('dir', 'rtl');
    } else {
      document.documentElement.setAttribute('dir', 'ltr');
    }

    if (targetLang === 'it') {
      // Revert to native Italian
      clearCookie('googtrans');
      setCookie('googtrans', '/it/it', 365);
      setCookie('nundia_lang', 'it', 365);
      try {
        localStorage.removeItem('__GT_TRANSLATE_LANGS');
        sessionStorage.removeItem('googtrans');
      } catch (e) {}

      triggerGTranslate('it');
      return;
    }

    // Set translation cookies and localStorage
    clearCookie('googtrans');
    setCookie('googtrans', '/it/' + targetLang, 365);
    setCookie('nundia_lang', targetLang, 365);
    try {
      localStorage.setItem('__GT_TRANSLATE_LANGS', JSON.stringify({ srcLang: 'it', tgtLang: targetLang }));
    } catch (e) {}

    // Execute translation in-place without page reload
    triggerGTranslate(targetLang);
  };

  $$('[data-lang-switcher]').forEach(function (sw) {
    var btn = $('.nd-lang-btn', sw);
    var menu = $('.nd-lang-menu', sw);
    if (!btn || !menu) return;

    var open = function (o) {
      menu.hidden = !o;
      sw.classList.toggle('is-open', o);
      btn.setAttribute('aria-expanded', o ? 'true' : 'false');
    };

    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      open(menu.hidden);
    });

    document.addEventListener('click', function (e) {
      if (!sw.contains(e.target)) open(false);
    });

    $$('[data-lang]', menu).forEach(function (item) {
      item.addEventListener('click', function (e) {
        e.stopPropagation();
        var lang = item.dataset.lang;
        var flag = item.dataset.flag;
        open(false);
        changeLanguage(lang, flag);
      });
    });
  });

  // On page load, synchronize if localStorage has a stored language
  try {
    var storedGt = JSON.parse(localStorage.getItem('__GT_TRANSLATE_LANGS'));
    var activeLang = null;
    if (storedGt && storedGt.tgtLang && storedGt.tgtLang !== 'it') {
      activeLang = storedGt.tgtLang.toLowerCase();
    } else if (cfg.lang && cfg.lang !== 'it') {
      activeLang = cfg.lang.toLowerCase();
    }

    if (activeLang && activeLang !== 'it') {
      var activeBtn = document.querySelector('[data-lang="' + activeLang + '"]');
      var activeFlag = activeBtn ? activeBtn.dataset.flag : null;
      updateSwitcherUI(activeLang, activeFlag);
      document.documentElement.setAttribute('lang', activeLang);
      if (activeLang === 'ar') document.documentElement.setAttribute('dir', 'rtl');
      triggerGTranslate(activeLang);
    } else {
      revealPage();
    }
  } catch (e) {
    revealPage();
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
