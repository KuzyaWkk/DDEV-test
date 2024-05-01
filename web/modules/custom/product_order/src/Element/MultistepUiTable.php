<?php

namespace Drupal\product_order\Element;

use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Element;
use Drupal\Core\Render\Element\Table;

/**
 * Provides a multistep_ui table element.
 *
 * @RenderElement ("multistep_ui_table")
 */
class MultistepUiTable extends Table {

  /**
   * {@inheritDoc}
   */
  public function getInfo(): array {
    $info = parent::getInfo();
    $info['#status'] = ['' => []];
    $info['#theme'] = 'multistep_ui_table';
    array_unshift($info['#pre_render'], [$this, 'tablePreRender'], [$this, 'preRenderStatusRows']);
    return $info;
  }

  /**
   * Performs pre-render tasks on multistep_ui_table elements.
   *
   * @param array $elements
   *   A structured array containing two sub-levels of elements. Properties
   *   used:
   *   - #tabledrag: The value is a list of $options arrays that are passed to
   *     drupal_attach_tabledrag(). The HTML ID of the table is added to each
   *     $options array.
   *
   * @return array
   *   The $element with prepared variables ready for
   *   multistep-ui-table.html.twig.
   */
  public static function tablePreRender(array $elements): array {
    $js_settings = [];
    $statuses = $elements['#status'];
    $tree = ['children' => []];
    $trees = array_fill_keys(array_keys($statuses), $tree);
    $children = Element::children($elements);

    foreach ($children as $row_name) {
      $row = &$elements[$row_name];
      $status = $row['status']['#value'];
      $target = &$trees[$status];
      $target['children'][$row_name] = [
        'name' => $row_name,
        'weight' => $row['weight']['#value'],
      ];
      $id = Html::getClass($row_name);
      $row['#attributes']['id'] = $id;

      if (isset($row['#js_settings'])) {
        $row['#js_settings'] += [
          'rowHandler' => $row['#row_type'],
          'name' => $row_name,
          'status' => $status,
        ];
        $js_settings[$id] = $row['#js_settings'];
      }
    }

    foreach ($statuses as $name => $value) {
      uasort($trees[$name]['children'], function ($a, $b) {
        return $a['weight'] - $b['weight'];
      });
      $elements['#status'][$name]['rows_order'] = array_keys($trees[$name]['children']);
    }
    $elements['#attached']['drupalSettings']['multistepStatusData'] = $js_settings;

    if (!empty($elements['#tabledrag']) && isset($elements['#attributes']['id'])) {
      foreach ($elements['#tabledrag'] as $options) {
        $options['table_id'] = $elements['#attributes']['id'];
        drupal_attach_tabledrag($elements, $options);
      }
    }

    return $elements;
  }

  /**
   * Performs pre-render to move #status to rows.
   *
   * @param array $elements
   *   A structured array containing two sub-levels of elements. Properties
   *   used:
   *   - #tabledrag: The value is a list of $options arrays that are passed to
   *     drupal_attach_tabledrag(). The HTML ID of the table is added to each
   *     $options array.
   *
   * @return array
   *   The $element with prepared variables ready for field-ui-table.html.twig.
   */
  public static function preRenderStatusRows($elements) {
    $columns_count = count($elements['#header']);
    $rows = [];

    foreach (Element::children($elements) as $key) {
      $rows[$key] = $elements[$key];
      unset($elements[$key]);
    }

    foreach ($elements['#status'] as $name => $status) {
      $status_class = Html::getClass($name);
      if (isset($status['title']) && empty($status['invisible'])) {
        $elements['#rows'][] = [
          'class' => [
            'status-title',
            'status-' . $status_class . '-title',
          ],
          'no_striping' => TRUE,
          'data' => [
            ['data' => $status['title'], 'colspan' => $columns_count],
          ],
        ];
      }
      if (isset($status['message'])) {
        $class = (empty($status['rows_order']) ? 'status-empty' : 'status-populated');
        $elements['#rows'][] = [
          'class' => [
            'status-message',
            'status-' . $status_class . '-message', $class,
          ],
          'no_striping' => TRUE,
          'data' => [
            ['data' => $status['message'], 'colspan' => $columns_count],
          ],
        ];
      }

      foreach ($status['rows_order'] as $row_name) {
        $element = $rows[$row_name];
        $row = ['data' => []];

        if (isset($element['#attributes'])) {
          $row += $element['#attributes'];
        }

        foreach (Element::children($element) as $cell_key) {
          $child = $element[$cell_key];

          if (!(isset($child['#type']) && $child['#type'] == 'value')) {
            $cell = ['data' => $child];

            if (isset($child['#cell_attributes'])) {
              $cell += $child['#cell_attributes'];
            }
            $row['data'][] = $cell;
          }
        }
        $elements['#rows'][] = $row;
      }
    }

    return $elements;
  }

}
