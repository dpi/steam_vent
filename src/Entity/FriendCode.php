<?php

/**
 * @file
 * Contains \Drupal\steam_vent\Entity\FriendCode.
 */

namespace Drupal\steam_vent\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\steam_vent\FriendCodeInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\steam_vent\BotInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\UserInterface;

/**
 * Defines the steam vent friend code entity.
 *
 * @ContentEntityType(
 *   id = "steam_vent_friend_code",
 *   label = @Translation("Friend code"),
 *   handlers = {
 *   },
 *   base_table = "steam_vent_friend_code",
 *   entity_keys = {
 *     "id" = "id",
 *   },
 *   links = {
 *   },
 * )
 */
class FriendCode extends ContentEntityBase implements FriendCodeInterface {

  /**
   * {@inheritdoc}
   */
  public function getCode() {
    return $this->get('code')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCode($code) {
    $this->set('code', ['value' => $code]);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getUser() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setUser(EntityInterface $entity) {
    $this->set('uid', ['entity' => $entity]);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getBot() {
    return $this->get('bot')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setBot(BotInterface $entity) {
    $this->set('bot', ['entity' => $entity]);
    return $this;
  }

  /**
   * @param $code
   * @return FriendCodeInterface
   */
  static public function getFriendCodeByCode($code) {
    $entity_manager = \Drupal::entityManager();
    $storage = $entity_manager
      ->getStorage($entity_manager->getEntityTypeFromClass(get_called_class()));

    $ids = $storage
      ->getQuery()
      ->condition('code', $code)
      ->execute();

    if ($ids) {
      return $storage->load(reset($ids));
    }

    return NULL;
  }

  /**
   * @param int $uid
   *
   * @return FriendCodeInterface
   */
  static public function getFriendCodeByUser($uid) {
    $entity_manager = \Drupal::entityManager();
    $storage = $entity_manager
      ->getStorage($entity_manager->getEntityTypeFromClass(get_called_class()));

    $ids = $storage
      ->getQuery()
      ->condition('uid', $uid)
      ->execute();

    if ($ids) {
      return $storage->load(reset($ids));
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Friend Code ID'))
      ->setDescription(t('The friend code ID.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);

    $fields['code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Friend code'))
      ->setDescription(t('The generated friend code.'))
      ->setRequired(TRUE)
      ->setDefaultValue('');

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User'))
      ->setSetting('target_type', 'user')
      ->setCardinality(1)
      ->setReadOnly(TRUE)
      ->setRequired(TRUE);

    $fields['bot'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Bot'))
      ->setSetting('target_type', 'steam_vent_bot')
      ->setCardinality(1)
      ->setReadOnly(TRUE)
      ->setRequired(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created on'))
      ->setDescription(t('The time that the friend code was created.'));

    return $fields;
  }

}
