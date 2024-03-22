<?php

namespace Drupal\weather_api\Enum;

/**
 * List of Units.
 */
enum UnitsEnum: string {

  case DegreesCelsius = 'metric';
  case DegreesKelvin = 'standart';
  case DegreesFahrenheit = 'imperial';

  /**
   * Get options for units from Enum.
   */
  public static function getUnitsOption(): array {
    $options = [];
    $options[self::DegreesCelsius->value] = t('Degrees Celcius');
    $options[self::DegreesKelvin->value] = t('Degrees Kelvin');
    $options[self::DegreesFahrenheit->value] = t('Degrees Fahrenheit');
    return $options;
  }

}
