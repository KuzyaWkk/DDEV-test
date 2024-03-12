<?php

namespace Drupal\kenny_core\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Weather Block.
 *
 * @package Drupal\kenny_core\Plugin\Block
 */
#[Block(
  id: "kenny_weather_block",
  admin_label: new TranslatableMarkup("Custom Weather block"),
  category: new TranslatableMarkup("KennyCore")
)]
class WeatherBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The Config Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected ConfigFactoryInterface $configFactory;

  /**
   * The Logger Chanel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected LoggerChannelFactoryInterface $logger;

  /**
   * Constructor for WeatherBlock.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $configFactory, LoggerChannelFactoryInterface $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $configFactory;
    $this->logger = $logger;
  }

  /**
   * Container for WeatherBlock.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition):static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('logger.factory'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function build():array {
    $weatherData = $this->getWeatherApi();

    if (empty($weatherData)) {
      return [];
    }

    $config = $this->configFactory->get('kenny_core.weather_settings');
    $city = $config->get('city');
    $units = $config->get('units');
    $temp = round($weatherData['main']['temp']);
    $weather_text = $weatherData['weather'][0]['main'];

    return [
      '#theme' => 'kenny_weather_block',
      '#city' => $city,
      '#temp' => $temp,
      '#weather_text' => $weather_text,
      '#units' => $units,
    ];
  }

  /**
   * Get the value from weather API.
   *
   * @return mixed
   *   Return the decoded object.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  protected function getWeatherApi():mixed {
    $client = new Client();
    $config = $this->configFactory->get('kenny_core.weather_settings');
    $city = $config->get('city');
    $appid = $config->get('appid');
    $units = $config->get('units');

    $logger = $this->logger;

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
      catch (RequestException $e) {
        $logger->get('kenny_core')
          ->error('An error occurred while making a request: @error', [
            '@error' => $e->getMessage(),
          ]);
        return '';
      }
    }
    return '';
  }

}
