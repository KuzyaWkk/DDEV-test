<?php

namespace Drupal\kenny_core\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Kenny Core setting for this site.
 */
final class WeatherConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId():string {
    return 'kenny_core_weather_config';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['kenny_core.weather_settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state):array {

    $list_of_city = [
      'Lutsk' => $this->t('Lutsk'),
      'Lviv' => $this->t('Lviv'),
      'London' => $this->t('London'),
    ];

    $list_of_units = [
      'standart' => $this->t('Degrees Kelvin'),
      'metric' => $this->t('Degrees Celsius'),
      'imperial' => $this->t('Degrees Fahrenheit'),
    ];
    $config = $this->config('kenny_core.weather_settings');
    $selected_city = $config->get('city');
    $selected_units = $config->get('units');
    $selected_appid = $config->get('appid');

    $form['city'] = [
      '#type' => 'select',
      '#title' => $this->t('Select city'),
      '#options' => $list_of_city,
      '#default_value' => $selected_city,
    ];
    $form['units'] = [
      '#type' => 'select',
      '#title' => $this->t('Select units'),
      '#options' => $list_of_units,
      '#default_value' => $selected_units,
    ];
    $form['appid'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Add key for api'),
      '#default_value' => $selected_appid,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state):void {
    $this->config('kenny_core.weather_settings')
      ->set('city', $form_state->getValue('city'))
      ->set('units', $form_state->getValue('units'))
      ->set('appid', $form_state->getValue('appid'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
