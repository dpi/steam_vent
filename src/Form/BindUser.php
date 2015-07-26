<?php

/**
 * @file
 * Contains \Drupal\steam_vent\Form\BindUser.
 */

namespace Drupal\steam_vent\Form;

use Drupal\Core\Form\FormBase;
use Drupal\steam_vent\Service\SteamVentManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\steam_vent\Entity\FriendCode;

/**
 * Bind Steam account to user.
 */
class BindUser extends FormBase {

  /**
   * Constructs a new BindUser form.
   *
   * @param \Drupal\steam_vent\Service\SteamVentManagerInterface $steam_vent_manager
   *   The steam_vent manager.
   */
  public function __construct(SteamVentManagerInterface $steam_vent_manager) {
    $this->steamVentManager = $steam_vent_manager;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('steam_vent.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'steam_vent_bind_user';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\user\UserInterface $user */
    $user = $this->getRouteMatch()->getParameter('user');
    $form['#friend_code'] = FriendCode::getFriendCodeByUser($user->id());
    $friend_code = $form['#friend_code'];

    $t_args = [
      '!site-name' => \Drupal::config('system.site')->get('name'),
    ];

    if ($friend_code) {
      $t_args['!steam_bot_link'] = $friend_code->getBot()->getProfileUrl();
      $t_args['@friend_code'] = $friend_code->getCode();

      $items[]['#markup'] = $this->t('Add the <a href="!steam_bot_link">Steam Bot</a> to your friends list.', $t_args);
      $items[] = $this->t('Wait for the Steam Bot to accept your friend request.');
      $items[]['#markup'] = $this->t('Send the following code as a Steam chat message to the Steam Bot: <code>@friend_code</code>', $t_args);

      $estimate = '';
      $average = cron_oracle_predict();
      if ($average && $average > 60) {
        $minutes = ceil($average / 60);
        $estimate = $this->formatPlural(
          $minutes,
          'This usually happens within @count minute.',
          'This usually happens within @count minutes.'
        );
      }
      $items[]['#markup'] = $this->t('The Steam Bot will inform you when your account is bound. @estimate', ['@estimate' => $estimate]);

      $items[] = $this->t('You must keep the Steam Bot on your friends list indefinitely in order to maintain the binding between to your Steam account.');
      $form['steps'] = [
        '#theme' => 'item_list',
        '#title' => $this->t('Steps to link your !site-name account to a Steam account:', $t_args),
        '#list_type' => 'ol',
        '#items' => $items,
      ];
    }
    else {
      $items[] = $this->t('Clicking <em>Continue</em> will generate a confirmation code.');
      if (($code_lifetime = \Drupal::config('steam_vent.settings')->get('code_lifetime')) > 0) {
        $items[] = $this->t('This code will expire after @minutes minutes.', [
          '@minutes' => ceil($code_lifetime / 60),
        ]);
      }
      $items[] = $this->t('If the code expires, you may return to this page for a new code.');
      $form['steps'] = [
        '#theme' => 'item_list',
        '#title' => $this->t('This form will guide you through binding your !site-name account with a Steam account.', $t_args),
        '#list_type' => 'ul',
        '#items' => $items,
      ];
    }

    if (!$friend_code) {
      $form['actions'] = ['#type' => 'actions'];
      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => t('Continue'),
        '#button_type' => 'primary',
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\user\UserInterface $user */
    $user = $this->getRouteMatch()->getParameter('user');
    if (!$friend_code = $form['#friend_code']) {
      $friend_code = $this->steamVentManager->createFriendCode($user);
      $friend_code->save();
      $bot_meta = $this->steamVentManager->getMeta($friend_code->getBot());
      $bot_meta->sendFriendCode($friend_code);
    }
  }

}
