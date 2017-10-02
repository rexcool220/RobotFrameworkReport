<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\suite;
use App\test;
use App\kw;
use App\kwDetail;
use ZipArchive;

class reportController extends Controller
{
    public function showReport($suiteId)
    {
        $suite = suite::where('suiteId', $suiteId)->get()->toArray();
        $criticalTestsResult = array(
            array('Result', 'Rating'),
            array('criticalTestsFail',$suite[0]['criticalTestsFail']),
            array(' criticalTestsPass',$suite[0]['criticalTestsPass'])
        );

        $encodedCriticalTestsResulta = json_encode($criticalTestsResult);

        $allTestsResult = array(
            array('Result', 'Rating'),
            array('allTestsFail',$suite[0]['allTestsFail']),
            array(' allTestsPass',$suite[0]['allTestsPass'])
        );

        $allTestsResult = json_encode($allTestsResult);

        $tests = test::where('suiteId', $suiteId)->get()->toArray();
        for($i = 0; $i < count($tests); $i++)
        {
            $kws = kw::where('testId', $tests[$i]['testId'])->get()->toArray();
            $tests[$i]['kws'] = $kws;
            for($j = 0; $j < count($kws); $j++)
            {
                $kwDetails = kwDetail::where('kwId', $kws[$j]['kwId'])->get()->toArray();
                $tests[$i]['kws'][$j]['kwDetails'] = $kwDetails;
            }
        }
        return view('report/report', compact('tests', 'suite', 'encodedCriticalTestsResulta', 'allTestsResult'));
    }
    public function uploadFile()
    {
        return view('uploadFile/uploadFile');
    }
    public function processFile(Request $request)
    {
        $file = Input::file('uploadFile');
        $extension = $file->getClientOriginalExtension();
        $file_name = strval(time()).str_random(5).'.'.$extension;

        $destination_path = public_path().'/upload/';

        if (Input::hasFile('uploadFile')) {
            $upload_success = $file->move($destination_path, $file_name);
            $zip = new ZipArchive;
            $res = $zip->open($destination_path.$file_name);
            $dir = dirname($zip->getNameIndex(0));
            if ($res === TRUE) {
                $zip->extractTo($destination_path);
                $zip->close();
            } else {
                $result = "false";
            }

            $result = reportController::report($destination_path . $dir . "/output.xml");
        } else {
            return view('processFile/result');
            $result = "false";
        }
        return view('processFile/result', compact('result'));
    }
    private function report($filename)
    {
        $xml = simplexml_load_file($filename);
        preg_match('/([^\/]+)\/[^\/]+$/', $filename, $matches);
        $dir = $matches[1];

        $statistic = $xml->xpath("/robot/statistics");
        $criticalTestsFail = $statistic[0]->total[0]->stat[0]['fail'];
        $criticalTestsPass = $statistic[0]->total[0]->stat[0]['pass'];
        $allTestsFail = $statistic[0]->total[0]->stat[1]['fail'];
        $allTestsPass = $statistic[0]->total[0]->stat[1]['pass'];
        $error = $xml->xpath("/robot/errors");

        $suites = $xml->xpath("/robot/suite");
        $suitSource = $suites[0]['source'];
        $suiteId = $suites[0]['id'];
        $suiteName = $suites[0]['name'];
        $suiteStatus = $suites[0]->status['status'];
        $suiteEndTime = $suites[0]->status['endtime'];
        $suiteStartTime = $suites[0]->status['starttime'];
        \DB::table('suite')->insert(
            ['created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
                'source' => $suitSource,
                'id' => $suiteId,
                'name' => $suiteName,
                'status' => $suiteStatus == 'PASS' ? true : false,
                'endTime' => $suiteEndTime,
                'startTime' => $suiteStartTime,
                'criticalTestsFail' => $criticalTestsFail,
                'criticalTestsPass' => $criticalTestsPass,
                'allTestsFail' => $allTestsFail,
                'allTestsPass' => $allTestsPass,
                'error' => $error[0]]

        );
        $suiteLastInsertId = \DB::table('suite')->select('suiteId')->max('suiteId');
        foreach($suites[0]->test as $test)
        {
            $testId = $test['id'];
            $testName = $test['name'];
            $testStatus = $test->status['status'];
            $testEndTime = $test->status['endtime'];
            $testCritical = $test->status['critical'];
            $testStartTime = $test->status['starttime'];
            \DB::table('test')->insert(
                ['created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'suiteId' => $suiteLastInsertId,
                    'id' => $testId,
                    'name' => $testName,
                    'status' => $testStatus == 'PASS' ? true : false,
                    'endTime' => $testEndTime,
                    'critical' => $testCritical == 'yes' ? true : false,
                    'startTime' => $testStartTime]
            );
            $testLastInsertId = \DB::table('test')->select('testId')->max('testId');
            foreach($test->kw as $kw)
            {
                $kwName = $kw['name'];
                $kwStatus = $kw->status['status'];
                $kwEndTime = $kw->status['endtime'];
                $kwStartTime = $kw->status['starttime'];
                \DB::table('kw')->insert(
                    ['created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now(),
                        'testId' => $testLastInsertId,
                        'name' => $kwName,
                        'status' => $kwStatus == 'PASS' ? true : false,
                        'endTime' => $kwEndTime,
                        'startTime' => $kwStartTime]
                );
                $kwLastInsertId = \DB::table('kw')->select('kwId')->max('kwId');
                foreach($kw->kw as $kwDetail)
                {
                    $kwDetailImage = "";
                    if($kwDetail['name'] == "Capture Page Screenshot")
                    {
                        preg_match('/img src=\"([^\"]+)\"/', $kwDetail->msg, $matches);
                        $kwDetailImage = "/upload/".$dir."/".$matches[1];
                    }

                    $kwDetailName = $kwDetail['name'];
                    $kwDetailLibrary = $kwDetail['library'];
                    $kwDetailDoc = $kwDetail->doc;
                    $kwDetailMsg = $kwDetail->msg;
                    $kwDetailStatus = $kwDetail->status['status'];
                    $kwDetailEndTime = $kwDetail->status['endtime'];
                    $kwDetailStartTime = $kwDetail->status['starttime'];
                    \DB::table('kwDetail')->insert(
                        ['created_at' => \Carbon\Carbon::now(),
                            'updated_at' => \Carbon\Carbon::now(),
                            'kwId' => $kwLastInsertId,
                            'name' => $kwDetailName,
                            'library' => $kwDetailLibrary,
                            'doc' => $kwDetailDoc,
                            'msg' => $kwDetailMsg,
                            'status' => $kwDetailStatus == 'PASS' ? true : false,
                            'endTime' => $kwDetailEndTime,
                            'startTime' => $kwDetailStartTime,
                            'image' => $kwDetailImage]
                    );
                }
            }
        return $suiteLastInsertId;

        }
    }
}