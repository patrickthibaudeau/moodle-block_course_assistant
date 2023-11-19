<?php
require_once("../../config.php");

global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

require_once($CFG->dirroot . "/blocks/al_course_assistant/classes/forms/syllabus.php");

$courseid = required_param('courseid', PARAM_INT);

$context = context_course::instance($courseid);

// Get files
$file_name = '';
$fs = get_file_storage();
$files = $fs->get_area_files($context->id, 'block_course_assistant', 'syllabus', $courseid);
foreach ($files as $f) {
    // $f is an instance of stored_file
    if ($f->get_filename() != '.') {
        $file_name = $f->get_filename();
    }
}

$file_url = moodle_url::make_pluginfile_url($context->id, 'block_course_assistant', 'syllabus', $courseid, '/', $file_name, false);

header("Location: $file_url");
die();
