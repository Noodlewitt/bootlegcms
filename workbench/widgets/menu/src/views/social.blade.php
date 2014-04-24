<?php
    $links = $content->childs()->live()->with('setting')->get();
?>
<ul>
    @foreach($links as $cont)
    <li>
        <a href='{{$cont->slug}}'>dfs</a>
    </li>
    @endforeach
</ul>