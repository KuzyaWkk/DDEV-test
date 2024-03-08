(function (Drupal, once) {
  Drupal.behaviors.kuzyaMainMenuBehavior = {
    attach(context) {
      once('kuzyaMainMenuBehavior', '.header', context).forEach(
        function (element) {
          const expandMenu = element.querySelectorAll('.expand-sub');
          const toggleExpand = element.querySelector('.toggle-expand');
          const menu = element.querySelector('.main-nav');
          toggleExpand.addEventListener('click', function (e) {
            toggleExpand.classList.toggle('toggle-expand--open');
            menu.classList.toggle('main-nav--open');
            document.body.classList.toggle('scroll-none');
          });
          expandMenu.forEach((item) => {
            item.addEventListener('click', (e) => {
              const menuItem = e.currentTarget;
              const subMenu = menuItem.nextElementSibling;
              menuItem.classList.toggle('expand-sub--open');
              subMenu.classList.toggle('main-menu--sub-open');
            });
          });
        },
      );
    },
  };
})(Drupal, once);
