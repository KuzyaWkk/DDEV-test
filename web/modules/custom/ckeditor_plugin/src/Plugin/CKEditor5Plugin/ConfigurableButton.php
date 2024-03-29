<?php

namespace Drupal\ckeditor_plugin\Plugin\CKEditor5Plugin;

use Drupal\ckeditor5\HTMLRestrictions;
use Drupal\ckeditor5\Plugin\CKEditor5PluginConfigurableInterface;
use Drupal\ckeditor5\Plugin\CKEditor5PluginConfigurableTrait;
use Drupal\ckeditor5\Plugin\CKEditor5PluginDefault;
use Drupal\ckeditor5\Plugin\CKEditor5PluginElementsSubsetInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\editor\EditorInterface;

/**
 * CKEditor 5 Configurable Button plugin.
 *
 * @internal
 *   Plugin Configurable Button are internal.
 */
class ConfigurableButton extends CKEditor5PluginDefault implements CKEditor5PluginConfigurableInterface, CKEditor5PluginElementsSubsetInterface {
  use CKEditor5PluginConfigurableTrait;

  /**
   * The default configuration for this plugin.
   *
   * @var string[][]
   */
  const DEFAULT_CONFIGURATION = [
    'enabled_colors' => [
      'blue',
      'black',
      'gray',
      'white',
      'gold',
    ],
    'enabled_background_colors' => [
      'blue',
      'black',
      'gray',
      'white',
      'gold',
    ],

    'default_color' => 'blue',

    'default_background_color' => 'blue',
  ];

  /**
   * {@inheritDoc}
   */
  public function defaultConfiguration(): array {
    return static::DEFAULT_CONFIGURATION;
  }

  /**
   * {@inheritdoc}
   *
   * Form for choosing which background color classes are available.
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state):array {
    $form['enabled_colors'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Select the content colors'),
      '#description' => $this->t('These are description.'),
    ];

    foreach ($this
      ->getPluginDefinition()
      ->getCKEditor5Config()['button']['colors']['options'] as $color_option) {
      $name = $color_option['label'];
      $lower_name = strtolower($name);
      $form['enabled_colors'][$lower_name] = [
        '#type' => 'checkbox',
        '#title' => $this->t($name),
        '#return_value' => $lower_name,
        '#default_value' => in_array(
          $lower_name, $this->configuration['enabled_colors'], TRUE
        ) ? $lower_name : NULL,
      ];
    }

    $default_color_options = $this->configuration['enabled_colors'];
    $options = [];
    foreach ($default_color_options as $color) {
      $options[$color] = $color;
    }
    $form['default_color'] = [
      '#type' => 'select',
      '#title' => $this->t('Select a default color'),
      '#options' => $options,
      '#default_value' => $this->configuration['default_color'],
    ];

    $form['enabled_background_colors'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Select the background colors'),
      '#description' => $this->t('These are description.'),
    ];

    foreach ($this
      ->getPluginDefinition()
      ->getCKEditor5Config()['button']['background_colors']['options'] as $bgcolor_options) {
      $name = $bgcolor_options['label'];
      $lower_name = strtolower($name);
      $form['enabled_background_colors'][$lower_name] = [
        '#type' => 'checkbox',
        '#title' => $this->t($name),
        '#return_value' => $lower_name,
        '#default_value' => in_array(
          $lower_name, $this->configuration['enabled_background_colors'], TRUE
        ) ? $lower_name : NULL,
      ];
    }

    $default_bg_color_options = $this->configuration['enabled_background_colors'];
    $options = [];
    foreach ($default_bg_color_options as $bg_color) {
      $options[$bg_color] = $bg_color;
    }
    $form['default_background_color'] = [
      '#type' => 'select',
      '#title' => $this->t('Select a default color'),
      '#options' => $options,
      '#default_value' => $this->configuration['default_background_color'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state):void {
    $enabled_colors = $form_state->getValue('enabled_colors');
    $config_value = array_values(array_filter($enabled_colors));
    $form_state->setValue('enabled_colors', $config_value);

    $default_color = $form_state->getValue('default_color');
    $form_state->setValue('default_color', $default_color);

    $enabled_background_colors = $form_state->getValue('enabled_background_colors');
    $config_value_bg = array_values(array_filter($enabled_background_colors));
    $form_state->setValue('enabled_background_colors', $config_value_bg);

    $default_bg_color = $form_state->getValue('default_background_color');
    $form_state->setValue('default_background_color', $default_bg_color);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state):void {
    $this->configuration['enabled_colors'] = $form_state
      ->getValue('enabled_colors');
    $this->configuration['default_color'] = $form_state
      ->getValue('default_color');
    $this->configuration['enabled_background_colors'] = $form_state
      ->getValue('enabled_background_colors');
    $this->configuration['default_background_color'] = $form_state
      ->getValue('default_background_color');
  }

  /**
   * {@inheritdoc}
   */
  public function getDynamicPluginConfig(array $static_plugin_config, EditorInterface $editor): array {
    $enabled_colors = $this->configuration['enabled_colors'];
    $all_color_options = $static_plugin_config['button']['colors']['options'];
    $configured_color_options = array_filter(
      $all_color_options, function ($option) use ($enabled_colors) {
        return in_array(strtolower($option['label']), $enabled_colors, TRUE);
      }
    );

    $enabled_background_colors = $this->configuration['enabled_background_colors'];
    $all_bgcolor_options = $static_plugin_config['button']['background_colors']['options'];

    $configured_background_color_options = array_filter(
      $all_bgcolor_options, function ($option) use ($enabled_background_colors) {
        return in_array(strtolower($option['label']), $enabled_background_colors, TRUE);
      }
    );

    return [
      'button' => [
        'colors' => [
          'options' => $configured_color_options,
          'default_color' => $this->configuration['default_color'],
        ],
        'background_colors' => [
          'options' => $configured_background_color_options,
          'default_background_color' => $this->configuration['default_background_color'],
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getElementsSubset(): array {
    $enabled_colors = $this->configuration['enabled_colors'];
    $enabled_background_colors = $this->configuration['enabled_background_colors'];
    $plugin_definition = $this->getPluginDefinition();
    $all_elements = $plugin_definition->getElements();
    $subset = HTMLRestrictions::fromString(implode($all_elements));
    foreach ($plugin_definition->getCKEditor5Config()['button']['colors']['options'] as $configured_colors) {
      if (!in_array(strtolower($configured_colors['label']), $enabled_colors, TRUE)) {
        $element_string = '<a class=' . '"' . $configured_colors["className"] . '"' . '>';
        $subset = $subset->diff(HTMLRestrictions::fromString($element_string));
      }
    }

    foreach ($plugin_definition->getCKEditor5Config()['button']['background_colors']['options'] as $configured_background_colors) {
      if (!in_array(strtolower($configured_background_colors['label']), $enabled_background_colors, TRUE)) {
        $element_string = '<a class=' . '"' . $configured_background_colors["className"] . '"' . '>';
        $subset = $subset->diff(HTMLRestrictions::fromString($element_string));
      }
    }

    return $subset->toCKEditor5ElementsArray();
  }

}
