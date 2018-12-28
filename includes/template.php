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
        <link rel="stylesheet" href="https://cdn.lucacastelnuovo.nl/general/css/materialize.css">
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
        <script src="https://cdn.lucacastelnuovo.nl/general/js/materialize.js"></script>
        <script src="https://cdn.lucacastelnuovo.nl/test.lucacastelnuovo.nl/js/init.js"></script>
HTML;
    alert_display();
    echo <<<HTML
    </body>

    </html>
HTML;
}
