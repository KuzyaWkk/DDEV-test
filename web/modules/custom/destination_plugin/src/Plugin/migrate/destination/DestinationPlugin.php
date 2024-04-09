<?php

namespace Drupal\destination_plugin\Plugin\migrate\destination;

use Drupal\migrate\Plugin\migrate\destination\DestinationBase;
use Drupal\migrate\Row;

/**
 * Destination plugin for saving data to a custom table in the database.
 *
 * @MigrateDestination(
 *    id = "custom_destination",
 *  )
 */
class DestinationPlugin extends DestinationBase {

  /**
   * {@inheritDoc}
   */
  public function getIds(): array {
    return [
      'uid' => [
        'type' => 'integer',
      ],
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function fields(): array {
    return [
      ['uid' => ['type' => 'integer']],
      ['city' => ['type' => 'string']],
      ['country' => ['type' => 'string']],
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function import(Row $row, array $old_destination_id_values = []): void {
    $uid = $row->getSourceProperty('uid');
    $city = $row->getSourceProperty('city');
    $country = $row->getSourceProperty('country');
    \Drupal::database()->upsert('destination_database')
      ->fields([
        'uid' => $uid,
        'city' => $city,
        'country' => $country,
      ])
      ->key('uid')
      ->execute();
  }

}
