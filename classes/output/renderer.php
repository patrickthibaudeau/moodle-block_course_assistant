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
namespace block_course_assistant\output;

class renderer extends \plugin_renderer_base {
    /**
     *
     * @param \templatable $branchList
     * @return type
     */
    public function render_content(\templatable $dashboard) {
        $data = $dashboard->export_for_template($this);
        return $this->render_from_template('block_course_assistant/content', $data);
    }
}