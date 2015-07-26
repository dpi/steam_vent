<?php

/**
 * @file
 * Contains \Drupal\steam_vent\SteamMessageInterface.
 */

namespace Drupal\steam_vent;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\courier\ChannelInterface;

/**
 * Provides an interface defining a steam_vent_message entity.
 */
interface SteamMessageInterface extends ContentEntityInterface, ChannelInterface {

  /**
   *
   */
  public function getSteamId();

  /**
   *
   */
  public function setSteamId($steam_id);

}
