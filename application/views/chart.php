<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="http://unicon.com.hk/edu_bloomberg_core/nv.d3.css" rel="stylesheet" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.2/d3.min.js" charset="utf-8"></script>
    <script src="http://unicon.com.hk/edu_bloomberg_core/nv.d3.js"></script>

    <style>
        text {
            font: 12px sans-serif;
        }
        svg {
            display: block;
        }
        html, body, #chart1, svg {
            margin: 0px;
            padding: 0px;
            height: 100%;
            width: 100%;
        }
        #chartZoom {
            position: absolute;
            top: 0;
            left: 0;
        }
    </style>
</head>
<body>

<div id="chartZoom">
    <a href="#" id="zoomIn">Zoom In</a> <a href="#" id="zoomOut">Zoom Out</a>
</div>

<div id="chart1" class='with-transitions'>
    <svg></svg>
</div>

<script>
    
    nv.addGraph(function() {
        var chart = nv.models.lineChart();
        var fitScreen = true;
        var width = 600;
        var height = 300;
        var zoom = 2;

        chart.useInteractiveGuideline(true).forceY([2,35]);
        chart.xAxis
            .tickFormat(d3.format(',r'));

        chart.lines.dispatch.on("elementClick", function(evt) {
            console.log(evt);
        });

        chart.yAxis
            .axisLabel('Mark')
            .tickFormat(d3.format(',.2f'));

        d3.select('#chart1 svg')
            .attr('perserveAspectRatio', 'xMinYMid')
            .attr('width', width)
            .attr('height', height)
            .datum(sinAndCos());
            
        setChartViewBox();
        resizeChart();

        nv.utils.windowResize(resizeChart);

        d3.select('#zoomIn').on('click', zoomIn);
        d3.select('#zoomOut').on('click', zoomOut);

        function setChartViewBox() {
            var w = width * zoom,
                h = height * zoom;

            chart
                .width(w)
                .height(h);

            d3.select('#chart1 svg')
                .attr('viewBox', '0 0 ' + w + ' ' + h)
                .transition().duration(500)
                .call(chart);
        }

        function zoomOut() {
            zoom += .25;
            setChartViewBox();
        }

        function zoomIn() {
            if (zoom <= .5) return;
            zoom -= .25;
            setChartViewBox();
        }

        // This resize simply sets the SVG's dimensions, without a need to recall the chart code
        // Resizing because of the viewbox and perserveAspectRatio settings
        // This scales the interior of the chart unlike the above
        function resizeChart() {
            var container = d3.select('#chart1');
            var svg = container.select('svg');

            if (fitScreen) {
                // resize based on container's width AND HEIGHT
                var windowSize = nv.utils.windowSize();
                svg.attr("width", windowSize.width);
                svg.attr("height", windowSize.height);
            } else {
                // resize based on container's width
                var aspect = chart.width() / chart.height();
                var targetWidth = parseInt(container.style('width'));
                svg.attr("width", targetWidth);
                svg.attr("height", Math.round(targetWidth / aspect));
            }
        }
        return chart;
    });

    function sinAndCos() {
        var data='<?php echo $json;?>';
        var json= JSON.parse(data);
        
        var cal_4C1 = [],
            dec_4C1 = [],
            cal_4C2 = [],
            dec_4C2 = [],
            cal_Best5 = [],
            dec_Best5 = [],
            cal_RSI = [];
            
        var or_cal_4C1 = json.map(function(obj){
            return obj.cal_4C1;
        });

         var or_dec_4C1 = json.map(function(obj){
            return obj.dec_4C1;
        });
            var or_cal_4C2 = json.map(function(obj){
            return obj.cal_4C2;
        });
            var or_dec_4C2 = json.map(function(obj){
            return obj.dec_4C2;
        });
            var or_cal_Best5 = json.map(function(obj){
            return obj.cal_Best5;
        });
            var or_dec_Best5 = json.map(function(obj){
            return obj.dec_Best5;
        });
            var or_cal_RSI = json.map(function(obj){
            return obj.cal_RSI;
        });

        or_cal_4C1.sort();
        or_cal_RSI.sort();
        or_dec_Best5.sort();
        or_cal_Best5.sort();
        or_dec_4C1.sort();
        or_dec_4C2.sort();
        or_cal_4C2.sort();
        for (var i = 0; i < json.length; i++) {
            cal_4C1.push({x: i, y: or_cal_4C1[i] });
             dec_4C1.push({x: i, y: or_dec_4C1[i] });
            cal_4C2.push({x: i, y: or_cal_4C2[i] });
            dec_4C2.push({x: i, y: or_dec_4C2[i] });
            cal_Best5.push({x: i, y: or_cal_Best5[i] });
            dec_Best5.push({x: i, y: or_dec_Best5[i] });
            cal_RSI.push({x: i, y: or_cal_RSI[i] });
        }
        
        return [
           
            {
                values: dec_4C1,
                key: "DEC_4C1",
                color: "#EA0000"
            }, {
                values: cal_4C1,
                key: "CAL_4C1",
                color: "#ff7f0e"
            },{
                values: cal_Best5,
                key: "CAL_BEST5",
                color: "#FFD306"
            },
            {
                values: dec_Best5,
                key: "DEC_BEST5",
                color: "#00A600"
            },
            {
                values: cal_4C2,
                key: "CAL_4C2",
                color: "#0000E3"
            },
            {
                values: dec_4C2,
                key: "DEC_4C2",
                color: "#E800E8"
            },
            {
                values: cal_RSI,
                key: "CAL_RSI",
                color: "#5B00AE"
            },
            
            
        ];
    }

</script>
</body>
</html>