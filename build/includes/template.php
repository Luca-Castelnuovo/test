<?php

function page_header($title = 'Unknown')
{
    echo <<<HTML
    <!DOCTYPE html>
    <html>

    <head>
        <!-- Config -->
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <link rel="manifest" href="/manifest.json"></link>
        <title>{$title} || Test</title>

        <!-- SEO -->
        <link href="https://test.lucacastelnuovo.nl" rel="canonical">
        <meta content="A system to develop your quick ideas" name="description">

        <!-- Icons -->
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

        <!-- Analytics -->
        <script type="text/javascript">
            var _paq = window._paq || [];
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            (function() {
                var u="//analytics.lucacastelnuovo.nl/";
                _paq.push(['setTrackerUrl', u+'matomo.php']);
                _paq.push(['setSiteId', '13']);
                var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
            })();
        </script>
    </head>

    <body>
        <nav>
            <div class="nav-wrapper blue accent-4">
                <a href="/home" class="brand-logo" style="padding-left: 15px">{$title}</a>
                <a href="#" data-target="sidenav" class="right sidenav-trigger"><i class="material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">
                    <li><a href="/?logout">Logout</a></li>
                </ul>
            </div>

            <ul class="sidenav" id="sidenav">
                <li><a href="/home">Home</a></li>
                <li><a href="/?logout">Logout</a></li>
            </ul>
        </nav>
        <div class="section">
            <div class="container">
HTML;
}

function page_footer()
{
    echo <<<HTML
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        <script src="<?= cdnPath('/test.lucacastelnuovo.nl/js/init.js') ?>"></script>
HTML;
    alert_display();
    echo <<<HTML
    </body>

    </html>
HTML;
}
