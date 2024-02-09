<?php

namespace Drupal\kenny_hero_block\Plugin\KennyHeroBlock;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Common interface for all KennyHeroBlock plugin types.
 */
interface KennyHeroBlockPluginInterface extends PluginInspectionInterface {

  /**
   * Gets plugin status.
   *
   * @return bool
   *  The plugin status.
   */
  public function getEnabled();

  /**
   * Gets plugin weight.
   *
   * @return int
   *  The plugin weight.
   */
  public function getWeight();

  /**
   * Gets hero title.
   *
   * @return string
   *  The title.
   */
  public function getHeroTitle();

  /**
   * Gets hero subtitle.
   *
   * @return string
   *  The subtitle.
   */
  public function getHeroSubtitle();

  /**
   * Gets list of category.
   *
   * @return array
   *  The list of category.
   */
  public function getHeroCategory();

  /**
   * Gets hero image uri.
   *
   * @return string
   *  The URI of image.
   */
  public function getHeroImage();




}
