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
 * Quizaccess plugin for integration with ProctorStone proctoring system.
 *
 * @package    quizaccess_ps
 * @category   quiz
 * @copyright  2022 ProctorStone <cto@proctorstone.com>
 * @copyright  based on work by 2020 Brain Station 23 <moodle@brainstation-23.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once __DIR__ . "/../../../../config.php";
require_once $CFG->dirroot . "/lib/tablelib.php";

// Get vars.
$courseid = required_param("courseid", PARAM_INT);
$cmid = required_param("cmid", PARAM_INT);
$studentid = optional_param("studentid", "", PARAM_INT);
list ($course, $cm) = get_course_and_cm_from_cmid($cmid, 'quiz');
require_login($course, true, $cm);
$COURSE = $DB->get_record("course", ["id" => $courseid]);
$quiz = $DB->get_record("quiz", ["id" => $cm->instance]);

//Create params array
$params = [
    "courseid" => $courseid,
    "userid" => $studentid,
    "cmid" => $cmid,
];
if ($studentid) {
    $params["studentid"] = $studentid;
}
if ($reportid) {
    $params["reportid"] = $reportid;
}

//Establish the report page
$url = new moodle_url("/mod/quiz/accessrule/ps/report.php", $params);
$PAGE->set_url($url);
$PAGE->set_pagelayout("course");
$PAGE->set_title(
    $COURSE->shortname . ": " . get_string("pluginname", "quizaccess_ps")
);
$PAGE->set_heading(
    $COURSE->fullname . ": " . get_string("pluginname", "quizaccess_ps")
);
$PAGE->navbar->add(get_string("report:title", "quizaccess_ps"), $url);
// $PAGE->requires->js_call_amd( 'quizaccess_ps/lightbox');
echo $OUTPUT->header();

/**************************************************************/

global $OUTPUT, $USER, $DB;

//Get app_key
$appkeysql = "SELECT * FROM {config_plugins} 
            WHERE plugin = 'quizaccess_ps' AND name = 'app_key'";
$app_key_array = $DB->get_records_sql($appkeysql);
if (count($app_key_array) > 0) {
    foreach ($app_key_array as $row) {
        $app_key = $row->value;
    }
}

//Get app_secret
$appsecretsql = "SELECT * FROM {config_plugins} 
                WHERE plugin = 'quizaccess_ps' AND name = 'app_secret'";
$app_secret_array = $DB->get_records_sql($appsecretsql);
if (count($app_secret_array) > 0) {
    foreach ($app_secret_array as $row) {
        $app_secret = $row->value;
    }
}

//Create a safe app_token request
$algorithm = "AES-256-CBC";
$str =
    "command=get_api_token&api_key=" .
    $app_key .
    "&api_secret=" .
    $app_secret .
    "&course_id=" .
    $courseid .
    "&course_name=" .
    $COURSE->fullname .
    "&quiz_id=" .
    $cmid .
    "&quiz_name=" .
    $quiz->name;

//Request an app_token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://app.proctorstone.com/dash_apix/");
//curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
curl_setopt($ch, CURLOPT_REFERER, "https://" . $_SERVER["HTTP_HOST"]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
$app_token = json_decode($result)->data;
//echo "TOKEN" . $result;

//Get the preferred language from Moodle
$lang = $SESSION->lang;
if ($lang == "en") {
    $lang = $lang . "-US";
}
if ($lang == "tr") {
    $lang = $lang . "-TR";
}
if ($lang == null) {
    $lang = "en-US";
}

//Initiate ProctorStone Dashboard Api
//This api embeds the remote dashboard into this report page in the Moodle
echo '
    <script src="https://app.proctorstone.com/dash_rem/app.js?v=31"></script>
    <div id="main" class="overlay"></div>
    <script>
        var lang = "' .
    $lang .
    '";
        dash.setOptions({      
            "api_key":"' .
    $app_key .
    '",
            "api_token":"' .
    $app_token .
    '",
            "lang":lang,
            "quiz_name":"' .
    $quiz->name .
    '"
        });  
        dash.init();
    </script>
';

echo $OUTPUT->footer();
