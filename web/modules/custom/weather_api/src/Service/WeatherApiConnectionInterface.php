<?php

namespace Drupal\weather_api\Service;

/**
 * Interface for WeatherApiConnection.
 */
interface WeatherApiConnectionInterface {

  /**
   * Get data from weather API.
   *
   * @param string $city
   *   The selected city.
   * @param string $units
   *   The selected units.
   *
   * @return array|false
   *   Return object with data or exception or false.
   */
  public function getWeatherApi(string $city, string $units):array|FALSE;

}
