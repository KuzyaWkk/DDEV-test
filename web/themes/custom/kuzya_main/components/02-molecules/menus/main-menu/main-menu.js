Drupal.behaviors.mainMenu = {
  attach(context) {
    const toggleExpand = context.getElementById('toggle-expand');
    const menu = context.getElementById('main-nav');
    // const social = context.getElementById('social-bar-mobile');
    const branding = context.getElementById('branding');
    const social = context.getElementById('social-desc');
    const socialMobile = context.getElementById('social-mobile');
    const search = context.getElementById('search-desc');
    const main = document.querySelector('.main');
    const contentBottom = document.querySelector('.content-bottom');
    const aboutUs = document.querySelector('.about-us');
    const footer = document.querySelector('.footer');
    const header = document.querySelector('.header');

    if (menu) {
      const expandMenu = menu.getElementsByClassName('expand-sub');

      // Mobile Menu Show/Hide.
      toggleExpand.addEventListener('click', (e) => {
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
        header.classList.toggle('header__mobile');
        e.preventDefault();
      });

      // Expose mobile sub menu on click.
      Array.from(expandMenu).forEach((item) => {
        item.addEventListener('click', (e) => {
          const menuItem = e.currentTarget;
          const subMenu = menuItem.nextElementSibling;

          menuItem.classList.toggle('expand-sub--open');
          subMenu.classList.toggle('main-menu--sub-open');
        });
      });
    }
  },
};
