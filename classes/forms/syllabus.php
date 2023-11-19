<?php
namespace block_course_assistant;
use media_videojs\external\get_language;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->dirroot/lib/formslib.php");

class syllabus extends \moodleform {

    protected function definition() {
        global $USER, $CFG, $DB, $OUTPUT;

        $formdata = $this->_customdata['formdata'];
        $maxbytes = 10 * 1048576; // 10MB

        $langs = get_string_manager()->get_list_of_translations();

        $mform = & $this->_form;
        $mform->addElement('hidden', 'id');
        $mform->addElement('hidden', 'courseid');
        $mform->addElement('header', 'general', get_string('general'));

        // File
        $mform->addElement('filemanager',
            'syllabus',
            get_string('syllabus', 'block_course_assistant'),
            null,
            array('subdirs' => 0, 'maxbytes' => $maxbytes, 'areamaxbytes' => $maxbytes, 'maxfiles' => 1,
                'accepted_types' => array('.docx','.pdf')));

        // Language used
        $mform->addElement('select',
            'lang',
            get_string('language'),
            $langs);

        // Add File to Section 0
        $mform->addElement('selectyesno',
            'add_file',
            get_string('add_file_section_0', 'block_course_assistant')
            );

        $mform->setType('id', PARAM_INT);
        $mform->setType('courseid', PARAM_INT);
        $mform->setType('lang', PARAM_TEXT);

        $this->add_action_buttons();
        $this->set_data($formdata);
    }

}