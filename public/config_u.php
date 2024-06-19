<?php

    header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
    header('X-Frame-Options: ALLOW-FROM https://www.youtube.com');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: no-referrer');
    header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
    header('Cross-Origin-Opener-Policy: same-origin');
    header('Cross-Origin-Resource-Policy: same-origin');

    $acceptLang = ['en', 'fr', 'de', 'it', 'lt', 'cn', 'ru', 'ua', 'sv', 'no', 'tc', 'bg'];

    // Check if HTTP_ACCEPT_LANGUAGE is set and then check the browser's preferred language
    $lang_br = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : 'en';
    $lang_br = in_array($lang_br, $acceptLang) ? $lang_br : 'en';

    // Set the session cookie lifetime and start session
    session_set_cookie_params(864000); 
    session_start();

    // Set session language if not already set
    if (!isset($_SESSION['lang'])) {
        $_SESSION['lang'] = $lang_br;
    } elseif (isset($_GET['lang']) && in_array($_GET['lang'], $acceptLang) && $_SESSION['lang'] != $_GET['lang']) {
        $_SESSION['lang'] = $_GET['lang'];
    }

    // Include the language file based on session language
    require_once "assets/languages/u/" . $_SESSION['lang'] . ".php";
?>
