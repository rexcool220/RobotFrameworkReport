@extends('admin.layouts.dashboard')

@section('page_heading','功能測試結果報告(Keyword driven testing)')
@section('section')

    <!-- /.row -->
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-thumbs-o-up fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{$suite[0]['allTestsFail'] > 0 ? '未通過' : '通過'}}</div>
                                <div>狀態</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <span class="pull-left">本次功能測試通過結果</span>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-clock-o fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">
                                    <?php
                                    $datetime1 = DateTime::createFromFormat('Ymd H:i:s.u', $suite[0]['startTime']);
                                    $datetime2 = DateTime::createFromFormat('Ymd H:i:s.u', $suite[0]['endTime']);
                                    $diffInSeconds = $datetime2->getTimestamp() - $datetime1->getTimestamp();
                                    echo $diffInSeconds . " 秒";
                                    ?>
                                </div>
                                <div>花費時間</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <span class="pull-left">測試花費時間</span>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-ban fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">
                                    {{$suite[0]['allTestsFail']}}
                                </div>
                                <div>失敗次數</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <span class="pull-left">測試失敗次數</span>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-exclamation fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">
                                    {{$suite[0]['error'] == "\n" ? '無錯誤發生' : $suite[0]['錯誤發生']}}
                                </div>
                                <div>錯誤</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <span class="pull-left">測試期間是否發生例外錯誤</span>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.col-sm-12 -->
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
            @component('admin.widgets.panel')
                @slot('panelTitle', '測試總結')
                @slot('panelBody')
                    <canvas id="allTests"></canvas>
                @endslot
            @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <table class="collaptable table-bordered table-hover" id="testReport" width="100%">
                    <?php foreach($tests as $test)
                    { ?>
                    <tr class="bg-info">
                        <th>name</th>
                        <th>value</th>
                    </tr>
                    <tr>
                        <td>test name</td>
                        <td>{{$test['name']}}</td>
                    </tr>
                    <tr>
                        <td>test id</td>
                        <td>{{$test['id']}}</td>
                    </tr>
                    <tr>
                        <td>test status</td>
                        <td>{{$test['status']}}</td>
                    </tr>
                    <tr>
                        <td>test endTime</td>
                        <td>{{$test['endTime']}}</td>
                    </tr>
                    <tr>
                        <td>test startTime</td>
                        <td>{{$test['startTime']}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <table class="table-bordered table-hover" width="100%">
                                <tr class="bg-info">
                                    <th>name</th>
                                    <th>value</th>
                                </tr>
                                <?php foreach($test['kws'] as $kw)
                                { ?>
                                <tr>
                                    <td>key word name</td>
                                    <td>{{$kw['name']}}</td>
                                </tr>
                                <tr>
                                    <td>key word status</td>
                                    <td>{{$kw['kwId']}}</td>
                                </tr>
                                <tr>
                                    <td>key word endTime</td>
                                    <td>{{$test['endTime']}}</td>
                                </tr>
                                <tr>
                                    <td>key word startTime</td>
                                    <td>{{$kw['startTime']}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <table class="table-bordered table-hover" width="100%">
                                            <tr class="bg-info">
                                                <th>name</th>
                                                <th>value</th>
                                            </tr>
                                            <?php foreach($kw['kwDetails'] as $kwDetail)
                                            { ?>
                                            <tr data-id={{"kwDetail".$kwDetail['kwDetailId']}} data-parent="">
                                                <td>key word detail name</td>
                                                <td>{{$kwDetail['name']}}</td>
                                            </tr>
                                            <tr data-parent={{"kwDetail".$kwDetail['kwDetailId']}}>
                                                <td>key word detail library</td>
                                                <td>{{$kwDetail['library']}}</td>
                                            </tr>
                                            <tr data-parent={{"kwDetail".$kwDetail['kwDetailId']}}>
                                                <td>key word detail doc</td>
                                                <td>{{$kwDetail['doc']}}</td>
                                            </tr>
                                            <tr data-parent={{"kwDetail".$kwDetail['kwDetailId']}}>
                                                <td>key word detail msg</td>
                                                <td>{{$kwDetail['msg']}}</td>
                                            </tr>
                                            <tr data-parent={{"kwDetail".$kwDetail['kwDetailId']}}>
                                                <td>key word detail status</td>
                                                <td>{{$kwDetail['status']}}</td>
                                            </tr>
                                            <tr data-parent={{"kwDetail".$kwDetail['kwDetailId']}}>
                                                <td>key word detail endTime</td>
                                                <td>{{$kwDetail['endTime']}}</td>
                                            </tr>
                                            <tr data-parent={{"kwDetail".$kwDetail['kwDetailId']}}>
                                                <td>key word detail startTime</td>
                                                <td>{{$kwDetail['startTime']}}</td>
                                            </tr>
                                            <tr data-parent={{"kwDetail".$kwDetail['kwDetailId']}}>
                                                <td>key word detail image</td>
                                                <td><img src="{{$kwDetail['image']}}" width=800></img></td>
                                            </tr>
                                            <?php } ?>

                                        </table>
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <div class="col-sm-1"></div>
        </div>
    </div>
    <script>
        window.onload = function() {
            var data = {
                datasets: [{
                    <?php
                        $array = array();
                        foreach ($tests as $test) {
                            foreach ($test['kws'] as $kw) {
                                foreach ($kw['kwDetails'] as $kwDetail) {
                                    if (array_key_exists($kwDetail['name'], $array) == true) {
                                        $array[$kwDetail['name']]++;
                                    } else {
                                        $array[$kwDetail['name']] = 1;
                                    }
                                }
                            }
                        }
                        $data = "data: [";
                        foreach ($array as $key => $value) {
                            $data = $data . $value . ",";
                        }
                        $data = substr($data, 0, -1);
                        $data = $data . "],";
                        echo $data;
                    ?>
                    backgroundColor: "#FC9775",
                    label: "Total"
                },{
                    <?php
                        $passedData = array();
                    foreach ($tests as $test) {
                        foreach ($test['kws'] as $kw) {
                            foreach ($kw['kwDetails'] as $kwDetail) {
                                if($kwDetail['status'] == 1)
                                {
                                    if (array_key_exists($kwDetail['name'], $passedData) == true) {
                                        $passedData[$kwDetail['name']]++;
                                    } else {
                                        $passedData[$kwDetail['name']] = 1;
                                    }
                                }
                                else{
                                    if (array_key_exists($kwDetail['name'], $passedData) == false) {
                                        $passedData[$kwDetail['name']] = 0;
                                    }
                                }
                            }
                        }
                    }
                    $data = "data: [";
                    foreach ($passedData as $key => $value) {
                        $data = $data . $value . ",";
                    }
                    $data = substr($data, 0, -1);
                    $data = $data . "],";
                    echo $data;
                    ?>
                    backgroundColor: "#5A69A6",
                    label: "Passed"
                }
                ],

                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    <?php
                        $label = "";
                        foreach ($array as $key => $value) {
                            $label = $label . "'" . $key . "',";
                        }
                        $label = substr($label, 0, -1);
                        echo $label;
                    ?>
                ]
            };
            var options = {
                // All of my other bar chart option here
                scales: {
                    xAxes: [{
                        ticks: {
                            fontSize: 20
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            mine: 0,
                            stepSize: 1,
                            beginAtZero:true,
                            fontSize: 20
                        }
                    }]
                }
            }
            var ctx = document.getElementById("allTests").getContext('2d');
            var allTests = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: options
            });
        }
    </script>
@endsection