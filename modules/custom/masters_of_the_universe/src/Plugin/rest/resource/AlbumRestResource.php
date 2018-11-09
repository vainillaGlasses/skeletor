<?php

namespace Drupal\masters_of_the_universe\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\Database\Connection;
use Drupal\Core\StreamWrapper\PublicStream;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "albums",
 *   label = @Translation("Album rest resource"),
 *   uri_paths = {
 *     "canonical" = "/api/albums"
 *   }
 * )
 */
class AlbumRestResource extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * A connection to the active database.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\StreamWrapper\PublicStream
   */
  protected $stream;

  /**
   * Constructs a new AlbumRestResource object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   * @param \Drupal\Core\Database\Connection $database
   *   A connection to the active database.
   * @param \Drupal\Core\StreamWrapper\PublicStream $stream
   *   A wrapper for the public file.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user,
    Connection $database,
    PublicStream $stream
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

    $this->currentUser = $current_user;
    $this->database = $database;
    $this->stream = $stream;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('masters_of_the_universe'),
      $container->get('current_user'),
      $container->get('database'),
      $container->get('stream_wrapper.public')
    );
  }

  /**
   * Responds to GET requests.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity object.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get()
  {

    // You must to implement the logic of your REST Resource here.
    // Use current user after pass authentication to validate access.
    //if (!$this->currentUser->hasPermission('access content')) {
    //throw new AccessDeniedHttpException();
    //}

    try {

      // Select the node_field_data table and we will join off of it

      $query = $this->database->select('node_field_data', 'nfd');
      $query->condition('nfd.type', 'album');

      // Join the fields we need plus the files table (for covers)

      $query->join('node__field_artist', 'n_fa', 'n_fa.entity_id = nfd.nid');
      $query->join('node__field_released', 'n_fr', 'n_fr.entity_id = nfd.nid');
      $query->join('node__field_cover', 'n_fc', 'n_fc.entity_id = nfd.nid');
      $query->join('file_managed', 'f', 'f.fid = n_fc.field_cover_target_id');

      // addField outputs the raw data, and addExpression processes it.

      $query->addField('nfd', 'title');
      $query->addField('n_fa', 'field_artist_value', 'artist');
      $query->addExpression("DATE_FORMAT(n_fr.field_released_value, '%Y')", 'released');
      $query->addExpression("REPLACE(f.uri, 'public:/', :base_path)", 'cover', [':base_path' => '/sites/default/files']);

      $albums = $query->execute()->fetchAll(\PDO::FETCH_ASSOC);

      /** @var \Drupal\rest\ResourceResponse $response */
      $response = new ResourceResponse(['albums' => $albums]);

      $response->setMaxAge(strtotime('1 day', 0));

      return $response;

    } catch (\Exception $e) {
      throw new BadRequestHttpException($this->t('Could not find any albums'), $e);
    }

  }

}