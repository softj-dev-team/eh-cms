
<style>
.pagination>li>a:focus, .pagination>li>a:hover, .pagination>li>span:focus, .pagination>li>span:hover {
    background-color: #36c6d3;
    color: #fff;
}
.page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    cursor: auto;
    background-color: #fff;
    border-color: #dee2e6;
}
.page-item.active .page-link {
    z-index: 1;
    color: #fff;
    background-color: #36c6d3;
    border-color: #36c6d3;
}
.pagination li a {
    background-color: #f1f1f1;
    color: #777!important;
    border-radius: 0!important;
    text-decoration: none!important;
    margin-left: 3px;
}
.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
    background-color: #36c6d3;
    cursor: default;
}
.select2-selection__rendered {
    height: 30px;
}
.select2-container--default .select2-selection--multiple .select2-selection__arrow:before, .select2-container--default .select2-selection--single .select2-selection__arrow:before {
    content: '';
}
.fa-search {
    margin-top: -5px ;
}
.select2-result-repository {
    padding-top: 4px;
    padding-bottom: 3px;
}
.select2-result-repository__avatar {
    float: left;
    width: 60px;
    margin-right: 10px;
}
.select2-result-repository__avatar img {
    width: 100%;
    height: auto;
    border-radius: 2px;
}
.select2-result-repository__meta {
    margin-left: 70px;
}
.select2-container--open {
    z-index: 100 !important;
}
</style>
<div>
    <div class="row ">
      <div class="col-12 align-self-end" style="z-index: 800">
        <div class="form-group">
            <div class="input-group ele-render-select2">
                <select id="single-append-text-author" class="select2-author select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                    <option></option>
                </select>
            </div>
        </div>
        </div>
    </div>
</div>
<br>
<input type="hidden" value="{{$options['data-old-member']}}" id="old_member_id">
<table class="table table-striped table-hover vertical-middle dataTable no-footer" style="text-align: center;">
    <thead>
        <th style="text-align: center;width: 150px;">ID</th>
        <th >Nickname</th>
    </thead>
    <tbody id="data-search">
        @if(!empty($options['data-value']))
        <tr role="row" class="odd">
            <td>
                {{$options['data-value']->id}}
            </td>
            <td style="text-align: left">
                {{$options['data-value']->nickname}}
            </td>
        </tr>
        @else
        <tr role="row" class="odd">
            <td colspan="2">
                No value
            </td>
        </tr>
        @endif
    </tbody>
</table>
<br>
  <script>
    $(document).ready(function() {

        function formatState (state) {
            if (!state.id) {
                return state.name;
            }
            var id_login = state.id_login;
            var nickname = state.nickname;
            var email = state.email;
            var avatar = state.account_image;

            var html = `
                    <div class="select2-result-repository clearfix">
                            <div class="select2-result-repository__avatar">
                                <img src="${avatar}">
                            </div>
                            <div class="select2-result-repository__meta">
                                <div class="select2-result-repository__title">${email}</div>
                                <div class="select2-result-repository__description">
                                    <span>${id_login}</span>
                                </div>
                                <div class="select2-result-repository__description">
                                    <span>${nickname}</span>
                                </div>
                            </div>
                    </div>`;
            var $state = $(html);
            return $state;
        };

        $('.select2-author').each(function () {
            let $parent = $(this).closest('.ele-render-select2');
            let $idRoom = $('#idRoom').val();
            $(this).select2({
                width: '100%',
                dropdownParent: $parent,
                ajax: {
                    url: "{{route('garden.egarden.room.seach.auhor')}}",
                    dataType: 'json',
                    delay: 250, // wait 250 milliseconds before triggering the request
                    data: function (params) {
                        return {
                            keyword: params.term, // search term,
                            idRoom : $idRoom
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
                minimumInputLength: 1
            });
        });

        $('.select2-selection__arrow').html(
            '<span class="form-control__icon"><i class="fa fa-search" aria-hidden="true"></i></span>'
        );

        function formatRepoSelection (repo) {
            $('.select2-container--default .select2-selection--single .select2-selection__clear ').html(
                '<span class="form-control__icon"><i class="fa fa-search" aria-hidden="true"></i></span>'
            );
            return  repo.name;
        }

        $('.select2-author').on('select2:select', function (e) {
            e.preventDefault();
            var data = e.params.data;
            var id = data.id;
            var old_member = $('#old_member_id').val();
            var idRoom = $('#idRoom').val();

            e.preventDefault();
                var $this = $(this);
                if($this.data('isRunAjax')==true){return;}
                $this.css('pointer-events', 'none').data('isRunAjax', true);
                $.ajax({
                    type:'POST',
                    url: '{{route('garden.egarden.room.author.add')}}',
                    data:{
                            _token: "{{ csrf_token() }}",
                            'id' : id,
                            'idRoom' : idRoom,
                            'old_member' : old_member
                    },
                    dataType: 'json',
                    success: function( data ) {
                        $('#data-search').html(data.member);
                        $('#old_member_id').val(data.old_member);
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
        let id = $(this).attr('data-id');
        let idRoom = $('#idRoom').val();
        $.ajax({
            type:'POST',
            url: '{{route('garden.egarden.room.member.remove')}}',
            data:{
                    _token: "{{ csrf_token() }}",
                    'id' : id,
                    'idRoom' : idRoom
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

