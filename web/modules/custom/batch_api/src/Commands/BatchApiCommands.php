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

    $paragraph_storage = $this->entityTypeManager
      ->getStorage('paragraph');
    $query = $paragraph_storage
      ->getQuery()
      ->accessCheck(FALSE)
      ->exists('field_body');
    $paragraphs = $query->execute();
    $format = 'limited_html';

    $batch = [
      'title' => $this->t('Bulk updating text format'),
      'operations' => [
        [[$this, 'batchProcess'], [$paragraphs, $format]],
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
  public function batchProcess($paragraphs, $format, &$context):void {

    $limit = 20;

    if (!isset($context['sandbox']['total'])) {
      $context['sandbox']['current'] = 0;
      $context['sandbox']['total'] = count($paragraphs);
    }

    if (empty($context['sandbox']['total'])) {
      $context['finished'] = 1;
    }

    if (empty($context['sandbox']['items'])) {
      $context['sandbox']['items'] = $paragraphs;
    }

    $counter = 0;
    if (!empty($context['sandbox']['items'])) {

      if ($context['sandbox']['current'] != 0) {
        array_splice($context['sandbox']['items'], 0, $limit);
      }

      foreach ($context['sandbox']['items'] as $item) {
        if ($counter != $limit) {
          $this->changeTextFormat($item, $format);

          $counter++;
          $context['sandbox']['current']++;
          $context['results']['processed'] = $context['sandbox']['current'];
        }
      }
    }

    if ($context['sandbox']['current'] != $context['sandbox']['total']) {
      $context['finished'] = $context['sandbox']['current'] / $context['sandbox']['total'];
    }
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
  protected function changeTextFormat($pid, $format):void {
    $paragraph = $this->entityTypeManager
      ->getStorage('paragraph')
      ->load($pid);
    $paragraph->get('field_body')->format = $format;
    $paragraph->save();
  }

}
