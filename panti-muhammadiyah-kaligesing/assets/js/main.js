/**
 * Panti Asuhan Muhammadiyah Kaligesing — Interactive Front-End Scripts v2.2
 * - Accessible Mobile Drawer Navigation
 * - Scroll Reveal Animations
 * - Hero Background Slideshow Controller (4.8s cycle + clickable pills)
 * - 3-Box Staggered Vertical Rolling Collage Controller
 * - Animated Count-Up Numbers (IntersectionObserver + easeOutQuart)
 * - Activity & Documentation Slideshow Carousel + Touch Swipe + Card Sync
 * - 1-Click Copy Donation Account Number
 * - Facility Gallery Lightbox Modal
 * - Client-side Contact Form Validation
 */
(function () {
  'use strict';

  // 0. Smooth Modern Page-Load & Refresh Entrance
  var pageLoader = document.getElementById('pmk-page-loader');
  if (pageLoader) {
    function finishPageLoader() {
      pageLoader.classList.add('is-loaded');
    }
    setTimeout(finishPageLoader, 360);
    window.addEventListener('pageshow', finishPageLoader);
  }

  // 1. Floating Translucent Cinematic Side Menu + Header Scroll State
  var headerEl = document.querySelector('.pmk-header');
  if (headerEl) {
    function syncHeaderScroll() {
      headerEl.classList.toggle('is-scrolled', window.scrollY > 18);
    }
    window.addEventListener('scroll', syncHeaderScroll, { passive: true });
    syncHeaderScroll();
  }

  var toggleBtn = document.getElementById('pmk-nav-toggle');
  var drawer = document.getElementById('pmk-mobile-nav');
  if (toggleBtn && drawer) {
    function setDrawer(open) {
      drawer.classList.toggle('is-open', open);
      toggleBtn.classList.toggle('is-open', open);
      toggleBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    toggleBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      setDrawer(!drawer.classList.contains('is-open'));
    });

    drawer.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        setDrawer(false);
      });
    });

    document.addEventListener('click', function (e) {
      if (drawer.classList.contains('is-open') && !drawer.contains(e.target) && !toggleBtn.contains(e.target)) {
        setDrawer(false);
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
        setDrawer(false);
        toggleBtn.focus();
      }
    });
  }

  // 2. Scroll Reveal Elements
  var reveals = document.querySelectorAll('.pmk-reveal');
  if (reveals.length > 0) {
    function revealVisibleInViewport() {
      reveals.forEach(function (el) {
        var rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight * 0.96) {
          el.classList.add('is-visible');
        }
      });
    }

    if ('IntersectionObserver' in window) {
      var revealObs = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            revealObs.unobserve(entry.target);
          }
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -36px 0px' });

      reveals.forEach(function (el) {
        revealObs.observe(el);
      });
    } else {
      reveals.forEach(function (el) { el.classList.add('is-visible'); });
    }

    setTimeout(revealVisibleInViewport, 200);
    window.addEventListener('pageshow', revealVisibleInViewport);
    window.addEventListener('resize', revealVisibleInViewport);
  }

  // 3. Hero Background Slideshow Controller (#pmk-hero-bg-slider)
  var heroBgSlider = document.getElementById('pmk-hero-bg-slider');
  if (heroBgSlider) {
    var heroSlides = Array.prototype.slice.call(heroBgSlider.querySelectorAll('.pmk-hero__bg-slide'));
    var heroPills = Array.prototype.slice.call(document.querySelectorAll('[data-hero-bg-btn]'));
    var heroIdx = 0;
    var heroTimer = null;

    function setHeroBg(idx) {
      if (!heroSlides.length) return;
      heroIdx = (idx + heroSlides.length) % heroSlides.length;
      heroSlides.forEach(function (s, i) {
        s.classList.toggle('is-active', i === heroIdx);
      });
      heroPills.forEach(function (p, i) {
        p.classList.toggle('is-active', i === heroIdx);
      });
    }

    function startHeroBgTimer() {
      if (heroTimer) clearInterval(heroTimer);
      heroTimer = setInterval(function () {
        setHeroBg(heroIdx + 1);
      }, 4800);
    }

    heroPills.forEach(function (pill) {
      pill.addEventListener('click', function () {
        var target = parseInt(pill.getAttribute('data-hero-bg-btn') || '0', 10);
        setHeroBg(target);
        startHeroBgTimer();
      });
    });

    startHeroBgTimer();
  }

  // 4. 3-Box Orbiting Collage Stage + Exclusive Bottom-Box Photo Slideshow
  var orbitStage = document.getElementById('pmk-orbit-stage');
  if (orbitStage) {
    var poolScript = document.getElementById('pmk-orbit-pool-data');
    var poolRaw = poolScript ? poolScript.textContent : (orbitStage.getAttribute('data-photo-pool') || '[]');
    var photoPool = [];
    try {
      photoPool = JSON.parse(poolRaw);
    } catch (err) {
      photoPool = [];
    }
    var orbitCards = Array.prototype.slice.call(orbitStage.querySelectorAll('.pmk-orbit-card'));
    var poolCursor = 3;
    var swapTimeout = null;
    var orbitInterval = null;
    var finalizeTimer = null;
    var swapToken = 0;

    // Preload seluruh foto agar efek crossfade dan teks langsung serempak tanpa jeda jaringan
    photoPool.forEach(function (entry) {
      if (entry && entry.img) {
        var preload = new Image();
        preload.src = entry.img;
      }
    });

    function runSyncedSwap(bottomCard, currentImg, nextImg, tagEl, item, nextIdx, token) {
      if (token !== swapToken || !bottomCard || !bottomCard.isConnected) return;

      nextImg.style.transition = 'none';
      nextImg.classList.remove('is-visible');
      void nextImg.offsetWidth;
      nextImg.style.transition = '';

      requestAnimationFrame(function () {
        if (token !== swapToken) return;

        bottomCard.classList.add('is-swapping-photo');
        nextImg.classList.add('is-visible');
        bottomCard.setAttribute('data-photo-index', String(nextIdx));

        if (tagEl) {
          tagEl.classList.add('is-updating');
          setTimeout(function () {
            if (token !== swapToken) return;
            tagEl.textContent = item.tag;
            tagEl.classList.remove('is-updating');
          }, 190);
        }

        if (finalizeTimer) clearTimeout(finalizeTimer);
        finalizeTimer = setTimeout(function () {
          currentImg.src = item.img;
          currentImg.alt = item.tag;
          requestAnimationFrame(function () {
            nextImg.style.transition = 'none';
            nextImg.classList.remove('is-visible');
            void nextImg.offsetWidth;
            nextImg.style.transition = '';
            bottomCard.classList.remove('is-swapping-photo');
          });
        }, 680);
      });
    }

    function swapBottomBoxPhoto() {
      if (!photoPool.length) return;
      var bottomCard = orbitStage.querySelector('.pmk-orbit-card[data-slot="bottom"]');
      if (!bottomCard) return;

      // Collect photo indices currently visible on main and top so we never duplicate
      var usedIndices = [];
      orbitCards.forEach(function (c) {
        if (c !== bottomCard) {
          usedIndices.push(parseInt(c.getAttribute('data-photo-index') || '-1', 10));
        }
      });

      var nextIdx = poolCursor % photoPool.length;
      var guard = 0;
      while (usedIndices.indexOf(nextIdx) !== -1 && guard < photoPool.length) {
        nextIdx = (nextIdx + 1) % photoPool.length;
        guard++;
      }
      poolCursor = (nextIdx + 1) % photoPool.length;

      var item = photoPool[nextIdx];
      var currentImg = bottomCard.querySelector('.pmk-orbit-card__img.is-current');
      var nextImg = bottomCard.querySelector('.pmk-orbit-card__img.is-next');
      var tagEl = bottomCard.querySelector('.pmk-orbit-card__tag');

      if (!currentImg || !nextImg || !item) return;

      var token = ++swapToken;
      nextImg.src = item.img;
      nextImg.alt = item.tag;

      if (typeof nextImg.decode === 'function') {
        nextImg.decode().then(function () {
          runSyncedSwap(bottomCard, currentImg, nextImg, tagEl, item, nextIdx, token);
        }).catch(function () {
          runSyncedSwap(bottomCard, currentImg, nextImg, tagEl, item, nextIdx, token);
        });
      } else if (nextImg.complete) {
        runSyncedSwap(bottomCard, currentImg, nextImg, tagEl, item, nextIdx, token);
      } else {
        nextImg.onload = function () {
          nextImg.onload = null;
          runSyncedSwap(bottomCard, currentImg, nextImg, tagEl, item, nextIdx, token);
        };
      }
    }

    function rotateSlotsOrbit() {
      // Arah terbalik & rapi: bottom -> top (0ms), top -> main (70ms), main -> bottom (140ms)
      var bottomCard = orbitStage.querySelector('.pmk-orbit-card[data-slot="bottom"]');
      var topCard = orbitStage.querySelector('.pmk-orbit-card[data-slot="top"]');
      var mainCard = orbitStage.querySelector('.pmk-orbit-card[data-slot="main"]');
      if (!bottomCard || !topCard || !mainCard) return;

      bottomCard.setAttribute('data-orbit-layer', 'mid');
      topCard.setAttribute('data-orbit-layer', 'front');
      mainCard.setAttribute('data-orbit-layer', 'back');

      bottomCard.setAttribute('data-slot', 'top');
      setTimeout(function () {
        topCard.setAttribute('data-slot', 'main');
      }, 70);
      setTimeout(function () {
        mainCard.setAttribute('data-slot', 'bottom');
      }, 140);

      setTimeout(function () {
        orbitCards.forEach(function (c) {
          c.removeAttribute('data-orbit-layer');
        });
      }, 1200);
    }

    function scheduleCycle() {
      if (swapTimeout) clearTimeout(swapTimeout);
      if (orbitInterval) clearInterval(orbitInterval);

      // Phase 1: kotak bawah ganti foto pada 1.6s, Phase 2: 3 kotak berputar halus tiap 4.4s
      swapTimeout = setTimeout(swapBottomBoxPhoto, 1600);
      orbitInterval = setInterval(function () {
        rotateSlotsOrbit();
        swapTimeout = setTimeout(swapBottomBoxPhoto, 1850);
      }, 4400);
    }

    // Klik kotak kecil manapun untuk memindahkannya ke kotak utama dengan lapisan bersih
    orbitCards.forEach(function (card) {
      card.addEventListener('click', function () {
        var clickedSlot = card.getAttribute('data-slot');
        if (clickedSlot === 'main') return;
        var mainCard = orbitStage.querySelector('.pmk-orbit-card[data-slot="main"]');
        if (mainCard) {
          card.setAttribute('data-orbit-layer', 'front');
          mainCard.setAttribute('data-orbit-layer', 'back');
          mainCard.setAttribute('data-slot', clickedSlot);
          card.setAttribute('data-slot', 'main');
          setTimeout(function () {
            card.removeAttribute('data-orbit-layer');
            mainCard.removeAttribute('data-orbit-layer');
          }, 1150);
          scheduleCycle();
        }
      });
    });

    scheduleCycle();
  }

  // 5. Animated Counter Numbers ([data-counter-target])
  var counters = document.querySelectorAll('[data-counter-target]');
  if (counters.length > 0) {
    function animateCounter(el) {
      if (el.dataset.counted === '1') return;
      el.dataset.counted = '1';
      var target = parseInt(el.getAttribute('data-counter-target') || '0', 10);
      var suffix = el.getAttribute('data-counter-suffix') || '';
      var duration = 1400;
      var startTime = null;

      function step(ts) {
        if (!startTime) startTime = ts;
        var progress = Math.min((ts - startTime) / duration, 1);
        var eased = 1 - Math.pow(1 - progress, 4);
        var current = Math.round(eased * target);
        el.textContent = current + suffix;
        if (progress < 1) {
          requestAnimationFrame(step);
        } else {
          el.textContent = target + suffix;
        }
      }
      requestAnimationFrame(step);
    }

    if ('IntersectionObserver' in window) {
      var counterObs = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            animateCounter(entry.target);
            counterObs.unobserve(entry.target);
          }
        });
      }, { threshold: 0.25 });

      counters.forEach(function (el) { counterObs.observe(el); });
    } else {
      counters.forEach(animateCounter);
    }
  }

  // 6. Activity & Documentation Slideshow Carousel
  var slideshow = document.getElementById('pmk-activity-slideshow');
  if (slideshow) {
    var slides = Array.prototype.slice.call(slideshow.querySelectorAll('.pmk-slide'));
    var dots = Array.prototype.slice.call(slideshow.querySelectorAll('[data-slide-dot]'));
    var jumpCards = Array.prototype.slice.call(document.querySelectorAll('[data-slide-jump]'));
    var counterEl = document.getElementById('pmk-slide-counter');
    var prevBtn = document.getElementById('pmk-slide-prev');
    var nextBtn = document.getElementById('pmk-slide-next');
    var activeIdx = 0;
    var timer = null;

    function goToSlide(idx) {
      if (!slides.length) return;
      activeIdx = (idx + slides.length) % slides.length;
      slides.forEach(function (s, i) {
        s.classList.toggle('is-active', i === activeIdx);
      });
      dots.forEach(function (d, i) {
        d.classList.toggle('is-active', i === activeIdx);
      });
      jumpCards.forEach(function (card) {
        var jumpIdx = parseInt(card.getAttribute('data-slide-jump') || '-1', 10);
        card.classList.toggle('is-active-card', jumpIdx === activeIdx);
      });
      if (counterEl) {
        var num = (activeIdx + 1 < 10 ? '0' : '') + (activeIdx + 1);
        var tot = (slides.length < 10 ? '0' : '') + slides.length;
        counterEl.textContent = num + ' / ' + tot;
      }
    }

    function startAuto() {
      stopAuto();
      timer = setInterval(function () {
        goToSlide(activeIdx + 1);
      }, 4500);
    }

    function stopAuto() {
      if (timer) {
        clearInterval(timer);
        timer = null;
      }
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        goToSlide(activeIdx - 1);
        startAuto();
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', function () {
        goToSlide(activeIdx + 1);
        startAuto();
      });
    }

    dots.forEach(function (dot) {
      dot.addEventListener('click', function () {
        var idx = parseInt(dot.getAttribute('data-slide-dot') || '0', 10);
        goToSlide(idx);
        startAuto();
      });
    });

    jumpCards.forEach(function (card) {
      card.addEventListener('click', function () {
        var idx = parseInt(card.getAttribute('data-slide-jump') || '0', 10);
        goToSlide(idx);
        startAuto();
      });
    });

    slideshow.addEventListener('mouseenter', stopAuto);
    slideshow.addEventListener('mouseleave', startAuto);

    var touchStartX = 0;
    slideshow.addEventListener('touchstart', function (e) {
      touchStartX = e.changedTouches[0].clientX;
    }, { passive: true });
    slideshow.addEventListener('touchend', function (e) {
      var dx = e.changedTouches[0].clientX - touchStartX;
      if (Math.abs(dx) > 40) {
        goToSlide(dx < 0 ? activeIdx + 1 : activeIdx - 1);
        startAuto();
      }
    }, { passive: true });

    goToSlide(0);
    startAuto();
  }

  // 7. Copy Bank Account Number
  var copyButtons = document.querySelectorAll('[data-copy-target]');
  copyButtons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var text = btn.getAttribute('data-copy-target') || '';
      if (!text) return;

      var original = btn.innerHTML;
      function showCopied() {
        btn.classList.add('is-copied');
        btn.textContent = 'Tersalin!';
        setTimeout(function () {
          btn.classList.remove('is-copied');
          btn.innerHTML = original;
        }, 2000);
      }

      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(showCopied).catch(function () {
          fallbackCopy(text);
          showCopied();
        });
      } else {
        fallbackCopy(text);
        showCopied();
      }
    });
  });

  function fallbackCopy(val) {
    var el = document.createElement('textarea');
    el.value = val;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    el.select();
    try { document.execCommand('copy'); } catch (err) {}
    document.body.removeChild(el);
  }

  // 8. Facilities Lightbox Modal
  var lightbox = document.getElementById('pmk-lightbox');
  if (lightbox) {
    var lbImg = document.getElementById('pmk-lightbox-img');
    var lbTitle = document.getElementById('pmk-lightbox-title');
    var lbClose = document.getElementById('pmk-lightbox-close');

    document.querySelectorAll('[data-lightbox-src]').forEach(function (trigger) {
      trigger.addEventListener('click', function () {
        var src = trigger.getAttribute('data-lightbox-src');
        var title = trigger.getAttribute('data-lightbox-title') || '';
        if (lbImg) {
          lbImg.src = src;
          lbImg.alt = title;
        }
        if (lbTitle) lbTitle.textContent = title;
        lightbox.classList.add('is-open');
        lightbox.setAttribute('aria-hidden', 'false');
      });
    });

    function closeLightbox() {
      lightbox.classList.remove('is-open');
      lightbox.setAttribute('aria-hidden', 'true');
    }

    if (lbClose) lbClose.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', function (e) {
      if (e.target === lightbox) closeLightbox();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && lightbox.classList.contains('is-open')) {
        closeLightbox();
      }
    });
  }

  // 9. Contact Form Client-Side Required Validation
  var contactForm = document.getElementById('pmk-contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      var nameInput = document.getElementById('pmk_name');
      var emailInput = document.getElementById('pmk_email');
      var msgInput = document.getElementById('pmk_message');
      var alertBox = document.getElementById('pmk-client-alert');

      var errors = [];
      [nameInput, emailInput, msgInput].forEach(function (field) {
        if (!field) return;
        field.classList.remove('is-invalid');
        var errNode = document.getElementById(field.id + '_error');
        if (errNode) errNode.textContent = '';
      });

      if (nameInput && !nameInput.value.trim()) {
        nameInput.classList.add('is-invalid');
        var ne = document.getElementById('pmk_name_error');
        if (ne) ne.textContent = 'Nama lengkap wajib diisi.';
        errors.push('Nama lengkap');
      }

      var emailVal = emailInput ? emailInput.value.trim() : '';
      var emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal);
      if (emailInput && (!emailVal || !emailOk)) {
        emailInput.classList.add('is-invalid');
        var ee = document.getElementById('pmk_email_error');
        if (ee) ee.textContent = !emailVal ? 'Alamat email wajib diisi.' : 'Format alamat email tidak valid.';
        errors.push('Alamat email');
      }

      if (msgInput && !msgInput.value.trim()) {
        msgInput.classList.add('is-invalid');
        var me = document.getElementById('pmk_message_error');
        if (me) me.textContent = 'Pesan wajib diisi.';
        errors.push('Pesan');
      }

      if (errors.length > 0) {
        e.preventDefault();
        if (alertBox) {
          alertBox.hidden = false;
          alertBox.textContent = 'Peringatan: Mohon lengkapi kolom wajib berikut dengan benar: ' + errors.join(', ') + '.';
          alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
      }
    });
  }
})();
