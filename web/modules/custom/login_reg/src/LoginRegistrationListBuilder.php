<?php declare(strict_types = 1);

namespace Drupal\login_reg;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for the login registration entity type.
 */
final class LoginRegistrationListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['id'] = $this->t('ID');
    $header['created'] = $this->t('Created');
    $header['changed'] = $this->t('Updated');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var \Drupal\login_reg\LoginRegistrationInterface $entity */
    $row['id'] = $entity->id();
    $row['created']['data'] = $entity
      ->get('created')
      ->view(['label' => 'hidden']);
    $row['changed']['data'] = $entity
      ->get('changed')
      ->view(['label' => 'hidden']);
    return $row + parent::buildRow($entity);
  }

}
