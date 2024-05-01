<?php

namespace Drupal\product_order\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration form for setting up the multistep form.
 */
final class ProductOrderMultistepFormSettingsJs extends ConfigFormBase {

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'product_order_multistep_form_settings_js';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['product_order.product_order_multistep_form_settings_js'];
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(
    array $form,
    FormStateInterface $form_state,
  ): array {
    $enabled_steps_options = $this->getStepsOptions();
    $fields = [];
    foreach ($enabled_steps_options as $step) {
      $fields[$step['id']] = $step['label'];
    }
    $form = [
      '#fields' => array_keys($fields),
    ];

    $table = [
      '#type' => 'multistep_ui_table',
      '#header' => [
        $this->t('Field name'),
        $this->t('Weight'),
        $this->t('Parent'),
        $this->t('Status'),
      ],
      '#status' => $this->getStatus(),
      '#attributes' => [
        'class' => ['multistep-ui-overview'],
        'id' => 'multistep-display-overview',
      ],
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'field-weight',
        ],
        [
          'action' => 'match',
          'relationship' => 'parent',
          'group' => 'field-parent',
          'subgroup' => 'field-parent',
          'source' => 'field-name',
        ],
        [
          'action' => 'match',
          'relationship' => 'parent',
          'group' => 'field-status',
          'subgroup' => 'field-status',
          'source' => 'field-name',
        ],
      ],
    ];

    foreach ($enabled_steps_options as $value) {
      $table[$value['id']] = $this->buildFieldRow($value, $form, $form_state);
    }

    $form['fields'] = $table;

    $form['#attached']['library'][] = 'product_order/product_order_multistep_form_ui';

    return parent::buildForm($form, $form_state);
  }

  /**
   * Builds the table row structure for a single field.
   */
  protected function buildFieldRow(
    array $value,
    array $form,
    FormStateInterface $form_state,
  ): array {
    $field_id = $value['id'];
    $label = $value['label'];
    $status = array_keys($this->getStatus());
    $field_row = [
      '#attributes' => ['class' => ['draggable', 'tabledrag-leaf']],
      '#row_type' => 'field',
      '#js_settings' => [
        'defaultPlugin' => $field_id,
      ],
      'human_name' => [
        '#plain_text' => $label,
      ],
      'weight' => [
        '#type' => 'weight',
        '#title' => $this->t('Weight for @name', ['@name' => $label]),
        '#title_display' => 'invisible',
        '#default_value' => $value['weight'],
        '#attributes' => ['class' => ['field-weight']],
      ],
      'parent_wrapper' => [
        'parent' => [
          '#type' => 'select',
          '#title' => $this->t('Label display for @title', ['@title' => $label]),
          '#title_display' => 'invisible',
          '#options' => array_combine($status, $status),
          '#empty_value' => '',
          '#attributes' => ['class' => ['js-field-parent', 'field-parent']],
          '#parents' => ['fields', $field_id, 'parent'],
        ],
        'hidden_name' => [
          '#type' => 'hidden',
          '#default_value' => $label,
          '#attributes' => ['class' => ['field-name']],
        ],
      ],
      'status' => [
        '#type' => 'select',
        '#title' => $this->t('Region for @title', ['@title' => $label]),
        '#title_display' => 'invisible',
        '#options' => $this->getStatusOptions(),
        '#default_value' => $value['status'],
        '#attributes' => ['class' => ['field-status']],
      ],
    ];
    return $field_row;
  }

  /**
   * The list of available steps options.
   */
  protected function getStepsOptions(): array {
    $config =
      $this->config('product_order.product_order_multistep_form_settings_js');
    $table_steps = $config->get('table_steps');

    if ($table_steps) {
      return $table_steps;
    }

    return [
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
   * Returns an associative array of all regions.
   */
  public function getStatusOptions(): array {
    $options = [];
    foreach ($this->getStatus() as $status => $data) {
      $options[$status] = $data['title'];
    }
    return $options;
  }

  /**
   * Get the regions needed to create the overview form.
   */
  public function getStatus(): array {
    return [
      'enabled' => [
        'title' => $this->t('Enabled'),
        'invisible' => TRUE,
        'message' => $this->t('No field is displayed.'),
      ],
      'hidden' => [
        'title' => $this->t('Disabled', [], ['context' => 'Plural']),
        'message' => $this->t('No field is hidden.'),
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
    $fields_values = $form_state->getValue('fields');
    $config_values = [];
    foreach ($fields_values as $name => $data) {
      $config_values[$name] = [
        'id' => $name,
        'weight' => $data['weight'],
        'status' => $data['status'],
        'label' => $data['parent_wrapper']['hidden_name'],
      ];
    }
    $config = $this->config('product_order.product_order_multistep_form_settings_js');
    $config->set('table_steps', $config_values);
    $config->save();
    $this->messenger()
      ->addStatus($this->t('The configuration options have been saved.'));
  }

}
