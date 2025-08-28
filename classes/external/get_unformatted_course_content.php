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

namespace local_logevent_api\external;

use \core_external\external_function_parameters;
use \core_external\external_multiple_structure;
use \core_external\external_single_structure;
use \core_external\external_value;

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_logevent_api
 * @copyright   2024 Alex Martinez <alemarti@uji.es>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_unformatted_course_content extends \core_external\external_api {

    /**
     * External function parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'Course ID', VALUE_REQUIRED)
        ]);
    }

    /**
     * External function.
     *
     * @param int $courseid The context ID.
     * @param int $from The points.
     * @param int $to The type details.
     */
    public static function execute($courseid) {
        global $DB;

        $modules = $DB->get_records_sql(
            "SELECT cm.id as cmid, cm.module as cmmod, m.name as modulename
            FROM {course_modules} AS cm
            JOIN {modules} AS m ON cm.module = m.id
            JOIN {course} AS c ON cm.course = c.id
            WHERE c.id = " . $courseid . " AND cm.deletioninprogress = 0"
        );

        // Return modules as an array.
        $modules_list = array_values($modules);

        foreach ($modules_list as $module) {
            $course_module = get_coursemodule_from_id($module->modulename, $module->cmid, $courseid, false, MUST_EXIST);
            $module->name = $course_module->name;
            $module->instance = $course_module->instance;
        }

        return $modules_list;
    }

    /**
     * External function return values.
     *
     * @return external_value
     */
    public static function execute_returns() {
        return new external_multiple_structure(
            new external_single_structure([
                'cmid' => new external_value(PARAM_INT, 'Course module ID'),
                'cmmod' => new external_value(PARAM_INT, 'Course module type ID'),
                'name' => new external_value(PARAM_TEXT, 'Course module name'),
		        'modulename' => new external_value(PARAM_TEXT, 'Course module type name'),
                'instance' => new external_value(PARAM_INT, 'Course module instance ID')
            ])
        );
    }

}