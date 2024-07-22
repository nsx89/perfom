<?php
namespace Media;

class MediaFilter
{
	static function getTable() {
		return 'm_media_filter';
	}

	static function siteLink($url){
        return "<a target='_blank' href='".MEDIA_FOLDER.'/'.$url."'>".$url."</a>";
    }
}