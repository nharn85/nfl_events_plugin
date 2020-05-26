<?php

namespace Drupal\nfl_events_plugin;

use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Class ValidateDate.
 */
class ValidateDate {

  /**
   * Validate Date - https://stackoverflow.com/a/19271434
   *
   * Validate string is valid date format.
   *
   * @param $date
   * @param string $format
   *
   * @return bool
   */
  public function validateDate($date, $format = 'Y-m-d') {
    $d = DrupalDateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
  }

}
