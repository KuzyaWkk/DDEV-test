<?php

namespace Drupal\weather_api\Cache;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CalculatedCacheContextInterface;
use Drupal\Core\Cache\Context\RequestStackCacheContextBase;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\weather_api\Service\WeatherDatabase\WeatherDataStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Defines the CityUnitsContext service, for "per city and units" caching.
 *
 *  Cache context ID: 'city_units_context'.
 */
class CityUnitsContext extends RequestStackCacheContextBase implements CalculatedCacheContextInterface {

  public function __construct(
    RequestStack $request_stack,
    protected WeatherDataStorageInterface $weatherDataStorage,
    protected AccountProxyInterface $currentUser,
  ) {
    parent::__construct($request_stack);
  }

  /**
   * {@inheritDoc}
   */
  public static function getLabel() {
    return t('Context by city and degrees');
  }

  /**
   * {@inheritDoc}
   */
  public function getContext($parameter = NULL) {
    $weather_data = $this->weatherDataStorage
      ->getWeatherData($this->currentUser->id());
    if ($weather_data) {
      $city = $weather_data['cid'];
      $units = $weather_data['units'];
      return "$units-$city";
    }
    return 'default';
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheableMetadata($parameter = NULL) {
    return new CacheableMetadata();
  }

}
