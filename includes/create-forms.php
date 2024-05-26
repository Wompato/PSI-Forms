<?php
namespace MyGravityFormsPlugin;

function create_forms() {
    if (!class_exists('GFAPI')) {
        return;
    }

    // Define the form structure.
    $form = array(
        'title' => 'Sample Form',
        'description' => 'This is a sample form created programmatically.',
        'fields' => array(
            array(
                'type' => 'text',
                'id' => 1,
                'label' => 'Name',
                'isRequired' => true,
            ),
            array(
                'type' => 'email',
                'id' => 2,
                'label' => 'Email',
                'isRequired' => true,
            ),
            array(
                'type' => 'textarea',
                'id' => 3,
                'label' => 'Message',
                'isRequired' => false,
            ),
            // Add additional fields as needed.
        ),
        'button' => array(
            'type' => 'text',
            'text' => 'Submit',
        ),
    );

    // Create the form.
    $form_id = \GFAPI::add_form($form);

    if (!is_wp_error($form_id)) {
        // Form created successfully.
        error_log("Form created successfully with ID: " . $form_id);
    } else {
        // There was an error creating the form.
        error_log("Error creating form: " . $form_id->get_error_message());
    }
}
?>
