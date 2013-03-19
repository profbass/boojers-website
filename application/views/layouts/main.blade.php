<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="@yield('page_keywords')">
        <meta name="description" content="@yield('page_description')">
        <meta name="robots" content="index,follow">
        <meta property="og:title" content="boojers are a group of original thinkers, social media lovers, search engine optimizers, web designers and developers that are lucky enough to say they work at booj.">
        <meta property="og:url" content="http://www.boojers.com">
        <meta property="og:image" content="http://www.boojers.com/img/boojers-header-logo.png">
        <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico">
        <title>boojers @yield('page_title')</title>
        <link href='http://fonts.googleapis.com/css?family=Cabin' rel='stylesheet' type='text/css'>
        <link href="/dist/style.min.<? if(!empty($build_version)) echo $build_version . '.';?>css" rel="stylesheet">
        {{ Asset::styles() }}
        @yield('styles')
        <script src="/js/lib/modernizer.min.js"></script>
    </head>
    <body>
        <header id="site-header">
            <div class="navbar">
                <div class="navbar-inner clearfix">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="/" title="Home" class="brand"><img src="/img/boojers-header-logo.png" alt="Boojers"></a>
                    <div class="nav-collapse collapse">
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
            </div>
        </header>
        
        @yield('content')

        <script src="/dist/app.min.<? if(!empty($build_version)) echo $build_version . '.';?>js"></script>
       
        {{ Asset::scripts() }}
        @yield('scripts')
        <script>
            var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-244849-37']); _gaq.push(['_trackPageview']); (function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);})();
        </script>
    </body>
</html>