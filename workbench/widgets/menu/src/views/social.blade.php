<?php
    $links = $content->childs()->live()->with('setting')->get();
?>
<ul>
    @foreach($links as $cont)
    <li>
        <a href='{{$cont->slug}}'>{{$cont->name}}</a>
    </li>
    @endforeach
</ul>