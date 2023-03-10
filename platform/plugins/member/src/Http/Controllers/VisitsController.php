<?php

namespace Botble\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use Botble\Member\Models\Member;
use Botble\Member\Tables\VisitDailyTable;
use Botble\Member\Tables\VisitMonthlyTable;
use Botble\Member\Tables\VisitUserTypesTable;
use Botble\Member\Tables\VisitWeeklyTable;

class VisitsController extends Controller
{
    public function getVisitDaily(VisitDailyTable $table)
    {
        page_title()->setTitle("목록");

        return $table->renderTable();
    }
    public function getVisitWeekly(VisitWeeklyTable $table)
    {
        page_title()->setTitle("목록");

        return $table->renderTable();
    }
    public function getVisitMonthly(VisitMonthlyTable $table)
    {
        page_title()->setTitle("목록");

        return $table->renderTable();
    }
    public function getVisitUserTypes(VisitUserTypesTable $table)
    {
        page_title()->setTitle("목록");

        return $table->renderTable();
    }
}
