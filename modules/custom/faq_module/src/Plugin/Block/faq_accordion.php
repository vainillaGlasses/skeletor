<?php

namespace Drupal\faq_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'faq_accordion' block.
 *
 * @Block(
 *  id = "faq_accordion",
 *  admin_label = @Translation("Faq_accordion"),
 * )
 */
class faq_accordion extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['faq_accordion']['#markup'] = 'Implement faq_accordion.';

    return $build;
    
  }

}
