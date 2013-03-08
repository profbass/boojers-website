<?php
Autoloader::map(array(
    'Tumbler_Base_Controller' => Bundle::path('tumbler').'controllers/base.php',
));
Autoloader::namespaces(array(
    'Tumbler\Models' => Bundle::path('tumbler').'models',
));