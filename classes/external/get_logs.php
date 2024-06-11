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
class get_logs extends \core_external\external_api {

    /**
     * External function parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'Course ID', VALUE_REQUIRED),
            'from' => new external_value(type: PARAM_INT, required: VALUE_DEFAULT, default: -1, allownull: true),
            'to' => new external_value(type: PARAM_INT, required: VALUE_DEFAULT, default: -1, allownull: true),
        ]);
    }

    /**
     * External function.
     *
     * @param int $courseid The context ID.
     * @param int $from The points.
     * @param int $to The type details.
     */
    public static function execute($courseid, $from, $to) {
        global $DB;

        // Validate parameters.
        $params = self::validate_parameters(self::execute_parameters(), [
            'courseid' => $courseid,
            'from' => $from,
            'to' => $to,
        ]);

        // Build SQL query.
        $conditions = ['courseid = :courseid'];
        $params_sql = ['courseid' => $params['courseid']];

        if ($params['from'] != -1) {
            $conditions[] = 'timecreated >= :from';
            $params_sql['from'] = $params['from'];
        }
        if ($params['to'] != -1) {
            $conditions[] = 'timecreated <= :to';
            $params_sql['to'] = $params['to'];
        }

        $sql = 'SELECT timecreated, userid, contextinstanceid, contextlevel, component, action, eventname, origin FROM {logstore_standard_log} WHERE ' . implode(' AND ', $conditions) . 'AND contextlevel=70 AND component <> "core" ORDER BY timecreated ASC';

        // Get logs from the database.
        $logs = $DB->get_records_sql($sql, $params_sql);

        // Return logs as an array.
	$logs_list = array_values($logs);

	foreach ($logs_list as $objeto) {
    		// Eliminar los atributos 'contextlevel' y 'component'
    		unset($objeto->contextlevel);
    		unset($objeto->component);
	}

	return $logs_list;
    }

    /**
     * External function return values.
     *
     * @return external_value
     */
    public static function execute_returns() {
        return new external_multiple_structure(
            new external_single_structure([
                'timecreated' => new external_value(PARAM_INT, 'Timestamp when the event was created'),
                'userid' => new external_value(PARAM_INT, 'User ID'),
		'contextinstanceid' => new external_value(PARAM_INT, 'Context instance ID'),
		'action' => new external_value(PARAM_TEXT, 'Action performed'),
		'eventname' => new external_value(PARAM_TEXT, 'Event performed'),
		'origin' => new external_value(PARAM_TEXT, 'Client were the event ocurred')
            ])
        );
    }

}