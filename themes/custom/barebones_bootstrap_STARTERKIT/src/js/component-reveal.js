import ScrollReveal from 'scrollreveal';

/**
* @file
* Global JS.
*/

(function componentReveal($, Drupal) {
    Drupal.behaviors.componentReveal = {
        attach: (context) => {
            if ($('.component--reveal', context).length) {
                ScrollReveal().reveal('.component--reveal');
            }
        }
    };
}(jQuery, Drupal));