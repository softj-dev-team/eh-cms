<div class="row">
    <input type="hidden" name="id_calculator" value="{{$item->id}}">
    <div class="col-md-6">
        <?php
            $item_group_1 = $item->group(1)->get();
            $item_group_2 = $item->group(2)->get();
            $item_group_3 = $item->group(3)->get();
            $item_group_4 = $item->group(4)->get();

            $count_1 = count($item_group_1) > 3 ? count($item_group_1) : 3 ;
            $count_2 = count($item_group_2) > 3 ? count($item_group_2) : 3 ;
            $count_3 = count($item_group_3) > 2 ? count($item_group_3) : 2 ;
            $count_4 = count($item_group_4) > 2 ? count($item_group_4) : 2 ;
        ?>
        <table class="table table-bordered center text-sm" id="grade_1_1_{{$item->id}}">
            <thead>
                <tr class="nopadding">
                    <th colspan="4">1학기</th>
                </tr>
                <tr class="bgcolor-gray">
                    <th style="width: 74px">구분</th>
                    <th style="width: 234px">강의명</th>
                    <th style="width: 50px">학점</th>
                    <th style="width: 50px">성적</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < $count_1; $i++)
                <tr>

                    <td class="nopadding bgcolor-light-gray">
                        <select class="full-width noborder form-small not-dark noborder input-division" name="{{$item->id}}classification[1][]" data-dropup-auto="false" >
                            @foreach ($item->getClassification() as $subitem)
                                <option value="{{$subitem['value']}}" @if(isset($item_group_1[$i]) && $item_group_1[$i]->classification == $subitem['value']) selected @endif >{{$subitem['label']}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="nopadding">
                        <input type="text" name="{{$item->id}}description[1][]" class="sm-form-control form-small not-dark noborder input-course" placeholder="강의명"
                            value="@if(isset($item_group_1[$i])){{$item_group_1[$i]->description}}@endif"
                        >
                    </td>
                    <td class="nopadding">
                        <input type="number" name="{{$item->id}}point[1][]" class="sm-form-control resume-grade form-small not-dark noborder center input-credit" placeholder="학점"
                            value="@if(isset($item_group_1[$i])){{$item_group_1[$i]->point}}@endif"
                        >
                    </td>
                    <td class="nopadding">
                        <select style="padding:0px" class="full-width noborder form-small not-dark noborder bgcolor-light-gray input-grades" name="{{$item->id}}grades[1][]" data-dropup-auto="false" >
                            @foreach ($item->getGrades() as $subitem)
                                <option value="{{$subitem['value']}}" @if(isset($item_group_1[$i]) && $item_group_1[$i]->grades == $subitem['value']) selected @endif>{{$subitem['label']}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                @endfor

                    <tr class="bgcolor-sr-light-blue">
                        <td colspan="4">
                            <a class="btn-add-grade-row" id="1_1_{{$item->id}}">
                                <p class="nobottommargin color-sr-blue pointer" data-group="1" data-item-id="{{$item->id}}">추가하기</p>
                            </a>
                        </td>
                    </tr>

                    <tr class="bgcolor-sr-light-gray" style="border: 1px solid #EC1469;">
                      <td colspan="4">
                        <a class="btn-add-grade-row" style="padding-top:6px;padding-bottom:6px;" id="1_1_{{$item->id}}">
                          <p class="remover" style="margin-bottom: 0" data-group="1">삭제하기</p>
                        </a>
                      </td>
                    </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-bordered center text-sm" id="grade_1_2_{{$item->id}}">
            <thead>
                <tr class="nopadding">
                    <th colspan="4">2학기</th>
                </tr>
                <tr class="bgcolor-gray">
                    <th style="width: 74px">구분</th>
                    <th style="width: 234px">강의명</th>
                    <th style="width: 50px">학점</th>
                    <th style="width: 50px">성적</th>
                </tr>
            </thead>
            <tbody>
                    @for ($i = 0; $i < $count_2; $i++)
                        <tr>
                            <td class="nopadding bgcolor-light-gray">
                                <select class="full-width noborder form-small not-dark noborder input-division" name="{{$item->id}}classification[2][]" data-dropup-auto="false" >
                                    @foreach ($item->getClassification() as $subitem)
                                        <option value="{{$subitem['value']}}" @if(isset($item_group_2[$i]) && $item_group_2[$i]->classification == $subitem['value']) selected @endif >{{$subitem['label']}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="nopadding">
                                <input type="text" name="{{$item->id}}description[2][]" class="sm-form-control form-small not-dark noborder input-course" placeholder="강의명"
                                    value="@if(isset($item_group_2[$i])){{$item_group_2[$i]->description}}@endif"
                                >
                            </td>
                            <td class="nopadding">
                                <input type="text" name="{{$item->id}}point[2][]" class="sm-form-control resume-grade form-small not-dark noborder center input-credit" placeholder="학점"
                                    value="@if(isset($item_group_2[$i])){{$item_group_2[$i]->point}}@endif"
                                >
                            </td>
                            <td class="nopadding">
                                <select style="padding:0px" class="full-width noborder form-small not-dark noborder bgcolor-light-gray input-grades" name="{{$item->id}}grades[2][]" data-dropup-auto="false" >
                                    @foreach ($item->getGrades() as $subitem)
                                        <option value="{{$subitem['value']}}" @if(isset($item_group_2[$i]) && $item_group_2[$i]->grades == $subitem['value']) selected @endif>{{$subitem['label']}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endfor

                    <tr class="bgcolor-sr-light-blue">
                        <td colspan="4">
                            <a class="btn-add-grade-row" id="1_2_{{$item->id}}">
                                <p class="nobottommargin color-sr-blue pointer" data-group="2">추가하기</p>
                            </a>
                        </td>
                    </tr>

                    <tr class="bgcolor-sr-light-gray" style="border: 1px solid #EC1469;">
                      <td colspan="4">
                        <a class="btn-add-grade-row" style="padding-top:6px;padding-bottom:6px;" id="1_2_{{$item->id}}">
                          <p class="remover" style="margin-bottom: 0" data-group="1">삭제하기</p>
                        </a>
                      </td>
                    </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-bordered center text-sm" id="grade_2_1_{{$item->id}}" style="border-bottom: 1px solid #EC1469;">
            <thead>
                <tr class="nopadding">
                    <th colspan="4">여름계절학기</th>
                </tr>
                <tr class="bgcolor-gray">
                    <th style="width: 74px">구분</th>
                    <th style="width: 234px">강의명</th>
                    <th style="width: 50px">학점</th>
                    <th style="width: 50px">성적</th>
                </tr>
            </thead>
            <tbody>
                    @for ($i = 0; $i < $count_3; $i++)
                        <tr>
                            <td class="nopadding bgcolor-light-gray">
                                <select class="full-width noborder form-small not-dark noborder input-division" name="{{$item->id}}classification[3][]" data-dropup-auto="false" >
                                    @foreach ($item->getClassification() as $subitem)
                                        <option value="{{$subitem['value']}}" @if(isset($item_group_3[$i]) && $item_group_3[$i]->classification == $subitem['value']) selected @endif >{{$subitem['label']}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="nopadding">
                                <input type="text" name="{{$item->id}}description[3][]" class="sm-form-control form-small not-dark noborder input-course" placeholder="강의명"
                                    value="@if(isset($item_group_3[$i])){{$item_group_3[$i]->description}}@endif"
                                >
                            </td>
                            <td class="nopadding">
                                <input type="text" name="{{$item->id}}point[3][]" class="sm-form-control resume-grade form-small not-dark noborder center input-credit" placeholder="학점"
                                    value="@if(isset($item_group_3[$i])){{$item_group_3[$i]->point}}@endif"
                                >
                            </td>
                            <td class="nopadding">
                                <select style="padding:0px" class="full-width noborder form-small not-dark noborder bgcolor-light-gray input-grades" name="{{$item->id}}grades[3][]" data-dropup-auto="false" >
                                    @foreach ($item->getGrades() as $subitem)
                                        <option value="{{$subitem['value']}}" @if(isset($item_group_3[$i]) && $item_group_3[$i]->grades == $subitem['value']) selected @endif>{{$subitem['label']}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endfor

                    <tr class="bgcolor-sr-light-blue">
                      <td colspan="4">
                        <a class="btn-add-grade-row" id="2_1_{{$item->id}}">
                          <p class="nobottommargin color-sr-blue pointer" data-group="3">추가하기</p>
                        </a>
                      </td>
                    </tr>

                    <tr class="bgcolor-sr-light-gray" style="border: 1px solid #EC1469;">
                      <td colspan="4">
                        <a class="btn-add-grade-row" style="padding-top:6px;padding-bottom:6px;" id="2_1_{{$item->id}}">
                          <p class="remover" style="margin-bottom: 0" data-group="1">삭제하기</p>
                        </a>
                      </td>
                    </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-bordered center text-sm" id="grade_2_2_{{$item->id}}" style="border-bottom: 1px solid #EC1469;">
            <thead>
                <tr class="nopadding">
                    <th colspan="4">겨울계절학기</th>
                </tr>
                <tr class="bgcolor-gray">
                    <th style="width: 74px">구분</th>
                    <th style="width: 234px">강의명</th>
                    <th style="width: 50px">학점</th>
                    <th style="width: 50px">성적</th>
                </tr>
            </thead>
            <tbody>
                    @for ($i = 0; $i < $count_4; $i++)
                        <tr>
                            <td class="nopadding bgcolor-light-gray">
                                <select class="full-width noborder form-small not-dark noborder input-division" name="{{$item->id}}classification[4][]" data-dropup-auto="false" >
                                    @foreach ($item->getClassification() as $subitem)
                                        <option value="{{$subitem['value']}}" @if(isset($item_group_4[$i]) && $item_group_4[$i]->classification == $subitem['value']) selected @endif >{{$subitem['label']}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="nopadding">
                                <input type="text" name="{{$item->id}}description[4][]" class="sm-form-control form-small not-dark noborder input-course" placeholder="강의명"
                                    value="@if(isset($item_group_4[$i])){{$item_group_4[$i]->description}}@endif"
                                >
                            </td>
                            <td class="nopadding">
                                <input type="text" name="{{$item->id}}point[4][]" class="sm-form-control resume-grade form-small not-dark noborder center input-credit" placeholder="학점"
                                    value="@if(isset($item_group_4[$i])){{$item_group_4[$i]->point}}@endif"
                                >
                            </td>
                            <td class="nopadding">
                                <select style="padding:0px" class="full-width noborder form-small not-dark noborder bgcolor-light-gray input-grades" name="{{$item->id}}grades[4][]" data-dropup-auto="false" >
                                    @foreach ($item->getGrades() as $subitem)
                                        <option value="{{$subitem['value']}}" @if(isset($item_group_4[$i]) && $item_group_4[$i]->grades == $subitem['value']) selected @endif>{{$subitem['label']}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endfor

                    <tr class="bgcolor-sr-light-blue">
                      <td colspan="4">
                        <a class="btn-add-grade-row" id="2_2_{{$item->id}}">
                          <p class="nobottommargin color-sr-blue pointer" data-group="4">추가하기</p>
                        </a>
                      </td>
                    </tr>

                    <tr class="bgcolor-sr-light-gray" style="border: 1px solid #EC1469;">
                      <td colspan="4">
                        <a class="btn-add-grade-row" style="padding-top:6px;padding-bottom:6px;" id="2_2_{{$item->id}}">
                          <p class="remover" style="margin-bottom: 0" data-group="1">삭제하기</p>
                        </a>
                      </td>
                    </tr>
            </tbody>
        </table>
    </div>
</div>
