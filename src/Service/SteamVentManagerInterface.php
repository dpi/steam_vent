<?php

/**
 * @file
 * Contains \Drupal\steam_vent\Service\SteamVentManagerInterface.
 */

namespace Drupal\steam_vent\Service;

use Drupal\steam_vent\BotInterface;
use Drupal\user\UserInterface;

/**
 * Interface for Steam vent manager.
 */
interface SteamVentManagerInterface {

  /**
   * Get BotMeta for a bot entity.
   *
   * @param \Drupal\steam_vent\BotInterface $bot
   *   A bot entity.
   *
   * @return \Drupal\steam_vent\BotMetaInterface
   *   A BotMeta wrapper.
   */
  public function getMeta(BotInterface $bot);

  /**
   * Create a friend code entity for a user.
   *
   * @param \Drupal\user\UserInterface $user
   *   A user entity.
   *
   * @return \Drupal\steam_vent\FriendCodeInterface
   *   An unsaved friend code entity.
   */
  public function createFriendCode(UserInterface $user);

  /**
   * Get newly claimed codes from the bot, and associate them with users.
   */
  public function handleNewClaims();

  /**
   * Set the Steam ID for a user.
   *
   * The user will be saved immediately.
   *
   * @param \Drupal\user\UserInterface $user
   *   A user entity.
   * @param int $steam_id
   *   A Steam ID.
   */
  function setSteamId(UserInterface $user, $steam_id);

  /**
   * Find which bot is friends with a Steam ID.
   *
   * @param int $steam_id
   *   A Steam ID.
   *
   * @return \Drupal\steam_vent\BotInterface|FALSE
   *   Which bot is friends with a Steam ID, or FALSE if none.
   */
  function getBotForSteamId($steam_id);

}
