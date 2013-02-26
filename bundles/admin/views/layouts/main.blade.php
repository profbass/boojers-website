<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="noindex,nofollow">
        <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico">
        <title>Admin @yield('page_title')</title>
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css" rel="stylesheet">
        <link href="/bundles/admin/css/bootstrap.2.2.1.css" media="all" rel="stylesheet">
        <link href="/bundles/admin/css/styles.css" media="all"rel="stylesheet">
        <link href="/bundles/admin/css/bootstrap.2.2.1-responsive.min.css" media="all" rel="stylesheet">
        {{ Asset::styles() }}
        @yield('styles')
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top" id="admin-header">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a href="/admin" class="brand"><img src="/bundles/admin/img/admin_logo.png" alt="Admin Logo"></a>
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="nav-collapse">
                        <ul class="nav">
                            @if (!empty($main_admin_nav))
                                @foreach($main_admin_nav as $menu_name => $menu_uri)
                                    <li<? if ($current_uri == $menu_uri) echo ' class="active"'; ?>><a href="<?=$menu_uri;?>"><?=$menu_name;?></a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container-fluid" id="admin-container">            
            <?php 
                $success_message = Session::get('success_message');
                if ($success_message) {
                    echo '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert">×</button>' . $success_message . '</div>';
                }
                $error_message = Session::get('error_message');
                if ($error_message) {
                    echo '<div class="alert alert-block alert-error"><button type="button" class="close" data-dismiss="alert">×</button><h4>Error!</h4>' . $error_message . '</div>';
                }

                if (isset($errors) && is_object($errors)) {
                    $validator_messages = $errors->all('<div class="alert alert-block alert-error"><button type="button" class="close" data-dismiss="alert">×</button><h4>Error!</h4>:message</div>');
                    if ($validator_messages) {
                        foreach($validator_messages as $validator_message) {
                            echo $validator_message;
                        }
                    }
                }
            ?>
            @yield('content')
        </div>
    </body>
    
    <script src="/bundles/admin/js/jquery-1.8.3.min.js"></script>
    <script src="/bundles/admin/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"></script>
    {{ Asset::scripts() }}
    @yield('scripts')
    <script src="/bundles/admin/js/admin.js"></script>
</html>