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
    global $CFG;

    // Fetch local_boostcoc config.
    $lbcocconfig = get_config('local_boostcoc');

    // Include local library from local_boostcoc.
    require_once(__DIR__ . '/locallib.php');

    // Include local library from local_boostnavigation.
    require_once(__DIR__ . '/../boostnavigation/locallib.php');

    // As we need the mycourses node for any enabled feature, fetch it only once and use it multiple times.
    // We have to check explicitely if the configurations are set because this function will already be
    // called at installation time and would then throw PHP notices otherwise.
    if ((isset($lbcocconfig->enablenotshown) && $lbcocconfig->enablenotshown == true) ||
            (isset($lbcocconfig->disableinprogressfilter) && $lbcocconfig->disableinprogressfilter == true) ||
            (isset($lbcocconfig->addactivefiltershint) && $lbcocconfig->addactivefiltershint == true)) {
        $mycoursesnode = $navigation->find('mycourses', global_navigation::TYPE_ROOTNODE);
    }

    // Check if admin wanted us to apply the COC filters to the mycourses list in Boost's nav drawer
    // and / or if admin wanted us to show all courses in Boost's nav drawer regardless of the course progress.
    if ((isset($lbcocconfig->enablenotshown) && $lbcocconfig->enablenotshown == true) ||
            (isset($lbcocconfig->disableinprogressfilter) && $lbcocconfig->disableinprogressfilter == true)) {
        // If yes, do it.
        if ($mycoursesnode) {
            // If admin wanted us to apply the COC filters to the mycourses list in Boost's nav drawer.
            if ($lbcocconfig->enablenotshown == true) {
                // Get list of not shown courses which is remembered by block_course_overview_campus for us.
                $notshowncourses = local_boostcoc_get_notshowncourses();
            }

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
                        // If admin wanted us to apply the COC filters to the mycourses list in Boost's nav drawer and
                        // if if the node is in the list of not shown courses.
                        if (isset($lbcocconfig->enablenotshown) && $lbcocconfig->enablenotshown == true &&
                                in_array($cn, $notshowncourses)) {
                            // Hide the course node.
                            $mycoursesnode->find($cn, null)->showinflatnavigation = false;

                            // Otherwise if admin wanted us to show all courses in Boost's nav drawer regardless
                            // of the course progress.
                        } else if (isset($lbcocconfig->disableinprogressfilter) && $lbcocconfig->disableinprogressfilter == true) {
                            // Show the course node.
                            $mycoursesnode->find($cn, null)->showinflatnavigation = true;
                        }
                    }

                    // Otherwise we have a flat navigation tree and hiding the courses is easy.
                } else {
                    // If admin wanted us to apply the COC filters to the mycourses list in Boost's nav drawer and
                    // if if the node is in the list of not shown courses.
                    if (isset($lbcocconfig->enablenotshown) && $lbcocconfig->enablenotshown == true &&
                            in_array($k, $notshowncourses)) {
                        // Hide the course node.
                        $mycoursesnode->get($k)->showinflatnavigation = false;

                        // Otherwise if admin wanted us to show all courses in Boost's nav drawer regardless
                        // of the course progress.
                    } else if (isset($lbcocconfig->disableinprogressfilter) && $lbcocconfig->disableinprogressfilter == true) {
                        // Show the course node.
                        $mycoursesnode->get($k)->showinflatnavigation = true;
                    }
                }
            }
        }
    }

    // Check if admin wanted us to add the active filters hint or change filters link to the mycourses list in Boost's nav drawer.
    if ((isset($lbcocconfig->addactivefiltershint) && $lbcocconfig->addactivefiltershint == true) ||
            isset($lbcocconfig->addchangefilterslink) && $lbcocconfig->addchangefilterslink == true) {
        // If yes, do it.
        if ($mycoursesnode) {
            // Do only if I am enrolled in at least one course.
            if (count($mycoursesnode->get_children_key_list()) > 0) {
                // Prepare string.
                $string = '';

                // Create active filters hint.
                if ($lbcocconfig->addactivefiltershint == true) {
                    // Build active filters hint string.
                    if ($lbcocconfig->enablenotshown == true) {
                        $string .= local_boostcoc_get_activefilters_string();
                    } else {
                        $string .= get_string('activefiltershintnotshowdisabled', 'local_boostcoc');
                    }
                }

                // Add line break if both settings are enabled.
                if ($lbcocconfig->addactivefiltershint == true && $lbcocconfig->addchangefilterslink == true) {
                    $string .= html_writer::empty_tag('br');
                }

                // Create change filters link.
                if ($lbcocconfig->addchangefilterslink == true) {
                    // Link target: Site home.
                    if ($lbcocconfig->changefilterslinktarget == HOMEPAGE_SITE) {
                        $url = new moodle_url('/', array('redirect' => 0));

                        // Link target: Dashboard.
                    } else if ($lbcocconfig->changefilterslinktarget == HOMEPAGE_MY) {
                        $url = new moodle_url('/my/');

                        // Should not happen.
                    } else {
                        $url = new moodle_url('/my/');
                    }
                    $string .= html_writer::link($url, get_string('activefiltershintnotshowenabledchangelink', 'local_boostcoc'));
                }

                // Create new navigation node.
                // (use TYPE_COURSE to get the correct indent instead of TYPE_CUSTOM which would be semantically correct).
                $navnode = navigation_node::create($string, null, global_navigation::TYPE_COURSE, null,
                        'localboostcocactivefiltershint', new pix_icon('i/filter', ''));

                // Show the navigation node in Boost's nav drawer.
                $navnode->showinflatnavigation = true;

                // Add the node to the mycourses list in Boost's nav drawer (will be added at the end where we want it to be).
                $mycoursesnode->add_node($navnode);
            }
        }
    }
}
