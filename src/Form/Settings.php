<?php

/**
 * @file
 * Contains \Drupal\steam_vent\Form\Settings.
 */

namespace Drupal\steam_vent\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure steam_vent settings.
 */
class Settings extends ConfigFormBase {

  /**
   * Constructs a \Drupal\system\ConfigFormBase object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'steam_vent_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'steam_vent.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('steam_vent.settings');

    $form['code_lifetime'] = [
      '#type' => 'number',
      '#title' => $this->t('Friend code lifetime'),
      '#description' => $this->t('How many seconds a friend code exists before expiring.'),
      '#default_value' => $config->get('code_lifetime') ? $config->get('code_lifetime') : (60 * 15),
      '#step' => 60,
      '#min' => 60,
      '#field_suffix' => $this->t('seconds'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('steam_vent.settings');
    $config->set('code_lifetime', $form_state->getValue('code_lifetime'));
    $config->save();
    drupal_set_message(t('Settings saved.'));
  }

}
