<?php

namespace Drupal\product_order\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration form for setting up the multistep form.
 */
final class ProductOrderMultistepFormSettings extends ConfigFormBase {

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'product_order_multistep_form_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['product_order.product_order_multistep_form_settings'];
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(
    array $form,
    FormStateInterface $form_state,
  ): array {
    $enabled_steps_options = $this->getStepsOptions();

    $form['enabled_steps'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Field name'),
        $this->t('Weight'),
      ],
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'field-weight',
        ],
      ],
    ];

    foreach ($enabled_steps_options as $row) {
      if ($row['id'] != 'hidden' && $row['id'] != 'enabled') {
        $form['enabled_steps'][$row['id']]['#attributes']['class'][] = 'draggable';
      }

      $form['enabled_steps'][$row['id']]['#weight'] = $row['weight'];
      $form['enabled_steps'][$row['id']]['field_container']['human_name'] = [
        '#plain_text' => $row['label'],
      ];

      $form['enabled_steps'][$row['id']]['field_container']['label'] = [
        '#type' => 'hidden',
        '#value' => $row['label'],
      ];

      $form['enabled_steps'][$row['id']]['weight'] = [
        '#type' => 'weight',
        '#title' => $this->t('Weight for @name', ['@name' => $row['label']]),
        '#title_display' => 'invisible',
        '#default_value' => $row['weight'],
        '#attributes' => ['class' => ['field-weight']],
      ];

    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * The list of available steps options.
   */
  protected function getStepsOptions(): array {
    $config = $this->config('product_order.product_order_multistep_form_settings');
    $table_steps = $config->get('table_steps');
    $table_region = $config->get('table_region');
    if ($table_steps || $table_region) {
      $data_array = array_merge($table_steps, $table_region);
      uasort($data_array, ['Drupal\Component\Utility\SortArray', 'sortByWeightElement']);
    }

    if (isset($data_array)) {
      return $data_array;
    }

    return [
      [
        'id' => 'enabled',
        'label' => $this->t('Enabled'),
        'weight' => -10,
      ],
      [
        'id' => 'hidden',
        'label' => $this->t('Hidden'),
        'weight' => 5,
      ],
      [
        'id' => 'product',
        'status' => 'enabled',
        'label' => $this->t('Product'),
        'weight' => 1,
      ],
      [
        'id' => 'payment',
        'status' => 'enabled',
        'label' => $this->t('Payment'),
        'weight' => 2,
      ],
      [
        'id' => 'delivery',
        'status' => 'enabled',
        'label' => $this->t('Delivery'),
        'weight' => 3,
      ],
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(
    array &$form,
    FormStateInterface $form_state,
  ): void {
    $config_data = $this->refactoringConfigData($form, $form_state);
    $config = $this->config('product_order.product_order_multistep_form_settings');
    $config->set('table_steps', $config_data['data']);
    $config->set('table_region', $config_data['region']);
    $config->save();
    $this->messenger()
      ->addStatus($this->t('The configuration options have been saved.'));
  }

  /**
   * Config data.
   */
  protected function refactoringConfigData(
    $form,
    FormStateInterface $form_state,
  ): array {
    $fields_values = $form_state->getValue('enabled_steps');
    $hidden_weight = $fields_values['hidden']['weight'];
    $config_data = [];
    $config_region = [];
    foreach ($fields_values as $region => &$row) {
      if ($region == 'enabled' || $region == 'hidden') {
        $config_region[$region] = [
          'id' => $region,
          'label' => $row['field_container']['label'],
          'weight' => $row['weight'],
        ];
      }
      else {
        if ($row['weight'] >= $hidden_weight) {
          $row['status'] = 'hidden';
        }
        else {
          $row['status'] = 'enabled';
        }
        $config_data[$region] = [
          'status' => $row['status'],
          'id' => $region,
          'label' => $row['field_container']['label'],
          'weight' => $row['weight'],
        ];
      }
    }

    return [
      'data' => $config_data,
      'region' => $config_region,
    ];
  }

}
