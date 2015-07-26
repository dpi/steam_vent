<?php

/**
 * @file
 * Contains \Drupal\steam_vent\FriendCodeInterface.
 */

namespace Drupal\steam_vent;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides an interface defining a steam_vent_friend_code entity.
 */
interface FriendCodeInterface extends ContentEntityInterface {

  /**
   * @return string
   */
  public function getCode();

  /**
   *
   */
  public function setCode($code);

  /**
   * @return \Drupal\user\UserInterface
   */
  public function getUser();

  /**
   *
   */
  public function setUser(EntityInterface $entity);

  /**
   * @return \Drupal\steam_vent\BotInterface
   */
  public function getBot();

  /**
   *
   */
  public function setBot(BotInterface $entity);

}
