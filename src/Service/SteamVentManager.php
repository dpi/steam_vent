<?php

/**
 * @file
 * Contains \Drupal\steam_vent\Service\SteamVentManager.
 */

namespace Drupal\steam_vent\Service;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Drupal\steam_vent\BotInterface;
use Drupal\steam_vent\BotMeta;
use Drupal\user\UserInterface;
use Drupal\steam_vent\Entity\FriendCode;
use Drupal\Component\Utility\Random;

/**
 * Steam vent manager.
 */
class SteamVentManager implements SteamVentManagerInterface {

  use ContainerAwareTrait;

  /**
   * ID of a `string` field attached to a user entity.
   *
   * The field is the target for Steam ID's associated with a user.
   */
  const FIELD_STEAM_ID = 'steam_id';

  /**
   * {@inheritdoc}
   */
  public function getMeta(BotInterface $bot) {
    return BotMeta::createInstance($this->container, $bot);
  }

  /**
   * Get a bot with available friend capacity.
   *
   * @return \Drupal\steam_vent\BotInterface|FALSE
   *   A bot instance, or FALSE if no bots are defined.
   */
  protected function getBot() {
    // @fixme just load the first bot.
    $bots = \Drupal::entityManager()
      ->getStorage('steam_vent_bot')
      ->loadMultiple();
    return $bots ? reset($bots) : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function createFriendCode(UserInterface $user) {
    $random = new Random();
    $words = [];
    for ($i = 0; $i < 4; $i++) {
      $words[] = $random->word(5);
    }

    if ($words) {
      $friend_code = FriendCode::create()
        ->setCode(strtoupper(implode('-', $words)))
        ->setUser($user)
        ->setBot($this->getBot());

      return $friend_code;
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function handleNewClaims() {
    // Fail if no bots exist.
    if (!$this->getBot()) {
      return;
    }

    $meta = $this->getMeta($this->getBot());
    foreach ($meta->getNewClaims() as $code => $steam_id) {
      // Remove other users with this Steam ID.
      $user_storage = \Drupal::entityManager()
        ->getStorage('user');
      $ids = $user_storage
        ->getQuery()
        ->condition($this::FIELD_STEAM_ID, $steam_id)
        ->execute();
      foreach ($user_storage->loadMultiple($ids) as $user) {
        $this->setSteamId($user, NULL);
      }

      // Associate the Steam ID.
      if ($friend_code = FriendCode::getFriendCodeByCode($code)) {
        $this->setSteamId($friend_code->getUser(), $steam_id);
        $meta->deleteFriendCode($friend_code, TRUE);
        $friend_code->delete();
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  function setSteamId(UserInterface $user, $steam_id) {
    $user->{$this::FIELD_STEAM_ID} = $steam_id;
    $user->save();
  }

  /**
   * {@inheritdoc}
   */
  function getBotForSteamId($steam_id) {
    // @todo
    return $this->getBot();
  }

}
