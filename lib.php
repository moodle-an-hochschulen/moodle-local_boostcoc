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
 * Local plugin "Boost course overview on campus" - Library
 *
 * @package    local_boostcoc
 * @copyright  2017 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Fumble with Moodle's global navigation by leveraging Moodle's *_extend_navigation() hook.
 *
 * @param global_navigation $navigation
 */
function local_boostcoc_extend_navigation(global_navigation $navigation) {
    global $PAGE, $CFG;

    // Fetch local_boostcoc config.
    $lbcoc_config = get_config('local_boostcoc');

    // Include local library from local_boostcoc.
    require_once(dirname(__FILE__) . '/locallib.php');

    // Include local library from local_boostnavigation.
    require_once(dirname(dirname(__FILE__)) . '/boostnavigation/locallib.php');

    // If we need the mycourses node for any enabled feature, fetch it only once and use it multiple times.
    // We have to check explicitely if the configurations are set because this function will already be
    // called at installation time and would then throw PHP notices otherwise.
    if ((isset($lbcoc_config->enablenotshown) && $lbcoc_config->enablenotshown == true) ||
            (isset($lbcoc_config->addusagehint) && $lbcoc_config->addusagehint == true)) {
        $mycoursesnode = $navigation->find('mycourses', global_navigation::TYPE_ROOTNODE);
    }

    // Check if admin wanted us to modify the mycourses list in Boost's nav drawer.
    if (isset($lbcoc_config->enablenotshown) && $lbcoc_config->enablenotshown == true) {
        // If yes, do it.
        if ($mycoursesnode) {
            // Get list of not shown courses which is remembered by block_course_overview_campus for us.
            $notshowncourses = json_decode(get_user_preferences('local_boostcoc-notshowncourses', '[]'));

            // We only need to continue if there are any courses not to be shown currently.
            if (count($notshowncourses) > 0) {
                // Check all courses below the mycourses node.
                $mycourseschildrennodeskeys = $mycoursesnode->get_children_key_list();
                foreach ($mycourseschildrennodeskeys as $k) {
                    // If the admin decided to display categories, things get slightly complicated.
                    if ($CFG->navshowmycoursecategories) {
                        // We need to find all children nodes first.
                        $allchildrennodes = local_boostnavigation_get_all_childrenkeys($mycoursesnode->get($k));
                        // Then we can check each children node.
                        // Unfortunately, the children nodes have navigation_node type TYPE_MY_CATEGORY or navigation_node type
                        // TYPE_COURSE, thus we need to search without a specific navigation_node type.
                        foreach ($allchildrennodes as $cn) {
                            // Hide course node if it is in the list of not shown courses.
                            if (in_array($cn, $notshowncourses)) {
                                $mycoursesnode->find($cn, null)->showinflatnavigation = false;
                            }
                        }
                    }
                    // Otherwise we have a flat navigation tree and hiding the courses is easy.
                    else {
                        // Hide course node if it is in the list of not shown courses.
                        if (in_array($k, $notshowncourses)) {
                            $mycoursesnode->get($k)->showinflatnavigation = false;
                        }
                    }
                }
            }
        }
    }

    // Check if admin wanted us to add the usage hint to the mycourses list in Boost's nav drawer.
    if (isset($lbcoc_config->addusagehint) && $lbcoc_config->addusagehint == true) {
        // If yes, do it.
        if ($mycoursesnode) {
            // Create usage hint string and url.
            if ($lbcoc_config->enablenotshown == true) {
                $usagehinturl = new moodle_url('/my/');
                $usagehintstring = local_boostcoc_get_activefilters_string();
                $usagehintstring .= html_writer::empty_tag('br');
                $usagehintstring .= html_writer::link($usagehinturl,
                        get_string('usagehintnotshowenabledchangenotshown', 'local_boostcoc'));
            } else {
                $usagehintstring = get_string('usagehintnotshowdisabled', 'local_boostcoc');
            }

            // Create usage hint node.
            $usagehintnode = navigation_node::create($usagehintstring, null, global_navigation::TYPE_COURSE, null,
                     'localboostcocusagehint');

            // Show the usage hint node in Boost's nav drawer.
            $usagehintnode->showinflatnavigation = true;

            // Add the node to the mycourses list in Boost's nav drawer.
            $mycoursesnode->add_node($usagehintnode);
        }
    }
}
