<?php
/**
 * *************************************************************************
 * *                           Course Assistant                        **
 * *************************************************************************
 * @package     blocks                                                    **
 * @subpackage  course_assistant                                       **
 * @name        Course Assistant                                       **
 * @copyright   UIT Innovation lab & EAAS                                 **
 * @link                                                                  **
 * @author      Patrick Thibaudeau                                        **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************ */
defined('MOODLE_INTERNAL') || die();

class block_course_assistant extends block_base
{

    public function init()
    {
        $this->title = '<b>' . get_string('pluginname', 'block_course_assistant') . '</b>';
    }

    public function get_content()
    {
        global $CFG, $USER, $DB, $COURSE;

        include_once($CFG->dirroot . '/user/lib.php');

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        $output = $this->page->get_renderer('block_course_assistant');
        $content = new \block_course_assistant\output\content($COURSE->id);

        $html = '<div id="block_course_assistant_container">';
        $html .= $output->render_content($content);
        $html .= '</div>';
        $this->content->text = $html;
        return $this->content;
    }

    // my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats()
    {
        return array(
            'site-index' => false,
            'my' => false,
            'course-view' => true,
            'mod' => false
        );
    }

    public function instance_allow_multiple()
    {
        return false;
    }

    function has_config()
    {
        return true;
    }

}
