<?php

namespace Drupal\weather_api\Service\WeatherDatabase;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Class for basic methods for working with the table "weather_api".
 */
class WeatherDatabaseConnection implements WeatherDatabaseConnectionInterface {

  public function __construct(
    protected Connection $database,
    protected LoggerChannelFactoryInterface $logger,
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {}

  /**
   * {@inheritDoc}
   */
  public function setWeatherData(int $uid, int $cid, string $units):void {
    try {
      $additional_information = $this->getAdditionalInfoAboutUser($uid);
      $additional_information->set('field_cities', $cid);
      $additional_information->set('field_units', $units);
      $additional_information->save();
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
  public function getWeatherData($uid):array {
    try {
      $additional_information = $this->getAdditionalInfoAboutUser($uid);
      if (!empty($additional_information)) {
        $city = $additional_information->get('field_cities')->target_id;
        $units = $additional_information->get('field_units')->value;
        return [
          'cid' => $city,
          'units' => $units,
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

  /**
   * Get additional info from user id.
   *
   * @param int $uid
   *   The user id.
   *
   * @return mixed
   *   Return array of Login Reg object.
   */
  protected function getAdditionalInfoAboutUser(int $uid):mixed {
    $user_storage = $this->entityTypeManager->getStorage('user');
    $user = $user_storage->load($uid);
    $additional_information = $user
      ->get('field_additional_information')
      ->referencedEntities();

    if ($additional_information) {
      return $additional_information[0];
    }

    return [];
  }

}
