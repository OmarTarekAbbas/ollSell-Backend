<?php


/**
 * @result get files from server
 * param $fileName => name file in server
 * param $path => path folder in server
 * param $fileNameServer => name folder we saved in it
 */
function getFile($fileName, $path, $fileNameServer)
{
    return asset($path . '/' . $fileNameServer . '/' . $fileName);
}
/**
 * @result get files from server
 * param $fileName => name file in server
 * param $path => path folder in server
 * param $fileNameServer => name folder we saved in it
 */
function getFileWithoutURL($fileName, $path, $fileNameServer)
{
    return  $path . '/' . $fileNameServer . '/' . $fileName;
}

/**
 * @result get folder name in server will search in it
 * param $data => object will get name folder for it
 */
function getFileNameServer($data)
{
    $name = null;
    if (isset($data->category_type)) {
        $name = strtolower(basename($data->category_type));
        if (strpos($name, '\\') !== false) {
            $name = explode('\\', $name);
            $name = $name[count($name) - 1];
        }
    }
    return $name ? $name . '/' . $data->category_id : null;
}

/**
 * @result get folder name in server will save in it
 * param $model => path model
 * param $id => id row we will save it
 */
function createFileNameServer($model, $id)
{
    $name = strtolower(basename($model));
    if (strpos($name, '\\') !== false) {
        $name = explode('\\', $name);
        $name = $name[count($name) - 1];
    }
    return $model ? $name . '/' . $id : null;
}