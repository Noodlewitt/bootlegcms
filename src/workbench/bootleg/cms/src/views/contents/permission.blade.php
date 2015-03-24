<div class="table-responsive">
    <table class="table table-striped">
        <tr>
            <th>
                Controller ID
            </th>
            <th>
                Controller Type
            </th>
            <th>
                Requestor ID
            </th>
            <th>
                Requestor Type
            </th>
            <th>
                X
            </th>
            <th>
                Comment
            </th>
        </tr>
        @foreach($allPermissions as $p)
        <tr>
            <td>
                @if($p->controller_id === '*')
                    <span class="glyphicon glyphicon-asterisk"><span>
                @else
                    {{$p->controller_id}}
                @endif
            </td>
            <td>
                {{$p->controller_type}}
            </td>
            <td>
                @if($p->requestor_id === '*')
                    <span class="glyphicon glyphicon-asterisk"><span>
                @else
                    {{$p->requestor_id}}
                @endif
            </td>
            <td>
                {{$p->requestor_type}}
            </td>            
            <td>
            @if($p->x === '1')
                <span class="glyphicon glyphicon-ok"></span>
            @elseif($p->x === '0')
                <span class="glyphicon glyphicon-remove"></span>
            @else
                <span class="glyphicon glyphicon-minus"></span>
            @endif
            </td>
            <td>
            {{$p->comment}}
            </td>
        </tr>
        @endforeach
    </table>
</div> 