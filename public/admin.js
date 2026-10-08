(() => {
  const $ = (s, c = document) => c.querySelector(s), $$ = (s, c = document) => [...c.querySelectorAll(s)];
  const root = document.documentElement;

  /* sidebar: mobile drawer + desktop collapse */
  $('#burger')?.addEventListener('click', () => document.body.classList.toggle('menu-open'));
  $('#scrim')?.addEventListener('click', () => document.body.classList.remove('menu-open'));
  addEventListener('keydown', e => { if (e.key === 'Escape') document.body.classList.remove('menu-open'); });
  $('#collapse')?.addEventListener('click', () => { const c = root.classList.toggle('collapsed'); try { localStorage.setItem('adm-collapsed', c ? '1' : '0'); } catch (e) {} });

  /* dark / light */
  $('#theme')?.addEventListener('click', () => { const t = root.dataset.theme === 'dark' ? 'light' : 'dark'; root.dataset.theme = t; try { localStorage.setItem('adm-theme', t); } catch (e) {} });

  /* toasts fade out */
  $$('.toast').forEach(t => setTimeout(() => { t.classList.add('out'); setTimeout(() => t.remove(), 450); }, 5000));

  /* delete confirmation modal */
  const dlg = $('#confirm');
  $$('form[data-confirm]').forEach(f => f.addEventListener('submit', e => {
    if (f.dataset.ok) return; e.preventDefault();
    if (!dlg?.showModal) { if (confirm(dlg?.querySelector('h3')?.textContent || 'Sure?')) { f.dataset.ok = 1; f.submit(); } return; }
    dlg.returnValue = ''; dlg.showModal();
    dlg.addEventListener('close', () => { if (dlg.returnValue === 'ok') { f.dataset.ok = 1; f.submit(); } }, { once: true });
  }));

  /* language tabs: switch every translatable field at once, remember choice, flag tabs that hold errors */
  const tabs = $$('.ltab');
  if (tabs.length) {
    const set = lang => {
      tabs.forEach(t => { const on = t.dataset.lang === lang; t.classList.toggle('on', on); t.setAttribute('aria-selected', on); });
      $$('.tl').forEach(p => p.hidden = p.dataset.lang !== lang);
      try { sessionStorage.setItem('adm-lang', lang); } catch (e) {}
    };
    tabs.forEach(t => t.addEventListener('click', () => set(t.dataset.lang)));
    $$('.tl').forEach(p => { if (p.querySelector('.err')) $(`.ltab[data-lang="${p.dataset.lang}"]`)?.classList.add('has-error'); });
    let start = document.documentElement.lang in {en:1,fr:1,ar:1} ? document.documentElement.lang : 'en'; try { start = sessionStorage.getItem('adm-lang') || start; } catch (e) {}
    const firstErr = $('.tl .err')?.closest('.tl')?.dataset.lang; set(firstErr || start);
  }

  /* image preview */
  $$('input[type=file][data-preview]').forEach(inp => inp.addEventListener('change', () => {
    const f = inp.files[0]; if (!f) return;
    if (f.size > 8 * 1024 * 1024) { alert('Max 8 MB'); inp.value = ''; return; }
    const box = inp.closest('.img-field'); let prev = $('.img-preview', box);
    if (!prev) { prev = document.createElement('div'); prev.className = 'img-preview'; prev.innerHTML = '<img alt="">'; box.prepend(prev); }
    $('img', prev).src = URL.createObjectURL(f);
  }));

  /* loading state on submit */
  $$('form.form').forEach(f => f.addEventListener('submit', () => { const b = $('button[type=submit].primary', f); if (b) setTimeout(() => b.classList.add('loading'), 0); }));

  /* drag & drop ordering */
  const list = $('#sortable');
  if (list) {
    let drag = null;
    list.addEventListener('dragstart', e => { drag = e.target.closest('.trow'); drag?.classList.add('dragging'); e.dataTransfer.effectAllowed = 'move'; });
    list.addEventListener('dragover', e => {
      e.preventDefault(); const row = e.target.closest('.trow'); if (!row || row === drag) return;
      $$('.over', list).forEach(r => r.classList.remove('over')); row.classList.add('over');
      const after = e.clientY > row.getBoundingClientRect().top + row.offsetHeight / 2; list.insertBefore(drag, after ? row.nextSibling : row);
    });
    list.addEventListener('dragend', async () => {
      drag?.classList.remove('dragging'); $$('.over', list).forEach(r => r.classList.remove('over')); drag = null;
      const ids = $$('.trow', list).map(r => r.dataset.id);
      try {
        const r = await fetch(list.dataset.url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': list.dataset.token, Accept: 'application/json' }, body: JSON.stringify({ ids }) });
        if (!r.ok) throw 0;
      } catch (e) { alert('Could not save the new order.'); }
    });
  }
})();
