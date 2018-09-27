<?php
/**
 * @file
 * Drupal site-specific configuration file.
 */

$databases = array();

/**
 * Include secret configuration.
 *
 * Contains database settings and other sensitive environment specific
 * information that shouldn't be in version control.
 */
if (file_exists(DRUPAL_ROOT . '/sites/default/settings.secret.php')) {
  include DRUPAL_ROOT . '/sites/default/settings.secret.php';
}
$config_directories = array();
$settings['update_free_access'] = FALSE;
$config['system.performance']['fast_404']['exclude_paths'] = '/\/(?:styles)|(?:system\/files)\//';
$config['system.performance']['fast_404']['paths'] = '/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';
$config['system.performance']['fast_404']['html'] = '<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';
$settings['container_yamls'][] = __DIR__ . '/services.yml';
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
ini_set('session.gc_maxlifetime', 200000);
ini_set('session.cookie_lifetime', 2000000);

/**
* relative path for default_content_deploy
**/
$config['content_directory'] = '../content';

/**
 * Include local configuration.
 *
 * IMPORTANT: This block should remain at the bottom of this file.
 */
if (file_exists(DRUPAL_ROOT . '/sites/default/settings.local.php')) {
  include DRUPAL_ROOT . '/sites/default/settings.local.php';
}
if (file_exists(DRUPAL_ROOT . '/sites/default/settings.pantheon.php')) {
  include DRUPAL_ROOT . '/sites/default/settings.pantheon.php';
}
$settings['install_profile'] = 'skeletor';
$config_directories['sync'] = 'sites/default/config/sync';


/*

    Split Settings

*/
if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
  switch ($_ENV['PANTHEON_ENVIRONMENT']) {
    case 'live':
      // Config Split.
      $config['config_split.config_split.dev']['status'] = FALSE;
      // Environment indicator.
      $config['environment_indicator.indicator']['bg_color'] = '#FF0100';
      $config['environment_indicator.indicator']['fg_color'] = '#FFFFFF';
      $config['environment_indicator.indicator']['name'] = 'Live';
      break;
    case 'test':
      // Config Split.
      $config['config_split.config_split.dev']['status'] = FALSE;
      // Environment indicator.
      $config['environment_indicator.indicator']['bg_color'] = '#F39500';
      $config['environment_indicator.indicator']['fg_color'] = '#FFFFFF';
      $config['environment_indicator.indicator']['name'] = 'Test';
      break;
    case 'dev':
      // Environment indicator.
      $config['environment_indicator.indicator']['bg_color'] = '#0FC37B';
      $config['environment_indicator.indicator']['fg_color'] = '#FFFFFF';
      $config['environment_indicator.indicator']['name'] = 'Development';
      // Config Split.
      $config['config_split.config_split.dev']['status'] = FALSE;
      break;

// not in example 
    case 'local':
      // Environment indicator.
      $config['environment_indicator.indicator']['bg_color'] = '#FFFFFF';
      $config['environment_indicator.indicator']['fg_color'] = '#000000';
      $config['environment_indicator.indicator']['name'] = 'Local';
      // Config Split.
      $config['config_split.config_split.dev']['status'] = TRUE;
      break;
  }
}
else {
  $config['config_split.config_split.config_dev']['status'] = TRUE;
}