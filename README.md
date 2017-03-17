moodle-local_boostcoc
=====================

Moodle plugin which adds support for filtering courses with block_course_overview_campus to the mycourses list in Boost's nav drawer.


Requirements
------------

This plugin requires Moodle 3.2+


Installation
------------

Install the plugin like any other plugin to folder
/local/local_boostcoc

See http://docs.moodle.org/en/Installing_plugins for details on installing Moodle plugins


Usage & Settings
----------------

After installing local_boostcoc, the plugin does not do anything to Moodle yet.
To configure the plugin and its behaviour, please visit Site administration -> Appearance -> Boost navdrawer course overview on campus.

There, you find one section:

### 1. TODO

TODO


Themes
------

local_boostcoc is designed to work with theme_boost or child themes of theme_boost.
It does not work with other themes which are not based on Boost.


Required other plugins
----------------------

local_boostcoc is designed to work with block_course_overview_campus which is published on https://moodle.org/plugins/block_course_overview_campus and https://github.com/moodleuulm/moodle-block_course_overview_campus.
It does not work without this plugin and it does especially not work with block_course_overview from Moodle core.

local_boostcoc also re-uses some code from local_boostnavigation which is published on https://moodle.org/plugins/local_boostnavigation and https://github.com/moodleuulm/moodle-local_boostnavigation.
Although you might not need the functionality of local_boostnavigation, local_boostcoc does not work without this plugin.


Motivation for this plugin
--------------------------

Since the release of Moodle 3.2, Moodle core ships with a shiny new theme called "Boost". While Boost does many things right and better than the legacy theme Clean, it also has some fixed behaviours which don't make sense for all Moodle installations. One of these behaviours is the fact that the mycourses list in the nav drawer (the menu which appears when you click on the hamburger menu button) is non-collapsible, always contains all of my courses and can hardly be configured by administrators.

Luckily, Moodle provides the *_extend_navigation() hook which allows plugin developers to fumble with Moodle's global navigation tree at runtime. This plugin leverages this hook and does its best to add support for filtering and hiding courses with block_course_overview_campus to the mycourses list in the nav drawer.


Further information
-------------------

local_boostcoc is found in the Moodle Plugins repository: http://moodle.org/plugins/view/local_boostcoc

Report a bug or suggest an improvement: https://github.com/moodleuulm/moodle-local_boostcoc/issues


Feature proposals to this plugin
--------------------------------

Due to limited resources, the functionality of local_boostcoc are primarily implemented for our own local needs and published as-is to the community. We expect that members of the community will have other needs and would love to see them solved by this plugin.

We are always interested to read about your feature proposals on https://github.com/moodleuulm/moodle-local_boostcoc/issues or even get a pull request from you on https://github.com/moodleuulm/moodle-local_boostcoc/pulls, but please accept that we can handle your issues only as feature _proposals_ and not as feature _requests_.


Moodle release support
----------------------

Due to limited resources, local_boostcoc is only maintained for the most recent major release of Moodle. However, previous versions of this plugin which work in legacy major releases of Moodle are still available as-is without any further updates in the Moodle Plugins repository.

There may be several weeks after a new major release of Moodle has been published until we can do a compatibility check and fix problems if necessary. If you encounter problems with a new major release of Moodle - or can confirm that local_boostcoc still works with a new major relase - please let us know on https://github.com/moodleuulm/moodle-local_boostcoc/issues


Right-to-left support
---------------------

This plugin has not been tested with Moodle's support for right-to-left (RTL) languages.
If you want to use this plugin with a RTL language and it doesn't work as-is, you are free to send us a pull request on
github with modifications.


PHP7 Support
------------

Since Moodle 3.0, Moodle core basically supports PHP7.
Please note that PHP7 support is on our roadmap for this plugin, but it has not yet been thoroughly tested for PHP7 support and we are still running it in production on PHP5.
If you encounter any success or failure with this plugin and PHP7, please let us know.


Copyright
---------

Ulm University
kiz - Media Department
Team Web & Teaching Support
Alexander Bias
