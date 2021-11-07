<?php

namespace Drupal\rir_interface\Plugin\Block;

use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;

/**
 * @package Drupal\rir_interface\Plugin\Block
 * @Block(
 *   id = "hir_menu_action_buttons_block",
 *   admin_label = @Translation("HiR Menu Action Buttons Block"),
 *   category = @Translation("Custom RIR Blocks")
 * )
 */
class HirMenuActionButtons extends BlockBase
{

  public function build(): array
  {
    return[
      '#theme' => 'hir_menu_action_buttons'
    ];
  }
}
