<?php
require_once("../../config.php");

global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$syllabi = $DB->get_records('block_course_assistant', [], 'timecreated DESC');

if ($syllabi) {
    foreach ($syllabi as $s) {
        $context = context_course::instance($s->courseid);
        $course = $DB->get_record('course', ['id' => $s->courseid]);
        // Get files
        $file_name = '';
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'block_course_assistant', 'syllabus', $s->courseid);
        foreach ($files as $f) {
            // $f is an instance of stored_file
            if ($f->get_filename() != '.') {
                $file_name = $f->get_filename();
            }
        }
        $file_url = moodle_url::make_pluginfile_url(
            $context->id,
            'block_course_assistant',
            'syllabus',
            $s->courseid,
            '/',
            $file_name,
            false
        );
//        echo $file_name . ':' . $s->courseid . ' ' .  $course->idnumber . ' ' . $file_url;
        echo $course->idnumber . ':' . $file_url . '<br>';
    }

}



