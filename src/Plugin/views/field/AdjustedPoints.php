<?php

namespace Drupal\nfl_events_plugin\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("adjusted_points")
 */
class AdjustedPoints extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function usesGroupBy() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Do nothing -- to override the parent query.
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['hide_alter_empty'] = ['default' => FALSE];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    // Get the field machine name.
    $field = $this->field;
    // Get the value.
    $string = $values->$field;
    if ($string) {
      // Convert string to float.
      $number = floatval($string);
      // Using number_format() to round 2 decimals as this will allow a trailing
      // zero if value allows.
      return number_format($number, 2);
    }

    return $string;
  }

}
