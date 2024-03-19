<?php

namespace Drupal\login_reg\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\user\RegisterForm;

/**
 * Custom register form.
 */
class AdvancedRegisterForm extends RegisterForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state):array {
    $form = parent::form($form, $form_state);

    $form['account']['mail']['#title'] = $this->t('Your email');
    $form['account']['mail']['#placeholder'] = $this->t('Your email');
    $form['account']['name']['#title'] = $this->t('Your name');
    $form['account']['name']['#placeholder'] = $this->t('Your name');

    unset($form['account']['mail']['#description']);
    unset($form['account']['name']['#description']);
    unset($form['account']['pass']['#description']);

    $form['check'] = [
      '#type' => 'checkbox',
      '#description' => $this->t('I agree all statements in Terms of service'),
      '#required' => TRUE,
      '#weight' => 100,
    ];
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  protected function actions(array $form, FormStateInterface $form_state): array {
    $element = parent::actions($form, $form_state);
    $element['submit']['#value'] = $this->t('Register');
    return $element;
  }

}
