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

add_action('admin_menu', function(){
    add_menu_page( 'WPMC Cruds', 'WPMC Cruds', '', 'wpmc-example', null, 'dashicons-admin-multisite' );
});

add_filter('wpmc_load_entities', function($entities){
    $entities['team'] = __DIR__ . '/cruds/team.json';
    $entities['player'] = __DIR__ . '/cruds/player.json';
    $entities['game'] = __DIR__ . '/cruds/game.json';
    return $entities;
});

function wpmcSetPlayerTeamActionCallback(\WPMC\Action\FieldableActionRunner $runner) {
    $entity = $runner->getRootAction()->getRootEntity();
    $playerIds = $runner->getContextIds();
    $teamId = $runner->getInputParam('team_id');

    foreach ( $playerIds as $id ) {
        $entity->saveDbData(['id' => $id, 'team_id' => $teamId]);
    }

    return sprintf(__('Team defined for: %s player(s)'), $runner->countIds());
}