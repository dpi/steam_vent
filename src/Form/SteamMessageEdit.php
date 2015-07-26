<?php

/**
 * @file
 * Contains \Drupal\steam_vent\Form\SteamMessageEdit.
 */

namespace Drupal\steam_vent\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\courier\Entity\TemplateCollection;

/**
 * Form controller for email.
 */
class SteamMessageEdit extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\steam_vent\SteamMessageInterface $steam_message */
    $steam_message = $this->entity;
    $form = parent::form($form, $form_state);

    if (!$steam_message->isNew()) {
      $form['#title'] = $this->t('Edit Steam Message');
    }

    $form['tokens'] = [
      '#type' => 'details',
      '#title' => $this->t('Tokens'),
      '#weight' => 51,
    ];
    $template_collection = TemplateCollection::getTemplateCollectionForTemplate($steam_message);
    if ($context = $template_collection->getContext()) {
      if ($this->moduleHandler->moduleExists('token')) {
        $form['tokens']['list'] = [
          '#theme' => 'token_tree',
          '#token_types' => $context->getTokens(),
        ];
      }
      else {
        $form['tokens']['list'] = [
          '#markup' => $this->t('Available tokens: @token_types', ['@token_types' => implode(', ', $context->getTokens())]),
        ];
      }
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $steam_message = $this->entity;
    $is_new = $steam_message->isNew();
    $steam_message->save();

    $t_args = array('%label' => $steam_message->label());
    if ($is_new) {
      drupal_set_message(t('Steam message has been created.', $t_args));
    }
    else {
      drupal_set_message(t('Steam message was updated.', $t_args));
    }
  }

}
