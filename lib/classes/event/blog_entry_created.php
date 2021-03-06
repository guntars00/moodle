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

namespace core\event;

/**
 * Event for when a new blog entry is added..
 *
 * @package    core_blog
 * @copyright  2013 Ankit Agarwal
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class blog_entry_created
 *
 * Class for event to be triggered when a blog entry is created.
 *
 * @package    core_blog
 * @copyright  2013 Ankit Agarwal
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class blog_entry_created extends \core\event\base {

    /** @var  \blog_entry A reference to the active blog_entry object. */
    protected $customobject;

    /**
     * Set basic properties for the event.
     */
    protected function init() {
        $this->context = \context_system::instance();
        $this->data['objecttable'] = 'post';
        $this->data['crud'] = 'c';
        $this->data['level'] = self::LEVEL_PARTICIPATING;
    }

    /**
     * Set custom data of the event.
     *
     * @param \blog_entry $data A reference to the active blog_entry object.
     */
    public function set_custom_data($data) {
        $this->customobject = $data;
    }

    /**
     * Returns localised general event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string("evententryadded", "core_blog");
    }

    /**
     * Returns non-localised event description with id's for admin use only.
     *
     * @return string
     */
    public function get_description() {
        return 'Blog entry "'. $this->other['subject']. '" was created by user with id '. $this->userid;
    }

    /**
     * Returns relevant URL.
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/blog/index.php', array('entryid' => $this->objectid, 'userid' => $this->userid));
    }

    /**
     * Does this event replace legacy event?
     *
     * @return string legacy event name
     */
    public static function get_legacy_eventname() {
        return 'blog_entry_added';
    }

    /**
     * Legacy event data if get_legacy_eventname() is not empty.
     *
     * @return \blog_entry
     */
    protected function get_legacy_eventdata() {
        return $this->customobject;
    }

    /**
     * replace add_to_log() statement.
     *
     * @return array of parameters to be passed to legacy add_to_log() function.
     */
    protected function get_legacy_logdata() {
        return array (SITEID, 'blog', 'add', 'index.php?userid='.$this->userid.'&entryid='.$this->objectid, $this->customobject->subject);
    }
}
