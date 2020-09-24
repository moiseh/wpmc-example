<?php

add_filter('wpmc_list_actions', function($actions, $item = []){
    $entity = wpmc_get_current_entity();
    $actions['set_team'] = $entity->get_action_link('set_team', $item['id'], __('Set team', 'wp-magic-crud'));
    return $actions;
}, 10, 2);

add_filter('wpmc_bulk_actions', function($actions){
    $actions['set_team'] = __('Set team', 'wp-magic-crud');
    return $actions;
}, 10, 2);

add_filter('wpmc_run_action', function($action){
    // check the current action 
    if ( $action == 'set_team' ) {

        // callback triggered when form was submitted
        $postCallback = function(){
            global $wpdb;

            $entity = wpmc_get_current_entity();
            $ids = wpmc_request_ids();
            $team_id = $_REQUEST['team_id'];
    
            foreach ( $ids as $id ) {
                $wpdb->update('mc_players', ['team_id' => $team_id], array('id' => $id));
            }
    
            wpmc_flash_message(sprintf(__('Team defined for: %s player(s)'), count($ids)));
            $entity->back_to_home();
            
        };

        // callback triggered to render action form fields
        $formCallback = function(){
            $teams = wpmc_get_entity('team')->build_options();

            ?>
            <div id="post-body-content">
                <?php wpmc_field_with_label(['name' => 'team_id', 'label' => __('Team'), 'type' => 'select', 'choices' => $teams]); ?>
            </div>
            <input type="submit" value="<?php echo __('Set team'); ?>" id="submit" class="button-primary" name="submit">
            <?php
        };

        wpmc_default_action_form($postCallback, $formCallback, __('Set player(s) team'));
    }
}, 10, 2);