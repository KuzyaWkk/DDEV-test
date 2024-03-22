<?php

/**
 * @file
 * Install, update and uninstall functions for the batch_api module.
 */

/**
 * Implements hook_post_update_NAME().
 */
function batch_api_post_update_bulk_edit_paragraphs_field_formatter(&$sandbox):void {

  $query = \Drupal::entityQuery('paragraph');
  $query->accessCheck(FALSE)
    ->exists('field_body');
  $paragraphs = $query->execute();
  $limit = 20;
  $format = 'limited_html';

  if (!isset($sandbox['total'])) {
    $sandbox['current'] = 0;
    $sandbox['total'] = count($paragraphs);
  }

  if (empty($sandbox['total'])) {
    $sandbox['#finished'] = 1;
  }

  if (empty($sandbox['items'])) {
    $sandbox['items'] = $paragraphs;
  }

  $counter = 0;
  if (!empty($sandbox['items'])) {

    if ($sandbox['current'] != 0) {
      array_splice($sandbox['items'], 0, $limit);
    }

    foreach ($sandbox['items'] as $item) {
      if ($counter != $limit) {
        change_text_format($item, $format);

        $counter++;
        $sandbox['current']++;
        $sandbox['processed'] = $sandbox['current'];
      }
    }
  }

  if ($sandbox['current'] != $sandbox['total']) {
    $sandbox['#finished'] = $sandbox['current'] / $sandbox['total'];
  }

}

/**
 * Change the text formatter of the paragraph.
 */
function change_text_format($pid, $format):void {
  $paragraph = \Drupal::entityTypeManager()
    ->getStorage('paragraph')
    ->load($pid);
  $paragraph->get('field_body')->format = $format;
  $paragraph->save();
}
