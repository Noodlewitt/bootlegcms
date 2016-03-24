<?php

function cmsViewPath($view, $package) {
    if ($package && view()->exists($package . '::' . $view)) return $package . '::' . $view;

    $application = Application::getApplication();

    if ($application && view()->exists($application->cms_package . '::' . $view)) return $application->cms_package . '::' . $view;

    return config('bootlegcms.cms_hint_path') . '::' . $view;
}
