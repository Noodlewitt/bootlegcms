<?php namespace Bootleg\Cms;

class S3{

    /**
     * upload any file to s3
     * @param  [string] $fileName   file url
     * @param  [string] $fileString file as a string
     * @param  string   $acl        access control
     * @return [string]             location final location of file
     */
    public static function upload($fileName, $location = NULL, $fileString = NULL, $acl = 'public-read'){
        $application = \Application::getApplication();
        if (@$application->getSetting('Enable s3')) {

            if (@$application->getSetting('s3 Folder')) {
                $pth = trim(@$application->getSetting('s3 Folder'), '/\ ').'/'.$fileName;
            } else {
                $pth = $fileName;
            }

            $s3 = \AWS::get('s3');
            if($location){
                $put = array(
                    'Bucket'     => @$application->getSetting('s3 Bucket'),
                    'Key'        => $pth,
                    'SourceFile' => $location,
                    'ACL'=>$acl
                );    
            }
            else{
                $put = array(
                    'Bucket'     => @$application->getSetting('s3 Bucket'),
                    'Key'        => $pth,
                    'Body' => $fileString,
                    'ACL'=>$acl
                );    
            }
            
            $s3->putObject($put);

            if (@$application->getSetting('s3 Cloudfront Url')) {
                $cloudUrl = trim($application->getSetting('s3 Cloudfront Url'), " /");
                $finalUrl = "//$cloudUrl/$pth";
            } else {
                $finalUrl = "//".@$application->getSetting('s3 Bucket')."/$pth";
            }
            return $finalUrl;
        }
        else{
            return $fileName;
        }
    }
}