<?php

namespace Drupal\weather_api\Service\WeatherDatabase;

use Drupal\Core\Database\Connection;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Class for basic methods for working with the table "weather_api".
 */
class WeatherDatabaseConnection implements WeatherDatabaseConnectionInterface {

  public function __construct(protected Connection $database, protected LoggerChannelFactoryInterface $logger) {

  }

  /**
   * {@inheritDoc}
   */
  public function isEmptyRow(int $uid):bool {
    if ($uid == 0) {
      return TRUE;
    }
    $count = $this->database->select('weather_api', 'wa')
      ->condition('uid', $uid)
      ->countQuery()
      ->execute()
      ->fetchField();

    if ($count == 0) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * {@inheritDoc}
   */
  public function setWeatherData(int $uid, int $cid, string $units):void {
    try {
      $this->database->insert('weather_api')
        ->fields([
          'uid' => $uid,
          'cid' => $cid,
          'units' => $units,
        ])
        ->execute();
    }
    catch (\Exception $e) {
      $this->logger->get('weather_api')
        ->error('Has a problem with database connection:@error', [
          '@error' => $e->getMessage(),
        ]);
    }
  }

  /**
   * {@inheritDoc}
   */
  public function updateWeatherData(int $uid, int $cid, string $units):void {
    try {
      $this->database->update('weather_api')
        ->fields([
          'cid' => $cid,
          'units' => $units,
        ])
        ->condition('uid', $uid)
        ->execute();
    }
    catch (\Exception $e) {
      $this->logger->get('weather_api')
        ->error('Has a problem with database connection:@error', [
          '@error' => $e->getMessage(),
        ]);
    }
  }

  /**
   * {@inheritDoc}
   */
  public function getWeatherData(int $uid):array {
    try {
      $query = $this->database->select('weather_api', 'wa')
        ->fields('wa', ['cid', 'units'])
        ->condition('uid', $uid)
        ->execute();

      $result = $query->fetchAssoc();
      if ($result) {
        return [
          'cid' => $result['cid'],
          'units' => $result['units'],
        ];
      }
      return [];
    }
    catch (\Exception $e) {
      $this->logger->get('weather_api')
        ->error('Has a problem with database connection:@error', [
          '@error' => $e->getMessage(),
        ]);
      return [];
    }

  }

}
