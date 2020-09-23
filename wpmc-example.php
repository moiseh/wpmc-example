<?php
/*
* Plugin Name: WP Magic Crud Example
* Description: Provides a clean example of how to use Magic admin CRUD plugin for WordPress plugin
* Version:     1.0
* Author:      Moises Heberle
* License:     GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or die( 'Not allowed' );

add_action('wpmc_entities', function($entities){
    $entities['team'] = [
        'table_name' => 'mc_teams',
        'default_order' => 'name',
        'display_field' => 'name',
        'singular' => 'Team',
        'plural' => 'Teams',
        'fields' => [
            'name' => [
                'label' => 'Name',
                'type' => 'text',
                'required' => true,
            ],
            'players' => [
                'label' => 'Players',
                'type' => 'one_to_many',
                'ref_entity' => 'player',
                'ref_column' => 'team_id',
            ],
        ]
    ];

    $entities['player'] = [
        'table_name' => 'mc_players',
        'default_order' => 'name',
        'display_field' => 'name',
        'singular' => 'Player',
        'plural' => 'Players',
        'fields' => [
            'name' => [
                'label' => 'Name',
                'type' => 'text',
                'required' => true,
            ],
            'lastname' => [
                'label' => 'Last name',
                'type' => 'text',
                'restrict_to' => ['sort','view','add','edit'],
            ],
            'email' => [
                'label' => 'E-mail',
                'type' => 'email',
            ],
            'team_id' => [
                'label' => 'Team',
                'type' => 'belongs_to',
                'ref_entity' => 'team',
                'required' => true,
            ],
        ]
    ];

    $entities['game'] = [
        'table_name' => 'mc_games',
        'default_order' => 'name',
        'display_field' => 'name',
        'singular' => 'Game',
        'plural' => 'Games',
        'fields' => [
            'name' => [
                'label' => 'Name',
                'type' => 'text',
                'required' => true,
            ],
            'players' => [
                'label' => 'Players',
                'type' => 'has_many',
                'ref_entity' => 'player',
                'pivot_table' => 'mc_game_players',
                'pivot_left' => 'game_id',
                'pivot_right' => 'player_id',
            ],
        ]
    ];

    return $entities;
}, 10, 2);

// example how to filter entities by current logged user_id
add_filter('wpmc_entity_query', function(WPMC_Query_Builder $qb, WPMC_Entity $entity){
    switch ( wpmc_current_entity() ) {
        case 'my_entity_1':
        case 'my_entiti_2':
            $qb->where("{$entity->tableName}.user_id", '=', get_current_user_id()); 
        break;
    }
    
    return $qb;
}, 10, 2);

// example how to create policies to check if user can do something with specific entity IDs
add_filter('wpmc_can_manage', function(WPMC_Entity $entity, $ids = []){
    global $wpdb;

    if ( !current_user_can('activate_plugins') ) {
        return false;
    }

    switch ( wpmc_current_entity() ) {
        case 'my_entity_1':
        case 'my_entiti_2':
            $uid = get_current_user_id();
            $ids = implode(',', $ids);
            $notAllowedIds = $wpdb->get_var("SELECT COUNT(id) FROM {$entity->tableName} WHERE id IN({$ids}) AND user_id <> {$uid}");
            if ( $notAllowedIds > 0 ) {
                return false;
            }
        break;
    }

    return true;
}, 10, 2);

// example how to modify entity data before save form to database
add_filter('wpmc_process_save_data', function($item, WPMC_Entity $entity){
    $item['user_id'] = get_current_user_id();
    return $item;
}, 10, 2);