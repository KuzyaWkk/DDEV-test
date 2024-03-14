<?php

namespace Drupal\weather_api\Enum;

/**
 * List of Units.
 */
enum UnitsEnum: string {
  case DegreesCelsius = 'metric';
  case DegreesKelvin = 'standart';
  case DegreesFahrenheit = 'imperial';

}
