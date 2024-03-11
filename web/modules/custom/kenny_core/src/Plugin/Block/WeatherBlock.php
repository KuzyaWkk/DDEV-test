<?php

namespace Drupal\kenny_core\Plugin\Block;

use Drupal;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use GuzzleHttp\Exception\GuzzleException;

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
class WeatherBlock extends BlockBase {

  /**
   * {@inheritDoc}
   */
  public function build():array {
    $weatherData = $this->getWeatherApi();
    $temp = $weatherData['main']['temp'];
    $feels_like = $weatherData['main']['feels_like'];
    $weather_text = $weatherData['weather'][0]['main'];

    $temp = round($temp - 273);
    $feels_like = round($feels_like - 273);

    return [
      '#theme' => 'kenny_weather_block',
      '#temp' => $temp,
      '#feels_like' => $feels_like,
      '#weather_text' => $weather_text,
    ];
  }

  /**
   * Get the value from weather API.
   *
   * @return mixed
   * @throws GuzzleException
   */
  protected function getWeatherApi():mixed {
    $client = Drupal::httpClient();
    $request = $client->get(
      'https://api.openweathermap.org/data/2.5/weather?lat=50.7593&lon=25.3424&appid=23033cae7426d33b990b8c0c4dffe352'
    );
    $response = $request->getBody()->getContents();
    return Json::decode($response);
  }

}
