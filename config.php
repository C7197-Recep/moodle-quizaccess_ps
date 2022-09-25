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
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

// @codingStandardsIgnoreLine .
require_once(__DIR__ . '/../../../../config.php');

// For the Development add $CFG->cachejs = false.
// $CFG->cachejs = false;

$cmid = required_param('cmid', PARAM_RAW);

$config = \quizaccess_ps\helper::get_proctoring_config_content($cmid);
\quizaccess_ps\helper::send_proctoring_config_file($config);
