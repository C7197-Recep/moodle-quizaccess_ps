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
 * @package     quizaccess_ps
 * @category    quiz
 * @copyright   2022 ProctorStone <cto@proctorstone.com>
 * @copyright   based on work by 2020 Brain Station 23 <moodle@brainstation-23.net>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined("MOODLE_INTERNAL") || die();

require_once $CFG->dirroot . "/mod/quiz/accessrule/accessrulebase.php";

$app_key = "";
$app_token = "";
$attempt = "";

function init_proctorstone()
{
    global $OUTPUT, $USER, $DB, $COURSE, $CFG, $SESSION;
    global $app_key, $app_token, $attempt;
    $cContext = context_course::instance($COURSE->id);

    //Get the viewreports permission of the user. 
    $isStudent = !has_capability("mod/quiz:viewreports", $cContext)
        ? true
        : false;
    if (!$isStudent) {
        return;
    }

    //Get quiz_id
    $quiz_id = optional_param("id", "", PARAM_INT);
    $cmid = optional_param("cmid", "", PARAM_INT);
    if (!isset($quiz_id) || $quiz_id == "") {
        $quiz_id = $cmid;
    }

    //Get attempt_id
    $attempt = optional_param("attempt", "", PARAM_INT);
    $attempt1 = $USER->attemptid;
    $attempt = isset($attempt) ? $attempt : $attempt1;

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

    //Create a Post Request for a safe app_token based on the parameters above
    $timestamp = time();
    $options = [
        "cost" => 10,
    ];

    $hashed_app_secret = base64_encode(
        password_hash($app_secret . $timestamp, PASSWORD_BCRYPT, $options)
    );

    $str =
        "command=get_api_token" .
        "&time_stamp=" .
        $timestamp .
        "&api_key=" .
        $app_key .
        "&hashed_api_secret=" .
        $hashed_app_secret .
        "&course_id=" .
        $COURSE->id .
        "&quiz_id=" .
        $quiz_id .
        "&user_id=" .
        $USER->id .
        "&attempt_id=" .
        $attempt .
        "&user_first_name=" .
        $USER->firstname .
        "&user_last_name=" .
        $USER->lastname;

    //Request the app_token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://app.proctorstone.com/app/api.php");
    curl_setopt($ch, CURLOPT_REFERER, "https://" . $_SERVER["HTTP_HOST"]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $app_token = json_decode($result)->data;
    //echo $result;

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

    /***********************************************************************************************/

    //Make the api request
    echo '     
                <!╌ Onboarding interface will be embedded in onboarding_panel ╌>
                <div id="onboarding_panel" class="overlay" style="z-index:100000"></div>
                
                <!╌ Load needed external libraries ╌>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
                <script src="https://app.proctorstone.com/app/ps.min.js"></script>
                
                <!╌ Initate ProctorStone╌>
                <script>
                window.onload = function() {
                    
                    var lang = "' . $lang . '";

                    /*********ON EXAM START**********/
                    /*Reload the page after Onboarding gets finished to close Onboarding window and start the Proctoring session*/
                    var start_exam=function (response) {
                      if (response=="start_exam"){
                          proctorstone.setf("on_exam_start");
                          window.location.href="#";
                          document.location.reload();
                      }
                    }    
                    
                    /*********ON PREFLIGHT PAGE**********/
                    /*Init ProctorStone scripts*/
                    try{        
                        proctorstone.setOptions({      
                            "api_key":"' . $app_key . '",
                            "api_token":"' . $app_token . '", 
                            "lang":lang,
                            "client_callback":start_exam,
                            "preflight_page":"/mod/quiz/view.php"
                        });  
                        proctorstone.init();
                    }catch(e){
                        document.documentElement.innerHTML = 
                        "Proctoring files are blocked by your browser. <br><br>" +
                        "Is your browser up to date?.<br><br>" +
                        "Did you disable browser extentions or unnecessary applications?<br><br>" + 
                        "You can relay this message to technical support : <br><br>"+ 
                        e.message;
                    }
                
                    /*********ON EXAM END**********/
                    /*Redirect students to Course page after they finish the exam. 
                    Otherwise, students will see the preflight page again, and the system will regard them as they will start a new attempt.*/
                    
                    // Select the node that will be observed for mutations
                    const targetNode = document;
                    // Options for the observer (which mutations to observe)
                    const config = { 
                        attributes: true,
                        childList: true,
                        characterData: true,
                        subtree: true,
                        //attributeFilter: [‘id’, ‘class’, ‘style’],
                        attributeOldValue: true,
                        characterDataOldValue: true
                    };
                    // Callback function to execute when mutations are observed
                    const callback = function(mutationsList, observer) {
                        for(const mutation of mutationsList) {
                            if ( mutation.type == "childList" ) {
                              if (mutation.addedNodes.length >= 1 && mutation.target.className.includes("moodle-dialogue-confirm")) {
                                    try{
                                        document.querySelectorAll("input[value=\'Submit all and finish\']")[0].addEventListener("click", function(){
                                            proctorstone.setf("on_exam_end");
                                            document.location.href="/course";
                                        });
                                    }catch(e){
                                        //alert(e);
                                    }
                                    try{
                                        document.querySelectorAll("input[value=\'Tümünü gönder ve bitir\']")[0].addEventListener("click", function(){
                                            proctorstone.setf("on_exam_end");
                                            document.location.href="/course";
                                        });
                                    }catch(e){
                                        //alert(e);
                                    }
                                    //return;
                                }
                            }
                        }
                    };
                    // Create an observer instance linked to the callback function
                    const observer = new MutationObserver(callback);
                    // Start observing the target node for configured mutations
                    observer.observe(targetNode, config);                   
                }
                </script>
        ';
}

class quizaccess_ps extends quiz_access_rule_base
{
    /**
     * Check if preflight check is required.
     *
     * @param mixed $attemptid
     * @return bool
     */
    public function is_preflight_check_required($attemptid)
    {
        return empty($attemptid);
    }

    /**
     * Information, such as might be shown on the quiz view page, relating to this restriction.
     * There is no obligation to return anything. If it is not appropriate to tell students
     * about this rule, then just return ''.
     *
     * @param quiz $quizobj
     * @param int $timenow
     * @param bool $canignoretimelimits
     * @return quiz_access_rule_base|quizaccess_ps|null
     */
    public static function make(quiz $quizobj, $timenow, $canignoretimelimits)
    {
        return new self($quizobj, $timenow);
    }

    /**
     * Information, such as might be shown on the quiz view page, relating to this restriction.
     * There is no obligation to return anything. If it is not appropriate to tell students
     * about this rule, then just return ''.
     *
     * @return mixed a message, or array of messages, explaining the restriction
     *         (may be '' if no message is appropriate).
     * @throws coding_exception
     */
    public function description()
    {
        global $PAGE;
        init_proctorstone();
        $messages = [get_string("preflight_information", "quizaccess_ps")];
        $messages[] = $this->get_download_config_button();
        return $messages;
    }

    /**
     * Sets up the attempt (review or summary) page with any special extra
     * properties required by this rule.
     *
     * @param moodle_page $page the page object to initialise.
     * @throws coding_exception
     * @throws dml_exception
     */

    public function setup_attempt_page($page)
    {
        $cmid = optional_param("cmid", "", PARAM_INT);
        $attempt = optional_param("attempt", "", PARAM_INT);

        $page->set_title(
            $this->quizobj->get_course()->shortname . ": " . $page->title
        );
        $page->set_popup_notification_allowed(false); // Prevent message notifications.
        $page->set_heading($page->title);

        global $DB, $COURSE, $USER, $CFG, $SESSION;

        if ($cmid) {
            init_proctorstone();
        }
    }

    /**
     * Get a button to view the Proctoring report.
     *
     * @return string A link to view report
     * @throws coding_exception
     */
    private function get_download_config_button(): string
    {
        global $OUTPUT, $USER;

        $context = context_module::instance($this->quiz->cmid, MUST_EXIST);
        if (has_capability("quizaccess/ps:viewreport", $context, $USER->id)) {
            $httplink = \quizaccess_ps\link_generator::get_link(
                $this->quiz->course,
                $this->quiz->cmid,
                false,
                is_https()
            );
            return $OUTPUT->single_button(
                $httplink,
                get_string("pluginname", "quizaccess_ps"),
                "get"
            );
        } else {
            return "";
        }
    }
}
