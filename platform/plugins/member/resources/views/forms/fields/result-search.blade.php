@if(!is_null($memberNotify))
    @foreach ($memberNotify as $item)
        <tr>
            <td scope="row">{{$item->id}}</td>
            <td>{{$item->member->fullname}}</td>
            <td>{{$item->member->nickname}}</td>
            <td>{{$item->member->email}}</td>
            <td>{{$item->member->id_login}}</td>
            <td>
                <a href="#" class="btn btn-icon btn-sm btn-danger deleteDialog tip"
                    data-toggle="modal"
                    data-section="{{route('member.notify.search.delete',['id' => $item->id])}}"
                    role="button" data-original-title="삭제">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
    @endforeach
@endif
