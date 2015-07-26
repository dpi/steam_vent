<?php

/**
 * @file
 * Contains \Drupal\steam_vent\Lists\Bot.
 */

namespace Drupal\steam_vent\Lists;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Component\Utility\Xss;

/**
 * List builder for steam_vent_bot entities.
 */
class Bot extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = t('ID');
    $header['label'] = t('Label');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['id'] = $entity->id();
    $row['label'] = $entity->label();
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build = parent::render();
    $build['table']['#empty'] = $this->t('No bot instances defined');
    return $build;
  }

}
