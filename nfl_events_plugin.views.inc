<?php

/**
 * @file
 * NFL Events Plugin Views Data.
 *
 * This views data will be used to display a new JSON array combining the
 * scoreboard API endpoint data, and the team rankings endpoint data.
 */

/**
 * Implements hook_views_data().
 */
function nfl_events_plugin_views_data() {

  $data = [];
  $data['nfleventdata_profile']['table']['group'] = t('NFL Event data');
  $data['nfleventdata_profile']['table']['base'] = [
    'title' => t('NFL Event data'),
    'help' => t('NFL event data JSON data combines the scoreboard and team ranking API data.'),
    'query_id' => 'nfleventdata',
  ];

  // Fields.
  $data['nfleventdata_profile']['event_id'] = [
    'title' => t('Event ID'),
    'help' => t('Event ID'),
    'field' => [
      'id' => 'standard',
    ],
  ];

  $data['nfleventdata_profile']['event_date'] = [
    'title' => t('Event Date'),
    'help' => t('Event Date'),
    'field' => [
      'id' => 'event_date',
    ],
  ];

  $data['nfleventdata_profile']['event_time'] = [
    'title' => t('Event Time'),
    'help' => t('Event Time'),
    'field' => [
      'id' => 'event_time',
    ],
  ];

  $data['nfleventdata_profile']['away_team_id'] = [
    'title' => t('Away Team ID'),
    'help' => t('Away Team ID'),
    'field' => [
      'id' => 'standard',
    ],
  ];

  $data['nfleventdata_profile']['away_nick_name'] = [
    'title' => t('Away Team Nick Name'),
    'help' => t('Away Team Nick Name'),
    'field' => [
      'id' => 'standard',
    ],
  ];

  $data['nfleventdata_profile']['away_city'] = [
    'title' => t('Away Team City'),
    'help' => t('Away Team City'),
    'field' => [
      'id' => 'standard',
    ],
  ];

  $data['nfleventdata_profile']['home_team_id'] = [
    'title' => t('Home Team ID'),
    'help' => t('Home Team ID'),
    'field' => [
      'id' => 'standard',
    ],
  ];

  $data['nfleventdata_profile']['home_nick_name'] = [
    'title' => t('Home Team Nickname'),
    'help' => t('Home Team Nickname'),
    'field' => [
      'id' => 'standard',
    ],
  ];

  $data['nfleventdata_profile']['home_city'] = [
    'title' => t('Home Team City'),
    'help' => t('Home Team City'),
    'field' => [
      'id' => 'standard',
    ],
  ];

  $data['nfleventdata_profile']['away_rank'] = [
    'title' => t('Away Team Rank'),
    'help' => t('Away Team Rank'),
    'field' => [
      'id' => 'numeric',
    ],
  ];

  $data['nfleventdata_profile']['away_rank_points'] = [
    'title' => t('Away Team Rank Points'),
    'help' => t('Away Team Rank Points'),
    'field' => [
      'id' => 'adjusted_points',
    ],
  ];

  $data['nfleventdata_profile']['home_rank'] = [
    'title' => t('Home Team Rank'),
    'help' => t('Home Team Rank'),
    'field' => [
      'id' => 'numeric',
    ],
  ];

  $data['nfleventdata_profile']['home_rank_points'] = [
    'title' => t('Home Team Rank Points'),
    'help' => t('Home Team Rank Points'),
    'field' => [
      'id' => 'adjusted_points',
    ],
  ];

  return $data;

}
