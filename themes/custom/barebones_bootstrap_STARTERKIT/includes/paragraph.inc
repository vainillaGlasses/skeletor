<?php

use Drupal\Core\Url;
/**
 * @file
 * Preprocess paragraph functions.
 */
function barebones_bootstrap_STARTERKIT_preprocess_paragraph(&$vars) {

  //ksm($vars);

  $paragraph = $vars['paragraph'];

  switch ($paragraph->getType()) {
    case 'component_accordion_item_cards':
      //ksm($paragraph->field_aic_link->getValue()[0]);

      if (!$paragraph->field_aic_link->isEmpty()) {
        $vars['link']['url'] = $paragraph->field_aic_link->getValue()[0]['uri'];
        $vars['link']['text'] = $paragraph->field_aic_link->getValue()[0]['title'];
        kint('full');
      }
      else {
        $vars['link']['url'] = Url::fromRoute('<front>');
        $vars['link']['text'] = 'Contact us';
        kint('empty');
      }
    break;
  }
}
