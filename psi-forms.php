<?php
/*
Plugin Name: PSI Forms
Description: A plugin that creates Gravity Forms programmatically and relies on several Gravity Perks and Add-Ons.
Version: 1.0
Author: Alex Shelton
*/

namespace PSI_Forms;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Include the necessary dependencies.
include_once ABSPATH . 'wp-admin/includes/plugin.php';

// Define a function to check for required plugins.
function check_required_plugins() {
    $required_plugins = array(
        'gravityforms/gravityforms.php' => 'Gravity Forms',
        'gravityperks/gravityperks.php' => 'Gravity Perks',
        'gravityformsadvancedpostcreation/advancedpostcreation.php' => 'Gravity Forms Advanced Post Creation Add-On',
        'gravityformsuserregistration/userregistration.php' => 'Gravity Forms User Registration Add-On',
        // Add other required plugins here.
    );

    $missing_plugins = array();

    foreach ($required_plugins as $plugin => $name) {
        if (!is_plugin_active($plugin)) {
            $missing_plugins[] = $name;
        }
    }

    return $missing_plugins;
}

// Admin notices for missing dependencies.
add_action('admin_notices', __NAMESPACE__ . '\\admin_notices');

function admin_notices() {
    $missing_plugins = check_required_plugins();

    if (!empty($missing_plugins)) {
        $message = 'My Gravity Forms Plugin requires the following plugins to be installed and active: ' . implode(', ', $missing_plugins);
        echo '<div class="notice notice-error"><p>' . esc_html($message) . '</p></div>';
    }
}

// Only include the form creation logic if all required plugins are active.
add_action('admin_init', __NAMESPACE__ . '\\init_plugin');

function init_plugin() {
    if (empty(check_required_plugins())) {
        // Include the form creation logic.
        require_once plugin_dir_path(__FILE__) . 'includes/create-forms.php';
        // Activation hook to create forms.
        register_activation_hook(__FILE__, __NAMESPACE__ . '\\create_forms');
    }
}
?>
