<?php

namespace Drupal\nfl_events_plugin\Plugin\views\query;

use Drupal\views\Plugin\views\query\QueryPluginBase;

use Drupal\views\ViewExecutable;
use Drupal\views\ResultRow;
use GuzzleHttp\Exception\RequestException;

/**
 * Placeholder views query plugin which wraps calls to the JSON Placeholder API in order to
 * expose the results to views.
 *
 * @ViewsQuery(
 *   id = "scoreboard",
 *   title = @Translation("Scoreboard"),
 *   help = @Translation("Query against the Chalk NFL Scoreboard API.")
 * )
 */
class Scoreboard extends QueryPluginBase {

  /**
   * ensureTable is used by Views core to make sure that the generated SQL query
   * contains the appropriate JOINs to ensure that a given table is included in
   * the results. In our case, we don’t have any concept of table joins,
   * so we return an empty string, which satisfies plugins that may call this method.
   */
  public function ensureTable($table, $relationship = NULL) {
    return '';
  }

  /**
   * addField is used by Views core to limit the fields that are part of the result set.
   * In our case, the Placeholder API has no way to limit the fields that come back in an
   * API response, so we don’t need this.
   */
  public function addField($table, $field, $alias = '', $params = array()) {
    return $field;
  }

  /**
   * {@inheritdoc}
   */
  public function execute(ViewExecutable $view) {
    // TODO: Add field plugins to alter data ✅
    // TODO: Add date contexual filters
    // TODO: Bring in as DI
    // TODO: For 2nd API call - make service for each?
    // TODO: Check for caching per day?
    $client = \Drupal::httpClient();
    try {
      $request = $client->get('https://delivery.chalk247.com/scoreboard/NFL/2020-01-12/2020-01-19.json?api_key=74db8efa2a6db279393b433d97c2bc843f8e32b0');
      $data = $request->getBody()->getContents();
      $dataObj = json_decode($data);

      // Results array - per game record
      foreach ($dataObj->results as $key => $value) {
        if (isset($value->data)) {
          $index = 0;
          foreach ($value->data as $inner_key => $inner_value) {
            // assign fields
            $row['event_id'] = $value->data->$inner_key->event_id;
            $row['event_date'] = $value->data->$inner_key->event_date;
            $row['event_time'] = $value->data->$inner_key->event_date;
            $row['away_team_id'] = $value->data->$inner_key->away_team_id;
            $row['away_nick_name'] = $value->data->$inner_key->away_nick_name;
            $row['away_city'] = $value->data->$inner_key->away_city;
            $row['home_team_id'] = $value->data->$inner_key->home_team_id;
            $row['home_nick_name'] = $value->data->$inner_key->home_nick_name;
            $row['home_city'] = $value->data->$inner_key->home_city;
            $row['away_rank'] = "15";
            $row['away_rank_points'] = "-6.126456";
            $row['home_rank'] = "45";
            $row['home_rank_points'] = "-4.54322";

            // set the rows index
            $row['index'] = $index++;

            // add this row the views results
            $view->result[] = new ResultRow($row);
          }
        }
      }
    }
    catch (RequestException $e) {
      watchdog_exception('nfl_events_plugin', $e->getMessage());
    }
  }
}
