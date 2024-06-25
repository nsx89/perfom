<?

header('Content-type:application/json;charset=utf-8');

$type = $_REQUEST['type'];
$folder = $_REQUEST['folder'];
$stat = $_REQUEST['stat'];

if(!$type) {
    echo 'Bad request';
    die();
}

if(!file_exists($_SERVER["DOCUMENT_ROOT"].'/upload/dealers/temp')) {
    mkdir($_SERVER["DOCUMENT_ROOT"].'/upload/dealers/temp');
}

/**
 * загрузить файл
 *
 * проверяем, есть ли уже постоянная папка (названная по id дилера)
 *
 * если есть - записываем туда
 *
 * при модерации все файлы записываем в temp
 *
 * нет - создаем временный каталог в temp и туда записываем,
 * после сохранения дилера переносим файлы в постоянную папку
 */

if($type == 'upload') {
    try {
        if (
            !isset($_FILES['file']['error']) ||
            is_array($_FILES['file']['error'])
        ) {
            throw new RuntimeException('Неверные параметры.');
        }

        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('Файл не отправлен.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Превышен размер файла.');
            default:
                throw new RuntimeException('Неизвестная ошибка.');
        }

        $path = $_SERVER["DOCUMENT_ROOT"].'/img/dealers/'.$folder;
        $path_temp = $_SERVER["DOCUMENT_ROOT"].'/upload/dealers/temp/'.$folder;


        if(file_exists($path)) {
            $dest = $path;
            $img_url = '/img/dealers/'.$folder;
        } elseif(file_exists($path_temp) || mkdir($path_temp)) {
            $dest = $path_temp;
            $img_url = '/upload/dealers/temp/'.$folder;
        }

        if($dest) {
            $filename = str_replace(Array(' ','(',')'),Array('_','',''),$_FILES['file']['name']);
            $filename = sprintf('%s_%s', uniqid(), $filename);

            if (!move_uploaded_file(
                $_FILES['file']['tmp_name'],
                $dest.'/'.$filename
            )) {
                throw new RuntimeException('Ошибка перемещения загруженного файла');
            }

            // All good, send the response
            echo json_encode([
                'status' => 'ok',
                'filename' => $img_url.'/'.$filename
            ]);
        }


    } catch (RuntimeException $e) {
        // Something went wrong, send the err message as JSON
        //  http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);

    }
}

/**
 * удалить файл
 *
 * проверяем, если файл во временной папке в temp - удаляем,
 * если в постоянной папке - просто возвращаем ok, удаяем после нажатия кнопки Сохранить
 */
if($type == 'remove') {
    $filename = $_REQUEST['filename'];
    if(!$folder || !$filename) {
        echo json_encode([
            'status' => 'ok',
        ]);
        die();
    }

    if(file_exists($_SERVER["DOCUMENT_ROOT"].$filename)) {
        echo json_encode([
            'status' => 'ok',
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Файл или директория не найдены'
        ]);
    }


    /*$path = $_SERVER["DOCUMENT_ROOT"].'/upload/dealers/temp/'.$folder;
    $path_loaded = $_SERVER["DOCUMENT_ROOT"].'/upload/dealers/'.$folder;

    if(file_exists($path)) {
        if(file_exists($path.'/'.$filename)) {
            if(unlink($path.'/'.$filename)) {
                if(count(scandir($path)) <= 2) {
                    rmdir($path);
                }
                echo json_encode([
                    'status' => 'ok',
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Не удалось удалить файл'
                ]);
            }
        } else if(file_exists($path_loaded.'/'.$filename)) {
            //если файл уже загружен в постоянную папку, удаляем после сохранения
            echo json_encode([
                'status' => 'ok',
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Файл не найден'
            ]);
        }

    } else if(file_exists($path_loaded) && file_exists($path_loaded.'/'.$filename) || $stat == 'mod') {
        //если файл уже загружен в постоянную папку, удаляем после сохранения;

        // если удаляем в режиме модерации из постоянной папки, файл из постоянной папки не удаляется
        // после отправки на модерацию или  промежуточного сохранения модерируемой инфы
        // все необходимые файлы копируются в папку с id элемнта на модерации
        echo json_encode([
            'status' => 'ok',
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Директория не найдена'
        ]);
    }*/


}

