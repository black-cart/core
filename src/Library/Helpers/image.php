<?php
/**
 * File function process image
 */
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * Function upload image
 */
function bc_image_upload($fileContent, $disk = 'public', $path = null, $name = null, $options = ['unique_name' => true, 'thumb' => false, 'watermark' => false])
{
    $pathFile = null;
    try {
        $fileName = false;
        if ($name) {
            $fileName = $name . '.' . $fileContent->getClientOriginalExtension();
        } elseif (empty($options['unique_name'])) {
            $fileName = $fileContent->getClientOriginalName();
        }

        //Save as file
        if ($fileName) {
            $pathFile = Storage::disk($disk)->putFileAs(($path ?? ''), $fileContent, $fileName);
        }
        //Save file id unique
        else {
            $pathFile = Storage::disk($disk)->putFile(($path ?? ''), $fileContent);
        }
    } catch (\Throwable $e) {
        return null;
    }

    if ($pathFile && $disk == 'public') {
        //generate thumb
        if (!empty($options['thumb']) && bc_config('upload_image_thumb_status')) {
            bc_image_generate_thumb($pathFile, $widthThumb = 250, $heightThumb = null, $disk);
        }

        //insert watermark
        if (!empty($options['watermark']) && bc_config('upload_watermark_status')) {
            bc_image_insert_watermark($pathFile);
        }
    }
    if ($disk == 'public') {
        $url =  'storage/' . $pathFile;
    } else {
        $url =  Storage::disk($disk)->url($pathFile);
    }

    return  [
        'fileName' => $fileName,
        'pathFile' => $pathFile,
        'url' => $url,
    ];
}

/**
 * Function upload file
 */
function bc_file_upload($fileContent, $disk = 'public', $path = null, $name = null)
{
    $pathFile = null;
    try {
        $fileName = false;
        if ($name) {
            $fileName = $name . '.' . $fileContent->getClientOriginalExtension();
        } else {
            $fileName = $fileContent->getClientOriginalName();
        }

        //Save as file
        if ($fileName) {
            $pathFile = Storage::disk($disk)->putFileAs(($path ?? ''), $fileContent, $fileName);
        }
        //Save file id unique
        else {
            $pathFile = Storage::disk($disk)->putFile(($path ?? ''), $fileContent);
        }
    } catch (\Throwable $e) {
        return null;
    }
    if ($disk == 'public') {
        $url =  'storage/' . $pathFile;
    } else {
        $url =  Storage::disk($disk)->url($pathFile);
    }
    return  [
        'fileName' => $fileName,
        'pathFile' => $pathFile,
        'url' => $url,
    ];
}

/**
 * Remove file
 *
 * @param   [string]  $disk  
 * @param   [string]  $path  
 * @param   [string]  $prefix  will remove
 *
 */
function bc_remove_file($pathFile, $disk = null) {
    if ($disk) {
        return Storage::disk($disk)->delete($pathFile);
    } else {
        return Storage::delete($pathFile);
    }
}

/**
 * Function insert watermark
 */
function bc_image_insert_watermark($pathFile)
{
    $pathWatermark = bc_config('upload_watermark_path');
    if (empty($pathWatermark)) {
        return false;
    }
    $pathReal = config('filesystems.disks.public.root') . '/' . $pathFile;
    Image::make($pathReal)
        ->insert(public_path($pathWatermark), 'bottom-right', 10, 10)
        ->save($pathReal);
}

/**
 * Function generate thumb
 */
