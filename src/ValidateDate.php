<?php

namespace Drupal\nfl_events_plugin;

use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Class ValidateDate.
 */
class ValidateDate {

  /**
   * ValidateDate constructor.
   */
  public function __construct() {
  }

  /**
   * Validate Date - https://stackoverflow.com/a/19271434.
   *
   * Validate string is valid date format.
   *
   * @param string $date
   *   Date from user URL entry.
   * @param string $format
   *   Date format expected or if NULL.
   *
   * @return bool
   *   True if date is valid format.
   */
  public function validateDate($date, $format = 'Y-m-d') {
    $d = DrupalDateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of
    // digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
  }

}
