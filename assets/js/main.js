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

  const launchTarget = countdown.dataset.launchTarget || '2026-07-17T00:00:00+08:00';
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