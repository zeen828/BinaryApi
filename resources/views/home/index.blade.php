<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <script type="text/javascript" src="{{URL::asset('jquery/3.6.0/jquery-3.6.0.min.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('bootstrap/4.6.0/js/bootstrap.min.js')}}"></script>
        <link rel="stylesheet" href="{{URL::asset('bootstrap/4.6.0/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{URL::asset('icons/1.4.0/font/bootstrap-icons.css')}}">
        <script type="text/javascript" src="{{URL::asset('vuejs/2.x/vue.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('echarts/5.0.2/echarts.min.js')}}"></script>
    </head>
    <body>
        <div id="app">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <a class="navbar-brand" href="#">期權</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#Menu" aria-controls="Menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div id="Menu" class="collapse navbar-collapse">
                    <div class="navbar-nav">
                        <a class="nav-item nav-link active" href="#">首頁</a>
                        <a class="nav-item nav-link" href="#" data-toggle="modal" data-target=".bd-example-modal-lg-a">下單紀錄</a>
                        <a class="nav-item nav-link" href="#" data-toggle="modal" data-target=".bd-example-modal-lg-b">商品說明</a>
                        <a class="nav-item nav-link" href="/logout">登出</a>
                    </div>
                    </div>
                </nav>
                <div class="row d-md-block">
                    <div class="col-12 col-md-3 float-md-left d-flex flex-wrap p-0">
                        <div class="col-12 order-2 order-md-1">
                            <ul class="list-group mb-3">
                                <li class="list-group-item list-group-item-secondary">澳元對美元 | 202103161210期</li>
                                <li class="list-group-item" @@click="sendMessage('帥帥帥慘了')">BTC/USD</li>
                            </ul>
                        </div>
                        <div class="col-12 order-1 order-md-2">
                            <ul class="list-group mb-3">
                                <li class="list-group-item"><i class="bi bi-person-fill"></i>：@{{ user.name }}</li>
                                <li class="list-group-item"><i class="bi bi-award"></i>：@{{ user.point }}</li>
                                <li class="list-group-item">LTC/USD</li>
                                <li class="list-group-item">DASH/USD</li>
                                <li class="list-group-item">DASH/USD</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-md-9 float-md-right p-0">
                        <div class="col-12">
                            <ul class="list-group mb-3">
                                <li class="list-group-item list-group-item-secondary">走勢圖</li>
                                <li class="list-group-item">
                                    <div id="trendChart" style="height: 40vh;"></div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12">
                            <ul class="list-group mb-3">
                                <li class="list-group-item list-group-item-secondary">K線</li>
                                <li class="list-group-item">
                                    <div id="kChart" style="height: 40vh;"></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 float-md-left p-0">
                        <div class="col-12">
                            <ul class="list-group mb-3">
                                <li class="list-group-item list-group-item-secondary">收盤價(每分鐘更新)</li>
                                <li v-for="(item, index) in commodity" :key="index" class="list-group-item"><i class="fas fa-cloud"></i>@{{ item.name }} - @{{ item.name }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div id="modal">
                <div class="modal fade bd-example-modal-lg-a" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">下單紀錄</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                XXXXXXXXXXXXXXXXX
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade bd-example-modal-lg-b" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">商品說明</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="">
                                    <div class="form-group col-md-4">
                                        <select id="inputState" class="form-control">
                                            <option v-for="(item, index) in commodity" :key="index" :value="index"> @{{ item.name }}</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary mb-2">Confirm identity</button>
                                </div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        new Vue({
            el: '#app',
            methods: {
                ready () {
                    console.log('ready');
                    console.log('echarts', echarts);
                    this.test();
                    this.updataTrendChartData();
                    this.updataKChartData();
                },
                test () {
                    // const wsUrl = 'wss://echo.websocket.org';
                    // const wsUrl = 'wss://echo.websocket.org/?encoding=text';
                    const wsUrl = 'ws://127.0.0.1:8000';
                    this.connection = new WebSocket(wsUrl);
                    this.connection.onmessage = function(event) {
                        console.log(event);
                    }
                    this.connection.onopen = function(event) {
                        console.log(event);
                        console.log('Successfully connected to the echo websocket server...');
                    }
                },
                sendMessage (message) {
                    console.log(this.connection);
                    this.connection.send(message);
                },
                // 走勢圖-更新資料
                updataTrendChartData () {
                    var that = this
                    that.trendChart.option.xAxis.data = that.trendChart.xAxisData;
                    that.trendChart.option.series[0].data = that.trendChart.seriesData;
                    that.runTrendChart();
                },
                // 走勢圖-運作
                runTrendChart () {
                    var that = this
                    // 基于准备好的dom，初始化echarts实例
                    that.trendChart.chart = echarts.init(document.getElementById('trendChart'));
                    // 使用刚指定的配置项和数据显示图表。
                    that.trendChart.chart.setOption(that.trendChart.option);
                    // 動態
                    var startNumber = 0;
                    // 有多少條資料
                    var xAxisDatalen = that.trendChart.option.xAxis.data.length;// xAxisData.length;
                    // len的值,可以根據你的數量量設定,不要超過xAxisDatalen的值,不然不會動
                    var len = 30;
                    that.timer && clearInterval(that.timer);
                    that.timer = setInterval(function () {
                        if (startNumber === xAxisDatalen - len) {
                            startNumber = 0;
                        }
                        that.trendChart.chart.dispatchAction({
                            type: 'dataZoom',
                            startValue: startNumber,
                            endValue: startNumber + len,
                        });
                        startNumber++;
                    }, 1000);
                },
                // K線圖-更新資料
                updataKChartData () {
                    var that = this
                    that.kChart.option.xAxis.data = that.kChart.xAxisData;
                    that.kChart.option.series[0].data = [20, 50, 75, 69, 69];
                    that.kChart.option.series[1].data = that.kChart.seriesData;
                    that.runKChart();
                },
                // K線圖-運作
                runKChart () {
                    var that = this
                    // 基于准备好的dom，初始化echarts实例
                    that.kChart.chart = echarts.init(document.getElementById('kChart'));
                    // 使用刚指定的配置项和数据显示图表。
                    that.kChart.chart.setOption(that.kChart.option);
                },
                // 更新視窗尺寸用
                eventResize () {
                    var that = this
                    that.trendChart.chart.resize();
                    that.kChart.chart.resize();
                }
            },
            data () {
                return {
                    timer: null,
                    connection: null,
                    // 使用者
                    // user: { name: '我是會員', point: 100 },
                    user: <?php echo json_encode($user); ?>,
                    // 商品項目
                    commodity: <?php echo json_encode($binary); ?>,
                    // [
                    //     { id: 1, name: '比特A', description: '', src: '#', point: 1111 },
                    //     { id: 2, name: '比特B', description: '', src: '#', point: 1122 },
                    //     { id: 3, name: '比特C', description: '', src: '#', point: 1133 },
                    //     { id: 4, name: '比特D', description: '', src: '#', point: 1144 }
                    // ],
                    // 走勢圖
                    trendChart: {
                        chart: null,
                        option: {
                            title: { text: '走勢圖' },
                            tooltip: { trigger: 'axis' },
                            dataZoom: [
                                {
                                    type: 'slider',
                                    show: false,
                                    realtime: true,
                                    startValue: 0,
                                    endValue: 30, // 初始顯示index0-30的資料,可根據你的資料量設定
                                    filterMode: 'none',
                                },
                            ],
                            legend: { data:['销量'] },
                            xAxis: {
                                show: true,
                                boundaryGap: false,
                                data: []
                            },
                            yAxis: { type: 'value' },
                            series: [
                                { name: '销量', type: 'line', data: [] }
                            ]
                        },
                        xAxisData: ["16:00", "16:05", "17:10", "17:15", "17:50", "18:05", "18:15", "18:20", "18:30", "18:40", "18:45", "19:00", "19:05", "19:35", "19:50", "20:00", "20:05", "20:25", "20:50", "20:55", "21:00", "21:05", "21:20", "21:35", "21:40", "21:45", "22:10", "22:20", "22:25", "22:30", "22:40", "22:45", "22:50", "22:55", "23:10", "23:15", "23:20", "23:30", "23:35", "23:40", "23:50", "23:55", "00:10", "00:25", "00:30", "00:35", "00:45", "00:50", "01:00", "01:10", "01:30", "01:35", "02:10", "02:15", "02:20", "02:25", "02:40", "02:45", "03:00", "03:20", "03:35", "03:50", "03:55", "04:00", "04:10", "04:15", "04:20", "04:30", "04:35", "04:50", "04:55", "05:00", "05:05", "05:15", "05:25", "05:30", "05:40", "05:55", "06:00", "06:05", "06:10", "06:20", "06:50", "06:55", "07:00", "07:05", "07:10", "07:15", "07:25", "07:35", "07:40", "07:45", "07:50", "08:00", "08:10", "08:20", "08:30", "08:40", "08:45", "08:55", "09:05", "09:10", "09:20", "09:35", "09:40", "09:50", "10:00", "10:05", "10:15", "10:25", "10:35", "10:40", "10:45", "10:50", "10:55", "11:00", "11:20", "11:45", "12:05", "12:20", "12:25", "12:35", "12:55", "13:00", "13:05", "13:10", "13:25", "13:30", "13:55", "14:10", "14:20", "14:30", "14:45", "15:00", "15:05", "15:15", "15:20", "15:25"],
                        seriesData: [0.93, 0.69, 0.65, 0.69, 1.21, 1.23, 0.63, 0.62, 1.16, 0.65, 1.16, 0.62, 0.85, 1.26, 0.67, 0.65, 1.33, 0.96, 0.61, 0.8, 0.85, 0.97, 1.14, 0.65, 0.86, 0.95, 1.1, 1.18, 0.62, 1.32, 1.19, 0.68, 0.67, 0.65, 0.68, 1.29, 0.65, 1.13, 0.87, 0.96, 0.64, 0.63, 1.24, 0.66, 0.66, 0.87, 1.13, 0.85, 0.99, 1.05, 1.35, 1.33, 0.67, 0.65, 0.65, 1.02, 1.08, 0.71, 0.65, 1.28, 1.35, 0.77, 0.94, 1.31, 1.11, 0.66, 0.9, 1.32, 0.68, 0.66, 0.72, 1.11, 0.65, 0.64, 0.64, 0.83, 1.24, 0.96, 1.11, 0.64, 1.31, 0.6, 0.62, 0.76, 0.63, 0.82, 1.01, 1.32, 1.24, 1.13, 0.84, 1.2, 0.65, 0.91, 0.79, 1.3, 1.27, 1.18, 0.65, 0.63, 1.17, 1.25, 0.7, 1.21, 0.89, 1.39, 1.02, 0.68, 0.69, 1.04, 0.67, 1.06, 1.31, 1.32, 1.2, 0.68, 1.4, 1.28, 0.9, 0.69, 1.38, 1.13, 1.04, 0.79, 0.71, 0.71, 1.2, 1.38, 0.76, 1.35, 0.82, 1.03, 1.29, 1.49, 0.69, 0.74, 0.75, 0.72]
                    },
                    // K線圖
                    kChart: {
                        chart: null,
                        option: {
                            title: { text:'K線圖' },
                            tooltip: {},
                            //图例
                            legend: {
                                data: ['销量', 'K線圖']	//此处图例名与series中的name须保持一致
                            },
                            //图表背景样式
                            //backgroundColor:'#ffffff',
                            //X轴
                            xAxis: {
                                data: []
                            },
                            //Y轴
                            yAxis: {},
                            series: [
                                { name: '销量', type: 'line', data: [] },
                                { name: 'K線圖', type: 'candlestick', data: [] }
                            ]
                        },
                        xAxisData: ['demo1', 'demo2', 'demo3', 'demo4', 'demo5'],
                        seriesData: [
                            [20, 30, 50, 90],
                            [10, 50, 60, 60],
                            [40, 60, 75, 20],
                            [20, 70, 29, 69],
                            [70, 90, 40, 69]
                        ]
                    },
                    // 下單紀錄
                    // 商品說明
                    debug: false
                }
            },
            beforeCreate () {},
            created () {
                window.addEventListener('resize', this.eventResize);
            },
            beforeMount () {},
            mounted () {
                this.ready();
            },
            beforeUpdate () {},
            updated () {},
            beforeDestroy () {},
            destroyed () {
                window.removeEventListener('resize', this.eventResize);
            }
        });
        </script>
    </body>
</html>
