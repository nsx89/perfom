<?
/**
 * подключается в /local/php_interface/autoload.php
 */
class CustomFile extends CFile {
    /**
     * изменены строки (по сравнению с CFile::ResizeImageGet):
     * $imageFile = "/".$file["SUBDIR"]."/".$file["FILE_NAME"];
     * $cacheImageFile = "/".$uploadDirName."/resize_cache/".$file["SUBDIR"]."/".$arSize["width"]."_".$arSize["height"]."_".$resizeType.(is_array($arFilters)? md5(serialize($arFilters)): "")."/".$file["FILE_NAME"];
     */
    public static function ResizeImageGet($file, $arSize, $resizeType = BX_RESIZE_IMAGE_PROPORTIONAL, $bInitSizes = false, $arFilters = false, $bImmediate = false, $jpgQuality = false)
    {
        if (!is_array($file) && intval($file) > 0)
        {
            $file = CFile::GetFileArray($file);
        }

        if (!is_array($file) || !array_key_exists("FILE_NAME", $file) || strlen($file["FILE_NAME"]) <= 0)
            return false;

        if ($resizeType !== BX_RESIZE_IMAGE_EXACT && $resizeType !== BX_RESIZE_IMAGE_PROPORTIONAL_ALT)
            $resizeType = BX_RESIZE_IMAGE_PROPORTIONAL;

        if (!is_array($arSize))
            $arSize = array();
        if (!array_key_exists("width", $arSize) || intval($arSize["width"]) <= 0)
            $arSize["width"] = 0;
        if (!array_key_exists("height", $arSize) || intval($arSize["height"]) <= 0)
            $arSize["height"] = 0;
        $arSize["width"] = intval($arSize["width"]);
        $arSize["height"] = intval($arSize["height"]);

        $uploadDirName = COption::GetOptionString("main", "upload_dir", "upload");

        $imageFile = "/".$file["SUBDIR"]."/".$file["FILE_NAME"];
        $arImageSize = false;
        $bFilters = is_array($arFilters) && !empty($arFilters);

        if (
            ($arSize["width"] <= 0 || $arSize["width"] >= $file["WIDTH"])
            && ($arSize["height"] <= 0 || $arSize["height"] >= $file["HEIGHT"])
        )
        {
            if($bFilters)
            {
                //Only filters. Leave size unchanged
                $arSize["width"] = $file["WIDTH"];
                $arSize["height"] = $file["HEIGHT"];
                $resizeType = BX_RESIZE_IMAGE_PROPORTIONAL;
            }
            else
            {
                global $arCloudImageSizeCache;
                $arCloudImageSizeCache[$file["SRC"]] = array($file["WIDTH"], $file["HEIGHT"]);

                return array(
                    "src" => $file["SRC"],
                    "width" => intval($file["WIDTH"]),
                    "height" => intval($file["HEIGHT"]),
                    "size" => $file["FILE_SIZE"],
                );
            }
        }

        $io = CBXVirtualIo::GetInstance();
        $cacheImageFile = "/".$uploadDirName."/resize_cache/".$file["SUBDIR"]."/".$arSize["width"]."_".$arSize["height"]."_".$resizeType.(is_array($arFilters)? md5(serialize($arFilters)): "")."/".$file["FILE_NAME"];

        $cacheImageFileCheck = $cacheImageFile;
        if ($file["CONTENT_TYPE"] == "image/bmp")
            $cacheImageFileCheck .= ".jpg";

        static $cache = array();
        $cache_id = $cacheImageFileCheck;
        if(isset($cache[$cache_id]))
        {
            return $cache[$cache_id];
        }
        elseif (!file_exists($io->GetPhysicalName($_SERVER["DOCUMENT_ROOT"].$cacheImageFileCheck)))
        {
            /****************************** QUOTA ******************************/
            $bDiskQuota = true;
            if (COption::GetOptionInt("main", "disk_space") > 0)
            {
                $quota = new CDiskQuota();
                $bDiskQuota = $quota->checkDiskQuota($file);
            }
            /****************************** QUOTA ******************************/

            if ($bDiskQuota)
            {
                if(!is_array($arFilters))
                    $arFilters = array(
                        array("name" => "sharpen", "precision" => 15),
                    );

                $sourceImageFile = $_SERVER["DOCUMENT_ROOT"].$imageFile;
                $cacheImageFileTmp = $_SERVER["DOCUMENT_ROOT"].$cacheImageFile;
                $bNeedResize = true;
                $callbackData = null;

                foreach(GetModuleEvents("main", "OnBeforeResizeImage", true) as $arEvent)
                {
                    if(ExecuteModuleEventEx($arEvent, array(
                        $file,
                        array($arSize, $resizeType, array(), false, $arFilters, $bImmediate),
                        &$callbackData,
                        &$bNeedResize,
                        &$sourceImageFile,
                        &$cacheImageFileTmp,
                    )))
                        break;
                }

                if ($bNeedResize && CFile::ResizeImageFile($sourceImageFile, $cacheImageFileTmp, $arSize, $resizeType, array(), $jpgQuality, $arFilters))
                {
                    $cacheImageFile = substr($cacheImageFileTmp, strlen($_SERVER["DOCUMENT_ROOT"]));

                    /****************************** QUOTA ******************************/
                    if (COption::GetOptionInt("main", "disk_space") > 0)
                        CDiskQuota::updateDiskQuota("file", filesize($io->GetPhysicalName($cacheImageFileTmp)), "insert");
                    /****************************** QUOTA ******************************/
                }
                else
                {
                    $cacheImageFile = $imageFile;
                }

                foreach(GetModuleEvents("main", "OnAfterResizeImage", true) as $arEvent)
                {
                    if(ExecuteModuleEventEx($arEvent, array(
                        $file,
                        array($arSize, $resizeType, array(), false, $arFilters),
                        &$callbackData,
                        &$cacheImageFile,
                        &$cacheImageFileTmp,
                        &$arImageSize,
                    )))
                        break;
                }
            }
            else
            {
                $cacheImageFile = $imageFile;
            }

            $cacheImageFileCheck = $cacheImageFile;
        }

        if ($bInitSizes && !is_array($arImageSize))
        {
            $arImageSize = CFile::GetImageSize($_SERVER["DOCUMENT_ROOT"].$cacheImageFileCheck);

            $f = $io->GetFile($_SERVER["DOCUMENT_ROOT"].$cacheImageFileCheck);
            $arImageSize[2] = $f->GetFileSize();
        }

        $cache[$cache_id] = array(
            "src" => $cacheImageFileCheck,
            "width" => intval($arImageSize[0]),
            "height" => intval($arImageSize[1]),
            "size" => $arImageSize[2],
        );
        return $cache[$cache_id];
    }

}