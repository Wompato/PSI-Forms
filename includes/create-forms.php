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

    // Loop over each file in the directory
    foreach ($form_template_files as $file) {
        // Construct the full path to the file
        $file_path = $form_templates_dir . $file;

        // Ensure it's a file
        if (!is_file($file_path)) {
            continue;
        }

        // Include the file and get the form array
        $form = require $file_path;

        // Create the form using GFAPI
        $form_id = \GFAPI::add_form($form);

        if (!is_wp_error($form_id)) {
            // Form created successfully
            error_log("Form created successfully with ID: " . $form_id);
        } else {
            // There was an error creating the form
            error_log("Error creating form: " . $form_id->get_error_message());
        }
    }
}
?>


