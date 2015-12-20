<?php namespace Bootleg\Cms;

class Img{
    //A bunch of tools for resizing and interacting with images.

    //returns url of resized image
    public static function get($url, $x, $y, $mode='crop'){
        $url = pathinfo($url);
        $filename = '/'.$url['filename']."_".$mode."_".$x."_".$y.'.'.$url['extension'];
        $finalUrl = $url['dirname'].$filename;
        return($finalUrl);
    }

    public static function resize($source_path, $desired_x=300, $desired_y=150, $mode = 'crop', $upload = true){
    //    echo("creating size: $desired_x x $desired_y");
    //    flush();
        $fileString = file_get_contents('http:'.$source_path, 'r');

        $source_path_parts = pathinfo($source_path);

        $source_gdim = imagecreatefromstring($fileString);
        list($source_width, $source_height, $source_type, $attr) = getimagesizefromstring($fileString);

        $source_aspect_ratio = $source_width / $source_height;
        $desired_aspect_ratio = $desired_x / $desired_y;

        if ($source_aspect_ratio > $desired_aspect_ratio) {
            /*
             * Triggered when source image is wider
             */
            $temp_height = $desired_y;
            $temp_width = ( int ) ($desired_y * $source_aspect_ratio);
        } else {
            /*
             * Triggered otherwise (i.e. source image is similar or taller)
             */
            $temp_width = $desired_x;
            $temp_height = ( int ) ($desired_x / $source_aspect_ratio);
        }

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
        $x0 = ($temp_width - $desired_x) / $desired_y;
        $y0 = ($temp_height - $desired_y) / 2;
        $desired_gdim = imagecreatetruecolor($desired_x, $desired_y);
        imagecopy(
            $desired_gdim,
            $temp_gdim,
            0, 0,
            $x0, $y0,
            $desired_x, $desired_y
        );
        
        $application = \Application::getApplication();
        $uploadFolder = trim(@$application->getSetting('Upload Folder'), '/\ ');

        if($upload){

            $finalFile = $source_path_parts['filename']."_".$mode."_".$desired_x."_".$desired_y.'.'.$source_path_parts['extension'];

            //this leaves us with something like this 55ee4c5e032e5_300_150.jpg
            
            $finalPath = storage_path()."/uploads/".$uploadFolder.$finalFile;

            switch ($source_type) {
            case IMAGETYPE_GIF:
                imagegif($desired_gdim, $finalPath);
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($desired_gdim, $finalPath);
                break;
            case IMAGETYPE_PNG:
                imagepng($desired_gdim, $finalPath);
                break;
            }
        //    echo("uploading size: $desired_x x $desired_y");
        //    flush();
            S3::upload($finalFile, $finalPath);
        //    echo("done");
        //    flush();
        }
    }
}
