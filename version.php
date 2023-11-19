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

$plugin->version   = 2023111800;        // The current plugin version (Date: YYYYMMDDXX)
$plugin->requires  = 2022112800;        // Requires this Moodle version
$plugin->component = 'block_course_assistant';      // Full name of the plugin (used for diagnostics)
$plugin->maturity  = MATURITY_ALPHA;
$plugin->dependencies = array(
    'local_criaai' => 2023111800
);
