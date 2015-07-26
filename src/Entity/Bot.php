<?php

/**
 * @file
 * Contains \Drupal\steam_vent\Entity\Bot.
 */

namespace Drupal\steam_vent\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\steam_vent\BotInterface;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Defines the Registration type configuration entity.
 *
 * @ConfigEntityType(
 *   id = "steam_vent_bot",
 *   label = @Translation("Steam Vent Bot type"),
 *   config_prefix = "bot",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   handlers = {
 *     "access" = "Drupal\steam_vent\AccessControl\Bot",
 *     "form" = {
 *       "add" = "Drupal\steam_vent\Form\Bot",
 *       "edit" = "Drupal\steam_vent\Form\Bot",
 *       "delete" = "\Drupal\steam_vent\Form\BotDelete",
 *     },
 *     "list_builder" = "Drupal\steam_vent\Lists\Bot",
 *   },
 *   links = {
 *     "edit-form" = "/admin/structure/steam_vent/bots/manage/{steam_vent_bot}",
 *     "delete-form" = "/admin/structure/steam_vent/bots/manage/{steam_vent_bot}/delete",
 *     "collection" = "/admin/structure/steam_vent/bots",
 *   }
 * )
 */
class Bot extends ConfigEntityBase implements BotInterface {

  /**
   * The machine name of this bot.
   *
   * @var string
   */
  public $id;

  /**
   * The human readable name of this bot.
   *
   * @var string
   */
  public $label;

  /**
   * A brief description of this bot.
   *
   * @var string
   */
  public $description;

  /**
   * Address to the REST server.
   *
   * @var string
   */
  public $url;

  /**
   * Address to a Steam Community profile.
   *
   * @var string
   */
  public $profile_url;

  /**
   * Maximum quantity of friends this bot can accept (friend limit).
   *
   * @var integer
   */
  public $max_friends;

  /**
   * {@inheritdoc}
   */
  function setLabel($label) {
    $this->label = $label;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  function getDescription() {
    return $this->description;
  }

  /**
   * {@inheritdoc}
   */
  function setDescription($description) {
    $this->description = $description;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  function getUrl() {
    return $this->url;
  }

  /**
   * {@inheritdoc}
   */
  function setUrl($url) {
    $this->url = $url;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  function getProfileUrl() {
    return $this->profile_url;
  }

  /**
   * {@inheritdoc}
   */
  function setProfileUrl($url) {
    $this->profile_url = $url;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  function getFriendLimit() {
    return $this->max_friends;
  }

  /**
   * {@inheritdoc}
   */
  function setFriendLimit($max_friends) {
    $this->max_friends = $max_friends;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function preDelete(EntityStorageInterface $storage, array $entities) {
    $friend_code_storage = \Drupal::entityManager()
      ->getStorage('steam_vent_friend_code');

    /** @var static[] $entities */
    foreach ($entities as $bot) {
      // Remove friend codes.
      $ids = $friend_code_storage
        ->getQuery()
        ->condition('bot', $bot->id())
        ->execute();

      $friend_code_storage->delete($friend_code_storage->loadMultiple($ids));
    }

    parent::preDelete($storage, $entities);
  }

}
