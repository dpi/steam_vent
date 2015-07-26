<?php

/**
 * @file
 * Contains \Drupal\steam_vent\Entity\SteamMessage.
 */

namespace Drupal\steam_vent\Entity;

use Drupal\courier\ChannelBase;
use Drupal\steam_vent\SteamMessageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines storage for a Steam message.
 *
 * @ContentEntityType(
 *   id = "steam_vent_message",
 *   label = @Translation("Steam message"),
 *   handlers = {
 *     "form" = {
 *       "default" = "Drupal\steam_vent\Form\SteamMessageEdit",
 *       "add" = "Drupal\steam_vent\Form\SteamMessageEdit",
 *       "edit" = "Drupal\steam_vent\Form\SteamMessageEdit",
 *     },
 *   },
 *   admin_permission = "administer steam_vent",
 *   base_table = "steam_vent_message",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "id",
 *   },
 *   links = {
 *     "canonical" = "/steam/message/{steam_vent_message}/edit",
 *     "edit-form" = "/steam/message/{steam_vent_message}/edit",
 *   }
 * )
 */
class SteamMessage extends ChannelBase implements SteamMessageInterface {

  /**
   * {@inheritdoc}
   */
  public function getSteamId() {
    return $this->get('steam_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSteamId($steam_id) {
    $this->set('steam_id', $steam_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getMessage() {
    return $this->get('message')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setMessage($message) {
    $this->set('message', ['value' => $message]);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  static public function sendMessages(array $messages, $options = []) {
    /** @var \Drupal\steam_vent\Service\SteamVentManagerInterface $steam_vent_manager */
    $steam_vent_manager = \Drupal::service('steam_vent.manager');
    /* @var static[] $messages */
    foreach ($messages as $message) {
      $tokens = $message->getTokenValues();
      $message->setMessage(\Drupal::token()->replace($message->getMessage(), $tokens));

      $bot = $steam_vent_manager->getBotForSteamId($message->getSteamId());
      $meta = $steam_vent_manager->getMeta($bot);
      $meta->sendMessage($message->getSteamId(), $message->getMessage());
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Message ID'))
      ->setDescription(t('The message ID.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);

    $fields['steam_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Steam ID'))
      ->setDescription(t('The Steam ID.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE)
      ->setSetting('size', 'big');

    $fields['message'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Message'))
      ->setDescription(t('The message to be sent.'))
      ->setDefaultValue('')
      ->setDisplayOptions('form', [
        'type' => 'string_textarea',
        'weight' => 50,
      ]);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The email language code.'));

    return $fields;
  }

}
