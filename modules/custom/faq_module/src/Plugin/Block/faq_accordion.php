<?php

/**
 * Created using service: entity.query service
 */

namespace Drupal\faq_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Provides a 'FAQ Accordion' block.
 *
 * @Block(
 *  id = "faq_accordion",
 *  admin_label = @Translation("Faq accordion"),
 * )
 */
class faq_accordion extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Symfony\Component\DependencyInjection\ContainerAwareInterface definition.
   *
   * @var Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;
  /**
   * Constructs a new FAQ Accordion object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManager $entity_type_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['faq_accordion']['#markup'] = 'Implement FAQ Accordion using service: entity.query service.';

    $items = [];

  /**
   * original code from source: https://www.sitepoint.com/drupal-8-version-entityfieldquery/
   * $query = \Drupal::entityQuery('node')
   * ->condition('status', 1);
   */

   //Modified Code
   $query = $this->entityTypeManager->getStorage('node')->getQuery()
   ->condition('type', 'frequently_asked_questions');

  /**
  * Modified Code Reference:https://www.drupal.org/node/2849874
  */

    $nids = $query->execute();

    $nodes = entity_load_multiple('node', $nids);

    foreach ($nodes as $node) {
      $item = [
        'title' => $node->label(),
        'description' => $node->field_faq_answer->value,
      ];

      $items[] = $item;
    }

    $build['items'][] = [
      '#theme' => 'items_accordion',
      '#items' => $items,
    ];

    return $build;
  }
}
