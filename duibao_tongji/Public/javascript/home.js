$(document).ready(function () {
    layer.ready(function () {
        var summary = $('.summary>div>div>div>p'),
            weekly_describe = $('.weekly-describe'),
            ratio = $('.ratio'),
            proportion = $('.proportion'),
            _number = proportion.find('.number'),
            _percentage = proportion.find('.percentage');

        getdata();
        window.setInterval(function () {
            getdata();
        }, 60000);

        /**
         * 获取折线图...数据
         */
        function getdata() {
            $.ajax({
                type: 'POST',
             //   url: 'http://192.168.1.68:8003/tongji/index.php',
                  url: '../Index/ajax_home',
                dataType: "jsonp",
                jsonp: "callback",
                // jsonpCallback: "jsoncallback",
                success: function (obj) {
                    console.log(obj);
                    try {
                        if (obj.returncode === '1') {
                            //用户数量
                            if (obj.list.allnum != undefined) {
                                summary.eq(0).text(obj.list.allnum);
                            } else {
                                summary.eq(0).text('0');
                            }
                            //活跃用户
                            if (obj.list.huoyuenum != undefined) {
                                summary.eq(2).text(obj.list.huoyuenum);
                            } else {
                                summary.eq(2).text('0');
                            }
                            //启动次数
                            if (obj.list.qidongnum != undefined) {
                                summary.eq(4).text(obj.list.qidongnum);
                            } else {
                                summary.eq(4).text('0');
                            }
                            //下载次数
                            if (obj.list.downnum != undefined) {
                                summary.eq(6).text(obj.list.downnum);
                            } else {
                                summary.eq(6).text('0');
                            }

                            var data = [], xaxis = [], weekly_s = '', weekly_e = '';

                            obj.list.daynum.forEach(function (val, index, arr) {
                                //val为数组中当前的值，index为当前值的下表，arr为原数组
                                var date = new Date(arr[index][0]);
                                // console.log(date.getFullYear());
                                // console.log(date.getMonth() + 1);
                                // console.log(date.getDate());
                                if (index == 0) {
                                    weekly_s = date.getFullYear() + '年' + (date.getMonth() + 1) + '月' + date.getDate() + '日';
                                } else if (index == arr.length - 1) {
                                    weekly_e = date.getFullYear() + '年' + (date.getMonth() + 1) + '月' + date.getDate() + '日';
                                }
                                xaxis.push([index, (date.getMonth() + 1) + "/" + date.getDate()]);
                                data.push([index, parseInt(arr[index][1])]);
                            });
                            weekly_describe.text('期间：' + weekly_s + ' - ' + weekly_e);
                            var plot = $.plot($("#placeholder"), [{
                                data: data,
                                label: "七日概述"
                            }], setfoltOptions(["#30a0eb"], xaxis));
                            var now = new Date(),
                                yday = new Date(now - 24 * 60 * 60 * 1000);
                            ratio.html((yday.getMonth() + 1) + '月' + yday.getDate() + '日-' + (now.getMonth() + 1) + '月' + now.getDate() + '日<br>各环节比例');
                            _number.eq(0).text(obj.list.today.addusernum);
                            _number.eq(1).text(obj.list.today.huoyuenum);
                            _number.eq(2).text(obj.list.today.qidongnum);
                            _number.eq(3).text(obj.list.today.downnum);

                            var temp = [];

                            temp.push(parseInt((obj.list.today.addusernum - obj.list.yesterday.addusernum) / obj.list.yesterday.addusernum * 100));
                            temp.push(parseInt((obj.list.today.huoyuenum - obj.list.yesterday.huoyuenum) / obj.list.yesterday.huoyuenum * 100));
                            temp.push(parseInt((obj.list.today.qidongnum - obj.list.yesterday.qidongnum) / obj.list.yesterday.qidongnum * 100));
                            temp.push(parseInt((obj.list.today.downnum - obj.list.yesterday.downnum) / obj.list.yesterday.downnum * 100));
                            temp.forEach(function (val, index, arr) {
                                if (temp[index] < 0) {
                                    _percentage.eq(index).html('<span class="lnr lnr-chevron-down text-danger"></span>' + Math.abs(isNaN(val) ? 0 : val) + '％');
                                } else {
                                    _percentage.eq(index).html('<span class="lnr lnr-chevron-up text-success"></span>' + Math.abs(isNaN(val) ? 0 : val) + '％');
                                }
                            });

                            // jsondata.list.addusernum;//今日新增
                            // jsondata.list.downnum;//今日下载
                            // jsondata.list.huoyuenum;//今日活跃
                            // jsondata.list.qidongnum;//今日启动
                        } else {
                            layer.msg('返回数据格式错误！', {icon: 5});
                        }
                    } catch (e) {
                        layer.msg('返回数据错误！', {icon: 2});
                    }
                },
                error: function (e) {
                    layer.msg('网络连接出错！', {icon: 2});
                }

            });
        }

        /**
         * Folt配置
         * @param colors
         * @param ticks
         * @returns {{series: {lines: {show: boolean, lineWidth: number, fill: boolean, fillColor: {colors: [null,null]}}, points: {show: boolean, lineWidth: number, radius: number}, shadowSize: number, stack: boolean}, grid: {hoverable: boolean, clickable: boolean, tickColor: string, borderWidth: number}, legend: {show: boolean, labelBoxBorderColor: string}, tooltip: {show: boolean}, colors: *, xaxis: {ticks: *, font: {size: number, family: string, variant: string, color: string}, tickColor: string}, yaxis: {ticks: number, tickDecimals: number, font: {size: number, color: string}}}}
         */
        function setfoltOptions(colors, ticks) {
            return options = {
                series: {
                    lines: {
                        show: true,
                        lineWidth: 1,
                        fill: true,
                        fillColor: {colors: [{opacity: 0.1}, {opacity: 0.13}]}
                    },
                    points: {
                        show: true,
                        lineWidth: 2,
                        radius: 3
                    },
                    shadowSize: 0,
                    stack: true
                },
                grid: {
                    hoverable: true,
                    clickable: true,
                    tickColor: "#D3D3D3",
                    borderWidth: 0
                },
                legend: {
                    show: false,
                    labelBoxBorderColor: "#fff"
                },
                tooltip: {
                    show: true
                },
                colors: colors,
                xaxis: {
                    ticks: ticks,
                    font: {
                        size: 12,
                        family: "Open Sans, Arial",
                        variant: "small-caps",
                        color: "#697695"
                    },
                    tickColor: "#fff"
                },
                yaxis: {
                    ticks: 3,
                    tickDecimals: 0,
                    font: {size: 12, color: "#9da3a9"}
                }
            };
        }
    });
});