(function (Drupal, once) {
  Drupal.behaviors.kuzyaMainMenuBehavior = {
    attach(context) {
      const elements = once('kuzyaMainMenuBehavior', '.header', context).forEach(function (element) {
        const body = document.body;
        const expandMenu = element.querySelectorAll('.expand-sub');
        const toggleExpand = element.querySelector('.toggle-expand');
        const menu = element.querySelector('.main-nav');
        const branding = element.querySelector('.header__branding');
        const social = element.querySelector('.header__social-bar');
        const socialMobile = element.querySelector(
          '.header__social-bar-mobile',
        );
        const search = element.querySelector('.header__search-menu');
        const open = element.querySelector('.toggle-expand__open');
        const close = element.querySelector('.toggle-expand__close');
        toggleExpand.addEventListener('click', function (e) {
          toggleExpand.classList.toggle('toggle-expand--open');
          menu.classList.toggle('main-nav--open');
          social.classList.toggle('hide');
          socialMobile.classList.toggle('hide');
          branding.classList.toggle('hide');
          search.classList.toggle('hide');
          body.classList.toggle('scroll-none');
          open.classList.toggle('hide');
          close.classList.toggle('show');
          element.classList.toggle('header__mobile');
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
