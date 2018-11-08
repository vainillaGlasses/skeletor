<?php

namespace Drupal\masters_of_the_universe\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class AlbumController.
 */
class AlbumController extends ControllerBase {

  /**
   * App.
   *
   * @return string
   *   Return Hello string.
   */
  public function app() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('<div id="root">"sammy"</div>'),
      '#attached' => [
        'library' => [
          'masters_of_the_universe/masters_of_the_universe',
        ],
      ],
    ];
  }

}
