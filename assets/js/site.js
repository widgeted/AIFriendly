/* AI Friendly — small, dependency-free interactions. */
document.documentElement.classList.remove('no-js');
document.documentElement.classList.add('js');
window.__AF_READY = true;
(() => {
  'use strict';

  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const lang = document.documentElement.lang.toLowerCase().startsWith('zh') ? 'zh' : 'en';

  const readJson = (id, fallback = {}) => {
    const node = document.getElementById(id);
    if (!node) return fallback;
    try { return JSON.parse(node.textContent); } catch (e) { return fallback; }
  };

  const ui = readJson('ui-i18n', {});
  const scanUi = readJson('scan-i18n', {});
  const t = (obj, key, fallback) => {
    const parts = key.split('.');
    let ref = obj;
    for (const part of parts) {
      if (!ref || typeof ref !== 'object' || !(part in ref)) return fallback;
      ref = ref[part];
    }
    return ref ?? fallback;
  };

  const live = document.createElement('div');
  live.className = 'sr-only';
  live.setAttribute('aria-live', 'polite');
  document.body.appendChild(live);
  const announce = (msg) => {
    live.textContent = '';
    window.setTimeout(() => { live.textContent = msg; }, 30);
  };

  /* ---------- header ---------- */
  const header = $('[data-header]');
  if (header) {
    const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 12);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* ---------- mobile menu ---------- */
  const menuBtn = $('[data-menu]');
  const nav = $('[data-nav]');
  const mainEl = $('main');
  const footerEl = $('.site-footer');
  let menuOpen = false;

  const setMenu = (open, restoreFocus = false) => {
    if (!menuBtn || !nav) return;
    menuOpen = open;
    nav.classList.toggle('is-open', open);
    document.body.classList.toggle('is-locked', open);
    menuBtn.setAttribute('aria-expanded', String(open));
    const label = $('[data-menu-label]', menuBtn);
    if (label) label.textContent = open ? (ui.close || 'Close') : (ui.menu || 'Menu');
    if (mainEl) mainEl.inert = open;
    if (footerEl) footerEl.inert = open;
    if (open) {
      const first = $('a', nav);
      if (first) first.focus({ preventScroll: true });
    } else if (restoreFocus) {
      menuBtn.focus({ preventScroll: true });
    }
  };

  if (menuBtn && nav) {
    menuBtn.addEventListener('click', () => setMenu(!menuOpen));
    $$('a', nav).forEach((a) => a.addEventListener('click', () => setMenu(false)));
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && menuOpen) setMenu(false, true);
    });
    window.addEventListener('resize', () => {
      if (window.innerWidth > 860 && menuOpen) setMenu(false);
    });
  }

  /* ---------- language switch: keep the current anchor ---------- */
  $$('.lang-switch a, .site-nav__lang a').forEach((a) => {
    a.addEventListener('click', () => {
      if (window.location.hash && !a.href.includes('#')) {
        a.href = a.href.split('#')[0] + window.location.hash;
      }
    });
  });

  /* ---------- reveal ---------- */
  const reveals = $$('.reveal');
  if (reveals.length) {
    if (!('IntersectionObserver' in window) || reduceMotion) {
      reveals.forEach((el) => el.classList.add('is-in'));
    } else {
      const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-in');
            io.unobserve(entry.target);
          }
        });
      }, { rootMargin: '0px 0px -8% 0px', threshold: 0.06 });
      reveals.forEach((el) => io.observe(el));
    }
  }

  /* ---------- clipboard ---------- */
  const copyText = async (text) => {
    try {
      if (navigator.clipboard && window.isSecureContext) {
        await navigator.clipboard.writeText(text);
        return true;
      }
    } catch (e) { /* fall through */ }
    try {
      const ta = document.createElement('textarea');
      ta.value = text;
      ta.setAttribute('readonly', '');
      ta.style.position = 'fixed';
      ta.style.left = '-9999px';
      document.body.appendChild(ta);
      ta.select();
      const ok = document.execCommand('copy');
      ta.remove();
      return ok;
    } catch (e) { return false; }
  };

  $$('[data-copy-target]').forEach((btn) => {
    btn.addEventListener('click', async () => {
      const target = $(btn.getAttribute('data-copy-target'));
      if (!target) return;
      const original = btn.innerHTML;
      const ok = await copyText(target.textContent.trim());
      btn.textContent = ok ? (ui.copied || 'Copied') : (ui.copyFailed || 'Copy failed');
      announce(ok ? (ui.copied || 'Copied') : (ui.copyFailed || 'Copy failed'));
      window.setTimeout(() => { btn.innerHTML = original; }, 1500);
    });
  });

  $$('[data-toggle-target]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const target = $(btn.getAttribute('data-toggle-target'));
      if (!target) return;
      const open = target.classList.toggle('is-open');
      btn.textContent = open ? (ui.hide || 'Hide code') : (ui.view || 'View code');
      btn.setAttribute('aria-expanded', String(open));
    });
  });

  /* ---------- self-audit ---------- */
  const audit = $('[data-audit]');
  if (audit) {
    const KEY = 'aifriendly.audit.v2';
    const checks = $$('input[data-check]', audit);
    const groups = { l1: [], l2: [], l3: [] };
    checks.forEach((c) => {
      const g = c.dataset.check;
      if (groups[g]) groups[g].push(c);
    });
    const scoreEl = $('[data-score]', audit);
    const barEl = $('[data-bar]', audit);
    const statusEl = $('[data-audit-status]', audit);
    const levelEls = {
      l1: $('[data-level="l1"]', audit),
      l2: $('[data-level="l2"]', audit),
      l3: $('[data-level="l3"]', audit),
    };
    const a = ui.audit || {};

    const readState = () => {
      try {
        const saved = JSON.parse(localStorage.getItem(KEY) || '{}');
        if (Array.isArray(saved.checked)) saved.checked.forEach((id) => {
          const el = document.getElementById(id);
          if (el && el.matches('input[data-check]')) el.checked = true;
        });
      } catch (e) { /* ignore */ }
    };
    const levelDone = (key) => groups[key].length > 0 && groups[key].every((c) => c.checked);
    const levels = () => {
      const l1 = levelDone('l1');
      const l2 = l1 && levelDone('l2');
      const l3 = l2 && levelDone('l3');
      return { l1, l2, l3 };
    };
    const update = (save = true) => {
      const total = checks.filter((c) => c.checked).length;
      if (scoreEl) scoreEl.textContent = String(total);
      if (barEl) barEl.style.width = `${(total / checks.length) * 100}%`;
      const state = levels();
      [['l1', state.l1], ['l2', state.l2], ['l3', state.l3]].forEach(([key, on]) => {
        if (levelEls[key]) levelEls[key].classList.toggle('is-on', on);
      });
      if (save) {
        try {
          localStorage.setItem(KEY, JSON.stringify({ checked: checks.filter((c) => c.checked).map((c) => c.id), at: Date.now() }));
          if (statusEl) statusEl.textContent = a.saved || 'Saved in your browser.';
        } catch (e) { /* ignore */ }
      }
      return { total, ...state };
    };
    const levelName = (state) => {
      const names = a.level_names || ['Level 1 — Readable', 'Level 2 — Actionable', 'Level 3 — Agent-ready'];
      if (state.l3) return names[2];
      if (state.l2) return names[1];
      if (state.l1) return names[0];
      return a.below || 'Below Level 1';
    };
    const reportText = () => {
      const state = update(false);
      const missing = checks.filter((c) => !c.checked).map((c) => {
        const strong = $('strong', c.parentElement);
        return `- ${strong ? strong.textContent.trim() : c.id}`;
      });
      return [
        a.title || 'AI Friendly self-audit',
        `${a.date || 'Date'}: ${new Date().toISOString().slice(0, 10)}`,
        `${a.score || 'Score'}: ${state.total} / ${checks.length}`,
        `${a.level || 'Level achieved'}: ${levelName(state)}`,
        '',
        `${a.open || 'Open checks'}:`,
        missing.length ? missing.join('\n') : `- ${a.none || 'none'}`,
        '',
        `${a.standard || 'Standard'}: https://aifriendly.fyi/${lang === 'zh' ? 'zh/' : ''}standard/`,
        a.disclaimer || 'This is a self-declaration, not a certification.',
      ].join('\n');
    };

    readState();
    update(false);
    checks.forEach((c) => c.addEventListener('change', () => {
      update();
      announce(`${checks.filter((x) => x.checked).length} / ${checks.length}`);
    }));

    const copyBtn = $('[data-audit-copy]', audit);
    if (copyBtn) copyBtn.addEventListener('click', async () => {
      const ok = await copyText(reportText());
      if (statusEl) statusEl.textContent = ok ? (a.report_copied || 'Report copied.') : (a.report_failed || 'Could not copy the report.');
    });

    const dlBtn = $('[data-audit-download]', audit);
    if (dlBtn) dlBtn.addEventListener('click', () => {
      const blob = new Blob([reportText()], { type: 'text/plain;charset=utf-8' });
      const link = document.createElement('a');
      link.href = URL.createObjectURL(blob);
      link.download = 'aifriendly-self-audit.txt';
      document.body.appendChild(link);
      link.click();
      link.remove();
      window.setTimeout(() => URL.revokeObjectURL(link.href), 1000);
      if (statusEl) statusEl.textContent = a.downloaded || 'Report downloaded.';
    });

    const resetBtn = $('[data-audit-reset]', audit);
    if (resetBtn) resetBtn.addEventListener('click', () => {
      checks.forEach((c) => { c.checked = false; });
      try { localStorage.removeItem(KEY); } catch (e) { /* ignore */ }
      update(false);
      if (statusEl) statusEl.textContent = a.cleared || 'Cleared.';
      announce(a.cleared || 'Cleared.');
    });
  }

  /* ---------- scanner ---------- */
  const scanForm = $('[data-scan-form]');
  if (scanForm) {
    const input = $('input[name="url"]', scanForm);
    const result = $('[data-scan-result]');
    const output = $('[data-scan-output]');
    const loading = $('[data-scan-loading]');
    const errorEl = $('[data-scan-error]');
    const submit = $('[data-scan-submit]', scanForm);
    const submitLabel = submit ? $('[data-scan-label]', submit) : null;
    let lastData = null;

    const normalize = (value) => {
      let v = (value || '').trim();
      if (!v) return '';
      if (!/^https?:\/\//i.test(v)) v = 'https://' + v;
      return v;
    };
    const iconFor = (status) => status === 'pass' ? '✓' : status === 'warn' ? '!' : '×';

    const animateNumber = (el, target) => {
      if (!el) return;
      if (reduceMotion) { el.textContent = String(target); return; }
      const start = performance.now();
      const step = (now) => {
        const p = Math.min(1, (now - start) / 650);
        el.textContent = String(Math.round(target * (1 - Math.pow(1 - p, 3))));
        if (p < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    };

    const render = (data) => {
      lastData = data;
      output.hidden = false;
      errorEl.hidden = true;
      const score = Number(data.score) || 0;
      animateNumber($('[data-score-num]'), score);
      const ring = $('[data-score-ring]');
      if (ring) ring.style.strokeDashoffset = String(339.292 * (1 - score / 100));
      const gradeKey = data.grade || '';
      const grade = (scanUi.grades && scanUi.grades[gradeKey]) || gradeKey;
      const summary = (lang === 'zh' && scanUi.summaries && scanUi.summaries[gradeKey]) || data.summary || '';
      $('[data-score-grade]').textContent = grade;
      $('[data-score-summary]').textContent = summary;
      $('[data-score-url]').textContent = data.final_url || data.url || '';
      const cats = data.categories || {};
      Object.keys(cats).forEach((key) => {
        const cat = cats[key];
        const bar = $(`[data-cat="${key}"]`);
        const label = $(`[data-cat-label="${key}"]`);
        const pct = cat.max ? Math.round((cat.score / cat.max) * 100) : 0;
        if (bar) bar.style.width = `${pct}%`;
        if (label) label.textContent = `${cat.score}/${cat.max}`;
      });

      const wrap = $('[data-result-groups]');
      wrap.textContent = '';
      const order = [
        ['read', scanUi.readable || 'Readable'],
        ['discover', scanUi.discoverable || 'Discoverable'],
        ['operate', scanUi.operable || 'Operable'],
        ['trust', scanUi.trust || 'Trust'],
      ];
      order.forEach(([key, title]) => {
        const items = (data.checks || []).filter((c) => c.category === key);
        if (!items.length) return;
        const passed = items.filter((c) => c.status === 'pass').length;
        const group = document.createElement('div');
        group.className = 'result-group';
        const h = document.createElement('h3');
        h.textContent = title;
        const count = document.createElement('span');
        count.textContent = `${passed}/${items.length}`;
        h.appendChild(count);
        group.appendChild(h);
        items.forEach((c) => {
          const row = document.createElement('div');
          row.className = `result-item is-${c.status}`;
          const icon = document.createElement('span');
          icon.className = 'result-item__icon';
          icon.textContent = iconFor(c.status);
          icon.setAttribute('aria-hidden', 'true');
          const body = document.createElement('div');
          const strong = document.createElement('strong');
          strong.textContent = c.label;
          body.appendChild(strong);
          if (lang === 'zh') {
            const linkP = document.createElement('p');
            linkP.className = 'result-link';
            const link = document.createElement('a');
            link.href = `/zh/standard/#${c.standard || c.id}`;
            link.textContent = scanUi.standard_check || 'See this check in the standard';
            linkP.appendChild(link);
            body.appendChild(linkP);
          } else {
            const p = document.createElement('p');
            p.textContent = c.detail || '';
            if (c.fix) {
              const b = document.createElement('b');
              b.textContent = ' ' + (scanUi.fix || 'Fix') + ': ';
              p.appendChild(b);
              p.appendChild(document.createTextNode(c.fix));
            }
            body.appendChild(p);
          }
          const chip = document.createElement('span');
          chip.className = 'chip';
          chip.textContent = c.status === 'pass' ? (scanUi.pass || 'Pass') : c.status === 'warn' ? (scanUi.warn || 'Review') : (scanUi.fail || 'Fix');
          row.appendChild(icon);
          row.appendChild(body);
          row.appendChild(chip);
          group.appendChild(row);
        });
        wrap.appendChild(group);
      });
      const complete = (scanUi.complete || 'Scan complete. Score {score} of 100. {grade}.')
        .replace('{score}', String(score)).replace('{grade}', grade);
      announce(complete);
    };

    const showError = (message) => {
      output.hidden = true;
      errorEl.hidden = false;
      errorEl.textContent = message;
    };

    const run = async (value, initial = false) => {
      const url = normalize(value);
      if (!url) { showError(scanUi.error || 'Enter a website address.'); result.hidden = false; return; }
      input.value = value.trim();
      result.hidden = false;
      loading.hidden = false;
      errorEl.hidden = true;
      output.hidden = true;
      if (submit) submit.disabled = true;
      if (submitLabel) submitLabel.textContent = scanUi.scanning || 'Scanning…';
      if (!initial && !reduceMotion) result.scrollIntoView({ behavior: 'smooth', block: 'start' });
      try {
        const res = await fetch(`/api/scan.php?url=${encodeURIComponent(url)}&lang=${lang}`, {
          headers: { 'Accept': 'application/json' },
          cache: 'no-store',
        });
        const data = await res.json().catch(() => null);
        if (!res.ok || !data || !data.ok) {
          throw new Error(lang === 'zh' ? (scanUi.error || 'The scan could not complete.') : ((data && data.error) || scanUi.error || 'The scan could not complete.'));
        }
        loading.hidden = true;
        render(data);
        try {
          const u = new URL(window.location.href);
          u.searchParams.set('url', data.final_url || url);
          history.replaceState(null, '', u.toString());
        } catch (e) { /* ignore */ }
      } catch (err) {
        loading.hidden = true;
        showError(err.message || scanUi.error || 'The scan could not complete.');
      } finally {
        if (submit) submit.disabled = false;
        if (submitLabel) submitLabel.textContent = scanUi.button || 'Run the scan';
      }
    };

    scanForm.addEventListener('submit', (e) => {
      e.preventDefault();
      run(input.value);
    });

    const copyBtn = $('[data-scan-copy]');
    if (copyBtn) copyBtn.addEventListener('click', async () => {
      if (!lastData) return;
      const cats = lastData.categories || {};
      const lines = [
        scanUi.result || 'Scan result',
        `URL: ${lastData.final_url || lastData.url}`,
        `${scanUi.of || 'Score'}: ${lastData.score}/100 — ${(scanUi.grades && scanUi.grades[lastData.grade]) || lastData.grade}`,
        `${scanUi.readable || 'Readable'}: ${cats.read ? cats.read.score + '/' + cats.read.max : '—'}`,
        `${scanUi.discoverable || 'Discoverable'}: ${cats.discover ? cats.discover.score + '/' + cats.discover.max : '—'}`,
        `${scanUi.operable || 'Operable'}: ${cats.operate ? cats.operate.score + '/' + cats.operate.max : '—'}`,
        `${scanUi.trust || 'Trust'}: ${cats.trust ? cats.trust.score + '/' + cats.trust.max : '—'}`,
        '',
        `${scanUi.fail || 'Fix'}:`,
        ...(lastData.checks || []).filter((c) => c.status !== 'pass').map((c) => `- ${c.label}`),
        '',
        `https://aifriendly.fyi/${lang === 'zh' ? 'zh/' : ''}standard/`,
      ];
      const ok = await copyText(lines.join('\n'));
      announce(ok ? (scanUi.copied || 'Copied') : (scanUi.copy_failed || 'Copy failed'));
      const original = copyBtn.innerHTML;
      copyBtn.textContent = ok ? (ui.copied || 'Copied') : (ui.copyFailed || 'Copy failed');
      window.setTimeout(() => { copyBtn.innerHTML = original; }, 1500);
    });

    const params = new URLSearchParams(window.location.search);
    const initialUrl = params.get('url');
    if (initialUrl) {
      input.value = initialUrl;
      run(initialUrl, true);
    }
  }

  /* ---------- forms ---------- */
  $$('[data-form]').forEach((form) => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const msg = $('[data-form-msg]', form);
      const btn = $('button[type="submit"]', form);
      const original = btn ? btn.innerHTML : '';
      if (msg) { msg.className = 'form-msg'; msg.textContent = t(ui, 'forms.sending', 'Sending…'); }
      if (btn) { btn.disabled = true; btn.textContent = t(ui, 'forms.sending', 'Sending…'); }
      try {
        const fd = new FormData(form);
        fd.append('lang', lang);
        const res = await fetch(form.action, {
          method: 'POST',
          body: fd,
          headers: { 'Accept': 'application/json' },
        });
        const data = await res.json().catch(() => null);
        if (!res.ok || !data || !data.ok) {
          throw new Error((data && data.error) || t(ui, 'forms.error', 'Something went wrong.'));
        }
        form.reset();
        if (msg) {
          msg.className = 'form-msg is-ok';
          msg.textContent = data.message || t(ui, 'forms.success', 'Thank you.');
        }
        announce(t(ui, 'forms.success', 'Thank you.'));
      } catch (err) {
        if (msg) {
          msg.className = 'form-msg is-error';
          msg.textContent = err.message || t(ui, 'forms.error', 'Something went wrong.');
        }
      } finally {
        if (btn) { btn.disabled = false; btn.innerHTML = original; }
      }
    });
  });
})();
