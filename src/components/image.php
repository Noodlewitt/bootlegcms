<?php

class Img{
    //A bunch of tools for resizing and interacting with images.

    //returns url of resized image
    public function get($url, $x, $y, $mode='crop'){
        $url = pathinfo($url);
        $filename = '/'.$url['filename']."_".$mode."_".$x."_".$y.$url['extension'];
        $finalUrl = $url['dirname'].$filename;
        return($finalUrl);
    }

    public function resize($source_path, $desired_x=300, $desired_y=150, $mode = 'crop', $upload = true){
        //TODO: resize with GD library.

        list($source_width, $source_height, $source_type) = getimagesize($source_path);

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

        $source_aspect_ratio = $source_width / $source_height;
        $desired_aspect_ratio = $x / $desired_y;

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

        $application = Application::getApplication();
        if($upload){

            //we gotta push this file locally first regardless of if we need it or not
            //since there's no method to get the image data out directly without
            //outputting to browser..

            try {
                $destinationPath    = storage_path()."/uploads/";
                $sourcePathInfo = pathinfo(Img::get($source_path, $desired_x, $desired_y, $mode));
                $newBaseName = $sourcePathInfo['basename'];

                switch ($source_type) {
                    case IMAGETYPE_GIF:
                        $loc = imagegif($source_path, $destinationPath.'/'.$newBaseName);
                        break;
                    case IMAGETYPE_JPEG:
                        $loc = imagejpeg($source_path, $destinationPath.'/'.$newBaseName);
                        break;
                    case IMAGETYPE_PNG:
                        $loc = imagepng($source_path, $destinationPath.'/'.$newBaseName);
                        break;
                }
                $finalUrl = "//".$_SERVER['SERVER_NAME']."/uploads/$newBaseName";
            } catch(Exception $e) {
                dd($e->getMessage());
                //TODO: proper error handling should really take place here..
                //in the mean time we'll make do with a dd.
            }
            //at this point we should have created the smaller image, and moved it
            //into the storage folder. The local uri should be set correctly into
            //$loc, and the finalUrl should also be correct.

            if(@$application->getSetting('Enable s3')){
                //we need to upload this into the s3
                //$uploadFolder
                //file and folder need to be concated and checked.
                if(@$application->getSetting('s3 Folder')){
                    $pth = trim(@$application->getSetting('s3 Folder'),'/\ ').'/'.$fileName;
                }
                else{
                    $pth = $fileName;
                }

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
                
                $s3 = AWS::get('s3');
                $s3->putObject(array(
                    'Bucket'     => @$application->getSetting('s3 Bucket'),
                    'Key'        => $pth,
                    'SourceFile' => $loc,
                    'ACL'=>'public-read' //todo: check this would be standard - would we ever need to have something else in here?
                ));
                if(@$application->getSetting('s3 Cloudfront Url')){
                    $cloudUrl = trim($application->getSetting('s3 Cloudfront Url'), " /");
                    $finalUrl = "//$cloudUrl/$pth";
                }
                else{
                    $finalUrl = "//".@$application->getSetting('s3 Bucket')."/$pth";
                }
            }
        }
    }
}
