<?php

namespace Drupal\kenny_core\Plugin\Block;

use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Class SocialMediaBarBlock
 *
 * @package Drupal\kenny_core\Plugin\Block
 * @Block(
 *   id="menu_social_media_bar",
 *   admin_label="Menu Social Media Bar",
 *   category="KennyCore"
 * )
 */
class SocialMediaBarBlock extends BlockBase {

  public function build() {
    return [
      '#theme' => 'kenny_social_media_bar'
    ];
  }

}
