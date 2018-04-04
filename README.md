moodle-local_boostcoc
=====================

[![Build Status](https://travis-ci.org/moodleuulm/moodle-local_boostcoc.svg?branch=master)](https://travis-ci.org/moodleuulm/moodle-local_boostcoc)

Moodle plugin which adds support for filtering courses with block_course_overview_campus to the mycourses list in Boost's nav drawer


Requirements
------------

This plugin requires Moodle 3.4+


Motivation for this plugin
--------------------------

Since the release of Moodle 3.2, Moodle core ships with a shiny new theme called "Boost". While Boost does many things right and better than the legacy theme Clean, it also has some fixed behaviours which don't make sense for all Moodle installations. One of these behaviours is the fact that the mycourses list in the nav drawer (the menu which appears when you click on the hamburger menu button) is non-collapsible, always contains all of my courses and can hardly be configured by administrators.


Installation
------------

Install the plugin like any other plugin to folder
/local/local_boostcoc

See http://docs.moodle.org/en/Installing_plugins for details on installing Moodle plugins


Required other plugins
----------------------

local_boostcoc is designed to work with block_course_overview_campus which is published on https://moodle.org/plugins/block_course_overview_campus and https://github.com/moodleuulm/moodle-block_course_overview_campus.
It does not work without this plugin and it does especially not work with block_course_overview from Moodle core.

local_boostcoc also re-uses some code from local_boostnavigation which is published on https://moodle.org/plugins/local_boostnavigation and https://github.com/moodleuulm/moodle-local_boostnavigation.
Although you might not need the functionality of local_boostnavigation, local_boostcoc does not work without this plugin.


Usage & Settings
----------------

After installing the plugin, it does not do anything to Moodle yet.

To configure the plugin and its behaviour, please visit:
Site administration -> Appearance -> Boost navdrawer course overview on campus.

There, you find two sections:

### 1. General functionality

Enabling one or both of these settings will modify the mycourses list in Boost's nav drawer to reflect the list of courses shown in block_course_overview_campus according to the user's current filter settings and to the courses which the user has marked as hidden. Please see also the notes for uniformity of the mycourses lists below.

### 2. Filter status

With the settings in this section, you can add a node to the end of the mycourses list in Boost's nav drawer telling the user why the mycourses list looks as it is (e.g. which filters and if hidden courses produced the current mycourses list) and / or telling the user where to change the current filters and hidden courses.


Uniformity of the mycourses lists
---------------------------------

The mycourses list which is added to the nav drawer by Moodle core and which are shown in block_course_overview_campus are created and configured independently. To help you to create a uniform look & feel of both mycourses lists, we want to give you some advice:

### List completeness

In block_course_overview_campus, you will see all courses where you are enrolled, no matter how old or new they are.

In Moodle core and thus in the nav drawer, there are only courses shown which are "in progress", which means that courses which haven't started yet (according to the course start date), which are already finished (according to the course end date) or which are completed (according to the user's course completion) are not shown. This behaviour was introduced in MDL-58136 and isn't configurable yet, unfortunately. 

To make the list of courses in the Moodle nav drawer complete again, you have to enable the setting "Disable Moodle built-in in-progress filter" to override Moodle's internal filter.


### List length

In block_course_overview_campus, you will see all courses where you are enrolled, no matter how long this list will get.

In Moodle core and thus in the nav drawer, there is a setting navcourselimit (introduced in MDL-59140) which limits the list of courses and which can be configured on/admin/settings.php?section=navigation.

To fully match the lists of courses in block_course_overview_campus and in the Moodle nav drawer, please set navcourselimit to a very high number (like 10000) to avoid any limiting in list length.


### Course name style

In block_course_overview_campus, you can use the setting firstrowcoursename to control if you want to see a course's full name or short name in the first line of the course list entries.

In Moodle core and thus in the nav drawer, the same control is possible with the setting navshowfullcoursenames which can be configured on /admin/settings.php?section=navigation.


### List sorting

In block_course_overview_campus, the mycourses list is currently sorted alphabetically (ignoring the fact if courses are visible or invisible) and, if you have enabled block_course_overview_campus's prioritizemyteachedcourses setting, will put courses in which the user has a teacher role at the beginning of his course list.

In Moodle core and thus in the nav drawer, the sorting of the mycourses list is controlled by the setting navsortmycoursessort which can be configured on /admin/settings.php?section=navigation. While this setting offers four options for sorting the courses, there is a hardcoded pre-sorting which always puts invisible courses at the end of the list.

We plan to improve this sorting mismatch in a later release of local_boostcoc. Until then, for a best possible match of list sortings we recommend
1. to set navsortmycoursessort = "Course full name" in Moodle core,
2. to set prioritizemyteachedcourses = No in block_course_overview_campus.

Optionally, if you can and want to add core hacks to Moodle core, you can apply this patch to prevent Moodle core from putting invisible courses at the end of the mycourses list in the nav drawer which will make the course list sortings really equal:

```
--- a/lib/navigationlib.php
+++ b/lib/navigationlib.php
@@ -2883,7 +2883,7 @@ class global_navigation extends navigation_node {
      */
     protected function load_courses_enrolled() {
         global $CFG;
-        $sortorder = 'visible DESC';
+        $sortorder = 'fullname ASC';
         // Prevent undefined $CFG->navsortmycoursessort errors.
         if (empty($CFG->navsortmycoursessort)) {
             $CFG->navsortmycoursessort = 'sortorder';
```


### List title

In block_course_overview_campus, you can use the setting blocktitle to control the title of the block which is set to "Course overview" by default, but may be set to other strings like "My courses".

In Moodle core and thus in the nav drawer, the mycourses list's navigation node gets the title "My courses". This string comes from the "core" language pack and has the identifier "mycourses". If you want to align the navigation node's title, you can change the string on /admin/tool/customlang/index.php.


How this plugin works
---------------------

Luckily, Moodle provides the *_extend_navigation() hook which allows plugin developers to fumble with Moodle's global navigation tree at runtime. This plugin leverages this hook and does its best to add support for filtering and hiding courses with block_course_overview_campus to the mycourses list in the nav drawer.


Theme support
-------------

This plugin is designed to work with Moodle core's Boost theme or child themes of Boost.
It does not work with other themes which are not based on Boost.


Plugin repositories
-------------------

This plugin is published and regularly updated in the Moodle plugins repository:
http://moodle.org/plugins/view/local_boostcoc

The latest development version can be found on Github:
https://github.com/moodleuulm/moodle-local_boostcoc


Bug and problem reports / Support requests
------------------------------------------

This plugin is carefully developed and thoroughly tested, but bugs and problems can always appear.

Please report bugs and problems on Github:
https://github.com/moodleuulm/moodle-local_boostcoc/issues

We will do our best to solve your problems, but please note that due to limited resources we can't always provide per-case support.


Feature proposals
-----------------

Due to limited resources, the functionality of this plugin is primarily implemented for our own local needs and published as-is to the community. We are aware that members of the community will have other needs and would love to see them solved by this plugin.

Please issue feature proposals on Github:
https://github.com/moodleuulm/moodle-local_boostcoc/issues

Please create pull requests on Github:
https://github.com/moodleuulm/moodle-local_boostcoc/pulls

We are always interested to read about your feature proposals or even get a pull request from you, but please accept that we can handle your issues only as feature _proposals_ and not as feature _requests_.


Moodle release support
----------------------

Due to limited resources, this plugin is only maintained for the most recent major release of Moodle. However, previous versions of this plugin which work in legacy major releases of Moodle are still available as-is without any further updates in the Moodle Plugins repository.

There may be several weeks after a new major release of Moodle has been published until we can do a compatibility check and fix problems if necessary. If you encounter problems with a new major release of Moodle - or can confirm that this plugin still works with a new major relase - please let us know on Github.

If you are running a legacy version of Moodle, but want or need to run the latest version of this plugin, you can get the latest version of the plugin, remove the line starting with $plugin->requires from version.php and use this latest plugin version then on your legacy Moodle. However, please note that you will run this setup completely at your own risk. We can't support this approach in any way and there is a undeniable risk for erratic behavior.


Translating this plugin
-----------------------

This Moodle plugin is shipped with an english language pack only. All translations into other languages must be managed through AMOS (https://lang.moodle.org) by what they will become part of Moodle's official language pack.

As the plugin creator, we manage the translation into german for our own local needs on AMOS. Please contribute your translation into all other languages in AMOS where they will be reviewed by the official language pack maintainers for Moodle.


Right-to-left support
---------------------

This plugin has not been tested with Moodle's support for right-to-left (RTL) languages.
If you want to use this plugin with a RTL language and it doesn't work as-is, you are free to send us a pull request on Github with modifications.


PHP7 Support
------------

Since Moodle 3.4 core, PHP7 is mandatory. We are developing and testing this plugin for PHP7 only.


Copyright
---------

Ulm University
kiz - Media Department
Team Web & Teaching Support
Alexander Bias
