@if (!empty($options['data-value']))
  <style>
    .pagination > li > a:focus, .pagination > li > a:hover, .pagination > li > span:focus, .pagination > li > span:hover {
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
      color: #777 !important;
      border-radius: 0 !important;
      text-decoration: none !important;
      margin-left: 3px;
    }

    .pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
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
      margin-top: -5px;
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

    .owner-label {
      margin: 20px 0;
    }
  </style>
  <div>
    <div class="row ">
      <div class="col-6 align-self-end" style="z-index: 800">
        <div class="form-group">
          <div class="input-group ele-render-select2">
            <select id="single-append-text" class="select2-allow-clear select2-hidden-accessible" tabindex="-1"
                    aria-hidden="true">
              <option></option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br>

  <table class="table table-striped table-hover vertical-middle dataTable no-footer" style="text-align: center;">
    <thead>
    <th style="text-align: center;width: 150px;">ID</th>
    <th>Nickname</th>
    <th style="text-align: center;width: 150px;">Operations</th>
    </thead>
    <tbody>
    @if(count($options['data-value']) == 0)
      <tr role="row" class="even">
        <td colspan="3" style="text-align: center">
          No have member
        </td>
      </tr>
    @else
      @foreach ($options['data-value'] as $key => $item)
        <tr role="row" class="{{$key % 2 ==0 ? 'odd' : 'even'}}">
          <td>
            {{$item->id}}
          </td>
          <td style="text-align: left">
            {{$item->nickname}}
          </td>
          <td>
            <div class="table-actions">
              <a href="#" class="btn btn-icon btn-sm btn-danger deleteDialog tip"
                 data-toggle="modal"
                 role="button"
                 data-id="{{$item->id}}"
                 data-original-title="삭제">
                <i class="fa fa-trash"></i>
              </a>
            </div>
          </td>
        </tr>
      @endforeach
    @endif
    </tbody>
  </table>
  <br>
  <?php $paging = $options['data-value'] ?>
  @if ($paging->hasPages() && $paging->currentPage() <= $paging->lastPage() )
    <!-- Pagination -->
    <nav aria-label="Pagination">
      <ul class="pagination pagination--custom" style="justify-content: flex-end">
        <li class="page-item  @if($paging->currentPage() == 1) disabled @endif ">
          <a class="page-link" href="{{$paging->previousPageUrl()}}" title="Prev">
            « Previous
          </a>
        </li>
        @if($paging->lastPage() <= 10)
          @for ($i = 1; $i <= $paging->lastPage();$i++)
            <li class="page-item @if($paging->currentPage() == $i) active @endif">
              <a class="page-link" href="{{ $paging->url($i)}}" title="{{$i}}">{{$i}}</a>
            </li>
          @endfor
        @else
          <?php
          $mod = (int)fmod($paging->currentPage(), 10);
          $quotient = (int)($paging->currentPage() / 10);
          if ($quotient - 1 < 0) {
            $quotient = 0;
          }
          ?>
          @switch($mod)
            @case(0)
            @if($quotient > 0)
              @for ($i = ($quotient-1) * 10 + 1; $i <=  ($quotient - 1) * 10 + 10 ;$i++)
                <li class="page-item @if($paging->currentPage() == $i) active @endif">
                  <a class="page-link" href="{{ $paging->url($i)}}" title="{{$i}}">{{$i}}</a>
                </li>
              @endfor
            @else
              @for ($i = ($quotient) * 10 + 1; $i <=  ($quotient) * 10 + 10 ;$i++)
                <li class="page-item @if($paging->currentPage() == $i) active @endif">
                  <a class="page-link" href="{{ $paging->url($i)}}" title="{{$i}}">{{$i}}</a>
                </li>
              @endfor
            @endif
            @break
            @case(1)
            @if($paging->currentPage() < $paging->lastPage())
              @for ($i = $paging->currentPage(); $i <= $paging->currentPage() + 9;$i++)
                <li class="page-item @if($paging->currentPage() == $i) active @endif">
                  <a class="page-link" href="{{ $paging->url($i)}}" title="{{$i}}">{{$i}}</a>
                </li>
              @endfor
              @break
            @endif
            @default
            @for ($i = $quotient * 10 + 1; $i <=  $quotient * 10 + 10 ;$i++)
              <li class="page-item @if($paging->currentPage() == $i) active @endif">
                <a class="page-link" href="{{ $paging->url($i)}}" title="{{$i}}">{{$i}}</a>
              </li>
            @endfor
            @break
          @endswitch
        @endif

        <li class="page-item @if($paging->currentPage() == $paging->lastPage()) disabled @endif">
          <a class="page-link rotate-180" href="{{$paging->nextPageUrl()}}" title="Next">
            Next »
          </a>
        </li>
      </ul>
    </nav>
  @endif
  <input type="hidden" name="idRoom" id="idRoom" value="{{$options['idRoom']}}">
  <script>
    $(document).ready(function () {

      function formatState(state) {
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

      $('.select2-allow-clear').each(function () {
        let $parent = $(this).closest('.ele-render-select2');
        let $idRoom = $('#idRoom').val();
        $(this).select2({
          width: '90%',
          dropdownParent: $parent,
          ajax: {
            url: "{{route('garden.egarden.room.seach.member')}}",
            dataType: 'json',
            delay: 250, // wait 250 milliseconds before triggering the request
            data: function (params) {
              return {
                keyword: params.term, // search term,
                idRoom: $idRoom
              };
            },
            processResults: function (results) {
              return {
                results: results.items,
              };
            },
            cache: false
          },
          placeholder: 'Select a state',
          allowClear: true,
          templateResult: formatState,
          templateSelection: formatRepoSelection,
          minimumInputLength: 1
        });
      });

      $('.select2-selection__arrow').html(
        '<span class="form-control__icon"><i class="fa fa-search" aria-hidden="true"></i></span>'
      );

      function formatRepoSelection(repo) {
        $('.select2-container--default .select2-selection--single .select2-selection__clear ').html(
          '<span class="form-control__icon"><i class="fa fa-search" aria-hidden="true"></i></span>'
        );
        return repo.name;
      }

      $('.select2-allow-clear').on('select2:select', function (e) {
        e.preventDefault();
        var data = e.params.data;
        var id = data.id;
        var idRoom = $('#idRoom').val();

        e.preventDefault();
        var $this = $(this);
        if ($this.data('isRunAjax') == true) {
          return;
        }
        $this.css('pointer-events', 'none').data('isRunAjax', true);
        $.ajax({
          type: 'POST',
          url: '{{route('garden.egarden.room.member.add')}}',
          data: {
            _token: "{{ csrf_token() }}",
            'id': id,
            'idRoom': idRoom
          },
          dataType: 'json',
          success: function (data) {
            if (data.msg == true) {
              location.reload();
            }
          }
        }).always(function () {
          $this.css('pointer-events', 'auto').data('isRunAjax', false);
        });
      });

    });

    $(document.body).on('click', '.deleteDialog', function () {
      let id = $(this).attr('data-id');
      $('.modal-confirm-delete').modal('toggle');
      $('.delete-crud-entry').attr('data-id', id);
    });
    $(document.body).on('click', '.delete-crud-entry', function () {
      let id = $(this).attr('data-id');
      let idRoom = $('#idRoom').val();
      $.ajax({
        type: 'POST',
        url: '{{route('garden.egarden.room.member.remove')}}',
        data: {
          _token: "{{ csrf_token() }}",
          'id': id,
          'idRoom': idRoom
        },
        dataType: 'json',
        success: function (data) {
          if (data.msg == true) {
            location.reload();
          }
        }
      }).always(function () {
        $this.css('pointer-events', 'auto').data('isRunAjax', false);
      });
    })
  </script>
@endif

<h3 class="owner-label">
  Owner Applications
</h3>

<table class="table table-striped table-hover vertical-middle dataTable no-footer" style="text-align: center;">
  <thead>
  <th style="text-align: center;width: 150px;">ID</th>
  <th>Nickname</th>
  <th style="text-align: center;width: 150px;">Operations</th>
  </thead>
  <tbody>
  @if(count($options['owner_applications']) == 0)
    <tr role="row" class="even">
      <td colspan="3" style="text-align: center">
        No have member
      </td>
    </tr>
  @else
    @foreach ($options['owner_applications'] as $key => $application)
      <tr role="row" class="{{$key % 2 ==0 ? 'odd' : 'even'}}">
        <td>
          {{ $application->member->id }}
        </td>
        <td style="text-align: left">
          {{ $application->member->nickname }}
        </td>
        <td>
          <div class="table-actions">
            <a href="#" class="btn btn-icon btn-sm btn-primary tip approveDialog" data-toggle="modal" role="button"
               data-id="{{ $application->id }}" data-nickname="{{ $application->member->nickname }}">
              <i class="fa fa-check-circle"></i>
            </a>
          </div>
        </td>
      </tr>
    @endforeach
  @endif
  </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="approveOwnership" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <span id="text-approve"></span>
        <input type="hidden" id="applicationId"/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
        <button type="button" class="btn btn-primary" onclick="approveOwnership()">저장</button>
      </div>
    </div>
  </div>
</div>

<script>
  function approveOwnership() {
    let applicationId = $('#applicationId').val();

    if (!applicationId) {
      return alert('Please choose member');
    }

    $.ajax({
      type: 'POST',
      url: '{{ route('approve-ownership') }}',
      data: {
        _token: "{{ csrf_token() }}",
        application_id: applicationId,
      },
      dataType: 'json',
      success: function (data) {
        if (data.error === false) {
          Botble.showNotice('success', data.message);
          location.reload();
        } else {
          Botble.showNotice('error', data.message);
        }
      }
    });
  }

  $(document).ready(function () {
    $('.approveDialog').click(function () {
      $('#applicationId').val($(this).data('id'));
      let nickname = $(this).data('nickname');

      $('#text-approve').html('Do you want to set "' + nickname + '" as the room owner?');
      $('#approveOwnership').modal('show');
    });
  });
</script>
