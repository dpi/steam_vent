<?php

/**
 * @file
 * Contains \Drupal\steam_vent\Plugin\IdentityChannel\Steam\User.
 */

namespace Drupal\steam_vent\Plugin\IdentityChannel\Steam;


use Drupal\courier\Plugin\IdentityChannel\IdentityChannelPluginInterface;
use Drupal\courier\ChannelInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\steam_vent\Service\SteamVentManager;
use Drupal\courier\Exception\IdentityException;

/**
 * Supports core user entities.
 *
 * @IdentityChannel(
 *   id = "identity:user:steam",
 *   label = @Translation("Drupal user to steam_vent_message"),
 *   channel = "steam_vent_message",
 *   identity = "user",
 *   weight = 10
 * )
 */
class User implements IdentityChannelPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function applyIdentity(ChannelInterface &$message, EntityInterface $identity) {
    /** @var \Drupal\user\UserInterface $identity */
    /** @var \Drupal\steam_vent\Entity\SteamMessage $message */

    if (empty($identity->{SteamVentManager::FIELD_STEAM_ID}->value)) {
      throw new IdentityException('User does not have a Steam ID.');
    }

    $message->setSteamId($identity->{SteamVentManager::FIELD_STEAM_ID}->value);
  }

}
