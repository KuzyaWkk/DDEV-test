(function (Drupal, once) {
  Drupal.behaviors.kuzyaMainMenuBehavior = {
    attach(context) {
      const headerRegion = '.header';
      const name = 'kuzyaMainMenuBehavior';
      const elements = once(name, headerRegion, context);
      elements.forEach(function (element) {
        const expandMenu = element.querySelectorAll('.expand-sub');
        const toggleExpand = element.querySelector('.toggle-expand');
        const menu = element.querySelector('.main-nav');
        const branding = element.querySelector('.header__branding');
        const social = element.querySelector('.header__social-bar');
        const socialMobile = element.querySelector(
          '.header__social-bar-mobile',
        );
        const search = element.querySelector('.header__search-menu');
        const main = document.querySelector('.main');
        const contentBottom = document.querySelector('.content-bottom');
        const aboutUs = document.querySelector('.about-us');
        const footer = document.querySelector('.footer');
        const open = element.querySelector('.toggle-expand__open');
        const close = element.querySelector('.toggle-expand__close');
        const header = element;

        toggleExpand.addEventListener('click', function (e) {
          e.preventDefault();
          toggleExpand.classList.toggle('toggle-expand--open');
          menu.classList.toggle('main-nav--open');
          social.classList.toggle('hide');
          socialMobile.classList.toggle('hide');
          branding.classList.toggle('hide');
          search.classList.toggle('hide');
          main.classList.toggle('hide');
          contentBottom.classList.toggle('hide');
          aboutUs.classList.toggle('hide');
          footer.classList.toggle('hide');
          open.classList.toggle('hide');
          close.classList.toggle('show');
          header.classList.toggle('header__mobile');
        });
        expandMenu.forEach((item) => {
          item.addEventListener('click', (e) => {
            const menuItem = e.currentTarget;
            const subMenu = menuItem.nextElementSibling;
            menuItem.classList.toggle('expand-sub--open');
            subMenu.classList.toggle('main-menu--sub-open');
          });
        });
      });
    },
  };
}(Drupal, once));
