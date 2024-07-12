<?php
namespace Media;

class Media
{
	static function getTable() {
		return 'm_media';
	}

	static function siteLink($url){
        return "<a target='_blank' href='".MEDIA_FOLDER.'/'.$url."'>".$url."</a>";
    }
}