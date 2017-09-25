@extends('admin.layouts.dashboard')

@section('page_heading','Report')

@section('section')
    <div class="box">
        <canvas id="chart-area" class="zone"></canvas>
    </div>
    <script>
        var doughnutData = [
            {
                value: 168,
                color:"#F7464A",
                highlight: "#FF5A5E",
                label: "孫小毛"
            },
            {
                value: 143,
                color: "#46BFBD",
                highlight: "#5AD3D1",
                label: "王大毛"
            },
            {
                value: 89,
                color: "#FDB45C",
                highlight: "#FFC870",
                label: "曾甲仙"
            }
        ]
        window.onload = function(){
            var ctx = document.getElementById("chart-area").getContext("2d");
            var myDoughnut = new Chart(ctx).Doughnut(doughnutData, {
                responsive : true,
                animationEasing: "easeOutQuart",
            });
        };
    </script>
@endsection