const hdr=document.querySelector('header');
  const onScroll=()=>hdr&&hdr.classList.toggle('scrolled', window.scrollY>120);
  onScroll(); window.addEventListener('scroll',onScroll,{passive:true});
  const io=new IntersectionObserver((es)=>{es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target);}})},{threshold:.1});
  document.querySelectorAll('.reveal').forEach(el=>io.observe(el));
  document.querySelectorAll('.ctab').forEach(t=>t.addEventListener('click',()=>{document.querySelectorAll('.ctab').forEach(x=>x.classList.remove('active'));t.classList.add('active');}));
  // drawer
  const burger=document.getElementById('burger'),drawer=document.getElementById('drawer'),dback=document.getElementById('drawerBack'),dclose=document.getElementById('drawerClose');
  const openD=()=>{drawer&&drawer.classList.add('open');dback&&dback.classList.add('open');document.body&&document.body.classList.add('drawer-open');burger&&burger.setAttribute('aria-expanded','true');},closeD=()=>{drawer&&drawer.classList.remove('open');dback&&dback.classList.remove('open');document.body&&document.body.classList.remove('drawer-open');burger&&burger.setAttribute('aria-expanded','false');};
  burger&&burger.addEventListener('click',openD);dclose&&dclose.addEventListener('click',closeD);dback&&dback.addEventListener('click',closeD);
  document.addEventListener('keydown',e=>{if(e.key==='Escape')closeD();});

