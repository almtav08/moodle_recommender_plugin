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
 * Plugin version and other meta-data are defined here.
 *
 * @package     block_hybridrecom
 * @copyright   2024 Alex Martinez <alemarti@uji.es>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_logevent_api_getlogs' => [
        'classname' => 'local_logevent_api\external\get_logs',
        'methodname' => 'execute',
        'description' => 'Obtain course logs from the Moodle database.',
        'type' => 'read',
        'ajax' => true,
    ],
    'local_logevent_api_getunformattedcoursecontent' => [
        'classname' => 'local_logevent_api\external\get_unformatted_course_content',
        'methodname' => 'execute',
        'description' => 'Obtain unformatted course content from the Moodle database.',
        'type' => 'read',
        'ajax' => true,
    ]
];
