<?php

namespace Drupal\weather_api\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Http\ClientFactory;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class to connection weather API.
 */
class WeatherApiConnection implements WeatherApiConnectionInterface, ContainerInjectionInterface {

  /**
   * Constructor for WeatherApiConnection class.
   */
  public function __construct(protected ClientFactory $httpClient, protected ConfigFactoryInterface $configFactory, protected LoggerChannelFactoryInterface $logger) {

  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container):static {
    return new static(
      $container->get('http_client_factory'),
      $container->get('config.factory'),
      $container->get('logger.factory'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getWeatherApi(string $city, string $units):array|FALSE {

    $appid = $this->configFactory->get('weather_api.weather_key_settings')
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
        return json_decode($response, TRUE);
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