// Temporary coming-soon landing page countdown and notify state.
(function () {
  const countdown = document.getElementById('countdown');
  if (!countdown) return;

  const launchTarget = countdown.dataset.launchTarget || '2026-07-17T01:00:00+08:00';
  const target = new Date(launchTarget).getTime();
  const days = document.getElementById('cd-days');
  const hours = document.getElementById('cd-hours');
  const mins = document.getElementById('cd-mins');
  const secs = document.getElementById('cd-secs');
  const form = document.getElementById('notify');
  const email = document.getElementById('coming-soon-email');
  const thanks = document.getElementById('thanks');
  const live = document.getElementById('coming-soon-live');
  const redirectUrl = countdown.dataset.launchUrl || '/';
  const redirectDelay = Number.parseInt(countdown.dataset.launchDelay || '5000', 10);
  const pad = (number) => String(number).padStart(2, '0');
  let launchHandled = false;
  let intervalId;

  const setCountdown = (diff) => {
    if (days) days.textContent = pad(Math.floor(diff / 86400000));
    if (hours) hours.textContent = pad(Math.floor((diff % 86400000) / 3600000));
    if (mins) mins.textContent = pad(Math.floor((diff % 3600000) / 60000));
    if (secs) secs.textContent = pad(Math.floor((diff % 60000) / 1000));
  };

  const handleLaunch = () => {
    if (launchHandled) return;
    launchHandled = true;
    if (intervalId) window.clearInterval(intervalId);
    countdown.hidden = true;
    if (form) form.style.display = 'none';
    if (thanks) thanks.hidden = true;
    if (live) live.hidden = false;
    window.setTimeout(() => {
      window.location.assign(redirectUrl);
    }, Number.isFinite(redirectDelay) ? redirectDelay : 5000);
  };

  const tick = () => {
    const diff = target - Date.now();
    if (diff <= 0) {
      setCountdown(0);
      handleLaunch();
      return;
    }
    setCountdown(diff);
  };

  tick();
  intervalId = window.setInterval(tick, 1000);

  if (form && email && thanks) {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      if (!email.value.trim()) return;
      form.style.display = 'none';
      thanks.hidden = false;
    });
  }
}());
// Homepage hero carousel.
(function () {
  const carousel = document.querySelector('.hero-carousel');
  if (!carousel) return;

  const slides = Array.from(carousel.querySelectorAll('.hero-slide'));
  const dots = Array.from(carousel.querySelectorAll('.hero-dot'));
  if (slides.length < 2 || dots.length < 2) return;

  let activeIndex = Math.max(0, slides.findIndex((slide) => slide.classList.contains('active')));
  let timerId;

  const showSlide = (index) => {
    activeIndex = (index + slides.length) % slides.length;
    slides.forEach((slide, slideIndex) => {
      slide.classList.toggle('active', slideIndex === activeIndex);
    });
    dots.forEach((dot, dotIndex) => {
      dot.classList.toggle('active', dotIndex === activeIndex);
      dot.setAttribute('aria-pressed', dotIndex === activeIndex ? 'true' : 'false');
    });
  };

  const start = () => {
    window.clearInterval(timerId);
    timerId = window.setInterval(() => showSlide(activeIndex + 1), 7000);
  };

  dots.forEach((dot) => {
    dot.addEventListener('click', () => {
      const index = Number.parseInt(dot.dataset.slide || '0', 10);
      showSlide(Number.isFinite(index) ? index : 0);
      start();
    });
  });

  showSlide(activeIndex);
  start();
}());
// Shared CINQtizen newsletter modal.
(function () {
  const modal = document.getElementById('cinqNewsletterModal');
  if (!modal) return;

  const panel = modal.querySelector('.cinq-modal-panel');
  const form = modal.querySelector('[data-cinq-modal-form]');
  const email = form ? form.querySelector('input[type="email"]') : null;
  const status = modal.querySelector('[data-cinq-modal-status]');
  const content = modal.querySelector('[data-cinq-modal-content]');
  const success = modal.querySelector('[data-cinq-modal-success]');
  const submit = form ? form.querySelector('button[type="submit"]') : null;
  let lastFocus = null;

  const setStatus = (message) => {
    if (status) status.textContent = message || '';
  };

  const open = () => {
    lastFocus = document.activeElement;
    modal.hidden = false;
    modal.classList.add('open');
    document.body.classList.add('cinq-modal-open');
    if (content) content.hidden = false;
    if (success) success.hidden = true;
    setStatus('');
    window.setTimeout(() => email && email.focus(), 30);
  };

  const close = () => {
    modal.classList.remove('open');
    modal.hidden = true;
    document.body.classList.remove('cinq-modal-open');
    if (lastFocus && typeof lastFocus.focus === 'function') lastFocus.focus();
  };

  document.querySelectorAll('[data-cinq-modal-open]').forEach((trigger) => {
    trigger.addEventListener('click', open);
  });

  modal.querySelectorAll('[data-cinq-modal-close]').forEach((button) => {
    button.addEventListener('click', close);
  });

  modal.addEventListener('click', (event) => {
    if (event.target === modal) close();
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !modal.hidden) close();
  });

  if (form) {
    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      const config = window.parcinqNewsletter || {};
      const formData = new FormData(form);
      formData.append('action', 'parcinq_newsletter_signup');
      formData.append('nonce', config.nonce || '');

      if (submit) submit.disabled = true;
      setStatus('');

      try {
        const response = await fetch(config.ajaxUrl || '/wp-admin/admin-ajax.php', {
          method: 'POST',
          credentials: 'same-origin',
          body: formData
        });
        const payload = await response.json();
        const data = payload && payload.data ? payload.data : {};

        if (!payload.success) {
          setStatus(data.message || (config.messages && config.messages.server) || 'Something went wrong. Please try again.');
          return;
        }

        if (data.code === 'duplicate') {
          setStatus(data.message || 'You’re already on the list.');
          return;
        }

        if (content) content.hidden = true;
        if (success) success.hidden = false;
        form.reset();
        if (panel && typeof panel.focus === 'function') panel.focus();
      } catch (error) {
        setStatus((config.messages && config.messages.server) || 'Something went wrong. Please try again.');
      } finally {
        if (submit) submit.disabled = false;
      }
    });
  }
}());
// Full-screen search overlay.
(function () {
  const overlay = document.getElementById('parcinqSearchOverlay');
  if (!overlay) return;

  const input = overlay.querySelector('[data-search-input]');
  const closeButton = overlay.querySelector('[data-search-close]');
  const results = overlay.querySelector('[data-search-results]');
  const triggers = document.querySelectorAll('[data-search-open]');
  const config = window.parcinqSearch || {};
  const messages = config.messages || {};
  const minLength = Number.parseInt(config.minLength || '1', 10);
  const debounceDelay = Number.parseInt(config.debounce || '300', 10);
  let lastFocus = null;
  let debounceId;
  let controller;
  let requestToken = 0;

  const escapeHtml = (value) => String(value || '').replace(/[&<>'"]/g, (char) => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    "'": '&#039;',
    '"': '&quot;'
  }[char]));

  const resultCountText = (count) => `${count} RESULT${count === 1 ? '' : 'S'}`;

  const setResults = (html) => {
    if (results) results.innerHTML = html;
  };

  const showInitial = () => {
    setResults(`<p class="search-hint">${escapeHtml(messages.initial || 'Start typing to search stories and sections')}</p>`);
  };

  const showLoading = () => {
    setResults(`<p class="search-hint">${escapeHtml(messages.loading || 'Searching...')}</p>`);
  };

  const showEmpty = (query) => {
    setResults(`<p class="sr-empty">${escapeHtml(messages.noResults || 'No results for')} <span>&quot;${escapeHtml(query)}&quot;</span></p>`);
  };

  const renderResults = (payload) => {
    const data = payload && payload.data ? payload.data : {};
    const rows = Array.isArray(data.results) ? data.results : [];
    const total = Number.parseInt(data.total || '0', 10);

    if (!rows.length) {
      showEmpty(data.query || (input ? input.value : ''));
      return;
    }

    const items = rows.map((item) => `
      <a class="sr-item" href="${escapeHtml(item.permalink)}">
        <h4>${escapeHtml(item.title)}</h4>
        <span class="cat">${escapeHtml(item.label)}</span>
      </a>
    `).join('');

    setResults(`<p class="search-hint">${escapeHtml(resultCountText(total))}</p>${items}`);
  };

  const abortCurrent = () => {
    if (controller) controller.abort();
    controller = null;
  };

  const runSearch = () => {
    const query = input ? input.value.trim() : '';
    requestToken += 1;
    const token = requestToken;

    abortCurrent();

    if (query.length < minLength) {
      showInitial();
      return;
    }

    showLoading();
    controller = new AbortController();

    const formData = new FormData();
    formData.append('action', 'parcinq_live_search');
    formData.append('nonce', config.nonce || '');
    formData.append('query', query);

    fetch(config.ajaxUrl || '/wp-admin/admin-ajax.php', {
      method: 'POST',
      credentials: 'same-origin',
      body: formData,
      signal: controller.signal
    })
      .then((response) => response.json())
      .then((payload) => {
        if (token !== requestToken || !input || query !== input.value.trim()) return;
        if (!payload || !payload.success) {
          setResults(`<p class="sr-empty">${escapeHtml(messages.error || 'Search is temporarily unavailable.')}</p>`);
          return;
        }
        renderResults(payload);
      })
      .catch((error) => {
        if (error && error.name === 'AbortError') return;
        if (token === requestToken) {
          setResults(`<p class="sr-empty">${escapeHtml(messages.error || 'Search is temporarily unavailable.')}</p>`);
        }
      });
  };

  const queueSearch = () => {
    window.clearTimeout(debounceId);
    debounceId = window.setTimeout(runSearch, Number.isFinite(debounceDelay) ? debounceDelay : 300);
  };

  const openSearch = (trigger) => {
    lastFocus = trigger || document.activeElement;
    overlay.hidden = false;
    overlay.classList.add('open');
    document.body.classList.add('search-open');
    showInitial();
    window.setTimeout(() => input && input.focus(), 30);
  };

  const closeSearch = () => {
    window.clearTimeout(debounceId);
    abortCurrent();
    overlay.classList.remove('open');
    overlay.hidden = true;
    document.body.classList.remove('search-open');
    if (input) input.value = '';
    showInitial();
    if (lastFocus && typeof lastFocus.focus === 'function') lastFocus.focus();
  };

  triggers.forEach((trigger) => {
    trigger.addEventListener('click', () => openSearch(trigger));
  });

  input && input.addEventListener('input', queueSearch);
  closeButton && closeButton.addEventListener('click', closeSearch);

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !overlay.hidden) closeSearch();
  });
}());
