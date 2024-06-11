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
    public static function user_course_resource_viewed(\core\event\course_module_viewed $event) {
        global $CFG;

        // Obtén el objeto de evento.
        $data = $event->get_data();

        // Obtén el ID del usuario y el ID del recurso visto.
        $userid = $data['userid'];
        $cmid = $data['contextinstanceid'];


        // Crear los datos a enviar al servicio web
        $data_to_send = array(
            "userid" => $userid,
            "cmid" => $cmid,
            "time" => time()
        );

        // Convertir los datos a JSON
        $json_data = json_encode($data_to_send);

        // URL del servicio web
        $url = 'http://150.128.97.41:8080/append_log';


        // Configurar la solicitud cURL
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return 'Error fetching data from API: ' . $error_msg;
        }

        curl_close($curl);

        $data = json_decode($response);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return 'Error decoding API response: ' . json_last_error_msg();
        }

        return true;
    }
}
