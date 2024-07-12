<?php
namespace Media;

class MediaTypes
{
	static function getTable() {
		return 'm_media_types';
	}

	static function getList() {
		global $DB;

		$data = [];

		$table = self::getTable();
		$res = $DB->Query("SELECT * FROM `{$table}` ORDER BY sort ASC, name ASC, id ASC");
	    while ($row = $res->fetch()) {
	        $data[] = $row;
	    }
	    return $data;
	}
}