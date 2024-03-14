<?php

namespace Drupal\weather_api\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\weather_api\Service\WeatherApiConnectionInterface;
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
  public function __construct(array $configuration, $plugin_id, $plugin_definition, protected WeatherApiConnectionInterface $weatherApi) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition):static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('weather_api.weather_connection'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function build():array {
    $config = $this->configuration;
    $city = $config['city'];
    $units = $config['units'];
    $weatherData = $this->weatherApi->getWeatherApi($city, $units);

    if (empty($weatherData)) {
      return [];
    }

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
   * {@inheritDoc}
   */
  public function blockForm($form, FormStateInterface $form_state):array {
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

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state):void {
    $this->configuration['city'] = $form_state->getValue('city');
    $this->configuration['units'] = $form_state->getValue('units');
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheMaxAge():int {
    return 1800;
  }

}
