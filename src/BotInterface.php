<?php

/**
 * @file
 * Contains \Drupal\steam_vent\BotInterface.
 */

namespace Drupal\steam_vent;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a steam_vent_bot entity.
 */
interface BotInterface extends ConfigEntityInterface {

  /**
   * Set Bot label.
   *
   * @param string $label
   *   Label for the Bot.
   *
   * @return \Drupal\steam_vent\BotInterface
   *   Returns Bot for chaining.
   */
  function setLabel($label);

  /**
   * Get description for the Bot.
   *
   * @return string
   *   The description for the Bot.
   */
  function getDescription();

  /**
   * Set description for the Bot.
   *
   * @param string $description
   *  The description for the Bot.
   *
   * @return \Drupal\steam_vent\BotInterface
   *   Returns Bot for chaining.
   */
  function setDescription($description);

  /**
   * Get REST endpoint URL for the Bot.
   *
   * @return string
   *   The REST endpoint URL.
   */
  function getUrl();

  /**
   * Set REST endpoint URL for the Bot.
   *
   * @param string $url
   *   The REST endpoint URL.
   *
   * @return \Drupal\steam_vent\BotInterface
   *   Returns Bot for chaining.
   */
  function setUrl($url);

  /**
   * Get Steam Community profile URL for the Bot.
   *
   * @return string
   *   A URL of a Steam Community profile.
   */
  function getProfileUrl();

  /**
   * Set Steam Community profile URL for the Bot.
   *
   * @param string $url
   *   A URL of a Steam Community profile.
   *
   * @return \Drupal\steam_vent\BotInterface
   *   Returns Bot for chaining.
   */
  function setProfileUrl($url);

  /**
   * Get friend limit for the Bot.
   *
   * @return integer
   *   The friend limit for the Bot.
   */
  function getFriendLimit();

  /**
   * Set friend limit for the Bot.
   *
   * @param int $max_friends
   *   The friend limit for the Bot.
   *
   * @return \Drupal\steam_vent\BotInterface
   *   Returns Bot for chaining.
   */
  function setFriendLimit($max_friends);

}
