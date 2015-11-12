<?php

namespace Bootleg\Cms\Components;

use Aws\Common\Aws;
use Bootleg\Cms\Components\ImageHelper;
use Event;
use Exception;
use File;
use stdClass;
use Validator;

class Uploader
{
    protected $application;

    //local settings
    protected $local_settings = [];
    protected $s3_settings = [];
    protected $aws_settings = [];

    protected $validation_rules;

    protected $errors = [];

    public function __construct($local_settings = null, $s3_settings = null, $aws_settings = null)
    {
        $this->application = \Application::getApplication();

        $local_settings = is_array($local_settings) || !$this->application ? $local_settings : [
            'delete_uploads' => $this->application->getSetting('deleteUploads') ? true : false,
            'folder'         => $this->application->getSetting('Upload Folder') ? trim($this->application->getSetting('Upload Folder'), '/\ ') : 'uploads',
        ];

        $s3_settings = is_array($s3_settings) || !$this->application ? $s3_settings : [
            'enabled'        => $this->application->getSetting('Enable s3') ? true : false,
            'folder'         => trim($this->application->getSetting('s3 Folder'), '/\ '),
            'bucket'         => $this->application->getSetting('s3 Bucket'),
            'cloudfront_url' => trim($this->application->getSetting('s3 Cloudfront Url'), " /"),
        ];

        $aws_settings = is_array($aws_settings) || !$this->application ? $aws_settings : [
            'key'    => $this->application->getSetting('s3 access key'),
            'secret' => $this->application->getSetting('s3 secret'),
            'region' => $this->application->getSetting('s3 region'),
        ];

        $this->setLocalSettings($local_settings)->setS3Settings($s3_settings)->setAwsSettings($aws_settings);
    }

    public static function create($local_settings = null, $s3_settings = null, $aws_settings = null)
    {
        return new static($local_settings, $s3_settings, $aws_settings);
    }

    public function upload($file, $compression = null, $resize = null)
    {
        $images = [];
        if (!is_array($resize)) {
            $image = $this->uploadFile($file, null, $resize); //return original image
        } else {
            $image = $this->uploadFile($file, null, $resize); //return resized image
            if ($resize['copy']) {
                $original = $this->uploadFile($file, $compression);
                $original->resized_url = $image->url;
                $image->original_url = $original->url;
                $images[] = $original;
                $images[] = $image;
                return !$this->hasErrors() ? $images : null; //return both images
            }
        }

        return $image;
    }

    protected function uploadFile($file, $compression = null, $resize = null)
    {
        $valid = true;
        $uploaded_file = new stdClass;
        if ($this->validation_rules) {
            $validator = Validator::make(['file' => $file], ['file' => $this->validation_rules]);
            if (!$validator->passes()) $valid = false;
        }
        if ($valid) {
            $uploaded_file->mime = $file->getMimeType();
            $uploaded_file->size = $file->getSize();
            $uploaded_file->name = uniqid() . '.' . $file->getClientOriginalExtension();
            $uploaded_file->original_name = $file->getClientOriginalName();
            $uploaded_file->url = '/' . $this->getLocalSetting('folder') . '/' . $uploaded_file->name;
            //$uploaded_file->thumbnailUrl = $uploaded_file->url;
            //$uploaded_file->deleteUrl = url($this->getLocalSetting('folder') . '/' . $uploaded_file->name);
            //$uploaded_file->deleteType = "DELETE";
            try {
                $file->move(public_path($this->getLocalSetting('folder')), $uploaded_file->name);
                if (is_array($resize)) {
                    $uploaded_file->name = ImageHelper::resizeImage(public_path($this->getLocalSetting('folder') . '/' . $uploaded_file->name), $resize);
                } elseif ($compression) {
                    ImageHelper::compressImage(public_path($this->getLocalSetting('folder') . '/' . $uploaded_file->name), $compression);
                }
            } catch (Exception $e) {
                $this->errors[] = 'Error processing file';
            }

            //if s3 is enabled, we can upload to s3!
            if ($this->getS3Setting('enabled')) {
                $uploaded_file = $this->uploadToS3($uploaded_file);
            }
        } else {
            $this->errors[] = 'Invalid file';
        }

        Event::fire('upload.complete', [$uploaded_file]);

        return $uploaded_file;
    }

    protected function uploadToS3($uploaded_file)
    {
        //file and folder need to be concatenated and checked.
        $s3_destination_path = $this->getLocalSetting('folder') . '/' . $uploaded_file->name;

        //prepend S3 folder if set
        if ($this->getS3Setting('folder')) {
            $s3_destination_path = $this->getS3Setting('folder') . '/' . $s3_destination_path;
        }

        //strip excess slashes
        $s3_destination_path = trim($s3_destination_path, '/\ ');

        $aws = Aws::factory($this->getAwsFactorySettings());
        $s3 = $aws->get('s3');
        $s3->putObject([
            'Bucket'     => $this->getS3Setting('bucket'),
            'Key'        => $s3_destination_path,
            'SourceFile' => public_path($this->getLocalSetting('folder') . '/' . $uploaded_file->name),
            'ACL'        => 'public-read'
        ]);

        $uploaded_file->url = '//' . ($this->getS3Setting('cloudfront_url') ? $this->getS3Setting('cloudfront_url') : $this->getS3Setting('bucket')) . '/' . $s3_destination_path;

        if ($this->getLocalSetting('delete_uploads') && File::exists($this->getLocalSetting('folder') . '/' . $uploaded_file->name)) {
            File::delete($this->getLocalSetting('folder') . '/' . $uploaded_file->name);
        }

        return $uploaded_file;
    }

    public function getLocalSetting($setting)
    {
        return isset($this->local_settings[ $setting ]) ? $this->local_settings[ $setting ] : null;
    }

    public function getS3Setting($setting)
    {
        return isset($this->s3_settings[ $setting ]) ? $this->s3_settings[ $setting ] : null;
    }

    public function setLocalSettings(Array $settings)
    {
        $this->local_settings = $settings;

        return $this;
    }

    public function setS3Settings(Array $settings)
    {
        $this->s3_settings = $settings;

        return $this;
    }

    public function setAwsSettings(Array $settings)
    {
        $this->aws_settings = $settings;

        return $this;
    }

    public function setRules($rules)
    {
        $this->validation_rules = $rules;

        return $this;
    }

    public function hasErrors()
    {
        return count($this->errors) ? true : false;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function getAwsFactorySettings()
    {
        return [
            'includes' => ['_aws'],
            'services' => [
                'default_settings' => [
                    'params' => $this->aws_settings
                ]
            ]
        ];
    }

}
