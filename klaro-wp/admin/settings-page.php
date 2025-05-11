<?php
// Aggiunge il menu principale "Klaro CMP" nel backoffice
add_action('admin_menu', function () {
    add_menu_page(
        'Klaro CMP Settings',
        'Klaro CMP',
        'manage_options',
        'klaro-cmp',
        'klaro_cmp_render_settings_page',
        'dashicons-shield-alt',
        80
    );
});

// Registra le opzioni salvate nel DB
add_action('admin_init', function () {
    register_setting('klaro_cmp_options_group', 'klaro_must_consent');
    register_setting('klaro_cmp_options_group', 'klaro_accept_all');
    register_setting('klaro_cmp_options_group', 'klaro_default_lang');
    register_setting('klaro_cmp_options_group', 'klaro_apps_json');
});

// Funzione che stampa la pagina impostazioni
function klaro_cmp_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Klaro CMP Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('klaro_cmp_options_group'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="klaro_must_consent">Force mandatory consent</label></th>
                    <td>
                        <input type="checkbox" name="klaro_must_consent" id="klaro_must_consent" value="1" <?php checked(1, get_option('klaro_must_consent'), true); ?> />
                        <p class="description">If checked, the user must consent before using the site.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="klaro_accept_all">Show 'Accept all' button</label></th>
                    <td>
                        <input type="checkbox" name="klaro_accept_all" id="klaro_accept_all" value="1" <?php checked(1, get_option('klaro_accept_all'), true); ?> />
                        <p class="description">If checked, Klaro will show an 'Accept all' button in the modal.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="klaro_default_lang">Default language</label></th>
                    <td>
                        <select name="klaro_default_lang" id="klaro_default_lang">
                            <?php
                            $languages = ['en' => 'English', 'it' => 'Italian', 'de' => 'German', 'fr' => 'French'];
                            $current = get_option('klaro_default_lang', 'en');
                            foreach ($languages as $key => $label) {
                                echo "<option value='" . esc_attr($key) . "'" . selected($key, $current, false) . ">$label</option>";
                            }
                            ?>
                        </select>
                        <p class="description">The default language Klaro will use if no match is found.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="klaro_apps_json">Configured services (apps)</label></th>
                    <td>
                        <textarea name="klaro_apps_json" id="klaro_apps_json" rows="10" cols="60"><?php echo esc_textarea(get_option('klaro_apps_json', '[]')); ?></textarea>
                        <p class="description">Insert Klaro 'apps' configuration in valid JSON format (array of services).</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
