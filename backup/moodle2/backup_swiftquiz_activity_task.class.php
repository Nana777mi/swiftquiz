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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/swiftquiz/backup/moodle2/backup_swiftquiz_stepslib.php');

/**
 * Backup task that provides all the settings and steps to perform one complete backup.
 */
class backup_swiftquiz_activity_task extends backup_activity_task {

    /**
     * This should define settings. Not used at the moment.
     */
    protected function define_my_settings() {

    }

    /**
     * Define (add) particular steps this activity can have.
     */
    protected function define_my_steps() {
        $this->add_step(new backup_swiftquiz_activity_structure_step('swiftquiz_structure', 'swiftquiz.xml'));
        // TODO: This might not be necessary in future Moodle versions, if discussed subclass is added.
        $this->add_step(new backup_calculate_question_categories('activity_question_categories'));
        $this->add_step(new backup_delete_temp_questions('clean_temp_questions'));
    }

    /**
     * Code the transformations to perform in the activity in order to get transportable (encoded) links.
     * @param string $content
     * @return string of content with the URLs encoded
     */
    static public function encode_content_links($content) {
        global $CFG;
        $base = preg_quote($CFG->wwwroot, '/');
        // Link to the list of swiftquizes.
        $search = "/(" . $base . "\/mod\/swiftquiz\/index.php\?id\=)([0-9]+)/";
        $content = preg_replace($search, '$@swiftquizINDEX*$2@$', $content);
        // Link to swiftquiz view by moduleid.
        $search = "/(" . $base . "\/mod\/swiftquiz\/view.php\?id\=)([0-9]+)/";
        $content = preg_replace($search, '$@swiftquizVIEWBYID*$2@$', $content);
        return $content;
    }

}
