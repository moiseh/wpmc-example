<?php

add_filter('wpmc_list_actions', function($actions, $item = [], WPMC_Entity $entity){
    $actions['set_team'] = $entity->get_action_link('set_team', $item['id'], __('Set team', 'wp-magic-crud'));
    return $actions;
}, 10, 3);

add_filter('wpmc_bulk_actions', function($actions, WPMC_Entity $entity){
    $actions['set_team'] = __('Set team', 'wp-magic-crud');
    return $actions;
}, 10, 2);

add_filter('wpmc_run_action', function($action, $ids = [], WPMC_Entity $entity){
    global $wpdb;

    if ( $action != 'set_team' ) {
        return;
    }

    if ( isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__)) ) {
        $team_id = $_REQUEST['team_id'];

        foreach ( $ids as $id ) {
            $wpdb->update('mc_players', ['team_id' => $team_id], array('id' => $id));
        }

        wpmc_flash_message(sprintf(__('Team defined for: %s player(s)'), count($ids)));
        wpmc_redirect($entity->listing_url());
    }

    $listingUrl = $entity->listing_url();
    $teams = wpmc_get_entity('team')->build_options();

    ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
        <h2>
            <?php echo _e('Set player(s) team', 'wp-magic-crud') ?>
            <a class="add-new-h2" href="<?php echo $listingUrl; ?>">
                <?php _e('Back to players', 'wp-magic-crud')?>
            </a>
        </h2>
        <form action="<?php echo get_current_page_url(); ?>" method="POST">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
            <input type="hidden" name="action" value="<?php echo $action; ?>"/>
            <input type="hidden" name="id" value="<?php echo implode(',', $ids); ?>"/>
            <div class="metabox-holder" id="poststuff">
                <div id="post-body">
                    <div id="post-body-content">
                        <label>Team:</label>
                        <?php wpmc_render_field(['name' => 'team_id', 'type' => 'select', 'choices' => $teams]); ?>
                    </div>
                </div>
                <input type="submit" value="<?php echo __('Set team'); ?>" id="submit" class="button-primary" name="submit">
            </div>
        </form>
    </div>
    <?php
}, 10, 3);