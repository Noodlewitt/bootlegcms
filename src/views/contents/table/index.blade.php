<?php
if(@$childrenSettings){
    $firstChildSettings = $childrenSettings->first();    
}
?>
<style>
    .setting.active{
        position: relative;
        width: 300%;
        left: -100%;
        z-index: 1;    
    }
    .popover{
        left:0 !important;
        max-width: 330px !important;
    }
    .popover .arrow{
        left:15% !important;
    }
    a.edit-field, a.ok-field{
        position: absolute;
        top:5px;
        right:5px;
        z-index: 2;
    }

    thead th{
        text-align: center;
    }

    .setting-cell{
        position: relative;
        text-align: center;
        min-width:155px;
    }
    .table-actions{
        width:155px;
    }
    
</style>
    <div class='overlay'></div>
    <div class="page-header row">
        <div class='col-xs-8'>
            <h1 class="">
                <i class="glyphicon glyphicon-list-alt"></i>&nbsp;&nbsp; {{$content->name or 'Content'}}
            </h1>
        </div>
        <div class='col-xs-4'>
            <form class='table-search pull-right' action="{{action('\Bootleg\Cms\ContentsController@getSearch', array('id'=>$content->id))}}">
                <div class="input-group">
                    <input type="search" name='search' value="{{@\Input::get('search')}}" class="form-control" placeholder="search">
                    <span class='input-group-btn'>
                        <button type="submit" class="btn btn-default js-children-search"><span class='glyphicon glyphicon-search'></span></button>
                    </span>
                </div>
                <input type="hidden" name='id' value='{{$content->id}}' placeholder="search">
            </form>
        </div>
    </div>

    @include('cms::layouts.flash_messages')
    @if(isset($children[0]) && $children[0])
        <table class="table table-striped table-bordered">
            @if(@$content->hide_templates)
                <thead>
                    <tr>
                        <th>
                        @if(!$children[0]->hide_id)
                        <th>#</th>
                        @endif
                        @if(!$children[0]->hide_name)
                        <th>name</th>
                        @endif
                        @if(!$children[0]->hide_slug)
                        <th>slug</th>
                        @endif
                        @if(@$firstChildSettings)
                            @foreach($firstChildSettings as $settingName=>$setting)
                                @if(\Input::get('sort') == $settingName)
                                    @if(\Input::get('direction') == 'asc')
                                        <th><a href='?sort={{$settingName}}&amp;direction=desc'><span class='glyphicon glyphicon-chevron-up'></span> {{$settingName}}</th>
                                    @else
                                        <th><a href='?sort={{$settingName}}&amp;direction=asc'><span class='glyphicon glyphicon-chevron-down'></span> {{$settingName}}</th>
                                    @endif
                                @else
                                    <th><a href='?sort={{$settingName}}&amp;direction=desc'>{{$settingName}}</th>
                                @endif
                            @endforeach
                        @endif
                    </tr>
                </thead>
            @endif
            <tbody>
                @if(@$children)
                    @foreach($children as $child)
                        <tr>
                            @if(!@$content->hide_templates)
                                <th>{{$child->template->name}}
                                    <div class="btn-group table-actions" role="group" aria-label="get children">
                                        <button title='expand' data-toggle="tooltip" href='{{action('\Bootleg\Cms\ContentsController@getTable', array($child->id))}}' class='btn btn-primary btn-sm js-show-children' data-toggle="button"><span class='glyphicon glyphicon-chevron-down'></span></button>
                                        <a title='open' data-toggle="tooltip" href='{{action('\Bootleg\Cms\ContentsController@getTable', array($child->id))}}' class='btn btn-info btn-sm '><span class='glyphicon glyphicon-th-list'></span></a>
                                        <a title='edit' data-toggle="modal" data-target="#popup" href='{{action('\Bootleg\Cms\ContentsController@anyEdit', array($child->id))}}' class='btn btn-warning btn-sm js-edit-row'><span class='glyphicon glyphicon-pencil'></span></a>
                                        <a title='delete' data-toggle="tooltip" href='{{action('\Bootleg\Cms\ContentsController@anyDestroy', array($child->id))}}' class='btn btn-danger btn-sm js-delete-item'><span class='glyphicon glyphicon-remove'></span></a>
                                    </div>
                                </th>
                            @endif
                            
                            @if(!$children[0]->hide_id && !$child->hide_id)
                            <td>{{$child->id}}</td>
                            @endif
                            @if(!$children[0]->hide_name && !$child->hide_name)
                            <th>{{$child->name}}</th>
                            @endif
                            @if(!$children[0]->hide_slug && !$child->hide_slug)
                            <td>{{$child->slug}}</td>
                            @endif
                            @foreach($childrenSettings[$child->id] as $setting)
                                @if(!$setting->parent_id) {{--we want ot ignore all the nested settings --}}
                                <td class='setting-cell {{$setting->field_type}}'>
                                    
                                    <form action='{{action('\Bootleg\Cms\ContentsController@anyUpdate', array($child->id))}}' method='POST'>
                                        <div class='setting'>
                                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" />

                                                @if($setting->field_type == 'upload')
                                                    @if(strtolower(pathinfo($setting->value, PATHINFO_EXTENSION)) ==  'png' || strtolower(pathinfo($setting->value, PATHINFO_EXTENSION)) ==  'jpg' || strtolower(pathinfo($setting->value, PATHINFO_EXTENSION)) ==  'gif')
                                                            <a href="{{$setting->value}}" target="_blank">
                                                                <img src='{{$setting->value}}'  width='100'/>
                                                            </a>
                                                    @else
                                                        <strong>{{$setting->name}}</strong>
                                                        <div class='value {{$setting->field_type}}'>
                                                        {{$setting->value}}
                                                        </div>
                                                    @endif
                                                @elseif($setting->field_type == 'textarea' || $setting->field_type == 'tinymce')
                                                    <strong>{{$setting->name}}</strong>
                                                    <div class='value {{$setting->field_type}}'>
                                                        ...
                                                    </div>
                                                @else
                                                    <strong>{{$setting->name}}</strong>
                                                    <div class='value {{$setting->field_type}}'>
                                                        <?php
                                                        if(!is_array($setting->value)){
                                                            $t = substr($setting->value, 0, 200);
                                                            if($t != $setting->value){
                                                                $t = strip_tags($t) . '&hellip;';
                                                            }
                                                        }
                                                        ?>
                                                        {{$t}}
                                                    </div>
                                                @endif
                                        </div>     
                                    </form>
                                    
                                    @if($setting->field_type != 'static')
                                    <a title='edit field' href='{{action('\Bootleg\Cms\ContentsController@getRenderSetting', array($setting->id, $child->id, get_class($setting)))}}' class='js-edit-click edit-field'>
                                        <span class='glyphicon glyphicon-pencil'></span>
                                    </a>
                                    @endif
                                </td>
                                @endif
                            @endforeach
                           
                               <?php /*    @for($i=0; $i < $padCells; $i++)
                                    <td class='setting-cell'>
                                        <div class='setting'>
                                        </div> 
                                    <a href='{{action('\Bootleg\Cms\ContentsController@getRenderSetting', array(NULL, $setting->content_id, 'setting name'))}}' data-update-href='{{action('\Bootleg\Cms\ContentsController@anyUpdate', array($setting->content_id))}}' class='js-edit-click edit-field'><span class='glyphicon glyphicon-pencil'></span></a>
                                    </td>
                                @endfor
                                */ ?>
                            @if($content->hide_templates)
                                <td class='table-actions'>
                                    <div class="btn-group" role="group" aria-label="get children">
                                        <button title='expand' data-toggle="tooltip" href='{{action('\Bootleg\Cms\ContentsController@getTable', array($child->id))}}' class='btn btn-primary btn-sm js-show-children' data-toggle="button"><span class='glyphicon glyphicon-chevron-down'></span></button>
                                        <a title='open' data-toggle="tooltip" href='{{action('\Bootleg\Cms\ContentsController@getTable', array($child->id))}}' class='btn btn-info btn-sm '><span class='glyphicon glyphicon-th-list'></span></a>
                                        <a title='edit' data-toggle="modal" data-target="#popup" href='{{action('\Bootleg\Cms\ContentsController@anyEdit', array($child->id))}}' class='btn btn-warning btn-sm' role="button" tabindex="0"><span class='glyphicon glyphicon-pencil'></span></a>
                                        <a title='delete' data-toggle="tooltip" href='{{action('\Bootleg\Cms\ContentsController@anyDestroy', array($child->id))}}' class='btn btn-danger btn-sm js-delete-item'><span class='glyphicon glyphicon-remove'></span></a>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="99999">
                        @if(!$content->hide_create)
                        <div class="btn-group" role="group" aria-label="get children">
                            <a href="{{action('\Bootleg\Cms\ContentsController@anyCreate', array($content->id))}}" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#popup"><span class="glyphicon glyphicon-plus"></span> Create Content</a>
                        </div>
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
    @endif

    @if(isset($children) && method_exists($children, 'currentPage'))
        <div class="js-main-content-container">{!!$children->appends(Input::get())->render()!!}</div>
    @endif

    <script type="text/javascript">
        $(function () {
            $('[data-toggle="popover"]').popover({
                'placement':'bottom'
            });

            if(typeof(tableEvents) === 'undefined'){
                tableEvents = true;
                $('.main-content').on('click', '.js-delete-item', function(e){
                    $me=$(this);
                    e.preventDefault();
                    swal({
                        title: "Are you sure?",
                        type: "error",
                        text: "Are you sure you want to delete?",
                        showCancelButton: true,
                        confirmButtonText: "Yes, delete it!"
                    },
                    function(){   
                        $.get($me.attr('href'), function(data){
                            if($me.closest('tr').hasClass('expanded')){
                                $me.closest('tr').next('tr.children').remove();
                            }
                            $me.closest('tr').remove();
                            
                        });
                    });
                });

                //Edit pencil button
                $('.main-content').on('click', '.js-edit-click', function(e){
                    e.preventDefault();
                    $me = $(this);
                    $td = $me.closest('td');
                    if($td.hasClass('tinymce')){
                        console.log($('.js-edit-row', $td.closest('tr')));
                        $('.js-edit-row', $td.closest('tr')).click();
                    }
                    else{
                        $.get($me.attr('href'), function(data){
                            var $data = $.parseHTML(data);
                            data+= '<button class="btn btn-small btn-success js-submit-setting">OK</button>';
                            $('form .setting', $td).popover({
                                html:true,
                                content:data,
                                placement:'bottom'
                            }).popover('toggle');
                        });
                    }
                });

                //OK on popover box
                $('.main-content').on('click', '.js-submit-setting', function(e){
                    e.preventDefault();
                    $me = $(this);
                    $form = $me.closest('form');
                    $td = $me.closest('td');
                    $.post($form.attr('action'), $form.serialize(), function(data){
                        $('form .setting', $td).popover('hide');
                        var formValue = $('.form-control', $form).val();
                        if(endsWith(formValue,'png') || endsWith(formValue,'jpg') || endsWith(formValue,'gif')){
                            formValue = '<img width="100" src="'+formValue+'" />';
                        }
                        if(formValue.constructor === Array){
                            formValue = formValue.join();
                        }
                        $('form .setting .value', $td).html(formValue);
                    });
                });

                jsShowChildrenReg = true;
                $('.main-content').on('click', '.js-show-children', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    var $parentRow = $(this).closest('tr');
                    if($parentRow.hasClass('expanded')){
                        $parentRow.next('tr.children').remove();
                    }
                    else{
                        $.get($(this).attr('href'), function(data){
                            //alert('arse');
                            $childrenRow = $('<tr class="children"><td colspan="999999"><div class="child">'+data+'</div></td></tr>');
                            $parentRow.after($childrenRow);
                           // $('tr', $childrenRow).removeClass('nice-hidden');
                        });
                    }
                    $parentRow.toggleClass('expanded');
                    
                });
            }
            
            $('body').off('click', '.js-content-update', function(e){});
            $('body').off('click', '.js-content-cancel', function(e){});
            $('body').on('click', '.js-content-update', function(e){
                e.preventDefault();
                var $btn = $(this).button('loading');
                $form = $(this).closest('form');
                $.post($form.attr('action'), $form.serialize(), function(data){
                    $btn.button('reset')
                    $('#popup').modal('hide');
                    //boop.
                //    tree.jstree("refresh");

                    swal('content created!');
                });
            });

            $('body').on('click', '.js-content-cancel', function(e){
                e.preventDefault();
                $('#popup').modal('hide');
            });


            /*if(typeof(jsChildrenSearch) === 'undefined'){
                jsChildrenSearch = true;
                $('.main-content').on('click', '.js-children-search', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    $form = $(this).closest('form');
                    alert($form.attr('action'));
                    $.get($form.attr('action'), $form.serialize(), function(data){
                        $('.main-content').html(data);
                    });
                });
            }*/
            
        });
    </script>