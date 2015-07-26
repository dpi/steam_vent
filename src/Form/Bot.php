<?php

/**
 * @file
 * Contains \Drupal\steam_vent\Form\Bot.
 */

namespace Drupal\steam_vent\Form;

use Drupal\Core\Entity\EntityForm;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Add/edit form for Bot entity.
 */
class Bot extends EntityForm {

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * Constructs the NodeTypeForm object.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager
   */
  public function __construct(EntityManagerInterface $entity_manager) {
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\steam_vent\BotInterface $bot */
    $bot = $this->entity;
    $form = parent::buildForm($form, $form_state);

    if (!$bot->isNew()) {
      $form['#title'] = $this->t('Edit oberon bot %label', [
        '%label' => $bot->label(),
      ]);
    }

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $bot->label(),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $bot->id(),
      '#machine_name' => [
        'exists' => ['Drupal\steam_vent\Entity\Bot', 'load'],
        'source' => ['label'],
      ],
      '#description' => t('A unique machine-readable name for the bot.'),
      '#disabled' => !$bot->isNew(),
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => t('Description'),
      '#default_value' => $bot->getDescription(),
    ];

    $form['url'] = [
      '#type' => 'url',
      '#title' => t('URL'),
      '#description' => t('URL to REST server.'),
      '#default_value' => $bot->getUrl(),
      '#required' => TRUE,
    ];

    $form['profile_url'] = [
      '#type' => 'url',
      '#title' => t('Profile URL'),
      '#description' => t('URL to Steam Community profile.'),
      '#default_value' => $bot->getProfileUrl(),
    ];

    $form['friend_limit'] = [
      '#type' => 'number',
      '#title' => t('Friend limit'),
      '#description' => t('Maximum quantity of friends this bot can accept (friend limit).'),
      '#default_value' => $bot->getFriendLimit(),
      '#required' => TRUE,
      '#min' => 250,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\steam_vent\BotInterface $bot */
    $bot = $this->entity;
    $bot

      ->setLabel($form_state->getValue('label'))
      ->setDescription($form_state->getValue('description'))
      ->setUrl($form_state->getValue('url'))
      ->setProfileUrl($form_state->getValue('profile_url'))
      ->setFriendLimit($form_state->getValue('friend_limit'));
    $status = $bot->save();

    $message = ($status == SAVED_NEW) ? '%label bot has been added.' : '%label bot has been updated.';
    $t_args = ['%label' => $bot->label()];

    drupal_set_message($this->t($message, $t_args));
    $this->logger('steam_vent')->notice($message, $t_args);

    $form_state->setRedirect('entity.steam_vent_bot.collection');
  }

}
