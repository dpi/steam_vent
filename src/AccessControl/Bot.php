<?php

/**
 * @file
 * Contains \Drupal\steam_vent\AccessControl\Bot.
 */

namespace Drupal\steam_vent\AccessControl;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for bots.
 */
class Bot extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, $langcode, AccountInterface $account) {
    $account = $this->prepareUser($account);
    return AccessResult::allowedIf($account->hasPermission('administer steam_vent bot'));
  }

}
