/* Public site behaviour. No translation logic here: all text is rendered by the server (Laravel). */
(() => {
  const $ = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => [...c.querySelectorAll(s)];
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* loader */
  const loader = $('.page-loader');
  if (loader) setTimeout(() => loader.classList.add('hide'), reduce ? 0 : 700);

  /* mobile menu */
  const menu = $('.menu-btn'), mob = $('.mobile-nav');
  const closeMenu = () => { mob?.classList.remove('open'); menu?.classList.remove('open'); menu?.setAttribute('aria-expanded', 'false'); document.body.style.overflow = ''; };
  menu?.addEventListener('click', () => {
    const open = mob.classList.toggle('open');
    menu.classList.toggle('open', open); menu.setAttribute('aria-expanded', open); document.body.style.overflow = open ? 'hidden' : '';
  });
  $$('.mobile-nav a').forEach(a => a.addEventListener('click', closeMenu));

  /* cursor glow (desktop only) */
  const glow = $('.cursor-glow');
  if (glow && matchMedia('(hover:hover) and (pointer:fine)').matches && !reduce)
    addEventListener('pointermove', e => { glow.style.left = e.clientX + 'px'; glow.style.top = e.clientY + 'px'; }, { passive: true });

  /* counters + reveal */
  const count = el => {
    const target = +el.dataset.count || 0; if (reduce || target === 0) return;
    const pad = String(el.textContent).length > String(target).length ? 2 : 0;
    const t0 = performance.now(), dur = 1100;
    const step = t => { const p = Math.min(1, (t - t0) / dur), v = Math.round(target * (1 - Math.pow(1 - p, 3))); el.textContent = String(v).padStart(pad, '0'); if (p < 1) requestAnimationFrame(step); };
    requestAnimationFrame(step);
  };
  if ('IntersectionObserver' in window && !reduce) {
    const io = new IntersectionObserver(es => es.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); $$('[data-count]', e.target).forEach(count); io.unobserve(e.target); } }), { threshold: .08 });
    $$('.reveal').forEach(e => io.observe(e));
  } else $$('.reveal').forEach(e => e.classList.add('in'));

  /* header + back to top */
  const hd = $('.site-header'), top = $('.backtop');
  const onScroll = () => { hd?.classList.toggle('solid', scrollY > 40); top?.classList.toggle('show', scrollY > 700); };
  addEventListener('scroll', onScroll, { passive: true }); onScroll();
  top?.addEventListener('click', () => scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' }));

  /* category filters (gallery + journal) */
  $$('.filters').forEach(group => {
    const scope = group.closest('section') || document;
    $$('.filter', group).forEach(btn => btn.addEventListener('click', () => {
      $$('.filter', group).forEach(b => { b.classList.toggle('active', b === btn); b.setAttribute('aria-pressed', b === btn); });
      const f = btn.dataset.filter;
      $$('[data-cat]', scope).forEach(el => { el.hidden = !(f === 'all' || el.dataset.cat === f); });
    }));
  });

  /* career: country filter + show more */
  const rows = $$('.bm-career-row'), more = $('.bm-career-more');
  let country = 'all', expanded = false;
  const applyCareer = () => rows.forEach((r, i) => {
    const okCountry = country === 'all' || r.dataset.country === country;
    r.hidden = !okCountry;
    r.classList.toggle('bm-career-hidden', !expanded && country === 'all' && i >= 6);
  });
  $$('.cfilter').forEach(b => b.addEventListener('click', () => {
    $$('.cfilter').forEach(x => x.classList.toggle('active', x === b)); country = b.dataset.c; applyCareer();
    if (more) more.hidden = country !== 'all';
  }));
  more?.addEventListener('click', () => { expanded = !expanded; more.textContent = expanded ? more.dataset.less : more.dataset.more; applyCareer(); });

  /* lightbox */
  const lb = $('.lightbox');
  if (lb) {
    const img = $('img', lb), cap = $('.lb-cap', lb);
    let list = [], idx = 0;
    const show = i => { idx = (i + list.length) % list.length; const t = list[idx]; img.src = t.currentSrc || t.src; img.alt = t.alt; cap.textContent = t.dataset.cap || t.alt || ''; };
    const open = t => { list = $$('.lightbox-trigger').filter(x => !x.closest('[hidden]')); show(Math.max(0, list.indexOf(t))); lb.hidden = false; lb.classList.add('open'); document.body.style.overflow = 'hidden'; $('.lb-close', lb).focus(); };
    const close = () => { lb.classList.remove('open'); lb.hidden = true; img.src = ''; document.body.style.overflow = ''; };
    $$('.lightbox-trigger').forEach(t => { t.addEventListener('click', () => open(t)); t.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(t); } }); });
    $('.lb-close', lb).addEventListener('click', close);
    $('.lb-prev', lb).addEventListener('click', e => { e.stopPropagation(); show(idx - 1); });
    $('.lb-next', lb).addEventListener('click', e => { e.stopPropagation(); show(idx + 1); });
    lb.addEventListener('click', e => { if (e.target === lb) close(); });
    addEventListener('keydown', e => {
      if (e.key === 'Escape') { close(); closeMenu(); }
      if (!lb.hidden) { const rtl = document.documentElement.dir === 'rtl'; if (e.key === 'ArrowRight') show(idx + (rtl ? -1 : 1)); if (e.key === 'ArrowLeft') show(idx + (rtl ? 1 : -1)); }
    });
  } else addEventListener('keydown', e => { if (e.key === 'Escape') closeMenu(); });

  /* home: career chapter tabs */
  const root = $('[data-hm-chapters]');
  if (root) {
    const tabs = $$('[role="tab"]', root), panel = $('[role="tabpanel"]', root), sum = $('.hm-panel-summary', root), label = root.dataset.chapterLabel || '';
    const update = tab => {
      tabs.forEach(t => { const a = t === tab; t.classList.toggle('is-active', a); t.setAttribute('aria-selected', a); t.tabIndex = a ? 0 : -1; });
      panel.classList.add('is-changing');
      setTimeout(() => {
        const im = $('img', panel); im.src = tab.dataset.image; im.alt = tab.dataset.club;
        $('.hm-panel-caption', panel).textContent = label + ' · ' + tab.dataset.period;
        $('.hm-panel-counter', panel).textContent = tab.dataset.index + ' / ' + String(tabs.length).padStart(2, '0');
        $('.hm-panel-country', sum).textContent = tab.dataset.country; $('.hm-panel-title', sum).textContent = tab.dataset.role; $('.hm-panel-club', sum).textContent = tab.dataset.club;
        panel.setAttribute('aria-labelledby', tab.id); panel.classList.remove('is-changing');
      }, 160);
    };
    tabs.forEach((tab, i) => {
      tab.addEventListener('click', () => update(tab));
      tab.addEventListener('keydown', e => {
        if (!['ArrowDown', 'ArrowRight', 'ArrowUp', 'ArrowLeft'].includes(e.key)) return; e.preventDefault();
        const dir = (e.key === 'ArrowDown' || e.key === 'ArrowRight') ? 1 : -1, next = tabs[(i + dir + tabs.length) % tabs.length]; next.focus(); update(next);
      });
    });
  }
})();