function bc_image_generate_thumb($pathFile, $widthThumb = null, $heightThumb = null, $disk = 'public')
{
    if ($widthThumb == '' && $heightThumb == '') {
        // check in origin folder
        $pathFile = explode('/', $pathFile);
        $pathFile = array_filter($pathFile);
        array_shift($pathFile);
        $pathFile = implode('/', $pathFile);
        if (Storage::disk($disk)->exists($pathFile)) {
            return Storage::url($pathFile);
        }
    }
    $widthThumb = $widthThumb ?? bc_config('upload_image_thumb_width');
    if (!Storage::disk($disk)->has('tmp')) {
        Storage::disk($disk)->makeDirectory('tmp');
    }
    // process get thumb pathF
    $hashPath = array_filter(explode('/', $pathFile));
    array_shift($hashPath);
    $fileName = array_pop($hashPath);
    $thumb_pathFile = implode('/', $hashPath);
    $thumb_pathFile = $thumb_pathFile.'/thumbs/';
    $fileName = $widthThumb.'_'.$heightThumb.'_'.$fileName;
    if (!Storage::exists(public_path($thumb_pathFile))) {
        Storage::makeDirectory(public_path($thumb_pathFile), 0775, true);
    }
    $full_file = $thumb_pathFile.$fileName;
    if (Storage::disk($disk)->exists($full_file)) {
        return Storage::url($full_file);
    }
    $pathReal = public_path().$pathFile;
    $image_thumb = Image::make($pathReal);
    $image_thumb->resize($widthThumb, $heightThumb, function ($constraint) {
        $constraint->aspectRatio();
    });
    $tmp = '/tmp/' . time() . rand(10, 100);

    $image_thumb->save(config('filesystems.disks.public.root') . $tmp);
    // if (Storage::disk($disk)->exists($full_file)) {
    //     Storage::disk($disk)->delete($full_file);
    // }
    Storage::disk($disk)->move($tmp,$full_file);
        
    return Storage::url($full_file);
}

/**
 * Function rener image
 */

function bc_image_render($path, $width = null, $height = null, $alt = null, $title = null, $url = null, $options = '')
{
    if (strpos($path, ',') !== false) {
        $path = explode(',', $path);
    }
    if (is_array($path)) {
        $path = $path[0];
    }
    $image = bc_image_get_path($path, $url);
    $style = '';
    $style .= ($width) ? ' width:' . $width . ';' : '';
    $style .= ($height) ? ' height:' . $height . ';' : '';
    return '<img  alt="' . $alt . '" title="' . $title . '" ' . (($options) ?? '') . ' src="' . asset($image) . '"   ' . ($style ? 'style="' . $style . '"' : '') . '   >';
}

/*
Return path image
 */
function bc_image_get_path($path, $urlDefault = null)
{
    if (strpos($path, ',') !== false) {
        $path = explode(',', $path);
    }
    if (is_array($path)) {
        $path = $path[0];
    }
    $image = $urlDefault ?? 'images/no-image.jpg';
    if ($path) {
        if (file_exists(public_path($path)) || filter_var(str_replace(' ', '%20', $path), FILTER_VALIDATE_URL)) {
            $image = $path;
        } else {
            $image = $image;
        }
    }
    return $image;
}

/*
Function get path thumb of image if saved in storage
 */
function bc_image_get_path_thumb($pathFile)
{
    if (strpos($pathFile, ',') !== false) {
        $pathFile = explode(',', $pathFile);
    }
    if (is_array($pathFile)) {
        $pathFile = $pathFile[0];
    }
    if (strpos($pathFile, "/storage/") === 0) {
        $arrPath = explode('/', $pathFile);
        $fileName = end($arrPath);
        $pathThumb = substr($pathFile, 0, -strlen($fileName)) . 'thumbs/' . $fileName;
        if (file_exists(public_path($pathThumb))) {
            return $pathThumb;
        } else {
            return bc_image_get_path($pathFile);
        }
    } else {
        return $pathFile;
    }
}
/*
Function get path thumb of image array by array saved in storage
 */
function bc_get_array_thumb($images,$w,$h,$asset = false)
{
    if (!is_array($images)) {
        return false;
    }
    $res_images = [];
    foreach ($images as $key => $image) {    
        if ($asset) {
            $res_images[] = asset(bc_image_generate_thumb($image,$w,$h));
        }else{
            $res_images[] = bc_image_generate_thumb($image,$w,$h);
        }
    }
    return $res_images;
}
/*
Function get path and name image 360 degree
 */
function bc_get_threesixty_image($images,$w = '',$h = '')
{
    if (!is_array($images)) {
        return false;
    }
    foreach ($images as $key => $image) {    
        //create image with height and width
        $full_path = bc_image_generate_thumb($image,$w,$h);
    }
    $full_path = pathinfo($full_path);
    $file_name = explode('-', $full_path['filename']);
    array_pop($file_name);
    $file_name = implode('-', $file_name);
    return [ 
        'path' => $full_path['dirname'],
        'file_name' => $file_name,
        'extension' => $full_path['extension'] 
    ];
}