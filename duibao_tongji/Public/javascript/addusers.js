$(document).ready(function () {
    layer.ready(function () {
        var BeginDate = $("#BeginDate"),
            EndDate = $("#EndDate"),
            dropdown = $('.flot-win>div>.flot-header>li>.dropdown'),
            activity1 = $('#activity1').find('tr'),
            activity2 = $('#activity2').find('tr');
        // dropdown_toggle = dropdown.find('.dropdown-toggle');
        //     select_all = $('#select-all').find('li'),
        //     select_hour = $('#select-hour'),
        //     select_day = $('#select-day');
        // select_week = $('#select-week'),
        // select_month = $('#select-month');
        var temp = ['', '', '', ''];

        if (browser.versions.mobile) {
            BeginDate.date({}, function (datestr) {
                console.log(datestr);
                temp[0] = datestr;
                getdata(false, temp[0], temp[1], temp[2], temp[3]);
            });
            EndDate.date({}, function (datestr) {
                console.log(EndDate.val());
                if (new Date(EndDate.val()) > new Date(temp[0])) {
                    temp[1] = EndDate.val();
                    getdata(false, temp[0], temp[1], temp[2], temp[3]);
                } else {
                    EndDate.val('');
                    layer.msg('结束时间必须大于开始时间！', {icon: 5});
                }
            });
        }
        else {
            BeginDate.calendar({
                upperLimit: new Date(),
                callback: function () {                               // 点击选择日期后的回调函数
                    console.log(BeginDate.val());
                    temp[0] = BeginDate.val();
                    getdata(false, temp[0], temp[1], temp[2], temp[3]);
                }
            });
            EndDate.calendar({
                upperLimit: new Date(),
                callback: function () {                               // 点击选择日期后的回调函数
                    console.log(EndDate.val());
                    if (new Date(EndDate.val()) > new Date(temp[0])) {
                        temp[1] = EndDate.val();
                        getdata(false, temp[0], temp[1], temp[2], temp[3]);
                    } else {
                        EndDate.val('');
                        layer.msg('结束时间必须大于开始时间！', {icon: 5});
                    }
                }
            });
        }


        getdata(true);
        dropdown.on('click', 'a', function () {
            var dropdown_toggle = $(this).parent().parent().prev('button');
            dropdown_toggle.html($(this).text() + ' <span class="caret"></span>');
            var data_id = $(this).attr('data-id');
            dropdown_toggle.attr('data-id', data_id);
            if (dropdown_toggle.attr('id') == 'channel') {
                temp[2] = data_id;
            } else if (dropdown_toggle.attr('id') == 'version') {
                temp[3] = data_id;
            }
            getdata(false, temp[0], temp[1], temp[2], temp[3]);
            // showselect(data_id);
        });

        /**
         * 获取折线图，表格数据
         * 获取配置参数
         */
        function getdata(getopt, _starttime, _endtime, _site, _version) {
            var index = layer.load(0, {shade: false});
            if (getopt)
                var err = [false, false];
            else
                var err = [true, false];
            if (getopt)
                $.ajax({
                    type: 'POST',
                    url: '../User/ajax_list',
                    dataType: "jsonp",
                    jsonp: "callback",
                    // jsonpCallback: "jsoncallback",
                    success: function (obj) {
                        // console.log(obj);
                        try {
                            var _html = '';
                            obj.site.forEach(function (value, index, array) {
                                _html += '<li><a href="#" data-id="' + value.id + '">' + value.name + '</a></li>';
                            });
                            $('ul[aria-labelledby="channel"]').append(_html);
                            _html = '';
                            obj.version.forEach(function (value, index, array) {
                                _html += '<li><a href="#" data-id="' + value.id + '">' + value.name + '</a></li>';
                            });
                            $('ul[aria-labelledby="version"]').append(_html);
                            err[0] = true;
                            if (err[0] || err[1]) {
                                layer.close(index);
                                err[0] = false;
                                err[1] = false;
                            }
                        } catch (e) {
                            layer.close(index);
                            layer.msg('返回数据错误！', {icon: 2});
                        }
                    },
                    error: function (e) {
                        layer.close(index);
                        layer.msg('网络连接出错！', {icon: 2});
                    }
                });
            var _url = '../User/ajax_addusers';
            if (_starttime != undefined && _starttime != '') {
                _url += '?starttime=' + _starttime;
                if (_endtime != undefined && _endtime != '')
                    _url += '&endtime=' + _endtime;
                else
                    _url += '&endtime=' + new Date().Format('yyyy-MM-dd');
            } else
                _url += '?passday=nowday';

            if (_version != undefined && _version != '' && _version != '0')
                _url += '&version=' + _version;

            if (_site != undefined && _site != '' && _site != '0')
                _url += '&site=' + _site;
            $.ajax({
                type: 'POST',
                url: _url,
                dataType: "jsonp",
                jsonp: "callback",
                // jsonpCallback: "jsoncallback1",
                success: function (obj) {
                    // console.log(obj);
                    try {
                        var data = [], xaxis = [];
                        err[1] = true;
                        if (err[0] || err[1]) {
                            layer.close(index);
                            err[0] = false;
                            err[1] = false;
                        }
                        if (obj.returncode == 1) {
                            obj.daynumlist.forEach(function (value, index, array) {
                                var date = new Date(value[0]);
                                xaxis.push([index, (date.getMonth() + 1) + "/" + date.getDate()]);
                                data.push([index, parseInt(value[1])]);
                            });

                            var plot = $.plot($("#placeholder"), [{
                                data: data,
                                label: "七日概述"
                            }], setfoltOptions(["#30a0eb"], xaxis));

                            $('#date-time').text('(' + new Date(obj.daynumlist[0][0]).Format('yyyy-MM-dd') + ' 至 ' + new Date(obj.daynumlist[obj.daynumlist.length-1][0]).Format('yyyy-MM-dd') + ')')

                            if (obj.prelist.length == 0)
                                $('.table-win').children('div').eq(0).hide();
                            else
                                $('.table-win').children('div').eq(0).show();
                            if (obj.detaillist == 0)
                                $('.table-win').children('div').eq(1).hide();
                            else
                                $('.table-win').children('div').eq(1).show();
                            obj.prelist.forEach(function (value, index, array) {
                                var ch = activity1.eq(index + 1)[0];
                                ch.cells[0].innerText = value[0];
                                ch.cells[1].innerText = value[1];
                                ch.cells[2].innerText = value[2];
                            });
                            obj.detaillist.forEach(function (value, index, array) {
                                var ch = activity2.eq(index + 1)[0];
                                var date = new Date(value.create_datetime);
                                ch.cells[0].innerText = date.Format('yyyy-MM-dd');
                                ch.cells[1].innerText = (value.nickname == '' ? '未设置' : value.nickname);
                                ch.cells[2].innerText = (value.phone == '' ? '未设置' : value.phone);
                                ch.cells[3].innerText = (value.plat_form == '' ? '未知来源' : value.plat_form);
                            });
                        } else {
                            layer.msg(obj.returnmsg, {icon: 5});
                        }

                    } catch (e) {
                        layer.close(index);
                        layer.msg('返回数据错误！', {icon: 2});
                    }
                },
                error: function (e) {
                    layer.close(index);
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

        /**
         * 判断显示 时，日，周，月
         * @param id
         */
        function showselect(id) {
            switch (id) {
                case '1'://当天
                    dispose(select_hour, [select_day]);
                    // dispose(select_hour, [select_day, select_week, select_month]);
                    break;
                case '2'://过去七天
                    dispose(select_day, [select_hour]);
                    // dispose(select_day, [select_hour, select_week, select_month]);
                    break;
                case '3'://过去30天
                    dispose(select_day, [select_hour]);
                    // dispose(select_day, [select_hour, select_month]);
                    break;
                case '4'://过去60天
                    dispose(select_day, [select_hour]);
                    break;
            }
        }

        /**
         * 设置显示，选中，虚化   时，日，周，月
         * @param active
         * @param hide
         */
        function dispose(active, hide) {
            select_all.each(function (index, element) {
                if ($(element).hasClass('active')) {
                    $(element).removeClass('active');
                } else if ($(element).hasClass('my_hide')) {
                    $(element).removeClass('my_hide');
                }
            });
            active.addClass('active');
            if (typeof hide == "object")
                hide.forEach(function (element) {
                    element.addClass('my_hide');
                });
        }

        Date.prototype.Format = function (fmt) { //author: meizz
            var o = {
                "M+": this.getMonth() + 1,                 //月份
                "d+": this.getDate(),                    //日
                "h+": this.getHours(),                   //小时
                "m+": this.getMinutes(),                 //分
                "s+": this.getSeconds(),                 //秒
                "q+": Math.floor((this.getMonth() + 3) / 3), //季度
                "S": this.getMilliseconds()             //毫秒
            };
            if (/(y+)/.test(fmt))
                fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
            for (var k in o)
                if (new RegExp("(" + k + ")").test(fmt))
                    fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
            return fmt;
        }
    });
});