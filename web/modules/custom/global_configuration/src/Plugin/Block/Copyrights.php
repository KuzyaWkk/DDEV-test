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
 * Class Register Button.
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
   * Constructs a new Copyrights instance.
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

    $field_copyrights_value = $global_configuration_storage
      ->get('field_copyrights')
      ->view();

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
