<?php

/**
 * @file
 * Install, update and uninstall functions for the batch_api module.
 */

/**
 * Implements hook_post_update_NAME().
 */
function batch_api_post_update_bulk_edit_paragraphs_field_formatter(&$sandbox):void {

  $limit = 20;
  $format = 'basic_html';

  $query = \Drupal::entityQuery('paragraph')
    ->accessCheck(FALSE)
    ->condition('field_body.format', $format, '<>')
    ->range(0, $limit);
  $paragraphs = $query->execute();

  if (empty($paragraphs)) {
    $sandbox['#finished'] = 1;
    return;
  }

  $load_items = \Drupal::entityTypeManager()
    ->getStorage('paragraph')
    ->loadMultiple($sandbox['items']);

  foreach ($load_items as $item) {
    change_text_format($item, $format);
  }

  $sandbox['#finished'] = 0;
}

/**
 * Change the text formatter of the paragraph.
 */
function change_text_format($paragraph, $format):void {
  $paragraph->get('field_body')->format = $format;
  $paragraph->save();
}
