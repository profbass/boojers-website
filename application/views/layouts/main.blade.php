<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="@yield('page_keywords')">
        <meta name="description" content="@yield('page_description')">
        <meta name="robots" content="index,follow">
        <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico">
        <title>Booj @yield('page_title')</title>
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link href='http://fonts.googleapis.com/css?family=Cabin' rel='stylesheet' type='text/css'>
        <link href="/css/filters.css" rel="stylesheet">
        <link href="/dist/style.min.css" rel="stylesheet">
        {{ Asset::styles() }}
        @yield('styles')
    </head>
    <body>
    <header id="site-header">
        <div class="navbar">
            <div class="navbar-inner clearfix">
                <ul class="nav" id="js-menu">
                    @if (isset($menu_items))
                        @foreach ($menu_items as $menu_item)
                            <li class="<? if ( $current_uri == $menu_item->uri || ($parent_menu_item == $menu_item->uri)) { echo 'active'; }?>">
                                @if ($menu_item->controller == 'link')
                                    <a href="{{ $menu_item->uri }}" target="_blank" rel="nofollow" title="{{ $menu_item->pretty_name }}">{{ $menu_item->pretty_name }}</a>
                                @else
                                    <a href="{{ $menu_item->uri }}" title="{{ $menu_item->pretty_name }}">{{ $menu_item->pretty_name }}</a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </header>
        <div class="main-container" style="min-height: 500px;">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        <footer id="site-footer">
            <div id="footer-top">
                <div class="container-fluid">
                    <div class="row-fluid">
                        <div class="span7">
                            twitter
                        </div>
                        <div class="span5">

                        </div>
                    </div>
                </div>
            </div>

            <div id="footer-bottom">
                <div class="container-fluid">
                    <div class="row-fluid">
                        <div class="span6 margin-bottom-30 clearfix">
                            <img src="/img/footer-facebook-circle.png" class="pull-left margin-right-30" alt="Facebook. Like us to stay in the loop with the latest technology trends.">
                        </div>
                        <div class="span6 margin-bottom-30 clearfix">
                            <a href="http://www.booj.com/career-portal" target="_blank"><img class="pull-left margin-right-30" src="/img/footer-career-circle.png" alt="Want to  join our team? Check out our career protal."></a>
                            <h3>Booj</h3>
                            <p>575 Union Boulevard, Suite 310-320<br>Lakewood, Colorado 80228<br>(303) 396-0104</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <script src="/dist/app.min.js"></script>
       
        {{ Asset::scripts() }}
        @yield('scripts')
        <script>
            var _gaq = _gaq || []; _gaq.push(['_setAccount', '']); _gaq.push(['_trackPageview']); (function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);})();
        </script>
    </body>
</html>