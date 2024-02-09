<?php
//
//namespace Drupal\kenny_core\Plugin\KennyHeroBlock\Path;
//
//
//use Drupal\kenny_hero_block\Plugin\KennyHeroBlock\Path\KennyHeroBlockPathPluginBase;
//use Drupal\node\NodeInterface;
//use Drupal\taxonomy\Plugin\views\argument\Taxonomy;
//use Drupal\taxonomy\Plugin\views\field\TaxonomyIndexTid;
//use Drupal\taxonomy\TermInterface;
//use Symfony\Component\DependencyInjection\Container;
//
///**
// * Default plugin which will be used if none of others met their requirements.
// *
// * @KennyHeroBlockPath(
// *   id = "kenny_hero_block_path_category",
// *   match_type="listed",
// *   match_path = {"/newspaper_life_news/*"}
// * )
// */
//class KennyHeroCategoryBlock extends KennyHeroBlockPathPluginBase {
//
//
//
//  /**
//   * {@inheritDoc}
//   */
//  public function getPageTitle() {
//    $taxonomy = $this->routeMatch->getParameter('taxonomy_term');
//    $name = $taxonomy->get('name')->value;
//
//
//    return $name;
//  }
//
//  public function getHeroSubtitle() {
//    $taxonomy = $this->routeMatch->getParameter('taxonomy_term');
//
//    if ($taxonomy instanceof TermInterface) {
//      $text = $taxonomy->get('description')->value;
//      $subtitle = strip_tags($text);
//
//      return $subtitle;
//    }
//
//
//   return NULL;
//
//
//
//  }
//
//
//  public function getHeroCategory() {
//    $taxonomy = $this->routeMatch->getParameter('taxonomy_term');
//
//    $categories = [];
//
//    if ($taxonomy instanceof TermInterface) {
//      $current_name = $taxonomy->getName();
//      $current_url = $taxonomy->toUrl()->toString();
//      $id = $taxonomy->id();
//
//
//
//      $taxonomy_storage = $this->entityTypeManager->getStorage('taxonomy_term');
//      $child = $taxonomy_storage->loadChildren($id);
//      if (!empty($child)) {
//        foreach ($child as $term) {
//          $name = $term->get('name')->value;
//          $categories[$name]['name'] = $name;
//          $categories[$name]['url'] = $term->toUrl()->toString();
//        }
//
//      }
//
//      $parent = $taxonomy_storage->loadParents($id);
//
//
//      if (!empty($parent)) {
//
//        $pid = array_key_first($parent);
//        $child = $taxonomy_storage->loadChildren($pid);
//
//        foreach ($child as $term) {
//          $name = $term->get('name')->value;
//          $categories[$name]['name'] = $name;
//          $categories[$name]['url'] = $term->toUrl()->toString();
//        }
//
//      }
//
//      if (empty($parent) && empty($child)) {
//        $unique_term = $taxonomy_storage->loadTree('category', 0, 1);
//        if (!empty($unique_term)) {
//          foreach ($unique_term as $term) {
//            $name = $term->name;
//            $categories[$name]['name'] = $name;
//            $tid = $term->tid;
//            $load_term = $taxonomy_storage->load($tid);
//
//            $categories[$name]['url'] = $load_term->toUrl()->toString();
//          }
//        }
//      }
//
//
//
//
//    }
//       return $categories;
//  }
//
//  /**
//   * last segment by url.
//   *
//   * @return false|string
//   */
//  protected function getlastSegment() {
//    $current_path = $this->getRequest()->getRequestUri();
//    $path_segments = explode('/', trim($current_path, '/'));
//    $last_segment = end($path_segments);
//
//
//
//    return $last_segment;
//  }
//
//
//}
