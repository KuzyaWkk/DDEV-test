<?php

namespace Drupal\weather_api\Service\WeatherDatabase;

/**
 * Interface for Weather Database Connection.
 */
interface WeatherDatabaseConnectionInterface {

  /**
   * Check if there ia a row with current user.
   *
   * @param int $uid
   *   The user id.
   *
   * @return bool
   *   Returns true if row is empty.
   */
  public function isEmptyRow(int $uid):bool;

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
   * Update data in database.
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
  public function updateWeatherData(int $uid, int $cid, string $units):void;

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
