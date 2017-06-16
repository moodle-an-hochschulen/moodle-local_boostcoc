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
 * Local plugin "Boost course overview on campus" - Local Library
 *
 * @package    local_boostcoc
 * @copyright  2017 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Get a string of the filters which are currently active in block_course_overview_campus
 *
 * @return string
 */
function local_boostcoc_get_activefilters_string() {
    // Get list of active filters which is remembered by block_course_overview_campus for us.
    $activefilters = json_decode(get_user_preferences('local_boostcoc-activefilters', '[]'), false, 2);

    // Check if there were problems decoding the JSON string or if we did get anything else than an array from JSON.
    if (json_last_error() != JSON_ERROR_NONE || !is_array($activefilters)) {
        // Pretend that everything is ok, we don't want to break the UI.
        return get_string('activefiltershintnotshowenablednoactivefilters', 'local_boostcoc');
    }

    // Clean the array elements, just to be sure that there was no malicious stuff in the JSON array.
    $activefilterscleaned = array();
    foreach ($activefilters as $filter) {
        $activefilterscleaned[] = clean_param($filter, PARAM_ALPHA);
    }

    // If any filter is active.
    if (count($activefilterscleaned) > 0) {
        // Prepare string.
        $activefiltersstring = get_string('activefiltershintnotshowenabledactivefilters', 'local_boostcoc');
        $activefiltersstring .= html_writer::empty_tag('br');

        // Mapping from element IDs to configured filter names.
        $map['filterterm'] = format_string(get_config('block_course_overview_campus', 'termcoursefilterdisplayname'));
        $map['filtercategory'] = format_string(get_config('block_course_overview_campus', 'categorycoursefilterdisplayname'));
        $map['filtertoplevelcategory'] = format_string(get_config('block_course_overview_campus',
                'toplevelcategorycoursefilterdisplayname'));
        $map['filterteacher'] = format_string(get_config('block_course_overview_campus', 'teachercoursefilterdisplayname'));
        $map['hidecourses'] = get_string('activefiltershiddencourses', 'local_boostcoc');

        // Put real filter names into array.
        $activefiltersrealnames = array();
        foreach ($activefilterscleaned as $filter) {
            $activefiltersrealnames[] = $map[$filter];
        }

        // Concat real filter names.
        $activefiltersstring .= implode(', ', $activefiltersrealnames);

        // Return string.
        return $activefiltersstring;

        // If no filter is active.
    } else {
        return get_string('activefiltershintnotshowenablednoactivefilters', 'local_boostcoc');
    }
}


/**
 * Get an array of the courses which are currently not shown in block_course_overview_campus
 *
 * @return array
 */
function local_boostcoc_get_notshowncourses() {
    // Get list of not shown courses which is remembered by block_course_overview_campus for us.
    $notshowncourses = json_decode(get_user_preferences('local_boostcoc-notshowncourses', '[]'), false, 2);

    // Check if there were problems decoding the JSON string or if we did get anything else than an array from JSON.
    if (json_last_error() != JSON_ERROR_NONE || !is_array($notshowncourses)) {
        // Pretend that there aren't any not shown courses, we don't want to break the UI.
        return array();
    }

    // Clean the array elements, just to be sure that there was no malicious stuff in the JSON array.
    $notshowncoursescleaned = array();
    foreach ($notshowncourses as $course) {
        $notshowncoursescleaned[] = clean_param($course, PARAM_INT);
    }

    // Return array.
    return $notshowncoursescleaned;
}
