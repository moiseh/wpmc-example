<?php

if ( !function_exists('my_check_manage') ) {
    function my_check_manage($ids, $entity = null) {
        global $wpdb;
    
        if ( !current_user_can('activate_plugins') ) {
            return false;
        }

        if ( empty($entity) ) {
            $entity = wpmc_get_current_entity();
        }
    
        // add your condition here to check if user can manage the ID
        if ( !empty($entity) ) {
            $uid = get_current_user_id();
            $ids = implode(',', (array) $ids);
            $notAllowedIds = $wpdb->get_var("SELECT COUNT(id) FROM {$entity->tableName} WHERE id IN({$ids}) AND user_id <> {$uid}");
            if ( $notAllowedIds > 0 ) {
                return false;
            }
        }
    
        return true;
    }
}

// you can use this function to check everytime when request have some id:
// $requestIds = wpmc_request_ids();
// if ( !empty($requestIds) && !my_check_manage($requestIds) ) {
//     wpmc_flash_message(sprintf(__('You cannot change the IDs: %s'), implode(',', $requestIds)), 'error');
//     $entity->back_to_home();
// }

add_filter('wpmc_validation_errors', function($errors, $fields){
    if ( !empty($_REQUEST['id']) && !my_check_manage($_REQUEST['id']) ) {
        $errors[] = sprintf(__('You cannot edit the ID: %s'), $_REQUEST['id']);
    }

    return $errors;
}, 10, 2);

add_filter('wpmc_before_delete_ids', function($ids, $entity){
    if ( !my_check_manage($ids) ) {
        wpmc_flash_message(sprintf(__('You cannot delete the IDs: %s'), implode(',', $ids)), 'error');
        $entity->back_to_home();
    }

    return $ids;
}, 10, 2);

add_filter('wpmc_entity_find', function($row, $entity){
    // apply your condition here to check the $row['id']
    return $row;
}, 10, 2);