<?php

namespace Drupal\weather_api\Service\WeatherDatabase;

/**
 * Interface for Weather Database Connection.
 */
interface WeatherDataStorageInterface {

  /**
   * Set data in database.
   *
   * @param int $uid
   *   The user id.
   * @param int $cid
   *   The taxonomy term id.
   * @param string $units
   *   The units name.
   *
   * @return void
   *   There is no return.
   */
  public function setWeatherData(int $uid, int $cid, string $units):void;

  /**
   * Get data from database.
   *
   * @param int $uid
   *   The user id.
   *
   * @return array
   *   Array with data.
   */
  public function getWeatherData(int $uid):array;

}
