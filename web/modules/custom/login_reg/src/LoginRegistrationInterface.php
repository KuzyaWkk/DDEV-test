<?php declare(strict_types = 1);

namespace Drupal\login_reg;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a login registration entity type.
 */
interface LoginRegistrationInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
