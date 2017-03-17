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

require_once(dirname(__FILE__) . '/lib.php');

if ($hassiteconfig) {
    // New settings page.
    $page = new admin_settingpage('local_boostcoc',
            get_string('pluginname', 'local_boostcoc', null, true));


    if ($ADMIN->fulltree) {
        // Add general settings heading.
        $page->add(new admin_setting_heading('local_boostcoc/generalsettingsheading',
                get_string('setting_generalsettingsheading', 'local_boostcoc', null, true),
                ''));

        // Create enable not shown courses control widget.
        $page->add(new admin_setting_configcheckbox('local_boostcoc/enablenotshown',
                get_string('setting_enablenotshown', 'local_boostcoc', null, true),
                get_string('setting_enablenotshown_desc', 'local_boostcoc', null, true).'<br />'.
                        get_string('setting_enablenotshowntechnicalhint', 'local_boostcoc', null, true).'<br />'.
                        get_string('setting_enablenotshownperformancehint', 'local_boostcoc'),
                0));

        // Create add usage hint control widget.
        $page->add(new admin_setting_configcheckbox('local_boostcoc/addusagehint',
                get_string('setting_addusagehint', 'local_boostcoc', null, true),
                get_string('setting_addusagehint_desc', 'local_boostcoc', null, true),
                0));
    }


    // Add settings page to the appearance settings category.
    $ADMIN->add('appearance', $page);
}
