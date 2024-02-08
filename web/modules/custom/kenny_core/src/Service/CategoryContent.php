<?php

namespace Drupal\kenny_core\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CategoryContent implements CategoryContentInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   *  Construct a CategoryContent manager.
   */
   public function __construct(EntityTypeManagerInterface $entity_type_manager) {
     $this->entityTypeManager = $entity_type_manager;
   }

  /**
   * CategoryContent create container
   */
  public function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
   }

  /**
   * {@inheritdoc}
   */
  public function getContent($term_name) {
    $taxonomy = $this->entityTypeManager->getStorage('taxonomy_term');
    $vocabulary = $taxonomy->loadTree('category');
    if ($term_name == 'food-drink') {
      $term_name = 'Food & Drink';
    }

    foreach ($vocabulary as $term) {

      if (strtolower($term->name) == $term_name) {
        return $term->tid;
      }



    }

    return 1;
//    $database = $this->database->select("kenny_favorite_training", 'kft');
//    $database->addField('kft', 'nid');
//    $database->condition('kft.uid', $uid);
//    $result = $database->execute();
//    return $result->fetchCol();

  }

}
