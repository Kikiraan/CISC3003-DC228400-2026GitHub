(() => {
  const $ = (s, r=document) => r.querySelector(s);
  const $$ = (s, r=document) => Array.from(r.querySelectorAll(s));

  const nav = $('.nav');
  const btn = $('.nav__toggle');
  const menu = $('.nav__menu');

  const closeMenu = () => {
    if (!menu) return;
    menu.classList.remove('is-open');
    btn?.setAttribute('aria-expanded', 'false');
  };

  btn?.addEventListener('click', () => {
    if (!menu) return;
    const open = menu.classList.toggle('is-open');
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });

  $$('.nav__menu a').forEach(a => a.addEventListener('click', closeMenu));
  window.addEventListener('resize', () => { if (window.innerWidth >= 900) closeMenu(); });

  const year = $('#year');
  if (year) year.textContent = String(new Date().getFullYear());

  const sections = $$('section[id]');
  const links = $$('a[data-spy]');
  const spy = () => {
    const y = window.scrollY + 140;
    let cur = null;
    for (const s of sections) {
      if (s.offsetTop <= y) cur = s;
    }
    if (!cur) return;
    links.forEach(a => a.classList.toggle('is-active', a.getAttribute('href') === `#${cur.id}`));
  };
  window.addEventListener('scroll', spy, { passive: true });
  spy();

  const form = $('#contactForm');
  form?.addEventListener('submit', (e) => {
    e.preventDefault();
    const name = $('#cname')?.value?.trim();
    const email = $('#cemail')?.value?.trim();
    const msg = $('#cmsg')?.value?.trim();
    const out = $('#formMsg');
    if (!name || !email || !msg) {
      if (out) out.textContent = 'Please fill in all fields before sending.';
      return;
    }
    if (out) out.textContent = 'Message prepared (demo). Replace this with real backend/email later.';
    form.reset();
  });
})();
