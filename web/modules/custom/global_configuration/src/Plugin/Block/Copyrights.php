<?php

namespace Drupal\global_configuration\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Copyrights.
 *
 * @package Drupal\global_configuration\Plugin\Block
 */
#[Block(
  id: "copyrights_block",
  admin_label: new TranslatableMarkup("Custom Copyrights block"),
  category: new TranslatableMarkup("GlobalConfiguration")
)]
class Copyrights extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs a new object of Copyrights.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritDoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition,
  ):static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function build():array {
    $global_configuration_storage = $this->entityTypeManager
      ->getStorage('config_pages')
      ->load('global_configurations');

    if (!$global_configuration_storage) {
      return [
        '#theme' => 'copyrights_block',
        '#cache' => [
          'max-age' => 0,
        ],
      ];
    }

    $field_copyrights_value = $global_configuration_storage
      ->get('field_copyrights')
      ->view();

    // Global configurations it`s a custom tag, that is invalidated
    // when field_copyrights is changed in Global Configurations entity.
    return [
      '#theme' => 'copyrights_block',
      '#text' => $field_copyrights_value,
      '#cache' => [
        'tags' => ['global_configurations'],
      ],
    ];

  }

  /**
   * {@inheritDoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['link'] = [
      '#type' => 'link',
      '#title' => $this->t('Configure your block here'),
      '#url' => Url::fromRoute('config_pages.global_configurations'),
    ];

    return $form;
  }

}
