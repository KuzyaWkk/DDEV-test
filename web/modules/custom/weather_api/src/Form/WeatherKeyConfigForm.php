<?php

namespace Drupal\weather_api\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration form for setting up the weather API key.
 */
final class WeatherKeyConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId():string {
    return 'weather_api_weather_key';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['weather_api.weather_key_settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state):array {

    $config = $this->config('weather_api.weather_key_settings');
    $selected_appid = $config->get('appid');

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
    $this->config('weather_api.weather_key_settings')
      ->set('appid', $form_state->getValue('appid'))
      ->save();
    $this->messenger()->addStatus($this->t('The configuration options have been saved.'));
  }

}
