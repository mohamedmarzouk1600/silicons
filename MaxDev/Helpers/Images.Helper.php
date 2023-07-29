<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 10/6/2018 9:52 PM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

function imagecreatefromfile($image_path)
{
    $ext = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
    switch ($ext) {
        case 'gif':
            return @imagecreatefromgif($image_path);
            break;
        case 'jpg':
        case 'jpeg':
            return @imagecreatefromjpeg($image_path);
            break;
        case 'png':
            return @imagecreatefrompng($image_path);
            break;
        default: return '';
            break;
    }
}

function gdImageContent($gdImg, $format='jpeg', $quality='50')
{
    ob_start();
    switch ($format) {
        case 'gif':
            imagegif($gdImg);
            break;
        case 'png':
            imagepng($gdImg, null, $quality);
            break;
        default:
        case 'jpg':
        case 'jpeg':
            imagejpeg($gdImg, null, $quality);
            break;
    }
    $image_data = ob_get_contents();
    ob_end_clean();
    return $image_data;
}

function resizeImage($filename, $newwidth, $newheight)
{
    list($width, $height) = getimagesize($filename);
    if ($newwidth > $width && $height > $newheight) {
        return imagecreatefromfile($filename);
    }
    if ($width > $height && $newheight < $height) {
        $newheight = $height / ($width / $newwidth);
    } elseif ($width < $height && $newwidth < $width) {
        $newwidth = $width / ($height / $newheight);
    } else {
        $newwidth = $width;
        $newheight = $height;
    }
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $source = imagecreatefromfile($filename);
    if (!$source) {
        return $thumb;
    }
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return $thumb;
}

function calcDim($width, $height, $maxwidth, $maxheight)
{
    if ($width != $height) {
        if ($width > $height) {
            $t_width = $maxwidth;
            $t_height = (($t_width * $height)/$width);
            //fix height
            if ($t_height > $maxheight) {
                $t_height = $maxheight;
                $t_width = (($width * $t_height)/$height);
            }
        } else {
            $t_height = $maxheight;
            $t_width = (($width * $t_height)/$height);
            //fix width
            if ($t_width > $maxwidth) {
                $t_width = $maxwidth;
                $t_height = (($t_width * $height)/$width);
            }
        }
    } else {
        $t_width = $t_height = min($maxheight, $maxwidth);
    }
    return array('height'=>(int)$t_height,'width'=>(int)$t_width);
}

function ResizeedImg($image, $width, $height)
{
    if (!file_exists('storage/'.$image)) {
        return asset('storage/'.$image);
    }
    $resizedImage = $width.'_'.$height.'_'.pathinfo($image, PATHINFO_BASENAME);
    if (file_exists('storage/thumb/'.$resizedImage)) {
        return asset('storage/thumb/'.$resizedImage);
    }
    try {
        $img = \Intervention\Image\Facades\Image::make('storage/' . $image);
        $dim = calcDim($img->width(), $img->height(), $width, $height);
        $img->resize($dim['width'], $dim['height'])->save('storage/thumb/' . $resizedImage, 40);
        return asset('storage/thumb/'.$resizedImage);
    } catch (\Exception $e) {
        return asset('storage/'.$image);
    }
}

function ImageOfHtml($image, $imgsCount)
{
    $pattern = '/src="([^"]*)"/';
    preg_match_all($pattern, $image, $matches);
    if (!count($matches[1])) {
        return false;
    }
    $image = explode('/storage/', array_slice($matches[1], 0, $imgsCount)[0]);
    return $image[0];
}


function upload_file($file, $folderName = '', $inPublicStorage = false, $name = false, $options = [])
{
    if ($file) {
        $time = \Carbon\Carbon::now();
        if ($folderName) {
            $folderName .='/';
        }
        $directory = $folderName.date_format($time, 'Y') . '/' . date_format($time, 'm');

        if ($name) {
            $uploaded = $file->storeAs($directory, $name, app()->environment('local') ? 'public' : 's3_assets');
        } else {
            if ($inPublicStorage) {
                $uploaded = $file->store($directory, app()->environment('local') ? 'public' : 's3_assets');
            } else {
                $uploaded = $file->store($directory, app()->environment('local') ? 'public' : 'patient_files');
            }
        }

        if (count($options)) {
            if (isset($options['width'], $options['height'])) {
                $imageQuality = 75;
                try {
                    $img = Image::make('storage/' . $uploaded);
                    $extension = $file->getClientOriginalExtension();
                    $img->encode($extension, $imageQuality);
                    if ($img->height() > $options['height'] || $img->width() > $options['width']) {
                        $img->height() > $img->width() ? $options['width'] = null : $options['height'] = null;
                        $img->resize($options['width'], $options['height'], function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                    $img->save();
                } catch (\Exception $e) {
                }
            }
        }

        if ($uploaded) {
            return str_replace('public/', '', $uploaded);
        }
    }
}
