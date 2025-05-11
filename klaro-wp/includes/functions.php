<?php
// Placeholder for plugin functionality

function klarowp_enqueue_scripts() {
    // Carica Klaro.js da CDN
    wp_enqueue_script('klaro-core', 'https://cdn.kiprotect.com/klaro/v0.7.22/klaro.js', [], null, true);

    // Recupera le opzioni da WP
    $must_consent = get_option('klaro_must_consent') ? 'true' : 'false';
    $accept_all = get_option('klaro_accept_all') ? 'true' : 'false';
    $default_lang = get_option('klaro_default_lang', 'en');
    $apps_json = get_option('klaro_apps_json', '[]');

    $apps = json_decode($apps_json, true);
    if (!is_array($apps)) $apps = [];

    // Genera il blocco JS inline
    $klaro_js = 'var klaroConfig = ' . json_encode([
        'elementID' => 'klaro',
        'storageMethod' => 'cookie',
        'cookieName' => 'klaro',
        'cookieExpiresAfterDays' => 180,
        'default' => false,
        'mustConsent' => $must_consent === 'true',
        'acceptAll' => $accept_all === 'true',
        'defaultLang' => $default_lang,
        'translations' => [
            $default_lang => [
                'consentModal' => [
                    'title' => 'Privacy Settings',
                    'description' => 'We use cookies and third-party services to improve your experience.',
                ],
                'purposes' => [
                    'analytics' => 'Analytics',
                    'marketing' => 'Marketing',
                    'video' => 'Video playback',
                    'functional' => 'Functional',
                    'security' => 'Security'
                ]
            ]
        ],
        'apps' => $apps
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . ';';

    // Inietta inline subito dopo klaro.js
    wp_add_inline_script('klaro-core', $klaro_js, 'before');
}
add_action('wp_enqueue_scripts', 'klarowp_enqueue_scripts');

add_action('wp_footer', function () {
    ?>
    <div id="klaro-toggle" style="position: fixed; bottom: 20px; left: 20px; z-index: 9999;">
        <a href="#" onclick="return klaro.show();" style="
            display: flex;
            align-items: center;
            background-color: #fff;
            color: #333;
            border: 1px solid #ccc;
            border-radius: 50px;
            padding: 8px 12px;
            font-size: 14px;
            font-family: sans-serif;
            text-decoration: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        ">
            🔒 <span style="margin-left: 8px;">Privacy settings</span>
        </a>
    </div>
    <?php
});


add_filter('script_loader_tag', function ($tag, $handle, $src) {
    $blocked_services = [
        'gtag',            // Google Analytics
        'google-analytics',
        'fbq',             // Facebook Pixel
        'hotjar',
        'matomo',
        'clarity',
        'linkedin-insight',
        'tiktok-pixel'
    ];

    foreach ($blocked_services as $service) {
        if (stripos($handle, $service) !== false || stripos($src, $service) !== false) {
            $tag = str_replace(
                '<script',
                '<script type="text/plain" data-type="application/javascript" data-name="' . esc_attr($service) . '"',
                $tag
            );
            break;
        }
    }

    return $tag;
}, 10, 3);
