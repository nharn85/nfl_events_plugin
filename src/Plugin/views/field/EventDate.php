<?php

namespace Drupal\nfl_events_plugin\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("event_date")
 */
class EventDate extends FieldPluginBase {

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
      // Convert string to timestamp.
      $date = strtotime($string);
      // Format to d-m-Y (Eg. 20-01-2020)
      return date('d-m-Y', $date);
    }

    return $string;
  }

}
