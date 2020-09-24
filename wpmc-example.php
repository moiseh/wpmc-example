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

// example how to add filters and/or actions just when viewing specific entity list or form
add_action('wpmc_before_entity', function(WPMC_Entity $entity){

    // protect user from manage not allowed IDs and other policies
    require_once 'inc.global.security.php';

    switch($entity->identifier()) {
        case 'player':
            // example how to alter the default per-page listing rows
            add_filter('wpmc_list_per_page', function(){
                return 4;
            }, 10, 1);

            // example how to add custom actions
            include __DIR__ . '/inc.player.action.set_team.php';
        break;
        default:
        case 'my_entity_1':
        case 'my_entity_2':
        break;
    }
});

// example how to filter entities by current logged user_id
add_filter('wpmc_entity_query', function(WPMC_Query_Builder $query, WPMC_Entity $entity){
    switch ( wpmc_current_entity() ) {
        case 'my_entity_1':
        case 'my_entiti_2':
            $query->where("{$entity->tableName}.user_id", '=', get_current_user_id()); 
        break;
    }
    
    return $query;
}, 10, 2);

// example how to modify entity data before save form to database
add_filter('wpmc_process_save_data', function($item, WPMC_Entity $entity){
    $item['user_id'] = get_current_user_id();
    return $item;
}, 10, 2);