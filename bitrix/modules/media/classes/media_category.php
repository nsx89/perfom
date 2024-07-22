<?php
namespace Media;

class MediaCategory
{
	static function getTable() {
		return 'm_media_category';
	}

	static function siteLink($url){
        return "<a target='_blank' href='".MEDIA_FOLDER.'/'.$url."'>".$url."</a>";
    }
}