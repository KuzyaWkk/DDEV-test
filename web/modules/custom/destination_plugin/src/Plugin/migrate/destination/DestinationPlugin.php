<?php

namespace Drupal\destination_plugin\Plugin\migrate\destination;

use Drupal\Core\Database\Connection;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\Plugin\migrate\destination\DestinationBase;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Destination plugin for saving data to a custom table in the database.
 *
 * @MigrateDestination(
 *    id = "custom_destination",
 *  )
 */
class DestinationPlugin extends DestinationBase implements ContainerFactoryPluginInterface {

  /**
   * Constructor for DestinationPlugin class.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    MigrationInterface $migration,
    protected Connection $database,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition,
    MigrationInterface $migration = NULL,
  ): static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $migration,
      $container->get('database'),
    );
  }

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
  public function fields():void {}

  /**
   * {@inheritDoc}
   */
  public function import(Row $row, array $old_destination_id_values = []): void {
    $uid = $row->getDestinationProperty('uid');
    $city = $row->getDestinationProperty('city');
    $country = $row->getDestinationProperty('country');
    $this->database->upsert('destination_database')
      ->fields([
        'uid' => $uid,
        'city' => $city,
        'country' => $country,
      ])
      ->key('uid')
      ->execute();
  }

}
