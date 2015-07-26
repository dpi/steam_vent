<?php

/**
 * @file
 * Contains \Drupal\steam_vent\Controller\BindUser.
 */

namespace Drupal\steam_vent\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\steam_vent\Service\SteamVentManager;

/**
 * Controller for Registration Groups.
 */
class BindUser extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Render a form explaining how to bind account, or a notice if already bound.
   *
   * @return array
   *   A render array.
   */
  public function BindUser() {
    $user = \Drupal::routeMatch()->getParameter('user');
    $build = [
      '#cache' => [
        'tags' => $user->getCacheTags(),
      ],
    ];

    if (!empty($user->{SteamVentManager::FIELD_STEAM_ID}->value)) {
      $build[]['#markup'] = $this->t('Your Steam account is already bound to your account. To unbind, unfriend the Steam Bot from your Steam account.');
    }
    else {
      $build[] = $this->formBuilder()->getForm('Drupal\steam_vent\Form\BindUser');
    }

    return $build;
  }

}
