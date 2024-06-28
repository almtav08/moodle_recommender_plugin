<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin settings for the local_logevent_api plugin.
 *
 * @package   local_logevent_api
 * @copyright Year, You Name <your@email.address>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Ensure the configurations for this site are set
if ($hassiteconfig) {

    // Create the new settings page
    // - in a local plugin this is not defined as standard, so normal $settings->methods will throw an error as
    // $settings will be null
    $settings = new admin_settingpage('local_logevent_api', 'Local Plugins settings');

    // Create
    $ADMIN->add('localplugins', $settings);

    // Add a setting field to the settings for this page
    $setting = new admin_setting_configtext('local_logevent_api/config_ipaddress',
        new lang_string('ipaddress', 'local_logevent_api'),
        new lang_string('ipaddress_desc', 'local_logevent_api'), 'http://127.0.0.1:8080', PARAM_TEXT);
    $settings->add($setting);
}