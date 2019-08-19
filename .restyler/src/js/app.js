import './components/DataToogle'

// Функция подстройки меню в шапке под экран
function resizeHeaderMenu() {
  const header = document.querySelector('.navbar-menu');
  const hContact = header.querySelector('.navbar-end');
  const hMenu = header.querySelector('.navbar-start');

  let more = hMenu.querySelector('.more-item');

  if (more) more.parentNode.removeChild(more);

  Object.values(document.querySelectorAll('ul.navbar-start > li.is-hidden')).forEach(el => {
    el.classList.remove('is-hidden');
  });

  if (window.innerWidth < 1024) return;

  hMenu.style.maxWidth = null;
  const currentWidth = hMenu.offsetWidth;

  hMenu.style.maxWidth = 0;
  const maxWidth = header.offsetWidth - hContact.offsetWidth;

  hMenu.style.maxWidth = maxWidth + 'px';

  const elementsMap = Object.values(document.querySelectorAll('ul.navbar-start > li'));

  more = document.createElement('li');
  more.innerHTML = '<a href="javascript:void(false)">Еще</a><ul class="navbar-dropdown is-radiusless header-menu__third-level"></ul>';
  hMenu.append(more);
  more.classList.add('more-item', 'navbar-item', 'has-dropdown', 'is-hoverable');

  let elWidth = more.offsetWidth;
  elementsMap.forEach(el => {
    elWidth += el.offsetWidth;
    if (elWidth > maxWidth) {
      more.querySelector('.navbar-dropdown').append(el.cloneNode(true));
      el.classList.add('is-hidden');
    }
  });

  if (!more.querySelector('li')) more.classList.add('is-hidden');
}

window.addEventListener('resize', resizeHeaderMenu);
window.addEventListener('load', resizeHeaderMenu);