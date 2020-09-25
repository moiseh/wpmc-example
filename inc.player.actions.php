<?php

// actions displayed in row context menu of items list
add_filter('wpmc_list_actions', function($actions, $item = []){
    $entity = wpmc_get_current_entity();
    $actions['set_team'] = $entity->get_action_link('set_team', $item['id'], __('Set team', 'wp-magic-crud'));

    return $actions;
}, 10, 2);

// define bulk actions to execute with one or more items
add_filter('wpmc_bulk_actions', function($actions){
    $actions['set_team'] = __('Set team', 'wp-magic-crud');
    return $actions;
}, 10, 2);

// "set_team" action executor
add_action('wpmc_run_action_set_team', function($ids){
    
    // check if form was submitted
    if ( wpmc_actions_form_posted() ) {
        global $wpdb;
        $team_id = (int) $_REQUEST['team_id'];

        if ( !empty($team_id) ) {
            foreach ( $ids as $id ) {
                $wpdb->update('mc_players', ['team_id' => $team_id], array('id' => $id));
            }
            
            wpmc_flash_message(sprintf(__('Team defined for: %s player(s)'), count($ids)));
            wpmc_redirect( wpmc_entity_home() );
        }
        else {
            wpmc_flash_message(__('Please select the team'), 'error');
        }
    }

    // render the form using default layout for actions
    wpmc_default_action_form([
        'title' => __('Set player(s) team'),
        'content' => function(){
            $teams = wpmc_get_entity('team')->build_options();

            ?>
            <div id="post-body-content">
                <?php wpmc_field_with_label(['name' => 'team_id', 'label' => __('Team'), 'type' => 'select', 'choices' => $teams]); ?>
            </div>
            <input type="submit" value="<?php echo __('Set team'); ?>" id="submit" class="button-primary" name="submit">
            <?php
        },
    ]);
}, 10, 2);