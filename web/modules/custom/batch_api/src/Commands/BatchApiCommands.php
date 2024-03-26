<?php

namespace Drupal\batch_api\Commands;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drush\Commands\DrushCommands;

/**
 * Class to created custom drush commands.
 */
class BatchApiCommands extends DrushCommands {

  use DependencySerializationTrait;
  use StringTranslationTrait;

  /**
   * Constructs a new object of BatchApiCommands.
   */
  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
    protected MessengerInterface $messenger,
  ) {}

  /**
   * Invoke post update hook.
   *
   * @command bulk-edit-text-format:executeEditParagraphsTextFormat
   * @aliases edit_text_format
   */
  public function executeEditParagraphsTextFormat():void {

    $batch = [
      'title' => $this->t('Bulk updating text format'),
      'operations' => [
        [[$this, 'batchProcess'], []],
      ],
      'finished' => [$this, 'batchFinished'],
      'progress_message' => t('Processes @current out of @total'),
    ];

    batch_set($batch);
    drush_backend_batch_process();
  }

  /**
   * Common batch processing callback for all operations.
   *
   * Required to load our include the proper batch file.
   */
  public function batchProcess(&$context):void {

    $limit = 20;
    $format = 'limited_html';

    $paragraph_storage = $this->entityTypeManager
      ->getStorage('paragraph');
    $query = $paragraph_storage
      ->getQuery()
      ->accessCheck(FALSE)
      ->condition('field_body.format', $format, '<>')
      ->range(0, $limit);
    $paragraphs = $query->execute();

    if (empty($paragraphs)) {
      $context['finished'] = 1;
      return;
    }

    $load_items = $this->entityTypeManager
      ->getStorage('paragraph')
      ->loadMultiple($paragraphs);

    foreach ($load_items as $item) {
      $this->changeTextFormat($item, $format);
    }

    $context['finished'] = 0;
  }

  /**
   * Batch finished callback.
   */
  public function batchFinished($success, $results, $operations):void {
    $message = $this->t('Number of paragraphs affected by batch: @count', [
      '@count' => $results['processed'],
    ]);

    $this->messenger->addStatus($message);
  }

  /**
   * Change the text formatter of the paragraph.
   */
  protected function changeTextFormat($paragraph, $format):void {
    $paragraph->get('field_body')->format = $format;
    $paragraph->save();
  }

}
