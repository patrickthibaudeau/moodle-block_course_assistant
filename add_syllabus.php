<?php
require_once("../../config.php");
require_once("locallib.php");

global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

require_once($CFG->dirroot . "/blocks/al_course_assistant/classes/forms/syllabus.php");

$courseid = required_param('courseid', PARAM_INT);

require_login($courseid, FALSE);

$context = context_course::instance($courseid);

if (!has_capability('block/al_course_assistant:add_syllabus', $context)) {
    redirect($CFG->wwwroot . "/course/view.php?id=$courseid",
        get_string('no_permission', 'block_course_assistant'),
        0, \core\notification::WARNING);
}

$PAGE->set_url(new moodle_url("/blocks/al_course_assistant/add_syllabus.php?id=$courseid"));
$PAGE->set_context($context);
$PAGE->set_title(get_string('add_syllabus', 'block_course_assistant'));
$PAGE->set_heading(get_string('add_syllabus', 'block_course_assistant'));

$maxbytes = 10 * 1048576; // 10MB

if (!$formdata = $DB->get_record('block_course_assistant', ['courseid' => $courseid])) {
    $formdata = new stdClass();
    $formdata->courseid = $courseid;
}

$formdata->add_file = 0;

$draftitemid = file_get_submitted_draft_itemid('syllabus');

file_prepare_draft_area($draftitemid, $context->id,
    'block_course_assistant',
    'syllabus',
    $courseid,
    array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));

$formdata->syllabus = $draftitemid;

$mform = new block_course_assistant\syllabus(null, array('formdata' => $formdata));

if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/course/view.php?id=' . $courseid);
} else if ($data = $mform->get_data()) {

    // Get file name
    $usercontext = context_user::instance($USER->id);
    $fs = get_file_storage();
    // Get file from user draft
    $draftfiles = $fs->get_area_files($usercontext->id, 'user', 'draft', $data->syllabus, 'id');

    foreach($draftfiles as $file) {
        if ($file->get_filename() != '.') {
            $data->filename = $file->get_filename();
        }
    }

    file_save_draft_area_files($data->syllabus,
        $context->id,
        'block_course_assistant',
        'syllabus',
        $data->courseid, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1));

    $data->usermodified = $USER->id;
    $data->timemodifed = time();
    if ($data->id) {
        $DB->update_record('block_course_assistant', $data);
    } else {
        $data->timecreated = time();
        $DB->insert_record('block_course_assistant', $data);
    }

    if ($data->add_file) {
        $file_url = moodle_url::make_pluginfile_url($context->id, 'block_course_assistant', 'syllabus', $data->courseid, '/', $data->filename, false);
        $url_params = [];
        $url_params['courseid'] = $data->courseid;
        $url_params['shortname'] = $data->filename;
        $url_params['intro'] = '';
        $url_params['showdescription'] = true;
        $url_params['externalurl'] = $file_url;
        $url_params['availabilityconditionsjson'] = '';
        $url_params['completionunlocked'] = 1;
        $url_params['completion'] = 0;
        $url_params['completionview'] = 1;
        $url_params['completionexpected'] = '';

        create_url_resource($url_params);

    }

    redirect(new moodle_url("/course/view.php?id=$data->courseid"));
} else {
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.
    //Set default data (if any)
    $mform->set_data($mform);
}

echo $OUTPUT->header();
//**********************
//*** DISPLAY HEADER ***
//
$mform->display();
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();