<?php

// example how to alter the default per-page listing rows
add_filter('wpmc_list_per_page', function(){
    return 4;
}, 10, 1);

