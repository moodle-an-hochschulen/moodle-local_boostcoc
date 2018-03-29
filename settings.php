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
 * Local plugin "Boost course overview on campus" - Settings
 *
 * @package    local_boostcoc
 * @copyright  2017 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/lib.php');

if ($hassiteconfig) {
    // New settings page.
    $page = new admin_settingpage('local_boostcoc',
            get_string('pluginname', 'local_boostcoc', null, true));


    if ($ADMIN->fulltree) {
        // Add general functionality heading.
        $page->add(new admin_setting_heading('local_boostcoc/generalfunctionalityheading',
                get_string('setting_generalfunctionalityheading', 'local_boostcoc', null, true),
                ''));

        // Create enable not shown courses control widget.
        $page->add(new admin_setting_configcheckbox('local_boostcoc/enablenotshown',
                get_string('setting_enablenotshown', 'local_boostcoc', null, true),
                get_string('setting_enablenotshown_desc', 'local_boostcoc', null, true).'<br />'.
                        get_string('setting_enablenotshowntechnicalhint', 'local_boostcoc', null, true).'<br />'.
                        get_string('setting_enablenotshownperformancehint', 'local_boostcoc'),
                0));

        // Create disable in-progress filter control widget.
        $page->add(new admin_setting_configcheckbox('local_boostcoc/disableinprogressfilter',
                get_string('setting_disableinprogressfilter', 'local_boostcoc', null, true),
                get_string('setting_disableinprogressfilter_desc', 'local_boostcoc', null, true).'<br />'.
                        get_string('setting_disableinprogressfilterperformancehint', 'local_boostcoc'),
                0));

        // Add filter status heading.
        $page->add(new admin_setting_heading('local_boostcoc/filterstatusheading',
                get_string('setting_filterstatusheading', 'local_boostcoc', null, true),
                ''));

        // Create add active filters hint control widget.
        $page->add(new admin_setting_configcheckbox('local_boostcoc/addactivefiltershint',
                get_string('setting_addactivefiltershint', 'local_boostcoc', null, true),
                get_string('setting_addactivefiltershint_desc', 'local_boostcoc', null, true),
                0));

        // Create add change filters link control widget.
        $page->add(new admin_setting_configcheckbox('local_boostcoc/addchangefilterslink',
                get_string('setting_addchangefilterslink', 'local_boostcoc', null, true),
                get_string('setting_addchangefilterslink_desc', 'local_boostcoc', null, true),
                0));

        // Create change filters link target control widget.
        $changefilterslinktarget[HOMEPAGE_MY] = get_string('myhome', 'core', null, false);
                // Don't use string lazy loading here because the string will be directly used and
                // would produce a PHP warning otherwise.
        $changefilterslinktarget[HOMEPAGE_SITE] = get_string('sitehome', 'core', null, true);
        $page->add(new admin_setting_configselect('local_boostcoc/changefilterslinktarget',
                get_string('setting_changefilterslinktarget', 'local_boostcoc', null, true),
                get_string('setting_changefilterslinktarget_desc', 'local_boostcoc', null, true),
                $changefilterslinktarget[HOMEPAGE_MY],
                $changefilterslinktarget));
    }


    // Add settings page to the appearance settings category.
    $ADMIN->add('appearance', $page);
}
