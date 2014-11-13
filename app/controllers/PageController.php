<?php

class PageController extends BaseController
{

    public function getRoot()
    {
        //this is here to generate the root url from. TODO: there's probably a better way of doing this.
    }

    
    //returns whatever file from the uploads dir.
    public function getUploads ($url = "")
    {
        dd('here');
        //TODO: security on this file.
        $filename = base_path() . '/uploads/'. $url;
        $file = File::get($filename);
        $fileData = new \Symfony\Component\HttpFoundation\File\File($filename);
        $response = Response::make($file, 200);
        $response->headers->set('Content-Type', $fileData->getMimeType());
        return($response);
    }
        
	/*
	 *Sets language for front end pages.
	 **/
	public function getLanguage($language){
		$language = '';
	}
}
