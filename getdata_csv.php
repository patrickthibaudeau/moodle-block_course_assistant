<?php
require_once("../../config.php");

global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$syllabi = $DB->get_records('block_course_assistant', [], 'timecreated DESC');

header('Content-Description: File Transfer');
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="alca_data.csv"');

if ($syllabi) {
    $delimiter = ",";
    $csv_file = fopen('php://output', 'w');
    $fields = array('filename', 'courseid', 'idnumber', 'url');
    fputcsv($csv_file, $fields, $delimiter);
    $line_data = '';

    foreach ($syllabi as $s) {
        $context = context_course::instance($s->courseid);
        $course = $DB->get_record('course', ['id' => $s->courseid]);
        // Get files
        $file_name = '';
        $fs = get_file_storage();
        $files = $fs->get_area_files(
            $context->id,
            'block_course_assistant',
            'syllabus',
            $s->courseid
        );
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
        $line_data = array($file_name, $s->courseid, $course->idnumber, $file_url);
        fputcsv($csv_file, $line_data);
    }

    fclose($csv_file);
}



