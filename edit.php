<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Course section color settings page.
 *
 * @package    local_sectioncolors
 * @copyright  2026 Rino Vincenti
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/course/lib.php');
$id = required_param('id', PARAM_INT);
$course = get_course($id);
require_login($course);
$context = context_course::instance($id);
require_capability('moodle/course:update', $context);
$PAGE->set_url(new moodle_url('/local/sectioncolors/edit.php', ['id' => $id]));
$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title(get_string('pluginname', 'local_sectioncolors'));
$PAGE->set_heading(format_string($course->fullname));
$returnurl = new moodle_url('/course/view.php', ['id' => $id]);
$modinfo = get_fast_modinfo($course);
$format = course_get_format($course);
$sections = [];
foreach (\local_sectioncolors\section_order::get($modinfo) as $section) {
    $label = format_string($format->get_section_name($section), true, ['context' => $context]);
    if ($section->component === 'mod_subsection') {
        $label = get_string('subsection', 'local_sectioncolors', $label);
    }
    $sections[$section->id] = $label;
}
$values = [];
foreach ($DB->get_records('local_sectioncolors', ['courseid' => $id]) as $row) {
    $values[$row->sectionid] = $row->color;
}
$form = new \local_sectioncolors\form\colors(null,
    ['courseid' => $id, 'sections' => $sections, 'values' => $values]);
if ($form->is_cancelled()) {
    redirect($returnurl);
}
if ($data = $form->get_data()) {
    require_sesskey();
    // Only accept section IDs belonging to the authenticated course; ignore arbitrary POST keys.
    $transaction = $DB->start_delegated_transaction();
    foreach ($sections as $sectionid => $label) {
        $color = $data->{'color_' . $sectionid} ?? '';
        $conditions = ['courseid' => $id, 'sectionid' => $sectionid];
        if ($color === '') {
            $DB->delete_records('local_sectioncolors', $conditions);
        } else if (isset(\local_sectioncolors\palette::COLORS[$color])) {
            $record = $DB->get_record('local_sectioncolors', $conditions);
            if ($record) {
                $record->color = $color;
                $DB->update_record('local_sectioncolors', $record);
            } else {
                $DB->insert_record('local_sectioncolors', (object)($conditions + ['color' => $color]));
            }
        }
    }
    $transaction->allow_commit();
    redirect($returnurl, get_string('saved', 'local_sectioncolors'), null, \core\output\notification::NOTIFY_SUCCESS);
}
$PAGE->requires->js_call_amd('local_sectioncolors/colorpicker', 'init',
    [\local_sectioncolors\palette::COLORS]);
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'local_sectioncolors'));
echo html_writer::tag('p', get_string('instructions', 'local_sectioncolors'));
$form->display();
echo $OUTPUT->footer();
