<?php

namespace Drupal\login_reg\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;

/**
 * Class Register Button.
 *
 * @package Drupal\log_reg\Plugin\Block
 */
#[Block(
  id: "register_button_block",
  admin_label: new TranslatableMarkup("Custom Register Button block"),
  category: new TranslatableMarkup("LoginReg")
)]
class RegisterButton extends BlockBase {

  /**
   * {@inheritDoc}
   */
  public function build():array {
    return [
      '#attached' => [
        'library' => [
          'core/drupal.dialog.ajax',
        ],
      ],
      '#theme' => 'links',
      '#links' => [
        'link' => [
          'title' => $this->t('Sign in'),
          'url' => Url::fromRoute('login_reg.register'),
          'attributes' => [
            'class' => ['use-ajax', 'form__register'],
            'data-dialog-type' => 'modal',
            'data-dialog-options' => json_encode([
              'width' => '800',
            ]),
          ],
        ],
      ],
    ];
  }

  /**
   * {@inheritDoc}
   */
  protected function blockAccess(AccountInterface $account): AccessResult {
    if ($account->id() == 0) {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }

}
