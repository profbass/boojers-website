<?php
Autoloader::map(array(
    'Content_Base_Controller' => Bundle::path('content').'controllers/base.php',
));
Autoloader::namespaces(array(
    'Content\Models' => Bundle::path('content').'models',
));