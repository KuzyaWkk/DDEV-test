<?php

namespace Drupal\kenny_core\Plugin\Block;

use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Class SocialMediaBarBlackBlock
 *
 * @package Drupal\kenny_core\Plugin\Block
 * @Block(
 *   id="menu_social_media_black_bar",
 *   admin_label="Menu Social Media Black Bar",
 *   category="KennyCore"
 * )
 */
class SocialMediaBarBlackBlock extends BlockBase {

  public function build() {
    return [
      '#theme' => 'kenny_social_media_black_bar'
    ];
  }

}
