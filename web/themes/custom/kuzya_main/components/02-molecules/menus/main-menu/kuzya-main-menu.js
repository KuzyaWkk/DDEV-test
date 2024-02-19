// (function ($, Drupal, once) {
//   Drupal.behaviors.kuzyaMainMenuBehavior = {
//     attach: function (context, settings) {
//
//       let name = 'kuzyaMainMenuBehavior';
//       let toggleExpand = context.getElementById('toggle-expand');
//       console.log('QQ');
//
//       once(name, toggleExpand, context).forEach(function (element) {
//         let button = $(element);
//         let menu = context.getElementById('main-nav');
//
//
//         if (menu) {
//           const expandMenu = menu.getElementsByClassName('expand-sub');
//
//           // Mobile Menu Show/Hide.
//           button.click(function (e) {
//             e.preventDefault();
//
//             button.classList.toggle('toggle-expand--open');
//             menu.classList.toggle('main-nav--open');
//
//         })
//
//           Array.from(expandMenu).forEach((item) => {
//             item.click(function(e) {
//              e.preventDefault();
//
//             let menuItem = e.currentTarget;
//             let subMenu = menuItem.nextElementSibling;
//
//             menuItem.classList.toggle('expand-sub--open');
//             subMenu.classList.toggle('main-menu--sub-open');
//
//           })
//
//         })
//
//
//       }
//
//
//
//
//     }
//   };
// })(jQuery, Drupal, once);
