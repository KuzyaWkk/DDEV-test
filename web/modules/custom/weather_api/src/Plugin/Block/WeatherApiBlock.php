<?php

namespace Drupal\weather_api\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\weather_api\Enum\UnitsEnum;
use Drupal\weather_api\Service\WeatherApiConnectionInterface;
use Drupal\weather_api\Service\WeatherDatabase\WeatherDataStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Weather Block.
 *
 * @package Drupal\weather_api\Plugin\Block
 */
#[Block(
  id: "weather_api_block",
  admin_label: new TranslatableMarkup("Custom Weather API block"),
  category: new TranslatableMarkup("WeatherApi")
)]
class WeatherApiBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs a WeatherBlock objects.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    protected WeatherApiConnectionInterface $weatherApi,
    protected EntityTypeManagerInterface $entityTypeManager,
    protected WeatherDataStorageInterface $weatherDataStorage,
    protected AccountProxyInterface $currentUser,
    protected CacheBackendInterface $cacheBackend,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritDoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition,
  ): static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('weather_api.weather_connection'),
      $container->get('entity_type.manager'),
      $container->get('weather_api.weather_data_storage'),
      $container->get('current_user'),
      $container->get('cache.default'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function build():array {

    $uid = $this->currentUser->id();
    $weather_data = $this->weatherDataStorage->getWeatherData($uid);
    if ($weather_data) {
      $cid = $weather_data['cid'];
      $term = $this->entityTypeManager
        ->getStorage('taxonomy_term')->load($cid);
      $city = $term->getName();
      $units = $weather_data['units'];
    }
    else {
      $city = 'Lutsk';
      $units = UnitsEnum::DegreesCelsius->value;
    }

    $cache_id = 'weather_api_' . $city . '_' . $units;

    if (!$cache = $this->cacheBackend->get($cache_id)) {
      $api_data = $this->weatherApi->getWeatherApi($city, $units);

      if (empty($api_data)) {
        return [];
      }

      $this->cacheBackend->set($cache_id, $api_data, time() + 1800);
    }
    else {
      $api_data = $cache->data;
    }

    $temp = round($api_data['main']['temp']);
    $weather_text = $api_data['weather'][0]['main'];

    return [
      '#theme' => 'weather_api_block',
      '#city' => $city,
      '#temp' => $temp,
      '#weather_text' => $weather_text,
      '#units' => $units,
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheMaxAge():int {
    return 60 * 30;
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheContexts():array {
    return ['user'];
  }

}
