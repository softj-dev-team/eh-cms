<style>
    .autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  width: 90%;
}

.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff;
  border-bottom: 1px solid #d4d4d4;
}

/*when hovering an item:*/
.autocomplete-items div:hover {
  background-color: #e9e9e9;
}

/*when navigating through the items using the arrow keys:*/
.autocomplete-active {
  background-color: DodgerBlue !important;
  color: #ffffff;
}
.select2-selection__rendered {
    height: 30px;
}
.result-search {
    display: block;
    width: 20%;
    flex: 1;
}
.select2-container--default .select2-selection--single .select2-selection__arrow:before {
    content: "";
}
</style>


<!--Make sure the form has the autocomplete function switched off:-->
  <!-- Search form -->
  <div style="display: flex;">
    <div class="autocomplete md-form active-cyan active-cyan-2 mb-3  ele-render-select2" style="width: 100%;margin-right: 10px">
        <input type="hidden" id="notify_id" value="{{$notify_id}}">
        <select class="select2-allow-clear select2-hidden-accessible" tabindex="-1" aria-hidden="true" name="">
            <option></option>
        </select>
    </div>
  </div>

<table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Nickname</th>
        <th scope="col">Email</th>
        <th scope="col">Id Login</th>
        <th scope="col">OPERATIONS</th>
      </tr>
    </thead>
    <tbody id="data-search">
        @if(!is_null($memberNotify))
            @foreach ($memberNotify as $item)
              @isset($item->member)
                <tr>
                  <td scope="row">{{$item->id}}</td>
                  <td>{{$item->member->fullname}}</td>
                  <td>{{$item->member->nickname}}</td>
                  <td>{{$item->member->email}}</td>
                  <td>{{$item->member->id_login}}</td>
                  <td>
                    <a href="#" class="btn btn-icon btn-sm btn-danger deleteDialog tip"
                       data-toggle="modal"
                       data-id="{{$item->id}}"
                       role="button" data-original-title="삭제">
                      <i class="fa fa-trash"></i>
                    </a>
                  </td>
                </tr>
              @endisset
            @endforeach
        @endif
    </tbody>
  </table>
<script>
    $(document).ready(function() {

        function formatState (state) {
            if (!state.id) {
                return state.fullname;
            }
            var fullname = state.fullname;
            var email = state.email;
            var id_login = state.id_login;
            var nickname = state.nickname;

            var html = `
                <div class="d-flex" >
                    <div class="result-search">${fullname}</div>
                    <div class="result-search">${nickname}</div>
                    <div class="result-search">${email}</div>
                    <div class="result-search">${id_login}</div>
                </div>
            `;
            var $state = $(html);
            return $state;
        };

        $('.select2-allow-clear').each(function () {
            let $parent = $(this).closest('.ele-render-select2');
            $(this).select2({
                width: '100%',
                dropdownParent: $parent,
                ajax: {
                    url: "{{route('member.notify.search')}}",
                    dataType: 'json',
                    delay: 250, // wait 250 milliseconds before triggering the request
                    data: function (params) {
                        return {
                            keyword: params.term, // search term,
                            notify_id : $('#notify_id').val(),
                        };
                    },
                    processResults: function (results) {
                        return {
                                results: results.items,
                            };
                    },
                    cache: false
                },
                placeholder: "Select a state",
                allowClear: true,
                templateResult: formatState,
                templateSelection :formatRepoSelection,
                minimumInputLength : 1
            });
        });

        function formatRepoSelection (repo) {
            return  repo.fullname;
        }

        $('.select2-allow-clear').on('select2:select', function (e) {
            e.preventDefault();
            var data = e.params.data;
            var id = data.id;

            var $this = $(this);
            if($this.data('isRunAjax')==true){return;}
            $this.css('pointer-events', 'none').data('isRunAjax', true);
            $.ajax({
                type:'POST',
                url: "{{route('member.notify.add')}}",
                data:{
                        _token: "{{ csrf_token() }}",
                        member_id :id,
                        notify_id : $('#notify_id').val(),
                },
                dataType: 'json',
                success: function( data ) {
                    $('#data-search').html(data.member);
                }
            }).always(function(){
                $this.css('pointer-events', 'auto').data('isRunAjax', false);
            });
        });

});
$(document.body).on('click','.deleteDialog', function () {
        let id = $(this).attr('data-id');
        $('.modal-confirm-delete').modal('toggle');
        $('.delete-crud-entry').attr('data-id',  id);
});
$(document.body).on('click','.delete-crud-entry', function () {
        let $this = $(this);
        let id = $this.attr('data-id');
        $.ajax({
            type:'POST',
            url: '{{route('member.notify.search.delete')}}',
            data:{
                    _token: "{{ csrf_token() }}",
                    'id' : id,
            },
            dataType: 'json',
            success: function( data ) {
                if(data.msg == true) {
                    location.reload();
                }
            }
        }).always(function(){
            $this.css('pointer-events', 'auto').data('isRunAjax', false);
        });
    })
</script>

