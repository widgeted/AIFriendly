document.documentElement.classList.remove('no-js');document.documentElement.classList.add('js');
window.setTimeout(function () {
  if (!window.__AF_READY) {
    document.documentElement.classList.add('no-js');
    document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('is-in'); });
  }
}, 2500);
