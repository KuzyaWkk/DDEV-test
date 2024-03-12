<?php

namespace Drupal\weather_api\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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
    $logger = $this->logger;
    try {
      $weatherData = $this->getWeatherApi();
    }
    catch (GuzzleException $e) {
      $logger->get('weather_api')
        ->error('An error occurred while making a request: @error', [
          '@error' => $e->getMessage(),
        ]);
    }

    if (empty($weatherData)) {
      return [];
    }

    $config = $this->configuration;
    $city = $config['city'];
    $units = $config['units'];
    $temp = round($weatherData['main']['temp']);
    $weather_text = $weatherData['weather'][0]['main'];

    return [
      '#theme' => 'weather_api_block',
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
    $config = $this->configuration;
    $city = $config['city'];
    $appid = $config['appid'];
    $units = $config['units'];

    if (!empty($appid)) {
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
    return '';
  }

  /**
   * {@inheritDoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $list_of_city = [
      'Lutsk' => $this->t('Lutsk'),
      'Lviv' => $this->t('Lviv'),
      'London' => $this->t('London'),
    ];

    $list_of_units = [
      'standart' => $this->t('Degrees Kelvin'),
      'metric' => $this->t('Degrees Celsius'),
      'imperial' => $this->t('Degrees Fahrenheit'),
    ];

    $selected_city = $this->configuration['city'];
    $selected_units = $this->configuration['units'];
    $selected_appid = $this->configuration['appid'];

    $form['city'] = [
      '#type' => 'select',
      '#title' => $this->t('Select city'),
      '#options' => $list_of_city,
      '#default_value' => $selected_city,
    ];
    $form['units'] = [
      '#type' => 'select',
      '#title' => $this->t('Select units'),
      '#options' => $list_of_units,
      '#default_value' => $selected_units,
    ];
    $form['appid'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Add key for api'),
      '#default_value' => $selected_appid,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state):void {
    $this->configuration['city'] = $form_state->getValue('city');
    $this->configuration['units'] = $form_state->getValue('units');
    $this->configuration['appid'] = $form_state->getValue('appid');
  }

}
