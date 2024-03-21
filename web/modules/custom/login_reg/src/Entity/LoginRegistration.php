<?php

declare(strict_types=1);

namespace Drupal\login_reg\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\login_reg\LoginRegistrationInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the login registration entity class.
 *
 * @ContentEntityType(
 *   id = "login_registration",
 *   label = @Translation("Login Registration"),
 *   label_collection = @Translation("Login Registrations"),
 *   label_singular = @Translation("login registration"),
 *   label_plural = @Translation("login registrations"),
 *   label_count = @PluralTranslation(
 *     singular = "@count login registration",
 *     plural = "@count login registrations",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\login_reg\LoginRegistrationListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\login_reg\LoginRegistrationAccessControlHandler",
 *     "form" = {
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "edit" = "Drupal\Core\Entity\ContentEntityForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "login_registration",
 *   admin_permission = "administer login_registration",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 *   field_ui_base_route = "entity.login_registration.settings",
 * )
 */
final class LoginRegistration extends ContentEntityBase implements LoginRegistrationInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the login registration was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the login registration was last edited.'));

    return $fields;
  }

}
