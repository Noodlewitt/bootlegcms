@if($application->getSetting('logo_image'))
    <div class="cms-logo">
        <img src="{{ $application->getSetting('logo_image') }}"/>
        <span class="force-vertical-center"></span>
    </div>
@else
    <div class="cms-logo no-padding">
        <img src="/vendor/bootleg/cms/img/cms.png"/>

        <div class="cms-logo-title">
            <div>Native Laravel Open-Source</div>
            <div>Content Management System</div>
        </div>
    </div>
@endif
