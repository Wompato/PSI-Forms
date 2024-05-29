<?php
namespace PSI_Forms;

function create_forms() {
    if (!class_exists('GFAPI')) {
        return;
    }

    // Path to the form templates directory
    $form_templates_dir = plugin_dir_path(__FILE__) . 'form-templates/';

    // Get an array of all files in the form templates directory
    $form_template_files = array_diff(scandir($form_templates_dir), ['.', '..']);

    // Current site domain
    $current_site_domain = get_site_url(); // Get the current site's URL

    

    // Loop over each file in the directory
    foreach ($form_template_files as $file) {
        $file_path = $form_templates_dir . $file;
        if (!is_file($file_path) || pathinfo($file_path, PATHINFO_EXTENSION) !== 'json') {
            continue;
        }

        $file_contents = file_get_contents( $file_path );
        $updated_contents = str_replace('{placeholder_domain}', $current_site_domain, $file_contents);

        \GFExport::import_json($updated_contents);

    }
}
