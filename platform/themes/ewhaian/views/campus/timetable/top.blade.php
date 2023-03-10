<style>
	.btn_active {
		background-color: #ec1469;
		color: #fff;
	}
</style>
<main id="main-content" data-view="timetable" class="home-page ewhaian-page">
	<div class="container">
		<div class="sidebar-template">
			<div class="sidebar-template__control"></div>
				<div class="nav nav-left">
					<ul class="nav__list">
						</a>
						<div class="nav__title">
							<a href="#" class="active" title="Add timeline" class="btn btn-default" data-toggle="modal"
								data-target="#schedulePopup">
								<img src="/themes/ewhaian/img/time_add_btn.jpg" width="25" id="addBtn">
							</a>
						</div>
					</ul>
				</div>
				<div class="btn-template">
					@foreach ($scheduleAll as $item)
					<a href="{{route('scheduleFE.timeline.top',['schedule_id'=>$item->id])}}" title=" {{$item->name}}"
						class="@if($item->id == $schedule->id) btn__ehw btn_active  @else  btn__ehw btn_line @endif ">
						{{$item->name}}
					</a>
					@endforeach
					@if(!is_null($schedule))
					<form action="{{route('scheduleFE.timeline.delete')}}" id="myform" method="POST">
						@csrf
						<input type="hidden" name="schedule_id" value="{{$schedule->id ?? 0}}">
						<input type="hidden" name="active_filter" value="{{ request()->active_filter }}">
						@if( is_null($schedule->timeline()->first() ) )

						<input type="hidden" name="deleteSchedule" value="true">

						@endif

						<a class="btn__trash" onclick="document.getElementById('myform').submit()">
							직접 추가하기
						</a>
					</form>
					@endif

					<div class="btn_line">
						<div class="btn__ehw grey">
							복사
						</div>
						<div class="btn__ehw grey">
							붙여넣기
						</div>
					</div>
					<a href="#" title="Add timeline" class="btn__ehw white-border" data-toggle="modal"
						data-target="#timelinePopup">
						직접 추가하기
					</a>
				</div>
			</div>
			<div class="sidebar-template__content">
				<ul class="breadcrumb">
					<li><a href="" title="Event">Campus</a></li>
					<li>
						<svg width="4" height="6" aria-hidden="true" class="icon">
							<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
						</svg>
					</li>
					<li>
						Timetable
					</li>

				</ul>
				<div class="heading">
					<div class="heading__title">
						Timetable
					</div>
					<p class="heading__description">
						{!!$description->description ?? ""!!}
					</p>
				</div>
				@if (session('err'))
				<div class="alert alert-danger" style="width:100% ; justify-content: center;">
					{{ session('err') }}
				</div>
				@endif

				@if (session('success'))
				<div class="alert alert-success" style="width:100%;justify-content: center;">
					{{ session('success') }}
				</div>
				@endif
				<div class="content">
					<div class="timetable" data-timetable="data-timetable"
						data-json='@if(!is_null($schedule)){{json_encode( $schedule->timeline()->select('id','title','day','from','to')->where('status','publish')->get() ) }}@endif'
						data-config='{{json_encode( $scheduleTime)}}'>
						{{-- <div class="timetable__schedule-list d-flex flex-wrap mb-3 pb-3">
								<div class="timetable__btn-group">
									<button class="btn btn-default btn-icon">
										<svg width="17" height="17" aria-hidden="true" class="icon">
											<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_setting">
											</use>
										</svg>
									</button>
									<button class="btn btn-default btn-icon">
										<svg width="18" height="15.781" aria-hidden="true" class="icon">
											<use xmlns:xlink="http://www.w3.org/1999/xlink"
												xlink:href="#icon_menu-list"></use>
										</svg>
									</button>
								</div>
							</div> --}}
						<div class="timetable__content">
							<div class="d-flex align-items-center">
								<div class="timetable__header flex-grow-1">
									<h3>{{$schedule->name ?? 'No have Schedule'}}</h3>
									@if(!is_null($schedule))
									<div class="timetable__time">
										<span class="timetable__circle">
											<svg width="10" height="13" aria-hidden="true" class="icon">
												<use xmlns:xlink="http://www.w3.org/1999/xlink"
													xlink:href="#icon_datetime"></use>
											</svg>
										</span>

										{{ date('Y/m/d', strtotime($schedule->start))}} -
										{{ date('Y/m/d', strtotime($schedule->end))}}

									</div>
									@endif
								</div>
							</div>
							@if(!is_null($schedule))
							<div class="timetable__content-schedule">
								<ul
									class="timetable__content-schedule__header d-flex align-items-center justify-content-around">
									@foreach ($scheduleDay as $item)
									<li>{{ ucfirst( $item->name ) }}</li>
									@endforeach
								</ul>
								<div class="d-flex align-items-center">
									<div class="d-flex flex-column align-items-center">
										@for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i++)
											<div class="timetable__content-number">{{$i}}</div>
											@endfor
									</div>
									<div class="d-flex flex-grow-1">
										<div class="row mx-0 flex-grow-1">
											@foreach ($scheduleDay as $item)
											<div class="col px-0 timetable__day" data-day="{{$item->name}}">
												@for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i = $i +
													$scheduleTime->unit)
													<div class="timetable__content-day" data-start="{{$i}}"></div>
													@endfor
											</div>
											@endforeach
										</div>
									</div>

								</div>

							</div>
							@endif
						</div>

					</div>
				</div>

			</div>

		</div>

	</div>
	<!-- Modal -->
	<div class="modal fade modal--border" id="schedulePopup" tabindex="-1" role="dialog"
		aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-md " role="document">
			<div class="modal-content">
				<div class="modal-body">
					<form action="{{route('scheduleFE.create')}}" method="post">
						@csrf
						<h3 class="text-bold mt-2 py-3 text-center">Create New Schedule</h3>
						<div class="form-group form-group--1 mb-4">
							<label for="scheduleName" class="form-control">
								<input type="text" required id="scheduleName" name="name" placeholder="&nbsp;">
								<span class="form-control__label"> Schedule name <span class="required">*</span></span>
							</label>
						</div>
						<span class="text-bold mr-3">
							Application period
							<span class="required">*</span>
						</span>
						<div class="flex-grow-1 mb-4">
							<div class="d-flex align-items-center">
								<div class="form-group form-group--search  flex-grow-1 mr-1">
									<span class="form-control__icon form-control__icon--gray">
										<svg width="20" height="17" aria-hidden="true" class="icon">
											<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_calender">
											</use>
										</svg>
									</span>
									<input data-datepicker-start type="text" class="form-control startDate"
										id="startDate" name="start" value="{{getToDate(1) }} " autocomplete="off">
								</div>
								<span class="filter__connect">~</span>
								<div class="form-group form-group--search  flex-grow-1 ml-1">
									<span class="form-control__icon form-control__icon--gray">
										<svg width="20" height="17" aria-hidden="true" class="icon">
											<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_calender">
											</use>
										</svg>
									</span>
									<input data-datepicker-end type="text" class="form-control endDate" id="endDate"
										name="end" value="{{  getToDate(1) }} " autocomplete="off">
								</div>
							</div>
						</div>
						<div class="button-group my-4 text-center">
							<button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary">Create</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal-->
	{{-- Add Timeline --}}
	<div class="modal fade modal--border" id="timelinePopup" tabindex="-1" role="dialog"
		aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<form action="{{route('scheduleFE.timeline.create')}}" method="post">
			@csrf
			<input type="hidden" name="schedule_id" value="{{$schedule->id ?? 0}}">
			<div class="modal-dialog modal-dialog-centered modal-md " role="document">
				<div class="modal-content">
					<div class="modal-body">
						<div class="modal__header mt-2 py-3 ">
							<h3 class="text-bold text-center">Add Timeline</h3>
							<p class="modal__header-description mb-0">__ Schedule __</p>
						</div>
						<div class="form-group form-group--1 mb-4">
							<label for="subjectName" class="form-control">
								<input type="text" required id="subjectName" name="title" placeholder="&nbsp;">
								<span class="form-control__label"> Subject name <span class="required">*</span></span>
							</label>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group form-group--select">
									<label class="form-control__label">On <span class="required">*</span></label>
									<select class="form-control form-control--select" name="day">
										@foreach ($scheduleDay as $item)
										<option value="{{$item->name}}">{{ ucfirst ($item->name ) }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col">
								<div class="form-group form-group--select">
									<label class="form-control__label">From <span class="required">*</span></label>
									<select class="form-control form-control--select" name="from">
										@for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i++)
											<option value="{{$i}}">{{$i.':00'}}</option>
											<option value="{{$i+0.5}}">{{$i.':30'}}</option>
											@endfor
									</select>
								</div>
							</div>
							<div class="col">
								<div class="form-group form-group--select">
									<label class="form-control__label">To <span class="required">*</span></label>
									<select class="form-control form-control--select" name="to">
										@for ($i = $scheduleTime->from; $i <= $scheduleTime->to; $i++)
											<option value="{{$i+0.5}}">{{$i.':30'}}</option>
											<option value="{{$i+1}}">{{$i.':00'}}</option>
											@endfor
									</select>
								</div>
							</div>
						</div>
						<div class="button-group my-4 text-center">
							<button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary">Create</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</main>
{{-- <script>
		$(function(){
			console.log("run");
			setInterval(function() {
				if(sessionStorage.getItem('test')){
					console.log(sessionStorage.getItem('test'));
				}
			}, 300);
		});
	</script> --}}
