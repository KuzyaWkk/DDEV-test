<?php

namespace Drupal\stores\Plugin\views\area;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\area\AreaPluginBase;

/**
 * Alter the Map Stores used by the view.
 *
 * @ingroup views_area_handlers
 *
 * @ViewsArea("map_stores")
 */
class MapStores extends AreaPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['color'] = ['default' => NULL];
    $options['size'] = ['default' => 10];
    $options['zoom'] = ['default' => '100'];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state):void {
    parent::buildOptionsForm($form, $form_state);
    $allowed_zoom = $this->getAllowedZoom();

    $form['color'] = [
      '#title' => $this->t('Select an color of point'),
      '#type' => 'color',
      '#default_value' => $this->options['color'],
      '#required' => TRUE,
    ];

    $form['size'] = [
      '#title' => $this->t('Enter radius of circle'),
      '#type' => 'textfield',
      '#default_value' => $this->options['size'],
      '#description' => $this->t('Radius must be an positive numeric. E.g. "7"'),
      '#required' => TRUE,
    ];

    $form['zoom'] = [
      '#title' => $this->t('Select a zoom of map'),
      '#type' => 'select',
      '#options' => $allowed_zoom,
      '#default_value' => $this->options['zoom'],
      '#required' => TRUE,
    ];

  }

  /**
   * {@inheritdoc}
   */
  public function validate():array {
    $errors = parent::validate();
    if (!is_numeric($this->options['size'])) {
      $errors[] = $this->t('The radius must be numeric');
    }

    if (floatval($this->options['size']) <= 0) {
      $errors[] = $this->t('The Radius must be positive numeric');
    }
    return $errors;
  }

  /**
   * {@inheritdoc}
   */
  public function render($empty = FALSE):array {
    $build['#attached']['library'][] = 'stores/stores_leaflet';
    $stores = $this->view->result;
    $display_id = $this->view->current_display;

    foreach ($stores as $store) {
      $entity = $store->_entity;
      $location = $entity->get('field_location')->getValue();
      $title = $entity->get('title')->value;
      $build['#attached']['drupalSettings']['coordinates'][$display_id][] = [
        'title' => $title,
        'location' => $location,
      ];
    }

    $build['#attached']['drupalSettings']['mapstores'][$display_id] = [
      'color' => $this->options['color'],
      'size' => $this->options['size'],
      'zoom' => $this->options['zoom'],
    ];
    $build['#markup'] = "<div class='leaflet__map' data-display-id='$display_id'></div>";
    return $build;
  }

  /**
   * Allowed zoom properties.
   */
  protected function getAllowedZoom():array {
    return [
      '1' => $this->t('Small'),
      '5' => $this->t('Medium'),
      '17' => $this->t('Large'),
      '100' => $this->t('Extra Large'),
    ];
  }

}
