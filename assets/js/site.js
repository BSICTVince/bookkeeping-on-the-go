(function () {
  var navBar = document.querySelector('.navBar');
  var burger = document.querySelector('.burger');
  var mainNav = document.getElementById('mainNavBar');
  var isDesktop = function () { return window.matchMedia('(min-width: 1024px)').matches; };

  /* Burger / collapse toggle */
  if (burger && mainNav) {
    burger.addEventListener('click', function (e) {
      e.preventDefault();
      var open = mainNav.classList.toggle('open');
      burger.classList.toggle('active', open);
      burger.setAttribute('aria-expanded', open);
    });
  }

  /* Client Area dropdown */
  var clientBtn = document.querySelector('.dropButton_button');
  var clientDd = clientBtn ? clientBtn.closest('.dropdown') : null;
  if (clientBtn && clientDd) {
    clientBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = clientDd.classList.toggle('open');
      clientBtn.setAttribute('aria-expanded', open);
    });
  }

  /* Main menu sub-menus: click-to-toggle on mobile, hover handles desktop via CSS */
  document.querySelectorAll('.navList > .menu-item.dropdown > a').forEach(function (link) {
    link.addEventListener('click', function (e) {
      if (isDesktop()) { return; }
      e.preventDefault();
      var item = link.parentElement;
      var wasOpen = item.classList.contains('open');
      item.parentElement.querySelectorAll('.menu-item.open').forEach(function (li) { li.classList.remove('open'); });
      item.classList.toggle('open', !wasOpen);
    });
  });

  /* Close dropdowns on outside click / Escape */
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.navList > .menu-item.dropdown')) {
      document.querySelectorAll('.navList > .menu-item.open').forEach(function (li) { li.classList.remove('open'); });
    }
    if (clientDd && !clientDd.contains(e.target)) {
      clientDd.classList.remove('open');
      if (clientBtn) { clientBtn.setAttribute('aria-expanded', 'false'); }
    }
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      if (clientDd) {
        clientDd.classList.remove('open');
        if (clientBtn) { clientBtn.setAttribute('aria-expanded', 'false'); }
      }
      if (!isDesktop() && mainNav && burger) {
        mainNav.classList.remove('open');
        burger.classList.remove('active');
        burger.setAttribute('aria-expanded', 'false');
      }
    }
  });

  /* Header shadow on scroll + sticky mobile CTA visibility */
  var stickyCta = document.getElementById('stickyCta');
  function onScroll() {
    if (navBar) { navBar.classList.toggle('navBar-scrolled', window.scrollY > 8); }
    if (stickyCta) { stickyCta.classList.toggle('is-visible', window.scrollY > 420); }
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* Scroll reveal fallback */
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (en) {
      if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); }
    });
  }, { threshold: 0.12 });
  document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });

  /* Scroll progress bar */
  var progressBar = document.createElement('div');
  progressBar.id = 'scrollProgress';
  document.body.appendChild(progressBar);
  window.addEventListener('scroll', function () {
    var d = document.documentElement;
    var p = window.scrollY / (d.scrollHeight - window.innerHeight || 1);
    progressBar.style.transform = 'scaleX(' + Math.min(p, 1) + ')';
  }, { passive: true });

  /* Partner logo marquee (duplicates the row for a seamless CSS-driven loop) */
  var partnerLogos = document.querySelector('[data-testid="partner-logos"]');
  if (partnerLogos && !partnerLogos.dataset.marquee) {
    partnerLogos.dataset.marquee = '1';
    var logosHtml = partnerLogos.innerHTML;
    var track = document.createElement('div');
    track.className = 'marquee_track';
    track.innerHTML = logosHtml + logosHtml;
    partnerLogos.innerHTML = '';
    partnerLogos.appendChild(track);
    partnerLogos.classList.add('marquee');
  }

  /* Process timeline: draws the vertical line progressively as you scroll past it */
  var tl = document.getElementById('processTimeline');
  var fill = document.getElementById('timelineFill');
  if (tl && fill) {
    var tlTick = false;
    var drawTl = function () {
      tlTick = false;
      var r = tl.getBoundingClientRect();
      var wh = window.innerHeight;
      var p = (wh * 0.75 - r.top) / r.height;
      fill.style.transform = 'scaleY(' + Math.max(0, Math.min(p, 1)).toFixed(3) + ')';
    };
    window.addEventListener('scroll', function () {
      if (!tlTick) { requestAnimationFrame(drawTl); tlTick = true; }
    }, { passive: true });
    drawTl();
  }

  /* Hero scroll-scrub: pinned background photo sharpens/settles as you
     scroll past the hero, like a scrollytelling intro. Desktop only —
     mobile shows the sharp photo with no scroll-linked motion. */
  var heroScrub = document.querySelector('.hero-scrub');
  var heroImg = heroScrub ? heroScrub.querySelector('.hero-bg-media--photo img') : null;
  if (heroScrub && heroImg && isDesktop() && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    var heroTick = false;
    var drawHero = function () {
      heroTick = false;
      var scrollable = heroScrub.offsetHeight - window.innerHeight;
      if (scrollable <= 0) { return; }
      var rect = heroScrub.getBoundingClientRect();
      var p = Math.max(0, Math.min(1, -rect.top / scrollable));
      heroImg.style.filter = 'blur(' + ((1 - p) * 16).toFixed(1) + 'px)';
      heroImg.style.transform = 'scale(' + (1.12 - p * 0.12).toFixed(3) + ')';
    };
    window.addEventListener('scroll', function () {
      if (!heroTick) { requestAnimationFrame(drawHero); heroTick = true; }
    }, { passive: true });
    drawHero();
  }
})();
