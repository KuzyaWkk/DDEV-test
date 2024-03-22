<?php

declare(strict_types=1);

namespace Drupal\login_reg;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityHandlerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the access control handler for the login registration entity type.
 */
final class LoginRegistrationAccessControlHandler extends EntityAccessControlHandler implements EntityHandlerInterface {

  /**
   * Constructs a new object of LoginRegistrationAccessControlHandler.
   */
  public function __construct(
    EntityTypeInterface $entity_type,
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {
    parent::__construct($entity_type);
  }

  /**
   * {@inheritDoc}
   */
  public static function createInstance(
    ContainerInterface $container,
    EntityTypeInterface $entity_type,
  ): LoginRegistrationAccessControlHandler {
    return new LoginRegistrationAccessControlHandler(
      $entity_type,
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(
    EntityInterface $entity,
    $operation,
    AccountInterface $account,
  ): AccessResult {
    $user_storage = $this->entityTypeManager->getStorage('user');
    $user = $user_storage
      ->loadByProperties(['field_additional_information' => $entity->id()]);
    $user = reset($user);

    if (empty($user)) {
      return AccessResult::forbidden();
    }

    if (
      $user->id() != $account->id() &&
      !$account->hasPermission('administer login_registration')
    ) {
      return AccessResult::forbidden();
    }

    return match($operation) {
      'view', 'update' => AccessResult::allowedIfHasPermissions($account, [
        'view edit own login_registration', 'administer login_registration',
      ], 'OR'),
      'delete' => AccessResult::allowedIfHasPermissions($account, [
        'administer login_registration',
      ]),
      default => AccessResult::neutral(),
    };
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(
    AccountInterface $account,
    array $context,
    $entity_bundle = NULL
  ): AccessResult {
    return AccessResult::allowedIfHasPermissions($account, [
      'create login_registration', 'administer login_registration',
    ], 'OR');
  }

}
