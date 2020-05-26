<?php

namespace Drupal\nfl_events_plugin\Plugin\views\query;

use Drupal\views\Plugin\views\query\QueryPluginBase;
use Drupal\views\ViewExecutable;
use Drupal\views\ResultRow;
use GuzzleHttp\Exception\RequestException;
use Drupal\nfl_events_plugin\ApiDataService as ApiDataService;
use Drupal\nfl_events_plugin\ValidateDate as ValidateDate;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
   * @var \Drupal\nfl_events_plugin\ApiDataService
   */
  protected $apiData;

  /**
   * @var \Drupal\nfl_events_plugin\ValidateDate
   */
  protected $validateDate;

  /**
   * Scoreboard constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\nfl_events_plugin\ApiDataService $apiData
   *   The API Data service.
   * @param \Drupal\nfl_events_plugin\ValidateDate $validateDate
   *   The Validate Date service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ApiDataService $apiData, ValidateDate $validateDate) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->apiData = $apiData;
    $this->validateDate = $validateDate;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('nfl_events_plugin.apidata'),
      $container->get('nfl_events_plugin.validatedate')
    );
  }

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
    try {
      // Get URL args from view
      $start_date = $view->args[0];
      $end_date = $view->args[1];

      // Validate dates are in the proper format
      $validStart = $this->validateDate->validateDate($start_date);
      $validEnd = $this->validateDate->validateDate($end_date);

      if (!$validStart || !$validEnd) {
        return;
      }

      // Get Scoreboard JSON Data
      $dataObj = $this->apiData->fetchScoreboardData($start_date, $end_date);

      // Loop results per game
      foreach ($dataObj->results as $key => $value) {

        // If there is game data available, continue to traverse the object
        if (isset($value->data)) {
          $index = 0;
          foreach ($value->data as $inner_key => $inner_value) {
            // Get team_ranking data by Team ID
            $away_team_id = $value->data->$inner_key->away_team_id;
            $away_data = $this->apiData->fetchRankingData($away_team_id);

            $home_team_id = $value->data->$inner_key->home_team_id;
            $home_data = $this->apiData->fetchRankingData($home_team_id);

            // Assign fields
            $row['event_id'] = $value->data->$inner_key->event_id;
            $row['event_date'] = $value->data->$inner_key->event_date;
            $row['event_time'] = $value->data->$inner_key->event_date;
            $row['away_team_id'] = $value->data->$inner_key->away_team_id;
            $row['away_nick_name'] = $value->data->$inner_key->away_nick_name;
            $row['away_city'] = $value->data->$inner_key->away_city;
            $row['away_rank'] = $away_data['rank'];
            $row['away_rank_points'] = $away_data['adjusted_points'];
            $row['home_team_id'] = $value->data->$inner_key->home_team_id;
            $row['home_nick_name'] = $value->data->$inner_key->home_nick_name;
            $row['home_city'] = $value->data->$inner_key->home_city;
            $row['home_rank'] = $home_data['rank'];
            $row['home_rank_points'] = $home_data['adjusted_points'];

            // Set the rows index
            $row['index'] = $index++;

            // Add this row the views results
            $view->result[] = new ResultRow($row);
          }
        }
      }
    } catch (RequestException $e) {
      watchdog_exception('nfl_events_plugin', $e->getMessage());
    }
  }
}
