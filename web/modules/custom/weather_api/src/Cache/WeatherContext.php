<?php

namespace Drupal\weather_api\Cache;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;

/**
 * Class for custom context Weather Context.
 */
class WeatherContext implements CacheContextInterface {

  /**
   * {@inheritDoc}
   */
  public static function getLabel():string {
    return t('Weather Context');
  }

  /**
   * {@inheritDoc}
   */
  public function getContext():string {
    return 'weather_context';
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheableMetadata():object {
    return new CacheableMetadata();
  }

}
