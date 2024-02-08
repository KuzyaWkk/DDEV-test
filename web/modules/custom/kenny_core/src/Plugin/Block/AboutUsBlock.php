<?php

namespace Drupal\kenny_core\Plugin\Block;

use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Class AboutUsBlock
 *
 * @package Drupal\kenny_core\Plugin\Block
 * @Block(
 *   id="about_us",
 *   admin_label="About Us Block",
 *   category="KennyCore"
 * )
 */
class AboutUsBlock extends BlockBase {

  public function build() {
    return [
      '#theme' => 'kenny_about_us'
    ];
  }

}
