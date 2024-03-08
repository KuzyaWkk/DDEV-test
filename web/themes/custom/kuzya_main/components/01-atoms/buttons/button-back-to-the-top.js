(function (Drupal, once) {
  Drupal.behaviors.kuzyaMainButtonBackToTheTopBehavior = {
    attach(context) {
      once(
        'kuzyaMainButtonBackToTheTopBehavior',
        '.button__back-to-the-top',
        context,
      ).forEach(function (element) {
        const button = element;
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
