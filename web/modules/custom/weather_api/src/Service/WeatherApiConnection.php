<?php

namespace Drupal\weather_api\Service;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Http\ClientFactory;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class to connection weather API.
 */
class WeatherApiConnection implements WeatherApiConnectionInterface {

  /**
   * Constructor for WeatherApiConnection class.
   */
  public function __construct(
    protected ClientFactory $httpClient,
    protected ConfigFactoryInterface $configFactory,
    protected LoggerChannelFactoryInterface $logger,
    protected CacheBackendInterface $cacheBackend,
  ) {}

  /**
   * {@inheritDoc}
   */
  public function getWeatherApi(string $city, string $units):array|FALSE {

    $cache_id = "weather_api_" . $city . '_' . $units;
    if ($cache = $this->cacheBackend->get($cache_id)) {
      return $cache->data;
    }

    $appid = $this->configFactory
      ->get('weather_api.weather_key_settings')
      ->get('appid');
    $client = $this->httpClient->fromOptions();
    if (!empty($appid)) {
      try {
        $request = $client->request('GET', 'https://api.openweathermap.org/data/2.5/weather', [
          'query' => [
            'q' => $city,
            'appid' => $appid,
            'units' => $units,
          ],
        ]);
        $response = $request->getBody()->getContents();
        $api_data = json_decode($response, TRUE);
        $this->cacheBackend->set($cache_id, $api_data, time() + 1800);
        return $api_data;
      }
      catch (GuzzleException $e) {
        $this->logger->get('weather_api')
          ->error('An error occurred while making a request: @error', [
            '@error' => $e->getMessage(),
          ]);
        return FALSE;
      }
    }
    return FALSE;
  }

}
