<?php declare(strict_types = 1);

namespace Drupal\login_reg;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the login registration entity type.
 *
 * phpcs:disable Drupal.Arrays.Array.LongLineDeclaration
 *
 * @see https://www.drupal.org/project/coder/issues/3185082
 */
final class LoginRegistrationAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account): AccessResult {
    return match($operation) {
      'view' => AccessResult::allowedIfHasPermissions($account, ['view login_reg_login_registration', 'administer login_reg_login_registration'], 'OR'),
      'update' => AccessResult::allowedIfHasPermissions($account, ['edit login_reg_login_registration', 'administer login_reg_login_registration'], 'OR'),
      'delete' => AccessResult::allowedIfHasPermissions($account, ['delete login_reg_login_registration', 'administer login_reg_login_registration'], 'OR'),
      default => AccessResult::neutral(),
    };
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL): AccessResult {
    return AccessResult::allowedIfHasPermissions($account, ['create login_reg_login_registration', 'administer login_reg_login_registration'], 'OR');
  }

}
