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
 * @copyright  based on work by 2020 Brain Station 23 <moodle@brainstation-23.net>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_edusynch\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\context;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;

defined('MOODLE_INTERNAL') || die();

/**
 * Privacy Subsystem implementation for quizaccess_ps.
 *
 * @copyright  2022 ProctorStone <cto@proctorstone.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\core_userlist_provider,
    \core_privacy\local\request\plugin\provider
{
     /**
     * Retrieve the user metadata stored by plugin.
     *
     * @param collection $collection Collection of metadata.
     * @return collection Collection of metadata.
     */
    public static function get_metadata(collection $collection) : collection {
        $collection->add_external_location_link(
            'quizaccess_ps_api',
            [
                'user_id' => 'privacy:metadata:quizaccess_ps:proctoring_api:user_id',
                'firstname' => 'privacy:metadata:quizaccess_ps:proctoring_api:firstname',
                'lastname' => 'privacy:metadata:quizaccess_ps:proctoring_api:lastname',
            ],
            'privacy:metadata:quizaccess_ps:proctoring_api'
        );
        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     * @param int $userid
     * @return contextlist
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function get_contexts_for_userid(int $userid) : contextlist {
        // External API
    }

    /**
     *  Get the list of users who have data within a context.
     * @param userlist $userlist
     */
    public static function get_users_in_context(userlist $userlist) {
        // External API
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param   approved_contextlist $contextlist The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        // External API
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param   context $context The specific context to delete data for.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        // External API
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param   approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        // External API
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param   approved_userlist       $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        // External API
    }
}