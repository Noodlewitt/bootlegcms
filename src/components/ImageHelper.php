<?php

namespace Bootleg\Cms\Components;

/**
 * Class ImageHelper
 *
 * @package Bootleg\Cms\Components
 */
class ImageHelper
{
    /**
     * @param $source_path: source path of image
     * @param $amount: compression amount 0-100
     * @param null $destination_path: replaces image if destination path not specified

     */
    public static function compressImage($source_path, $amount, $destination_path = null)
    {
        if ($destination_path == null) $destination_path = $source_path;
        $info = getimagesize($source_path);

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source_path);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source_path);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source_path);
        } else {
            error_log('Unknown image file format');
        }

        imagejpeg($image, $destination_path, $amount);
    }


    /**
     * @param $source_path: source path of image
     * @param $settings: array of settings:
     *          compression: compression amount 0-100. Null to skip compression
     *          mode: currently only crop available
     *          copy: if false, resized image will replace source image
     *          height: desired max output height
     *          width: desired max output width
     *
     * @return string
     */
    public static function resizeImage($source_path, $settings)
    {
        $defaults = [
            'compression' => null,
            'mode'        => 'crop',
            'copy'        => true,
            //height
            //width
        ];
        $settings = array_merge($defaults, $settings);

        list($source_width, $source_height, $source_type) = getimagesize($source_path);

        //aspect ratio calculations
        $source_aspect_ratio = $source_width / $source_height;
        $desired_aspect_ratio = $settings['width'] / $settings['height'];

        if ($source_aspect_ratio > $desired_aspect_ratio) {
            // Triggered when source image is wider than desired
            $temp_height = $settings['height'];
            $temp_width = ( int )($settings['height'] * $source_aspect_ratio);
        } else {
            // Triggered when source image is same or taller than desired
            $temp_width = $settings['width'];
            $temp_height = ( int )($settings['width'] / $source_aspect_ratio);
        }

        //create object depending on image type
        switch ($source_type) {
            case IMAGETYPE_GIF:
                $source_gdim = imagecreatefromgif($source_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gdim = imagecreatefromjpeg($source_path);
                break;
            case IMAGETYPE_PNG:
                $source_gdim = imagecreatefrompng($source_path);
                break;
        }

        if ($settings['mode'] == 'crop') {
            //Resize the image into a temporary GD image
            $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
            imagecopyresampled(
                $temp_gdim,
                $source_gdim,
                0, 0,
                0, 0,
                $temp_width, $temp_height,
                $source_width, $source_height
            );

            //Copy cropped region from temporary image into the desired GD image
            $x0 = ($temp_width - $settings['width']) / $settings['height'];
            $y0 = ($temp_height - $settings['height']) / 2;
            $desired_gdim = imagecreatetruecolor($settings['width'], $settings['height']);
            imagecopy(
                $desired_gdim,
                $temp_gdim,
                0, 0,
                $x0, $y0,
                $settings['width'], $settings['height']
            );
        }

        $resized_image = pathinfo($source_path);
        $resized_image_name = $resized_image['dirname'] . "/" . $resized_image['basename'] . "_" . $settings['mode'] . "_" . $settings['width'] . "_" . $settings['height'];

        switch ($source_type) {
            case IMAGETYPE_GIF:
                imagegif($desired_gdim, $resized_image_name);
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($desired_gdim, $resized_image_name);
                break;
            case IMAGETYPE_PNG:
                imagepng($desired_gdim, $resized_image_name);
                break;
        }

        if($settings['compression']){
            static::compressImage($resized_image_name . '.' .$resized_image['extension'], $settings['compression']);
        }

        return $resized_image_name . '.' .$resized_image['extension'];
    }
}
