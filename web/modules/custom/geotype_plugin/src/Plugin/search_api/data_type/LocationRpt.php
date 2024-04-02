<?php

namespace Drupal\geotype_plugin\Plugin\search_api\data_type;

use Drupal\search_api\DataType\DataTypePluginBase;

/**
 * Provides a custom location rpt data type.
 *
 * @SearchApiDataType(
 *   id = "location_rpt",
 *   label = @Translation("Custom Location RPT"),
 *   description = @Translation("Custom location rpt field."),
 *   fallback_type = "text",
 *   prefix = "rpt",
 * )
 */
class LocationRpt extends DataTypePluginBase {}
