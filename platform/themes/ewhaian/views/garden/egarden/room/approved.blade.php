<style>
        .bg-green {
            background-color: rgb(89, 198, 177);
            color: #ffffff;
        }

        .bg-main:hover,
        .bg-main:focus {
            color: #FFFFFF;
        }
    </style>
    <main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
        <div class="container">
            <div class="sidebar-template">
                <div class="sidebar-template__control">
                        <!-- category menu -->
                        <div class="category-menu">
                            <h4 class="category-menu__title">{{__('egarden.room')}}</h4>
                            <ul class="category-menu__links">
                            <li class="category-menu__item">
                                <a href="{{route('egardenFE.room.myroom.list') }}" title="{{__('egarden.room.my_room')}}">{{__('egarden.room.my_room')}}</a>
                            </li>
                            <li class="category-menu__item active">
                                <a title="{{__('egarden.room.approve_member_room')}} #{{$room->id}}">{{__('egarden.room.approve_member_room')}} #{{$room->id}}</a>
                            </li>
                            </ul>
                        </div>
                        <!-- end of category menu -->
                </div>
                <div class="sidebar-template__content">
                    <ul class="breadcrumb">
                        <li>
                            <a href="{{route('egardenFE.room.myroom.list') }}" title="{{__('egarden.room.my_room')}}">{{__('egarden.room.my_room')}}</a>
                        </li>
                        <li>
                            <svg width="4" height="6" aria-hidden="true" class="icon">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                            </svg>
                        </li>
                        <li>{{__('egarden.room')}}</li>
                    </ul>
                    <div class="heading">
                        <div class="heading__title">
                            {{__('egarden.room')}}
                        </div>
                    </div>
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="content event-list">
                        <div class="row mb-10" @if(count($members) <=0 ) style="justify-content: center;" @endif>

                                <table class="table table--custom table--event-comments" style="border-collapse:collapse;">
                                    <thead>
                                        <tr>
                                            <th class="table__col table__col--action">{{__('egarden.room.no')}}</th>
                                            <th class="table__col table__col--category">{{__('egarden.room.member')}}</th>
                                            <th class="table__col table__col--action">{{__('egarden.room.action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($members)>0)

                                        @foreach ($members as $key => $item)
                                        <tr>
                                            <td class="table__col table__col--no">{{$key+1}}</td>
                                            <td class="table__col table__col--category table__col--has-label"
                                                data-content="Category">{{$item->nickname}}</td>
                                            <td class="table__col table__col--title">{!! highlightWords2(
                                                $item->title,request('keyword') ) !!}</td>
                                            <td class="table__col table__col--action table__col--has-label"
                                                data-content="View more detail">
                                                <form action="{{route('egardenFE.room.approved',['id'=>$room->id])}}" id="my_form{{$key}}" method="POST">
                                                    @csrf
                                                        <input type="hidden" name="member_id" value="{{$item->id}}">

                                                        <a class="alert bg-main" href="javascript:void(0)" onclick="document.getElementById('my_form{{$key}}').submit();" style="display: block;margin-left: 10px;width: 100px" title="{{__('egarden.room.approved')}}"> {{__('egarden.room.approved')}}</a>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="3" style="text-align: center">{{__('egarden.room.no_members_need_approved')}}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                        </div>
                    </div>
                    {!! Theme::partial('paging',['paging'=>$members->appends(request()->input()) ]) !!}
                </div>

            </div>

        </div>
    </main>
