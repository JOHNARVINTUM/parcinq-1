const hdr=document.querySelector('header');
  const onScroll=()=>hdr&&hdr.classList.toggle('scrolled', window.scrollY>120);
  onScroll(); window.addEventListener('scroll',onScroll,{passive:true});
  const io=new IntersectionObserver((es)=>{es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target);}})},{threshold:.1});
  document.querySelectorAll('.reveal').forEach(el=>io.observe(el));
  document.querySelectorAll('.ctab').forEach(t=>t.addEventListener('click',()=>{document.querySelectorAll('.ctab').forEach(x=>x.classList.remove('active'));t.classList.add('active');}));
  // drawer
  const burger=document.getElementById('burger'),drawer=document.getElementById('drawer'),dback=document.getElementById('drawerBack'),dclose=document.getElementById('drawerClose');
  const openD=()=>{drawer.classList.add('open');dback.classList.add('open');},closeD=()=>{drawer.classList.remove('open');dback.classList.remove('open');};
  burger&&burger.addEventListener('click',openD);dclose&&dclose.addEventListener('click',closeD);dback&&dback.addEventListener('click',closeD);
  document.addEventListener('keydown',e=>{if(e.key==='Escape')closeD();});
