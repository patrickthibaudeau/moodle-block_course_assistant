<?php

namespace block_course_assistant\output;

class content implements \renderable, \templatable
{

    /**
     * @var $courseid
     */
    protected $courseid;

    public function __construct($courseid)
    {
        $this->courseid = $courseid;
    }

    /**
     *
     * @param \renderer_base $output
     * @return type
     * @global \moodle_database $DB
     * @global type $USER
     * @global type $CFG
     */
    public function export_for_template(\renderer_base $output)
    {
        global $CFG, $USER, $DB;

        $context = \context_course::instance($this->courseid);
        $course = $DB->get_record('course', ['id' => $this->courseid], 'idnumber');

        $idnumber = explode('_', $course->idnumber);
        if (isset($idnumber[10])) {
            $format = $idnumber[10];
        } else {
            $format = '';
        }

        // Can the user edit
        $can_edit = false;
        if (has_capability('block/course_assistant:add_syllabus', $context)) {
            $can_edit = true;
        }

        // Get files
        $file_name = '';
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'block_course_assistant', 'syllabus', $this->courseid);
        foreach ($files as $f) {
            // $f is an instance of stored_file
            if ($f->get_filename() != '.') {
                $file_name = $f->get_filename();
            }
        }

        $data = [
            'courseid' => $this->courseid,
            'can_edit' => $can_edit,
            'file_name' => $file_name,
            'first_name' => $USER->firstname,
            'idnumber' => $course->idnumber,
            'format' => $format,
        ];
        return $data;
    }
}