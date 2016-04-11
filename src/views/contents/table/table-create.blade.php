    <?php
    $settings = $settings->groupBy('section');
    ?>
    <div class='overlay'></div>
        
        <button type="button" class="close modal-close" data-dismiss="modal" aria-hidden="true">×</button>
        <div class='modal-header'>
            <div class="page-header row">
                <!-- Page header, center on small screens -->
                <h1 class="col-xs-12"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;&nbsp;{{@$content->id?'Update':'Create'}} {{$content->name or 'Content'}}</h1>
            </div>
        </div>
        <div class='modal-body'>
            @include('cms::layouts.flash_messages')
            <ul class="nav nav-tabs">
                <li class='active'><a href="#tab-Content" data-toggle="tab" data-target='.edit-Content-tab'>Content</a></li>
                <li><a href="#tab-advanced" data-toggle="tab" data-target='.edit-advanced-tab'>Advanced</a></li>
                <li><a href="#tab-permission" data-toggle="tab" data-target='.edit-permission-tab'>Permisssions</a></li>
                @foreach($settings as $key=>$section)
                    @if($key != 'Content' && $key != 'Advanced' && $key != 'Permissions')
                        <li><a href="#tab-{{$key}}" data-toggle="tab" data-target='.edit-{{$key}}-tab'>{{$key}}</a></li>
                    @endif
                @endforeach
                @if(count($application->languages) > 1 && config('bootlegcms.cms_languages'))
                    <li class='js-language-select'>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Languages:{{\App::getLocale()}} <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                @foreach($application->languages as $language)
                                    <li>
                                        <a class='align-right js-add-panel js-add-panel-{{$language->code}}' data-lang-code="{{$language->code}}" href="{{Applicationurl::getBaseUrl().config('bootlegcms.cms_route')}}{{$language->code}}{{'/'. $content_mode .'/'.'edit-tabs'.'/'.$content->id}}"><span class='glyphicon glyphicon-plus'></span></a>
                                        <a class='main ' href="{{Applicationurl::getBaseUrl().config('bootlegcms.cms_route')}}{{$language->code}}{{'/'. $content_mode .'/'.'edit'.'/'.$content->id}}">{{$language->name}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @endif
            </ul>          
        <div class='form-wrap row'>
            @include('cms::contents.edit-tabs')
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            
            $('[data-toggle="tooltip"]').tooltip();

            $('.form-wrap').on('click','.js-generate-slug', function(e){
                e.preventDefault();

                $form = $(this).closest('form');

                var str = $('.js-content-name', $form).val().replace(/ /g, '-');
                str = '/'+str.replace(/[^a-zA-Z0-9-_]/g, '');
                $('.js-slug', $form).val(str.toLowerCase());
            });

            @if(count($application->languages) > 1)
            //language selection
            $('.js-language-select a.main').click(function(e){
                e.preventDefault();
                $.get(($(this).attr('href')), function(data){
                    $('.main-content').html(data);
                });
                
            });

            //need to grab more langs if there are any?
            var cSettings = Cookies.getJSON('bootleg-settings');
            if(cSettings && ('contentLanguageTabs' in cSettings)){
                //clear what's in there already so we can start appending the lang blocks..
                if(cSettings.contentLanguageTabs.length > 1){
            //        $('.tab-language').remove();
            //        console.log('hhj');
                    var numOfTabs = 1;
                    var column = 1;
                    $.each(cSettings.contentLanguageTabs, function(){
                        if($('.main-content .tab-language-'+this.code).length == 0){
                            var $currentTabs = $('.main-content .tab-language');
                            var $container = $currentTabs.parent();

                            numOfTabs ++;

                            $link = $('.js-language-select ul li a.js-add-panel-' + this.code);
                            $.get($link.attr('href'), function(data){
                                $container.append(data);
                                $('.main-content .tab-language').removeClass(function (index, classes) {
                                    return (classes.match (/\bcol-sm-\S+/g) || []).join(' ');
                                }).addClass('col-sm-'+column);
                            });
                        }
                    });
                    console.log(numOfTabs);
                    column = 12/(numOfTabs);
                }
            }
            
            //hitting the close-lang button closes the panel.
            $('.form-wrap').on('click','.js-close-language-tab', function(e){
                e.stopPropagation();
                e.preventDefault();
                var $tab = $(this).closest('.tab-language');
                var $tmp = $tab;
                var $currentTabs = $('.tab-language');
                var numTabs = $currentTabs.length;
                var column = 12/(numTabs-1);
                var cSettings = Cookies.getJSON('bootleg-settings');
                $tab.remove();


                if(cSettings && ('contentLanguageTabs' in cSettings)){
                    
                    //add in existing langs:
                    $.each(cSettings.contentLanguageTabs, function(i){
                        //if it's the language we are currently removing, remove it from settings too.
                        if(this.code == $tab.data('lang-code')){
                            console.log(i);
                            cSettings.contentLanguageTabs.splice(i, 1);
                        }
                        else{
                            console.log('skip' + this.code);
                        }
                    });

                    Cookies.set('bootleg-settings', cSettings);
                }

                
               // $tab.remove();
                $('.main-content .tab-language').removeClass(function (index, classes) {
                    return (classes.match (/\bcol-sm-\S+/g) || []).join(' ');
                }).addClass('col-sm-'+column);
            });



            //Hitting the + button on the language splits the screen into 2 sections
            $('.js-language-select a.js-add-panel').click(function(e){
                e.preventDefault();
                var $me = $(this);
                var $currentTabs = $('.main-content .tab-language');
                var $container = $currentTabs.parent();
                var numberOfTabs = $currentTabs.length;
                var column = 12/(numberOfTabs+1);

                //does this already exist?
                //console.log($('.main-content .tab-language .tab-language-'+$me.data('lang-code')));
                //console.log($('.main-content .tab-language .tab-language-'+$me.data('lang-code')).length);
                if($('.main-content .tab-language-'+$me.data('lang-code')).length == 0){
                    $.get(($(this).attr('href')), function(data){
                        
                        //var settings = Cookies.getJSON('bootleg-settings');
                        var settings = {
                            contentLanguageTabs:[]
                        };

                        //add in existing langs:
                        $.each($currentTabs, function(){
                            //we need to set the cookie based off this..
                            settings.contentLanguageTabs.push({
                                code:$(this).data('lang-code')
                            });
                        });

                        //and add the current one:
                        settings.contentLanguageTabs.push({
                            code:$me.data('lang-code')
                        });
                        Cookies.set('bootleg-settings', settings);
                        
                        $container.append(data);
                        $('.main-content .tab-language').removeClass(function (index, classes) {
                            return (classes.match (/\bcol-sm-\S+/g) || []).join(' ');
                        }).addClass('col-sm-'+column);
                    });
                }
                else{
                    $('.main-content .tab-language-'+$me.data('lang-code')).addClass('pulsate').removeClass('pulsate');
                }
            });
            @endif
        });
    </script>