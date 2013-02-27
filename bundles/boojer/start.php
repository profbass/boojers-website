<?php
Autoloader::map(array(
    'Boojer_Base_Controller' => Bundle::path('boojer').'controllers/base.php',
));
Autoloader::namespaces(array(
    'Boojer\Models' => Bundle::path('boojer').'models',
));