<?php

namespace Drupal\product_order\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access Handler for Product Order Multistep Form.
 */
class ProductOrderMultistepFormAccessHandler implements AccessInterface {

  public function __construct(protected ConfigFactoryInterface $configFactory) {}

  /**
   * Checks access.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The currently logged in account.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $account): AccessResultInterface {
    $config = $this->configFactory
      ->get('product_order.product_order_multistep_form_settings_js')
      ->get('table_steps');
    $enabled_config = [];
    foreach ($config as $value) {
      if ($value['status'] == 'enabled') {
        $enabled_config[] = $value['id'];
      }
    }

    $max_step = count($enabled_config);
    if ($max_step == 0 && !$account->hasPermission('administer')) {
      return AccessResult::forbidden();
    }

    return AccessResult::allowed();
  }

}
