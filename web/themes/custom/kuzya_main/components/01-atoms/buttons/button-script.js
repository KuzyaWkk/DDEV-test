(function (Drupal, once) {
  Drupal.behaviors.kuzyaMainButtonBackToTheTopBehavior = {
    attach(context) {
      once(
        'kuzyaMainButtonBackToTheTopBehavior',
        '.layout-container',
        context,
      ).forEach(function (container) {
        const button = document.createElement('button');
        const image = document.createElement('img');
        image.src = '/themes/custom/kuzya_main/assets/images/arrow-top.svg';
        button.appendChild(image);
        button.classList.add('button__back-to-the-top');
        container.appendChild(button);
        const viewportHeight = window.innerHeight;
        window.addEventListener('scroll', function () {
          if (window.scrollY > viewportHeight * 0.8) {
            button.classList.add('active-btn');
          } else {
            button.classList.remove('active-btn');
          }
        });
        button.addEventListener('click', function (e) {
          window.scrollTo({
            top: 0,
            behavior: 'smooth',
          });
          e.preventDefault();
        });
      });
    },
  };
})(Drupal, once);
