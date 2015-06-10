<?php namespace Bootleg\Cms;
use \Illuminate\Database\Seeder;
use \Illuminate\Database\Eloquent\Model;

class PermissionsTableSeeder extends Seeder {
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('content')->truncate();
        
        \DB::table('content')->insert(array (
            array (
                'id'=>'39',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ApplicationController@anyCreate',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'53',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ApplicationController@anyIndex',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'41',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ApplicationController@anySettings',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'42',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ApplicationController@anyUpdate',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'61',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ApplicationController@getChildren',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'62',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ApplicationController@getView',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'40',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ApplicationController@postStore',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'29',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ContentsController@anyCreate',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'33',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ContentsController@anyDestroy',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'14',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ContentsController@anyEdit',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'48',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ContentsController@anyFixtree',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'12',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ContentsController@anyIndex',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'28',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ContentsController@anyStore',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'13',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ContentsController@anyTree',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'32',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ContentsController@anyUpdate',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'36',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ContentsController@deleteUpload',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'37',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\ContentsController@postUpload',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'43',
                'requestor_id'=> '*'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\RemindersController@getRemind',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'45',
                'requestor_id'=> '*'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\RemindersController@getReset',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'44',
                'requestor_id'=> '*'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\RemindersController@postRemind',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'46',
                'requestor_id'=> '*'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\RemindersController@postReset',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'27',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\TemplateController@anyCreate',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'34',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\TemplateController@anyDestroy',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'15',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\TemplateController@anyEdit',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'10',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\TemplateController@anyIndex',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'30',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\TemplateController@anyStore',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'11',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\TemplateController@anyTree',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'31',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\TemplateController@anyUpdate',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'35',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\TemplateController@deleteUpload',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'38',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\TemplateController@postUpload',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'21',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\UsersController@anyCreate',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'16',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\UsersController@anyDashboard',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'17',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\UsersController@anyIndex',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'18',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\UsersController@anyLogout',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'19',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\UsersController@anySettings',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'23',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\UsersController@anyStore',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
            array (
                'id'=>'25',
                'requestor_id'=> '2'
                'application_id'=>'1',
                'requestor_type'=>'role',
                'controller_id'=>'*', 
                'controller_type'=>'Bootleg\\Cms\\UsersController@anyUpdate',
                'updated_at'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s"),
                'comment'=>''
            ),
        ));
    }
}
