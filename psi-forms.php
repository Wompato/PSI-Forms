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
        'gp-advanced-select/gp-advanced-select.php' => 'GP Advanced Select',
        'gp-file-upload-pro/gp-file-upload-pro.php' => 'GP File Upload Pro',
        'gp-media-library/gp-media-library.php' => 'GP Media Library',
        'gp-populate-anything/gp-populate-anything.php' => 'GP Populate Anything',
        'gp-notification-scheduler/gp-notification-scheduler.php' => 'GP Notification Scheduler'
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
    $missing_plugins = get_option('psi_forms_missing_plugins');

    if (!empty($missing_plugins)) {
        $message = 'PSI Forms requires the following plugins to be installed and active: ' . implode(', ', $missing_plugins);
        echo '<div class="notice notice-error"><p>' . esc_html($message) . '</p></div>';
        // Remove the option after displaying the notice.
        delete_option('psi_forms_missing_plugins');
    }
}

// Check dependencies on plugin activation.
register_activation_hook(__FILE__, __NAMESPACE__ . '\\on_activation');

function on_activation() {
    $missing_plugins = check_required_plugins();

    if (!empty($missing_plugins)) {
        // Deactivate the plugin.
        deactivate_plugins(plugin_basename(__FILE__));
        // Store missing plugins in an option to display in an admin notice.
        update_option('psi_forms_missing_plugins', $missing_plugins);
        // Prevent the plugin from being activated.
        wp_die(
            'PSI Forms requires the following plugins to be installed and active: ' . implode(', ', $missing_plugins),
            'Plugin dependency check',
            array('back_link' => true)
        );
    } else {
        // Include the form creation logic.
        require_once plugin_dir_path(__FILE__) . 'includes/create-forms.php';
        create_forms(); // Call the form creation function during activation
    }
}
