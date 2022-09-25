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
 * privacy provider file
 * @package    quizaccess_ps
 * @category   quiz
 * @copyright  2022 ProctorStone <cto@proctorstone.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

// General
$string['pluginname'] = 'ProctorStone';
$string['preflight_information'] = '<strong>To continue with this quiz attempt you must have and allow your webcam and microphone. You will be required to capture your id and/or your face to verify your id. Webcam may take some of your pictures randomly or record your video and your screen may get recorded during the quiz aligning with the choice of your institution.</strong>';

//Report
$string['report:title'] = 'ProctorStone Proctoring';

// Settings
$string['setting:app_key'] = 'Application Key';
$string['setting:app_key_desc'] = 'The application key sent you by ProctorStone.';
$string['setting:app_secret'] = 'Application Secret';
$string['setting:app_secret_desc'] = 'The application secret sent you by ProctorStone.';

// Privacy API 
$string['privacy:metadata:quizaccess_ps:proctoring_api:user_id'] = 'We use user id to store the student\'s Moodle user id. This helps to make sure which proctoring report belongs to which student.';
$string['privacy:metadata:quizaccess_ps:proctoring_api:firstname'] = 'We use firstname to store the student\'s first name in our data base. This is for teachers to be able to identify the student.';
$string['privacy:metadata:quizaccess_ps:proctoring_api:lastname'] = 'We use lastname to store the student\'s last name in our data base. This is for teachers to be able to identify the student.';
$string['privacy:metadata:quizaccess_ps:proctoring_api'] = 'We use our Proctoring Api to determine if the user tried to do any kind of forbidden actions like openning new tabs, opening developer console, or plugging in second monitor.';

// For Later
// $string['proctoringrequired'] = 'ProctoreStone';
// $string['notrequired'] = 'not required';
// $string['proctoringrequired_help'] = 'If you enable this option, students will not be able to start an attempt until they have ticked a check-box confirming that they are aware of the policy on webcam.';
// $string['proctoringrequiredoption0'] = 'ProctorStone-Starter';
// $string['proctoringrequiredoption1'] = 'ProctorStone-Level1';
// $string['proctoringrequiredoption2'] = 'ProctorStone-Level2';
// $string['proctoringrequiredoption3'] = 'ProctorStone-Level3';
// $string['proctoringrequiredoption4'] = 'ProctorStone-Level4';