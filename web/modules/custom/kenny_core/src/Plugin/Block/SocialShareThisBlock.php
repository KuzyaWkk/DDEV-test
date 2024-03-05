<?php

namespace Drupal\kenny_core\Plugin\Block;

use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Class SocialMediaBarBlackBlock
 *
 * @package Drupal\kenny_core\Plugin\Block
 * @Block(
 *   id="menu_social_share_this",
 *   admin_label="Menu Social Share This",
 *   category="KennyCore"
 * )
 */
class SocialShareThisBlock extends BlockBase {

  public function build() {
    return [
      '#theme' => 'kenny_social_share_this'
    ];
  }

}
