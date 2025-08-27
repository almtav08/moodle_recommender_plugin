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
 * Defines the form for editing activity results block instances.
 *
 * @package    block_hybridrecom
 * @copyright  2024 Alex Martinez <alemarti@uji.es>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class local_events_handling_observer {
    // For normal resources
    public static function user_course_resource_viewed(\core\event\course_module_viewed $event) {
        global $CFG, $DB;

        $data = $event->get_data();
        $userid = $data['userid'];
        $cmid = $data['contextinstanceid'];

        debugging('Observer resource triggered for user ' . $userid, DEBUG_DEVELOPER);

        $cm = $DB->get_record('course_modules', array('id' => $cmid), 'id, module, instance, course', MUST_EXIST);
        $module = $DB->get_record('modules', array('id' => $cm->module), 'id, name', MUST_EXIST);

        if ($module->name === 'quiz') { // If it's a quiz, we don't log it here
            return true;
        }

        $data_to_send = array(
            "userid" => $userid,
            "cmid" => $cmid,
            "passed" => true,
            "time" => time()
        );

        self::send_to_api($data_to_send);
    }

    // For quiz attempts
    public static function quiz_attempt_submitted(\mod_quiz\event\attempt_submitted $event) {
        global $CFG, $DB;

        $data = $event->get_data();
        $userid = $data['userid'];

        debugging('Observer quiz triggered for user ' . $userid, DEBUG_DEVELOPER);

        // ID del intento
        $attemptid = $data['objectid'];

        // Cargar el intento completo
        $attempt = $DB->get_record('quiz_attempts', array('id' => $attemptid), '*', MUST_EXIST);

        // Nota obtenida y nota máxima
        $score = $attempt->sumgrades;
        $quizid = $attempt->quiz;

        // Para normalizar a nota sobre 10 o sobre la nota máxima del quiz
        $quiz = $DB->get_record('quiz', array('id' => $quizid), '*', MUST_EXIST);
        //$maxscore = $quiz->sumgrades;

        $cm = get_coursemodule_from_instance('quiz', $quizid, $quiz->course, false, MUST_EXIST);
        $cmid = $cm->id;

        $passed = ($quiz->gradepass > 0) && ($score >= $quiz->gradepass);

        $data_to_send = array(
            "userid"   => $userid,
            "cmid"   => $cmid,
            "passed"   => $passed,
            "time"     => time()
        );

        self::send_to_api($data_to_send);
    }

    // Función común para enviar los datos
    private static function send_to_api($data_to_send) {
        $baseurl = get_config('local_logevent_api', 'config_ipaddress');
        if (empty($baseurl)) {
            debugging('API base URL not set in plugin settings', DEBUG_DEVELOPER);
            return false;
        }

        $url = rtrim($baseurl) . '/append_log';
        $json_data = json_encode($data_to_send);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            debugging('Error fetching data from API: ' . $error_msg, DEBUG_DEVELOPER);
            return false;
        }

        curl_close($curl);

        $data = json_decode($response);
        if (json_last_error() !== JSON_ERROR_NONE) {
            debugging('Error decoding API response: ' . json_last_error_msg(), DEBUG_DEVELOPER);
            return false;
        }

        return true;
    }
}
