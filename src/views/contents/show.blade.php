@extends('cms::layouts.scaffold')

@section('main')

<h1>Show Content</h1>

<p>{{ link_to_action('ContentsController@anyIndex', 'Return to all contents') }}</p>


@include('contents.tree', array('content'=>$content, 'tree'=>$content->getDescendantsAndSelf()))



<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Slug</th>
            <th>Parent_id</th>
            <th>Content</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>{{{ $content->name }}}</td>
            <td>{{{ $content->slug }}}</td>
            <td>{{{ $content->parent_id }}}</td>
            <td>{{{ $content->content }}}</td>
            <td>{{ link_to_action('ContentsController@anyEdit', 'Edit', array($content->id), array('class' => 'btn btn-info')) }}</td>
            <td>
                {{ Form::open(array('method' => 'DELETE', 'action' => array('ContentsController@anyDestroy', $content->id))) }}
                    {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                {{ Form::close() }}
            </td>
        </tr>
    </tbody>
</table>

@stop