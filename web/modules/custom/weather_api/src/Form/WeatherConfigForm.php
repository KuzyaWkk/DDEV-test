<?php

namespace Drupal\weather_api\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Drupal\weather_api\Enum\UnitsEnum;
use Drupal\weather_api\Service\WeatherDatabase\WeatherDatabaseConnectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configuration form for setting up the weather API key.
 */
final class WeatherConfigForm extends FormBase {

  /**
   * Constructs a WeatherConfigForm objects.
   */
  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
    protected AccountProxyInterface $currentUser,
    protected Connection $database,
    protected WeatherDatabaseConnectionInterface $weatherDatabase,
  ) {}

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container): WeatherConfigForm {
    return new WeatherConfigForm(
      $container->get('entity_type.manager'),
      $container->get('current_user'),
      $container->get('database'),
      $container->get('weather_api.weather_database'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId():string {
    return 'weather_api_weather_config';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state):array {
    $list_of_city = [];
    $term_cities = $this->entityTypeManager
      ->getStorage('taxonomy_term')
      ->loadTree('cities');

    foreach ($term_cities as $term) {
      $list_of_city[$term->tid] = $term->name;
    }

    $cases = UnitsEnum::cases();
    $list_of_units = [];
    foreach ($cases as $case) {
      $list_of_units[$case->value] = $this
        ->t(preg_replace('/([A-Z])/', ' $1', $case->name));
    }

    $selected = $this->weatherDatabase->getWeatherData($this->currentUser->id());
    if ($selected) {
      $selected_units = $selected['units'];
      $selected_city = $selected['cid'];
    }

    $form['city'] = [
      '#type' => 'select',
      '#title' => $this->t('Select city'),
      '#options' => $list_of_city,
      '#default_value' => $selected_city ?? $this->t('Lutsk'),
    ];

    $form['units'] = [
      '#type' => 'select',
      '#title' => $this->t('Select units'),
      '#options' => $list_of_units,
      '#default_value' => $selected_units ?? UnitsEnum::DegreesCelsius->value,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#title' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state):void {
    $cid = $form_state->getValue('city');
    $units = $form_state->getValue('units');
    $uid = $this->currentUser->id();
    $this->weatherDatabase->setWeatherData($uid, $cid, $units);
    $form_state->setRedirectUrl(Url::fromUserInput('/'));
  }

}
