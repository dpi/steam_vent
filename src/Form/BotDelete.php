<?php

/**
 * @file
 * Contains \Drupal\steam_vent\Form\BotDelete.
 */

namespace Drupal\steam_vent\Form;

use Drupal\Core\Entity\EntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for bot deletion.
 */
class BotDelete extends EntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    return parent::buildForm($form, $form_state);
  }

}
