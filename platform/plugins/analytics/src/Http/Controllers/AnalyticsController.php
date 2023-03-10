<?php

namespace Botble\Analytics\Http\Controllers;

use Botble\Analytics\Exceptions\InvalidConfiguration;
use Botble\Analytics\Period;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Analytics;
use Carbon\Carbon;
use Exception;

class AnalyticsController extends BaseController
{

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getGeneral(BaseHttpResponse $response)
    {
        $startDate = Carbon::today(config('app.timezone'))->startOfDay();
        $endDate = Carbon::today(config('app.timezone'))->endOfDay();
        $dimensions = 'hour';

        try {
            $period = Period::create($startDate, $endDate);

            $visitorData = [];

            $answer = Analytics::performQuery($period, 'ga:visits,ga:pageviews', ['dimensions' => 'ga:' . $dimensions]);

            if ($answer->rows == null) {
                return $response;
            }

            if ($dimensions === 'hour') {
                foreach ($answer->rows as $dateRow) {
                    $visitorData[] = [
                        'axis'      => (int)$dateRow[0] . 'h',
                        'visitors'  => $dateRow[1],
                        'pageViews' => $dateRow[2],
                    ];
                }
            } else {
                foreach ($answer->rows as $dateRow) {
                    $visitorData[] = [
                        'axis'      => Carbon::parse($dateRow[0])->toDateString(),
                        'visitors'  => $dateRow[1],
                        'pageViews' => $dateRow[2],
                    ];
                }
            }

            $stats = collect($visitorData);
            $country_stats = Analytics::performQuery($period, 'ga:sessions', ['dimensions' => 'ga:countryIsoCode'])->rows;
            $total = Analytics::performQuery($period, 'ga:sessions, ga:users, ga:pageviews, ga:percentNewSessions, ga:bounceRate, ga:pageviewsPerVisit, ga:avgSessionDuration, ga:newUsers')->totalsForAllResults;

            return $response->setData(view('plugins.analytics::widgets.general', compact('stats', 'country_stats', 'total'))->render());
        } catch (InvalidConfiguration $ex) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/analytics::analytics.wrong_configuration', ['version' => get_cms_version()]));
        } catch (Exception $ex) {
            return $response
                ->setError()
                ->setMessage($ex->getMessage());
        }
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getTopVisitPages(BaseHttpResponse $response)
    {
        $startDate = Carbon::today(config('app.timezone'))->startOfDay();
        $endDate = Carbon::today(config('app.timezone'))->endOfDay();

        try {
            $period = Period::create($startDate, $endDate);
            $pages = Analytics::fetchMostVisitedPages($period, 10);

            return $response->setData(view('plugins.analytics::widgets.page', compact('pages'))->render());
        } catch (InvalidConfiguration $ex) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/analytics::analytics.wrong_configuration', ['version' => get_cms_version()]));
        } catch (Exception $ex) {
            return $response
                ->setError()
                ->setMessage($ex->getMessage());
        }
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getTopBrowser(BaseHttpResponse $response)
    {
        $startDate = Carbon::today(config('app.timezone'))->startOfDay();
        $endDate = Carbon::today(config('app.timezone'))->endOfDay();

        try {
            $period = Period::create($startDate, $endDate);
            $browsers = Analytics::fetchTopBrowsers($period, 10);

            return $response->setData(view('plugins.analytics::widgets.browser', compact('browsers'))->render());
        } catch (InvalidConfiguration $ex) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/analytics::analytics.wrong_configuration', ['version' => get_cms_version()]));
        } catch (Exception $ex) {
            return $response
                ->setError()
                ->setMessage($ex->getMessage());
        }
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getTopReferrer(BaseHttpResponse $response)
    {
        $startDate = Carbon::today(config('app.timezone'))->startOfDay();
        $endDate = Carbon::today(config('app.timezone'))->endOfDay();

        try {
            $period = Period::create($startDate, $endDate);
            $referrers = Analytics::fetchTopReferrers($period, 10);

            return $response->setData(view('plugins.analytics::widgets.referrer', compact('referrers'))->render());
        } catch (InvalidConfiguration $ex) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/analytics::analytics.wrong_configuration', ['version' => get_cms_version()]));
        } catch (Exception $ex) {
            return $response
                ->setError()
                ->setMessage($ex->getMessage());
        }
    }
}
