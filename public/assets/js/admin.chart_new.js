! function (t) {

    function createCookie(name, value, days) {
        var expires;
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        } else {
            expires = "";
        }
        document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
    }

    function getDate(n) {
        var date = new Date();
        date.setDate(date.getDate() - n);
        return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
    }

    function getnewDate(n) {
        var date = new Date();
        date.setDate(date.getDate() - n);
        return new Date(date.getFullYear(), date.getMonth(), date.getDate());
    }

    function indexOf2dArray(array2d, itemtofind) {
        index = [].concat.apply([], ([].concat.apply([], array2d))).indexOf(itemtofind);

        // return "false" if the item is not found
        if (index === -1) {
            return false;
        }

        // Use any row to get the rows' array length
        // Note, this assumes the rows are arrays of the same length
        numColumns = array2d[0].length;

        // row = the index in the 1d array divided by the row length (number of columns)
        row = parseInt(index / numColumns);

        // col = index modulus the number of columns
        col = index % numColumns;

        return [row, col];
    }

    "use strict";
    if (t("#regStatistics").length > 0) {

        var theme_color = "red";

        var a = document.getElementById("regStatistics").getContext("2d"),
            r = new Chart(a, {
                type: "line",
                data: {
                    labels: user_labels,
                    datasets: [{
                        label: "",
                        lineTension: 0,
                        backgroundColor: theme_color.base,
                        borderColor: theme_color.base,
                        barThickness: .4,
                        data: user_data
                    }]
                },
                options: {
                    legend: {
                        display: !1
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        callbacks: {
                            title: function (t, e) {
                                return !1
                            },
                            label: function (t, e) {
                                return e.datasets[0].data[t.index].toFixed(3) + " " + String(base_currency).toUpperCase() + " in Equity";
                            }
                        },
                        backgroundColor: "#f2f4f7",
                        bodyFontColor: theme_color.base,
                        bodyFontSize: 14,
                        bodySpacing: 5,
                        yPadding: 3,
                        xPadding: 10,
                        footerMarginTop: 10,
                        displayColors: !1
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !1,
                                fontSize: 10,
                                fontColor: theme_color.text
                            },
                            gridLines: {
                                color: "transparent",
                                tickMarkLength: 0,
                                zeroLineColor: "transparent"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontSize: 9,
                                fontColor: theme_color.text,
                                source: "auto"
                            },
                            gridLines: {
                                color: "transparent",
                                tickMarkLength: 7,
                                zeroLineColor: "transparent"
                            }
                        }]
                    }
                }
            });
        t(".reg-statistic-graph li a").on("click", function (e) {
            e.preventDefault();
            var o = t(this),
                a = t(this).attr("href");
            t.get(a).done(t => {
                e.preventDefault();
                var url = window.location.href;
                url.hash = "";

                
                
                 var n = new Date(today);
                var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                ];

                if (a == url + "?months=3") {

                    user_labels = user_labels3;

                    user_data = user_data3;
                    $("#months_prediction_button").html("3 Months");
                }

                if (a == url + "?months=6") {

                    user_labels = user_labels6;

                    user_data = user_data6;
                    $("#months_prediction_button").html("6 Months");

                }

                if (a == url + "?months=12") {

                    user_labels = user_labels12;

                    user_data = user_data12;
                    $("#months_prediction_button").html("12 Months");
                    
                }


                r.data.labels = user_labels, r.data.datasets[0].data = user_data, r.update(), o.parents(".reg-statistic-graph").find("a.toggle-tigger").text(o.text()), o.closest(".toggle-class").toggleClass("active")
            })
        })
    }
    if (t("#tknSale").length > 0) {

        var theme_color = "#2c80ff";
        //        var tnx_labels = ["14 Feb", "15 Feb", "16 Feb", "17 Feb", "18 Feb"];
        //        var tnx_data = [1, 2, 0, 100, 0];


        var e = document.getElementById("tknSale").getContext("2d"),
            o = new Chart(e, {
                type: "line",
                data: {
                    labels: tnx_labels,
                    datasets: [{
                        label: "",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: theme_color.base,
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: tnx_data
                    }]
                },
                options: {
                    legend: {
                        display: !1
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        callbacks: {
                            title: function (t, e) {
                                return "Date : " + e.labels[t[0].index]
                            },
                            label: function (t, e) {
                                return e.datasets[0].data[t.index].toFixed(6) + " Daily Average"
                            }
                        },
                        backgroundColor: "#f2f4f7",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 14,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: !1
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !1,
                                fontSize: 12,
                                fontColor: theme_color.text
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 0,
                                zeroLineColor: "#e9edf3"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontSize: 12,
                                fontColor: theme_color.text,
                                source: "auto"
                            },
                            gridLines: {
                                color: "transparent",
                                tickMarkLength: 20,
                                zeroLineColor: "#e9edf3"
                            }
                        }]
                    }
                }
            });
        t(".token-sale-graph li a").on("click", function (e) {
            e.preventDefault();
            var a = t(this),
                r = t(this).attr("href");
            t.get(r).done(t => {

                var url = window.location.href;
                url.hash = "";

                if (r == url + "?equity=7") {
                    current_days_selected = 7;
                    tnx_data = equity7;
                    tnx_labels = [];
                    for (i = 0; i < current_days_selected; i++) {
                        let today_m = new Date(new Date().setDate(new Date().getDate() + (i - current_days_selected)));
                        tnx_labels.push(today_m.getDate() + " " + monthNames[today_m.getMonth()]);
                    }
                }
                if (r == url + "?equity=15") {
                    current_days_selected = 15;
                    tnx_data = equity15;
                    tnx_labels = [];
                    for (i = 0; i < current_days_selected; i++) {
                        let today_m = new Date(new Date().setDate(new Date().getDate() + (i - current_days_selected)));
                        tnx_labels.push(today_m.getDate() + " " + monthNames[today_m.getMonth()]);
                    }
                }
                if (r == url + "?equity=30") {
                    current_days_selected = 30;
                    tnx_data = equity30;
                    tnx_labels = [];
                    for (i = 0; i < current_days_selected; i++) {
                        let today_m = new Date(new Date().setDate(new Date().getDate() + (i - current_days_selected)));
                        tnx_labels.push(today_m.getDate() + " " + monthNames[today_m.getMonth()]);
                    }
                }

                o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")
            })
        })
    }


    if (t("#coin_graph").length > 0) {

        
    }


    if (t("#coin_graph2").length > 0) {

        function readTextFile(file) {
            var rawFile = new XMLHttpRequest();
            rawFile.open("GET", file, false);
            rawFile.onreadystatechange = function () {
                if (rawFile.readyState === 4) {
                    if (rawFile.status === 200 || rawFile.status == 0) {
                        var allText = rawFile.responseText;
                        return allText;
                    }
                }
            }
            rawFile.send(null);
            return rawFile;
        }

        var chart_graph_req = readTextFile("../../assets/coin_graph.json");
        var chart_graph = JSON.parse(chart_graph_req.response);
        console.log(chart_graph);
        var date1 = new Date(2021, 9, 31);
        var date2 = getnewDate(0);
        var diffTime = Math.abs(date2 - date1);
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        console.log(diffDays);

        var chart = new CanvasJS.Chart("coin_graph", {
            zoomEnabled: true,
            axisY: {
                includeZero: false,
                title: "Price",
                prefix: "€ "
            },
            axisX: {
                interval: 2,
                intervalType: "month",
                valueFormatString: "DD-MMM-YY",
                labelAngle: -45
            },
            data: [
                {
                    type: "candlestick",
                    dataPoints: [
                        {
                            x: getnewDate(0),
                            y: chart_graph[diffDays - 0][1]
                        },
                        {
                            x: getnewDate(1),
                            y: chart_graph[diffDays - 1][1]
                        },
                        {
                            x: getnewDate(2),
                            y: chart_graph[diffDays - 2][1]
                        },
                        {
                            x: getnewDate(3),
                            y: chart_graph[diffDays - 3][1]
                        },
                        {
                            x: getnewDate(4),
                            y: chart_graph[diffDays - 4][1]
                        },
                        {
                            x: getnewDate(5),
                            y: chart_graph[diffDays - 5][1]
                        },
                        {
                            x: getnewDate(6),
                            y: chart_graph[diffDays - 6][1]
                        },
                        {
                            x: getnewDate(7),
                            y: chart_graph[diffDays - 7][1]
                        },
                        {
                            x: getnewDate(8),
                            y: chart_graph[diffDays - 8][1]
                        },
                        {
                            x: getnewDate(9),
                            y: chart_graph[diffDays - 9][1]
                        },
                        {
                            x: getnewDate(10),
                            y: chart_graph[diffDays - 10][1]
                        },
                        {
                            x: getnewDate(11),
                            y: chart_graph[diffDays - 11][1]
                        },
                        {
                            x: getnewDate(12),
                            y: chart_graph[diffDays - 12][1]
                        },
                        {
                            x: getnewDate(13),
                            y: chart_graph[diffDays - 13][1]
                        },
                        {
                            x: getnewDate(14),
                            y: chart_graph[diffDays - 14][1]
                        },
                        {
                            x: getnewDate(15),
                            y: chart_graph[diffDays - 15][1]
                        },
                        {
                            x: getnewDate(16),
                            y: chart_graph[diffDays - 16][1]
                        },
                        {
                            x: getnewDate(17),
                            y: chart_graph[diffDays - 17][1]
                        },
                        {
                            x: getnewDate(18),
                            y: chart_graph[diffDays - 18][1]
                        },
                        {
                            x: getnewDate(19),
                            y: chart_graph[diffDays - 19][1]
                        },
                        {
                            x: getnewDate(20),
                            y: chart_graph[diffDays - 20][1]
                        },
                        {
                            x: getnewDate(21),
                            y: chart_graph[diffDays - 21][1]
                        },
                        {
                            x: getnewDate(22),
                            y: chart_graph[diffDays - 22][1]
                        },
                        {
                            x: getnewDate(23),
                            y: chart_graph[diffDays - 23][1]
                        },
                        {
                            x: getnewDate(24),
                            y: chart_graph[diffDays - 24][1]
                        },
                        {
                            x: getnewDate(25),
                            y: chart_graph[diffDays - 25][1]
                        },
                        {
                            x: getnewDate(26),
                            y: chart_graph[diffDays - 26][1]
                        },
                        {
                            x: getnewDate(27),
                            y: chart_graph[diffDays - 27][1]
                        },
                        {
                            x: getnewDate(28),
                            y: chart_graph[diffDays - 28][1]
                        },
                        {
                            x: getnewDate(29),
                            y: chart_graph[diffDays - 29][1]
                        },
                        {
                            x: getnewDate(30),
                            y: chart_graph[diffDays - 30][1]
                        }
			]
		}
		]
        });
        chart.render();
        t(".coin-graph2 li a").on("click", function (e) {
            e.preventDefault();
            var a = t(this),
                r = t(this).attr("href");
            console.log(a);
            console.log(r);
            t.get(r).done(t => {

                var url = window.location.href;
                url.hash = "";

                chart.options.data[0].dataPoints = [];


                if (r == url + "?price=7") {
                    console.log('7');

                    chart.options.data[0].dataPoints.push({
                        x: getnewDate(0),
                        y: chart_graph[diffDays - 0][1]
                    }, {
                        x: getnewDate(1),
                        y: chart_graph[diffDays - 1][1]
                    }, {
                        x: getnewDate(2),
                        y: chart_graph[diffDays - 2][1]
                    }, {
                        x: getnewDate(3),
                        y: chart_graph[diffDays - 3][1]
                    }, {
                        x: getnewDate(4),
                        y: chart_graph[diffDays - 4][1]
                    }, {
                        x: getnewDate(5),
                        y: chart_graph[diffDays - 5][1]
                    }, {
                        x: getnewDate(6),
                        y: chart_graph[diffDays - 6][1]
                    });
                    chart.render();
                }
                if (r == url + "?price=30") {
                    console.log('30');
                    chart.options.data[0].dataPoints.push({
                        x: getnewDate(0),
                        y: chart_graph[diffDays - 0][1]
                    }, {
                        x: getnewDate(1),
                        y: chart_graph[diffDays - 1][1]
                    }, {
                        x: getnewDate(2),
                        y: chart_graph[diffDays - 2][1]
                    }, {
                        x: getnewDate(3),
                        y: chart_graph[diffDays - 3][1]
                    }, {
                        x: getnewDate(4),
                        y: chart_graph[diffDays - 4][1]
                    }, {
                        x: getnewDate(5),
                        y: chart_graph[diffDays - 5][1]
                    }, {
                        x: getnewDate(6),
                        y: chart_graph[diffDays - 6][1]
                    }, {
                        x: getnewDate(7),
                        y: chart_graph[diffDays - 7][1]
                    }, {
                        x: getnewDate(8),
                        y: chart_graph[diffDays - 8][1]
                    }, {
                        x: getnewDate(9),
                        y: chart_graph[diffDays - 9][1]
                    }, {
                        x: getnewDate(10),
                        y: chart_graph[diffDays - 10][1]
                    }, {
                        x: getnewDate(11),
                        y: chart_graph[diffDays - 11][1]
                    }, {
                        x: getnewDate(12),
                        y: chart_graph[diffDays - 12][1]
                    }, {
                        x: getnewDate(13),
                        y: chart_graph[diffDays - 13][1]
                    }, {
                        x: getnewDate(14),
                        y: chart_graph[diffDays - 14][1]
                    }, {
                        x: getnewDate(15),
                        y: chart_graph[diffDays - 15][1]
                    }, {
                        x: getnewDate(16),
                        y: chart_graph[diffDays - 16][1]
                    }, {
                        x: getnewDate(17),
                        y: chart_graph[diffDays - 17][1]
                    }, {
                        x: getnewDate(18),
                        y: chart_graph[diffDays - 18][1]
                    }, {
                        x: getnewDate(19),
                        y: chart_graph[diffDays - 19][1]
                    }, {
                        x: getnewDate(20),
                        y: chart_graph[diffDays - 20][1]
                    }, {
                        x: getnewDate(21),
                        y: chart_graph[diffDays - 21][1]
                    }, {
                        x: getnewDate(22),
                        y: chart_graph[diffDays - 22][1]
                    }, {
                        x: getnewDate(23),
                        y: chart_graph[diffDays - 23][1]
                    }, {
                        x: getnewDate(24),
                        y: chart_graph[diffDays - 24][1]
                    }, {
                        x: getnewDate(25),
                        y: chart_graph[diffDays - 25][1]
                    }, {
                        x: getnewDate(26),
                        y: chart_graph[diffDays - 26][1]
                    }, {
                        x: getnewDate(27),
                        y: chart_graph[diffDays - 27][1]
                    }, {
                        x: getnewDate(28),
                        y: chart_graph[diffDays - 28][1]
                    }, {
                        x: getnewDate(29),
                        y: chart_graph[diffDays - 29][1]
                    }, {
                        x: getnewDate(30),
                        y: chart_graph[diffDays - 30][1]
                    });
                    chart.render();
                }

                //                chart.data[0].dataPoints[0] = dataPoints, chart.render();
                //                o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")
            })
        })
    }

    if (t("#tknSale-general").length > 0) {

        var theme_color = "#2c80ff";
        //        var tnx_labels = ["14 Feb", "15 Feb", "16 Feb", "17 Feb", "18 Feb"];
        //        var tnx_data = [1, 2, 0, 100, 0];

        var e = document.getElementById("tknSale-general").getContext("2d"),
            o_general = new Chart(e, {
                type: "line",
                data: {
                    labels: tnx_labels,
                    datasets: [{
                        label: "General Bull",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: '#758698',
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }, {
                        type: "line",
                        label: "S&P 500",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: theme_color.base,
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }]

                },
                options: {
                    legend: {
                        display: 1
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        enabled: true,
                        mode: 'label',
                        backgroundColor: "#f2f4f7",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 14,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: 1
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !1,
                                fontSize: 12,
                                fontColor: theme_color.text
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 0,
                                zeroLineColor: "#e9edf3"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontSize: 12,
                                fontColor: theme_color.text,
                                source: "auto"
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 20,
                                zeroLineColor: "#e9edf3"
                            }
                        }]
                    },
                    plugins: {
                        autocolors: false,
                        annotation: {
                            annotations: {
                                box1: {
                                    type: 'box',
                                    xMin: 1,
                                    xMax: 2,
                                    yMin: 50,
                                    yMax: 70,
                                    backgroundColor: 'rgba(255, 99, 132, 1)'
                                }
                            }
                        }
                    }
                }
            });
        t(".popup_general").on("click", function (e) {
            // e.preventDefault();
            var a = t(this),
                r = t(this);

            t.get(r).done(t => {

                // tnx_data3
                var tnx_data2_general = $("#general_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < 12; i++) {
                    tnx_data3.push(tnx_data2_general[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(2);
                    }
                }



                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                // tnx_data
                var tnx_data2_general_bull = $("#general_bull_title").attr("bull").split(',');

                var tnx_data2_general_bull_1 = [0.49, 1.28, 2.56, 2.97, 3.68, 4.41, 4.80, 5.42, 6.57, 7.41, 7.97, 8.53];


                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_general_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        // try {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                        // } catch(err) { 
                        //     continue;
                        // }
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                // $("#general_bull_gains_text span").html((tnx_data[11] - tnx_data3[11]).toFixed(6));
                $("#general_bull_gains_text span").html("Enter an amount");

                // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")

                o_general.data.datasets[0].data = tnx_data;
                o_general.data.datasets[1].data = tnx_data3;

                o_general.update();

            })
        })
        t("#token-number-general-bull").on("input", function () {
            current_amount = $(this).val();

            createCookie("plan", "General Bull", 10);

            if ($(this).val() < 100) {
                current_amount = 100;
            }

            if ($(this).val() > 1000000) {
                current_amount = 1000000;
            }

            // tnx_data
            if ($("#slider-general").val() == 1) {
                $("#general_bull_gains_text span").html((parseFloat($("#token-number-general-bull").val()) + (parseFloat($("#token-number-general-bull").val()) * 8.53 / 100)).toFixed(6));
            }
            if ($("#slider-general").val() == 2) {
                $("#general_bull_gains_text span").html((parseFloat($("#token-number-general-bull").val()) + (parseFloat($("#token-number-general-bull").val()) * 17.23 / 100)).toFixed(6));
            }
            if ($("#slider-general").val() == 3) {
                $("#general_bull_gains_text span").html((parseFloat($("#token-number-general-bull").val()) + (parseFloat($("#token-number-general-bull").val()) * 37.04 / 100)).toFixed(6));
            }

            if ($(this).val() == '') {
                current_amount = 0;
                $("#general_bull_gains_text span").html("Enter an amount");
            }

        });
        t(document).on('input', '#slider-general', function () {

            tnx_labels = [];

            function chartdate(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 11;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            function chartdate2(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 6;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            if ($(this).val() == 1) {

                chartdate(90);

                var tnx_data2_general = $("#general_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_general.length; i++) {
                    tnx_data3.push(tnx_data2_general[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }



                var tnx_data2_general_bull = $("#general_bull_title").attr("bull").split(',');
                var tnx_data2_general_bull_1 = [0.49, 1.28, 2.56, 2.97, 3.68, 4.41, 4.80, 5.42, 6.57, 7.41, 7.97, 8.53];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_general_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#general_bull_gains_text span").html((parseFloat($("#token-number-general-bull").val()) + (parseFloat($("#token-number-general-bull").val()) * 8.53 / 100)).toFixed(6));
                $(".general_bull_roi").html("8%");

            }
            if ($(this).val() == 2) {

                chartdate2(180);

                var tnx_data2_general = $("#general_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_general.length; i++) {
                    tnx_data3.push(tnx_data2_general[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 6;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }




                var tnx_data2_general_bull = $("#general_bull_title").attr("bull2").split(',');
                // var tnx_data2_general_bull_1 = [0.49, 1.28, 2.56, 2.97, 3.68, 4.41, 4.80, 5.42, 6.57, 7.41, 7.97, 8.53];
                var tnx_data2_general_bull_1 = [3.34, 6.27, 8.53, 10.12, 13.57, 17.23];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 6; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_general_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 6;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#general_bull_gains_text span").html((parseFloat($("#token-number-general-bull").val()) + (parseFloat($("#token-number-general-bull").val()) * 17.23 / 100)).toFixed(6));
                $(".general_bull_roi").html("17%");

            }
            if ($(this).val() == 3) {
                chartdate(366);

                var tnx_data2_general = $("#general_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_general.length; i++) {
                    tnx_data3.push(tnx_data2_general[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                // var tnx_data2_general_bull = $("#general_bull_title").attr("bull2").split(',');
                var tnx_data2_general_bull_1 = [3.35, 6.57, 8.38, 10.61, 13.18, 17.74, 20.28, 23.46, 26.63, 29.10, 33.15, 37.04];
                // var tnx_data2_general_bull_1 = [3.34, 6.27, 8.53, 10.12, 13.57, 17.23];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_general_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#general_bull_gains_text span").html((parseFloat($("#token-number-general-bull").val()) + (parseFloat($("#token-number-general-bull").val()) * 37.04 / 100)).toFixed(6));
                $(".general_bull_roi").html("37%");

            }

            if ($("#token-number-general-bull").val() == '') {
                current_amount = 0;
                $("#general_bull_gains_text span").html("Enter an amount");
            }

            o_general.data.labels = tnx_labels;
            o_general.data.datasets[0].data = tnx_data;
            o_general.data.datasets[1].data = tnx_data3;

            o_general.update();

        });
    }
    if (t("#tknSale-stocks").length > 0) {

        var theme_color = "#2c80ff";
        //        var tnx_labels = ["14 Feb", "15 Feb", "16 Feb", "17 Feb", "18 Feb"];
        //        var tnx_data = [1, 2, 0, 100, 0];


        var e = document.getElementById("tknSale-stocks").getContext("2d"),
            o_stocks = new Chart(e, {
                type: "line",
                data: {
                    labels: tnx_labels_stocks,
                    datasets: [{
                        label: "Stocks Bull",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: "#1babfe",
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }, {
                        label: "Apple Stock",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: theme_color.base,
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }]
                },
                options: {
                    legend: {
                        display: 1
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        enabled: true,
                        mode: 'label',
                        backgroundColor: "#f2f4f7",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 14,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: 1
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !1,
                                fontSize: 12,
                                fontColor: theme_color.text
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 0,
                                zeroLineColor: "#e9edf3"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontSize: 12,
                                fontColor: theme_color.text,
                                source: "auto"
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 20,
                                zeroLineColor: "#e9edf3"
                            }
                        }]
                    }
                }
            });
        t(".popup_stocks").on("click", function (e) {
            // e.preventDefault();
            var a = t(this),
                r = t(this);

            t.get(r).done(t => {



                // tnx_data3
                var tnx_data2_stocks = $("#stocks_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_stocks.length; i++) {
                    tnx_data3.push(tnx_data2_stocks[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }



                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }


                // tnx_data
                // var tnx_data2_stocks_bull = $("#stocks_bull_title").attr("bull").split(',');

                var tnx_data2_stocks_bull_1 = [1.43, 2.54, 4.34, 5.73, 6.73, 8.53, 9.83, 10.58, 12.23, 14.73, 17.47, 18.10];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_stocks_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#stocks_bull_gains_text span").html("Enter an amount");

                // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")

                o_stocks.data.datasets[0].data = tnx_data;
                o_stocks.data.datasets[1].data = tnx_data3;

                o_stocks.update();

            })
        })
        t("#token-number-stocks-bull").on("input", function () {
            current_amount = $(this).val();

            createCookie("plan", "Stocks Bull", 10);

            if ($(this).val() < 100) {
                current_amount = 100;
            }

            if ($(this).val() > 1000000) {
                current_amount = 1000000;
            }

            if ($("#slider-stocks").val() == 1) {
                $("#stocks_bull_gains_text span").html((parseFloat($("#token-number-stocks-bull").val()) + (parseFloat($("#token-number-stocks-bull").val()) * 18.10 / 100)).toFixed(6));
            }
            if ($("#slider-stocks").val() == 2) {
                $("#stocks_bull_gains_text span").html((parseFloat($("#token-number-stocks-bull").val()) + (parseFloat($("#token-number-stocks-bull").val()) * 39.89 / 100)).toFixed(6));
            }
            if ($("#slider-stocks").val() == 3) {
                $("#stocks_bull_gains_text span").html((parseFloat($("#token-number-stocks-bull").val()) + (parseFloat($("#token-number-stocks-bull").val()) * 94.82 / 100)).toFixed(6));
            }

            if ($(this).val() == '') {
                current_amount = 0;
                $("#stocks_bull_gains_text span").html("Enter an amount");
            }

        });
        t(document).on('input', '#slider-stocks', function () {

            tnx_labels = [];

            function chartdate(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 11;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            function chartdate2(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 6;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            if ($(this).val() == 1) {

                chartdate(90);

                var tnx_data2_stocks = $("#stocks_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_stocks.length; i++) {
                    tnx_data3.push(tnx_data2_stocks[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }



                var tnx_data2_stocks_bull = $("#stocks_bull_title").attr("bull").split(',');
                var tnx_data2_stocks_bull_1 = [1.43, 2.54, 4.34, 5.73, 6.73, 8.53, 9.83, 10.58, 12.23, 14.73, 17.47, 18.10];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_stocks_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#stocks_bull_gains_text span").html((parseFloat($("#token-number-stocks-bull").val()) + (parseFloat($("#token-number-stocks-bull").val()) * 18.10 / 100)).toFixed(6));
                $(".stocks_bull_roi").html("18%");

            }
            if ($(this).val() == 2) {

                chartdate2(180);

                // var tnx_data2_stocks = $("#stocks_bull_title").attr("data2").split(',');
                var tnx_data2_stocks = $("#stocks_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_stocks.length; i++) {
                    tnx_data3.push(tnx_data2_stocks[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 6;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }
                
                //[1.43, 2.54, 4.34, 5.73, 6.73, 8.53, 9.83, 10.58, 12.23, 14.73, 17.47, 18.10];
                var tnx_data2_stocks_bull_1 = [4.70, 13.42, 18.57, 25.67, 33.78, 39.89];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 6; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_stocks_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 6;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#stocks_bull_gains_text span").html((parseFloat($("#token-number-stocks-bull").val()) + (parseFloat($("#token-number-stocks-bull").val()) * 39.89 / 100)).toFixed(6));
                $(".stocks_bull_roi").html("39%");

            }
            if ($(this).val() == 3) {
                chartdate(366);

                var tnx_data2_stocks = $("#stocks_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_stocks.length; i++) {
                    tnx_data3.push(tnx_data2_stocks[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                // var tnx_data2_stocks_bull = $("#stocks_bull_title").attr("bull2").split(',');
                // [4.70, 13.42, 18.57, 25.67, 33.78, 39.89];
                var tnx_data2_stocks_bull_1 = [4.34, 14.87, 26.76, 26.56, 34.54, 40.47, 52.72, 64.58, 72.59, 78.59, 84.33, 94.82];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_stocks_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#stocks_bull_gains_text span").html((parseFloat($("#token-number-stocks-bull").val()) + (parseFloat($("#token-number-stocks-bull").val()) * 94.82 / 100)).toFixed(6));
                $(".stocks_bull_roi").html("94%");

            }

            if ($("#token-number-stocks-bull").val() == '') {
                current_amount = 0;
                $("#stocks_bull_gains_text span").html("Enter an amount");
            }

            o_stocks.data.labels = tnx_labels;
            o_stocks.data.datasets[0].data = tnx_data;
            o_stocks.data.datasets[1].data = tnx_data3;

            o_stocks.update();

        });
    }
    if (t("#tknSale-crypto").length > 0) {

        var theme_color = "#2c80ff";
        //        var tnx_labels = ["14 Feb", "15 Feb", "16 Feb", "17 Feb", "18 Feb"];
        //        var tnx_data = [1, 2, 0, 100, 0];


        var e = document.getElementById("tknSale-crypto").getContext("2d"),
            o_crypto = new Chart(e, {
                type: "line",
                data: {
                    labels: tnx_labels_crypto,
                    datasets: [{
                        label: "Crypto Bull",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: "#ffc100",
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }, {
                        label: "CMC 200",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: theme_color.base,
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }]
                },
                options: {
                    legend: {
                        display: 1
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        enabled: true,
                        mode: 'label',
                        backgroundColor: "#ffc100",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 14,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: 1
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !1,
                                fontSize: 12,
                                fontColor: theme_color.text
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 0,
                                zeroLineColor: "#e9edf3"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontSize: 12,
                                fontColor: theme_color.text,
                                source: "auto"
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 20,
                                zeroLineColor: "#e9edf3"
                            }
                        }]
                    }
                }
            });
        t(".popup_crypto").on("click", function (e) {
            // e.preventDefault();
            var a = t(this),
                r = t(this);

            t.get(r).done(t => {

                // tnx_data3
                var tnx_data2_crypto = $("#crypto_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_crypto.length; i++) {
                    tnx_data3.push(tnx_data2_crypto[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }



                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }


                // tnx_data
                // var tnx_data2_crypto_bull = $("#crypto_bull_title").attr("bull").split(',');
                var tnx_data2_general_bull_1 = [1.29, 3.27, 5.46, 7.23, 8.46, 10.46, 12.41, 14.13, 16.22, 18.36, 21.27, 23.52];


                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_general_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#crypto_bull_gains_text span").html("Enter an amount");

                // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")

                o_crypto.data.datasets[0].data = tnx_data;
                o_crypto.data.datasets[1].data = tnx_data3;

                o_crypto.update();

            })
        })
        t("#token-number-crypto-bull").on("input", function () {
            current_amount = $(this).val();

            createCookie("plan", "Crypto Bull", 10);

            if ($(this).val() < 500) {
                current_amount = 500;
            }

            if ($(this).val() > 1000000) {
                current_amount = 1000000;
            }

            if ($("#slider-crypto").val() == 1) {
                $("#crypto_bull_gains_text span").html((parseFloat($("#token-number-crypto-bull").val()) + (parseFloat($("#token-number-crypto-bull").val()) * 23.52 / 100)).toFixed(6));
            }
            if ($("#slider-crypto").val() == 2) {
                $("#crypto_bull_gains_text span").html((parseFloat($("#token-number-crypto-bull").val()) + (parseFloat($("#token-number-crypto-bull").val()) * 51.83 / 100)).toFixed(6));
            }
            if ($("#slider-crypto").val() == 3) {
                $("#crypto_bull_gains_text span").html((parseFloat($("#token-number-crypto-bull").val()) + (parseFloat($("#token-number-crypto-bull").val()) * 129.93 / 100)).toFixed(6));
            }

            if ($(this).val() == '') {
                current_amount = 0;
                $("#crypto_bull_gains_text span").html("Enter an amount");
            }

        });
        t(document).on('input', '#slider-crypto', function () {

            tnx_labels = [];

            function chartdate(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 11;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            function chartdate2(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 6;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            if ($(this).val() == 1) {

                chartdate(90);

                var tnx_data2_crypto = $("#crypto_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_crypto.length; i++) {
                    tnx_data3.push(tnx_data2_crypto[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }



                var tnx_data2_general_bull_1 = [1.29, 3.27, 5.46, 7.23, 8.46, 10.46, 12.41, 14.13, 16.22, 18.36, 21.27, 23.52];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_general_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#crypto_bull_gains_text span").html((parseFloat($("#token-number-crypto-bull").val()) + (parseFloat($("#token-number-crypto-bull").val()) * 23.52 / 100)).toFixed(6));
                $(".crypto_bull_roi").html("23%");
            }
            if ($(this).val() == 2) {

                chartdate2(180);

                var tnx_data2_crypto = $("#crypto_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_crypto.length; i++) {
                    tnx_data3.push(tnx_data2_crypto[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 6;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }



                // [1.29, 3.27, 5.46, 7.23, 8.46, 10.46, 12.41, 14.13, 16.22, 18.36, 21.27, 23.52];
                var tnx_data2_general_bull_1 = [2.16, 11.46, 23.32, 32.34, 43.56, 51.83];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 6; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_general_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 6;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#crypto_bull_gains_text span").html((parseFloat($("#token-number-crypto-bull").val()) + (parseFloat($("#token-number-crypto-bull").val()) * 51.83 / 100)).toFixed(6));
                $(".crypto_bull_roi").html("51%");
            }
            if ($(this).val() == 3) {
                chartdate(366);

                var tnx_data2_crypto = $("#crypto_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_crypto.length; i++) {
                    tnx_data3.push(tnx_data2_crypto[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                // [2.16, 11.46, 23.32, 32.34, 43.56, 51.83];
                var tnx_data2_general_bull_1 = [2.14, 11.61, 18.26, 30.41, 41.13, 61.42, 73.27, 85.28, 93.46, 101.27, 117.56, 129.93];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_general_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#crypto_bull_gains_text span").html((parseFloat($("#token-number-crypto-bull").val()) + (parseFloat($("#token-number-crypto-bull").val()) * 129.93 / 100)).toFixed(6));
                $(".crypto_bull_roi").html("129%");
            }

            if ($("#token-number-crypto-bull").val() == '') {
                current_amount = 0;
                $("#crypto_bull_gains_text span").html("Enter an amount");
            }

            // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")

            o_crypto.data.labels = tnx_labels;
            o_crypto.data.datasets[0].data = tnx_data;
            o_crypto.data.datasets[1].data = tnx_data3;

            o_crypto.update();

        });
    }
    if (t("#tknSale-real-estate").length > 0) {

        var theme_color = "#2c80ff";
        //        var tnx_labels = ["14 Feb", "15 Feb", "16 Feb", "17 Feb", "18 Feb"];
        //        var tnx_data = [1, 2, 0, 100, 0];


        var e = document.getElementById("tknSale-real-estate").getContext("2d"),
            o_real_estate = new Chart(e, {
                type: "line",
                data: {
                    labels: tnx_labels_real_estate,
                    datasets: [{
                        label: "Real Estate Bull",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: "#253992",
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }, {
                        label: "Vanguard Real Estate Index Fund",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: theme_color.base,
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }]
                },
                options: {
                    legend: {
                        display: 1
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        enabled: true,
                        mode: 'label',
                        backgroundColor: "#253992",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 14,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: 1
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !1,
                                fontSize: 12,
                                fontColor: theme_color.text
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 0,
                                zeroLineColor: "#e9edf3"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontSize: 12,
                                fontColor: theme_color.text,
                                source: "auto"
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 20,
                                zeroLineColor: "#e9edf3"
                            }
                        }]
                    }
                }
            });
        t(".popup_real_estate").on("click", function (e) {
            // e.preventDefault();
            var a = t(this),
                r = t(this);

            t.get(r).done(t => {

                // tnx_data3
                var tnx_data2_real_estate = $("#real_estate_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_real_estate.length; i++) {
                    tnx_data3.push(tnx_data2_real_estate[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }



                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }


                // tnx_data
                var tnx_data2_general_bull_1 = [1.23, 3.23, 3.56, 4.12, 5.98, 6.66, 7.43, 8.23, 9.56, 10.44, 11.44, 12, 43];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_general_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#real_estate_bull_gains_text span").html("Enter an amount");

                // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")

                o_real_estate.data.datasets[0].data = tnx_data;
                o_real_estate.data.datasets[1].data = tnx_data3;

                o_real_estate.update();

            })
        })
        t("#token-number-real_estate-bull").on("input", function () {
            current_amount = $(this).val();

            createCookie("plan", "Real Estate Bull", 10);

            if ($(this).val() < 100) {
                current_amount = 100;
            }

            if ($(this).val() > 1000000) {
                current_amount = 1000000;
            }

            // tnx_data
            if ($("#slider-real-estate").val() == 1) {
                $("#real_estate_bull_gains_text span").html((parseFloat($("#token-number-real_estate-bull").val()) + (parseFloat($("#token-number-real_estate-bull").val()) * 12.132 / 100)).toFixed(6));
            }
            if ($("#slider-real-estate").val() == 2) {
                $("#real_estate_bull_gains_text span").html((parseFloat($("#token-number-real_estate-bull").val()) + (parseFloat($("#token-number-real_estate-bull").val()) * 24.132 / 100)).toFixed(6));
            }
            if ($("#slider-real-estate").val() == 3) {
                $("#real_estate_bull_gains_text span").html((parseFloat($("#token-number-real_estate-bull").val()) + (parseFloat($("#token-number-real_estate-bull").val()) * 60.132 / 100)).toFixed(6));
            }

            if ($(this).val() == '') {
                current_amount = 0;
                $("#real_estate_bull_gains_text span").html("Enter an amount");
            }

        });
        t(document).on('input', '#slider-real-estate', function () {

            tnx_labels = [];

            function chartdate(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 11;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            function chartdate2(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 6;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            if ($(this).val() == 1) {

                chartdate(90);

                var tnx_data2_real_estate = $("#real_estate_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_real_estate.length; i++) {
                    tnx_data3.push(tnx_data2_real_estate[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                var tnx_data2_general_bull_1 = [1.23, 3.23, 3.56, 4.12, 5.98, 6.66, 7.43, 8.23, 9.56, 10.44, 11.44, 12, 43];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_general_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }
                $("#real_estate_bull_gains_text span").html((parseFloat($("#token-number-real_estate-bull").val()) + (parseFloat($("#token-number-real_estate-bull").val()) * 12.132 / 100)).toFixed(6));
                $(".real_estate_bull_roi").html("22%");

            }
            if ($(this).val() == 2) {

                chartdate2(180);

                var tnx_data2_real_estate = $("#real_estate_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_real_estate.length; i++) {
                    tnx_data3.push(tnx_data2_real_estate[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 6;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }


                var tnx_data2_general_bull_1 = [2.23, 6.56, 10.98, 14.43, 18.56, 24, 43];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 6; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_general_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 6;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#real_estate_bull_gains_text span").html((parseFloat($("#token-number-real_estate-bull").val()) + (parseFloat($("#token-number-real_estate-bull").val()) * 24.132 / 100)).toFixed(6));
                $(".real_estate_bull_roi").html("49%");
            }
            if ($(this).val() == 3) {
                chartdate(366);

                var tnx_data2_real_estate = $("#real_estate_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_real_estate.length; i++) {
                    tnx_data3.push(tnx_data2_real_estate[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                var tnx_data2_general_bull_1 = [2.23, 6.56, 10.98, 14.43, 18.56, 24, 43, 34.34, 39.23, 45.76, 52.23, 57.54, 60.34];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_general_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#real_estate_bull_gains_text span").html((parseFloat($("#token-number-real_estate-bull").val()) + (parseFloat($("#token-number-real_estate-bull").val()) * 60.132 / 100)).toFixed(6));
                $(".real_estate_bull_roi").html("122%");
            }

            if ($("#token-number-real_estate-bull").val() == '') {
                current_amount = 0;
                $("#real_estate_bull_gains_text span").html("Enter an amount");
            }

            // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")
            o_real_estate.data.labels = tnx_labels;
            o_real_estate.data.datasets[0].data = tnx_data;
            o_real_estate.data.datasets[1].data = tnx_data3;

            o_real_estate.update();

        });
    }
    if (t("#tknSale-green-bonds").length > 0) {

        var theme_color = "#2c80ff";
        //        var tnx_labels = ["14 Feb", "15 Feb", "16 Feb", "17 Feb", "18 Feb"];
        //        var tnx_data = [1, 2, 0, 100, 0];


        var e = document.getElementById("tknSale-green-bonds").getContext("2d"),
            o_green_bonds = new Chart(e, {
                type: "line",
                data: {
                    labels: tnx_labels_green_bonds,
                    datasets: [{
                        label: "Ecological Bull",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: "#00d285",
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }, {
                        label: "Federal Global Green Bonds",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: theme_color.base,
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }]
                },
                options: {
                    legend: {
                        display: 1
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        enabled: true,
                        mode: 'label',
                        backgroundColor: "#f2f4f7",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 14,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: 1
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !1,
                                fontSize: 12,
                                fontColor: theme_color.text
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 0,
                                zeroLineColor: "#e9edf3"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontSize: 12,
                                fontColor: theme_color.text,
                                source: "auto"
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 20,
                                zeroLineColor: "#e9edf3"
                            }
                        }]
                    }
                }
            });
        t(".popup_green_bonds").on("click", function (e) {
            // e.preventDefault();
            var a = t(this),
                r = t(this);

            t.get(r).done(t => {

                // tnx_data3
                var tnx_data2_green_bonds = $("#green_bonds_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_green_bonds.length; i++) {
                    tnx_data3.push(tnx_data2_green_bonds[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                // tnx_data
                var tnx_data2_stocks_bull_1 = [3.56, 5.87, 7.34, 8.82, 9.28, 10.64, 12.43, 14.23, 16.56, 18.56, 20.76, 22.62];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_stocks_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                // $("#green_bonds_bull_gains_text span").html((tnx_data[11] - tnx_data3[11]).toFixed(6));
                $("#green_bonds_bull_gains_text span").html("Enter an amount");

                // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")

                o_green_bonds.data.datasets[0].data = tnx_data;
                o_green_bonds.data.datasets[1].data = tnx_data3;

                o_green_bonds.update();

            })
        })
        t("#token-number-green_bonds-bull").on("input", function () {
            current_amount = $(this).val();

            createCookie("plan", "Ecological Bull", 10);

            if ($(this).val() < 100) {
                current_amount = 100;
            }

            if ($(this).val() > 1000000) {
                current_amount = 1000000;
            }

            // tnx_data
            if ($("#slider-green-bonds").val() == 1) {
                $("#green_bonds_bull_gains_text span").html((parseFloat($("#token-number-green_bonds-bull").val()) + (parseFloat($("#token-number-green_bonds-bull").val()) * 22.62 / 100)).toFixed(6));
            }
            if ($("#slider-green-bonds").val() == 2) {
                $("#green_bonds_bull_gains_text span").html((parseFloat($("#token-number-green_bonds-bull").val()) + (parseFloat($("#token-number-green_bonds-bull").val()) * 49.32 / 100)).toFixed(6));
            }
            if ($("#slider-green-bonds").val() == 3) {
                $("#green_bonds_bull_gains_text span").html((parseFloat($("#token-number-green_bonds-bull").val()) + (parseFloat($("#token-number-green_bonds-bull").val()) * 122.62 / 100)).toFixed(6));
            }

            if ($(this).val() == '') {
                current_amount = 0;
                $("#green_bonds_bull_gains_text span").html("Enter an amount");
            }

        });
        t(document).on('input', '#slider-green-bonds', function () {

            tnx_labels = [];

            function chartdate(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 11;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            function chartdate2(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 6;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            if ($(this).val() == 1) {

                chartdate(90);

                var tnx_data2_green_bonds = $("#green_bonds_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_green_bonds.length; i++) {
                    tnx_data3.push(tnx_data2_green_bonds[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }



                var tnx_data2_green_bonds_bull_1 = [3.56, 5.87, 7.34, 8.82, 9.28, 10.64, 12.43, 14.23, 16.56, 18.56, 20.76, 22.62];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_green_bonds_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#green_bonds_bull_gains_text span").html((parseFloat($("#token-number-green_bonds-bull").val()) + (parseFloat($("#token-number-green_bonds-bull").val()) * 22.62 / 100)).toFixed(6));
                $(".green_bonds_bull_roi").html("22%");

            }
            if ($(this).val() == 2) {

                chartdate2(180);

                var tnx_data2_green_bonds = $("#green_bonds_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_green_bonds.length; i++) {
                    tnx_data3.push(tnx_data2_green_bonds[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 6;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }



                // [3.56, 5.87, 7.34, 8.82, 9.28, 10.64, 12.43, 14.23, 16.56, 18.56, 20.76, 22.62];
                var tnx_data2_green_bonds_bull_1 = [4.73, 15.53, 21.48, 13, 38.57, 49.27];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 6; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_green_bonds_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 6;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#green_bonds_bull_gains_text span").html((parseFloat($("#token-number-green_bonds-bull").val()) + (parseFloat($("#token-number-green_bonds-bull").val()) * 49.32 / 100)).toFixed(6));
                $(".green_bonds_bull_roi").html("49%");

            }
            if ($(this).val() == 3) {
                chartdate(366);

                var tnx_data2_green_bonds = $("#green_bonds_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_green_bonds.length; i++) {
                    tnx_data3.push(tnx_data2_green_bonds[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                // [4.73, 15.53, 21.48, 13, 38.57, 49.27];
                var tnx_data2_green_bonds_bull_1 = [4.38, 13.47, 25.73, 25.73, 34.31, 45.47, 56.38, 68.11, 78.13, 92.42, 102.36, 122.62];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_green_bonds_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                // $("#green_bonds_bull_gains_text span").html((tnx_data[11] - tnx_data3[11]).toFixed(6));

                $("#green_bonds_bull_gains_text span").html((parseFloat($("#token-number-green_bonds-bull").val()) + (parseFloat($("#token-number-green_bonds-bull").val()) * 122.62 / 100)).toFixed(6));
                $(".green_bonds_bull_roi").html("122%");
            }


            if ($("#token-number-green_bonds-bull").val() == '') {
                current_amount = 0;
                $("#green_bonds_bull_gains_text span").html("Enter an amount");
            }

            // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")
            o_green_bonds.data.labels = tnx_labels;
            o_green_bonds.data.datasets[0].data = tnx_data;
            o_green_bonds.data.datasets[1].data = tnx_data3;

            o_green_bonds.update();

        });
    }
    if (t("#tknSale-high-risk").length > 0) {

        var theme_color = "#2c80ff";
        //        var tnx_labels = ["14 Feb", "15 Feb", "16 Feb", "17 Feb", "18 Feb"];
        //        var tnx_data = [1, 2, 0, 100, 0];


        var e = document.getElementById("tknSale-high-risk").getContext("2d"),
            o_ipo = new Chart(e, {
                type: "line",
                data: {
                    labels: tnx_labels_high_risk,
                    datasets: [{
                        label: "IPO Bull",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: "#ff6868",
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }, {
                        label: "International IPOs",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: theme_color.base,
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }]
                },
                options: {
                    legend: {
                        display: 1
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        enabled: true,
                        mode: 'label',
                        backgroundColor: "#f2f4f7",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 14,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: 1
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !1,
                                fontSize: 12,
                                fontColor: theme_color.text
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 0,
                                zeroLineColor: "#e9edf3"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontSize: 12,
                                fontColor: theme_color.text,
                                source: "auto"
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 20,
                                zeroLineColor: "#e9edf3"
                            }
                        }]
                    }
                }
            });
        t(".popup_ipo").on("click", function (e) {
            // e.preventDefault();
            var a = t(this),
                r = t(this);

            t.get(r).done(t => {



                // tnx_data3
                var tnx_data2_ipo = $("#ipo_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_ipo.length; i++) {
                    tnx_data3.push(tnx_data2_ipo[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }



                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                // tnx_data
                var tnx_data2_ipo_bull_1 = [3.23, 8.23, 12.56, 19.12, 27.98, 31.66, 37.43, 43.23, 51.56, 58.44, 65.44, 70, 43];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_ipo_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                // $("#high_risk_bull_gains_text span").html((tnx_data[11] - tnx_data3[11]).toFixed(6));
                $("#stocks_bull_gains_text span").html("Enter an amount");
                // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")

                o_ipo.data.datasets[0].data = tnx_data;
                o_ipo.data.datasets[1].data = tnx_data3;

                o_ipo.update();

            })
        })
        t("#token-number-ipo-bull").on("input", function () {
            current_amount = $(this).val();

            createCookie("plan", "IPO Bull", 10);

            if ($(this).val() < 500) {
                current_amount = 500;
            }

            if ($(this).val() > 1000000) {
                current_amount = 1000000;
            }

            if ($("#slider-high-risk").val() == 1) {
                $("#high_risk_bull_gains_text span").html((parseFloat($("#token-number-ipo-bull").val()) + (parseFloat($("#token-number-ipo-bull").val()) * 80.132 / 100)).toFixed(6));
            }
            if ($("#slider-high-risk").val() == 2) {
                $("#high_risk_bull_gains_text span").html((parseFloat($("#token-number-ipo-bull").val()) + (parseFloat($("#token-number-ipo-bull").val()) * 160.132 / 100)).toFixed(6));
            }
            if ($("#slider-high-risk").val() == 3) {
                $("#high_risk_bull_gains_text span").html((parseFloat($("#token-number-ipo-bull").val()) + (parseFloat($("#token-number-ipo-bull").val()) * 340.132 / 100)).toFixed(6));
            }

            if ($(this).val() == '') {
                current_amount = 0;
                $("#high_risk_bull_gains_text span").html("Enter an amount");
            }

        });
        t(document).on('input', '#slider-high-risk', function () {

            tnx_labels = [];

            function chartdate(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 11;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            function chartdate2(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 6;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            if ($(this).val() == 1) {

                chartdate(90);

                var tnx_data2_ipo = $("#ipo_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_ipo.length; i++) {
                    tnx_data3.push(tnx_data2_ipo[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }



                var tnx_data2_ipo_bull = $("#ipo_bull_title").attr("bull").split(',');
                var tnx_data2_general_bull_1 = [7.23, 14.23, 24.56, 29.12, 35.98, 41.66, 47.43, 56.23, 61.56, 67.44, 72.44, 80, 43];


                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_general_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#high_risk_bull_gains_text span").html((parseFloat($("#token-number-ipo-bull").val()) + (parseFloat($("#token-number-ipo-bull").val()) * 80.132 / 100)).toFixed(6));
                $(".ipo_bull_roi").html("30-80%");

            }
            if ($(this).val() == 2) {

                chartdate2(180);

                var tnx_data2_ipo = $("#ipo_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_ipo.length; i++) {
                    tnx_data3.push(tnx_data2_ipo[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 6;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }




                var tnx_data2_ipo_bull = $("#ipo_bull_title").attr("bull2").split(',');
                var tnx_data2_general_bull_1 = [14.23, 48.56, 70.98, 92.43, 122.56, 170, 43];


                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 6; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_general_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 6;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }
                $("#high_risk_bull_gains_text span").html((parseFloat($("#token-number-ipo-bull").val()) + (parseFloat($("#token-number-ipo-bull").val()) * 160.132 / 100)).toFixed(6));
                $(".ipo_bull_roi").html("60-160%");
            }
            if ($(this).val() == 3) {
                chartdate(366);

                var tnx_data2_ipo = $("#ipo_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_ipo.length; i++) {
                    tnx_data3.push(tnx_data2_ipo[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                var tnx_data2_ipo_bull = $("#ipo_bull_title").attr("bull2").split(',');
                var tnx_data2_general_bull_1 = [14.23, 48.56, 70.98, 92.43, 122.56, 150.32, 170.43, 220.32, 250.34, 290.32, 330.32, 370.32];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_general_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }
                $("#high_risk_bull_gains_text span").html((parseFloat($("#token-number-ipo-bull").val()) + (parseFloat($("#token-number-ipo-bull").val()) * 340.132 / 100)).toFixed(6));
                $(".ipo_bull_roi").html("160-360%");
            }

            if ($("#token-number-ipo-bull").val() == '') {
                current_amount = 0;
                $("#high_risk_bull_gains_text span").html("Enter an amount");
            }

            // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")
            o_ipo.data.labels = tnx_labels;
            o_ipo.data.datasets[0].data = tnx_data;
            o_ipo.data.datasets[1].data = tnx_data3;

            o_ipo.update();

        });
    }
    if (t("#tknSale-nft").length > 0) {

        var theme_color = "#2c80ff";
        //        var tnx_labels = ["14 Feb", "15 Feb", "16 Feb", "17 Feb", "18 Feb"];
        //        var tnx_data = [1, 2, 0, 100, 0];


        var e = document.getElementById("tknSale-nft").getContext("2d"),
            o_nft = new Chart(e, {
                type: "line",
                data: {
                    labels: tnx_labels_high_risk,
                    datasets: [{
                        label: "NFT Bull",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: "#e6effb",
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }, {
                        label: "International NFTs",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: theme_color.base,
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }]
                },
                options: {
                    legend: {
                        display: 1
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        enabled: true,
                        mode: 'label',
                        backgroundColor: "#f2f4f7",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 14,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: 1
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !1,
                                fontSize: 12,
                                fontColor: theme_color.text
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 0,
                                zeroLineColor: "#e9edf3"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontSize: 12,
                                fontColor: theme_color.text,
                                source: "auto"
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 20,
                                zeroLineColor: "#e9edf3"
                            }
                        }]
                    }
                }
            });
        t(".popup_nft").on("click", function (e) {
            // e.preventDefault();
            var a = t(this),
                r = t(this);

            t.get(r).done(t => {



                // tnx_data3
                var tnx_data2_nft = $("#nft_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_nft.length; i++) {
                    tnx_data3.push(tnx_data2_nft[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }



                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                // tnx_data
                var tnx_data2_nft_bull_1 = [1.64, 3.72, 4.67, 6.23, 8.72, 10.47, 12.92, 14.49, 16.49, 18.38, 23.24, 28.77];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_nft_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                // $("#high_risk_bull_gains_text span").html((tnx_data[11] - tnx_data3[11]).toFixed(6));
                $("#nft_bull_gains_text span").html("Enter an amount");
                // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")

                o_nft.data.datasets[0].data = tnx_data;
                o_nft.data.datasets[1].data = tnx_data3;

                o_nft.update();

            })
        })
        t("#token-number-nft-bull").on("input", function () {
            current_amount = $(this).val();

            createCookie("plan", "NFT Bull", 10);

            if ($(this).val() < 500) {
                current_amount = 500;
            }

            if ($(this).val() > 1000000) {
                current_amount = 1000000;
            }

            if ($("#slider-nft").val() == 1) {
                $("#nft_bull_gains_text span").html((parseFloat($("#token-number-nft-bull").val()) + (parseFloat($("#token-number-nft-bull").val()) * 28.77 / 100)).toFixed(6));
            }
            if ($("#slider-nft").val() == 2) {
                $("#nft_bull_gains_text span").html((parseFloat($("#token-number-nft-bull").val()) + (parseFloat($("#token-number-nft-bull").val()) * 64.11 / 100)).toFixed(6));
            }
            if ($("#slider-nft").val() == 3) {
                $("#nft_bull_gains_text span").html((parseFloat($("#token-number-nft-bull").val()) + (parseFloat($("#token-number-nft-bull").val()) * 168.77 / 100)).toFixed(6));
            }

            if ($(this).val() == '') {
                current_amount = 0;
                $("#nft_bull_gains_text span").html("Enter an amount");
            }

        });
        t(document).on('input', '#slider-nft', function () {

            tnx_labels = [];

            function chartdate(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 11;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            function chartdate2(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 6;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            if ($(this).val() == 1) {

                chartdate(90);

                var tnx_data2_nft = $("#nft_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_nft.length; i++) {
                    tnx_data3.push(tnx_data2_nft[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }



                var tnx_data2_nft_bull = $("#nft_bull_title").attr("bull").split(',');
                var tnx_data2_nft_bull_1 = [1.64, 3.72, 4.67, 6.23, 8.72, 10.47, 12.92, 14.49, 16.49, 18.38, 23.24, 28.77];


                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_nft_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#nft_bull_gains_text span").html((parseFloat($("#token-number-nft-bull").val()) + (parseFloat($("#token-number-nft-bull").val()) * 28.77 / 100)).toFixed(6));
                $(".nft_bull_roi").html("28%");

            }
            if ($(this).val() == 2) {

                chartdate2(180);

                var tnx_data2_nft = $("#nft_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_nft.length; i++) {
                    tnx_data3.push(tnx_data2_nft[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 6;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }




                var tnx_data2_nft_bull = $("#nft_bull_title").attr("bull2").split(',');
                // [1.64, 3.72, 4.67, 6.23, 8.72, 10.47, 12.92, 14.49, 16.49, 18.38, 23.24, 28.77];
                var tnx_data2_nft_bull_1 = [4.82, 14.37, 28.83, 35.53, 39.11, 47.22, 64.11];


                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 6; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_nft_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 6;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }
                $("#nft_bull_gains_text span").html((parseFloat($("#token-number-nft-bull").val()) + (parseFloat($("#token-number-nft-bull").val()) * 64.11 / 100)).toFixed(6));
                $(".nft_bull_roi").html("64%");
            }
            if ($(this).val() == 3) {
                chartdate(366);

                var tnx_data2_nft = $("#nft_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_nft.length; i++) {
                    tnx_data3.push(tnx_data2_nft[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                var tnx_data2_nft_bull = $("#nft_bull_title").attr("bull2").split(',');
                var tnx_data2_nft_bull_1 = [4.74, 14.47, 32.73, 32.88, 43.12, 64.36, 77.17, 87.43, 99.11, 127.37, 145.37, 168.77];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_nft_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }
                $("#nft_bull_gains_text span").html((parseFloat($("#token-number-nft-bull").val()) + (parseFloat($("#token-number-nft-bull").val()) * 168.77 / 100)).toFixed(6));
                $(".nft_bull_roi").html("168%");
            }

            if ($("#token-number-nft-bull").val() == '') {
                current_amount = 0;
                $("#nft_bull_gains_text span").html("Enter an amount");
            }

            // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")
            o_nft.data.labels = tnx_labels;
            o_nft.data.datasets[0].data = tnx_data;
            o_nft.data.datasets[1].data = tnx_data3;

            o_nft.update();

        });
    }
    if (t("#tknSale-metaverse").length > 0) {

        var theme_color = "#2c80ff";
        //        var tnx_labels = ["14 Feb", "15 Feb", "16 Feb", "17 Feb", "18 Feb"];
        //        var tnx_data = [1, 2, 0, 100, 0];


        var e = document.getElementById("tknSale-metaverse").getContext("2d"),
            o_metaverse = new Chart(e, {
                type: "line",
                data: {
                    labels: tnx_labels_high_risk,
                    datasets: [{
                        label: "Metaverse Bull",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: "#bc69fb",
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }, {
                        label: "Metaverse NFTs",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: theme_color.base,
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }]
                },
                options: {
                    legend: {
                        display: 1
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        enabled: true,
                        mode: 'label',
                        backgroundColor: "#f2f4f7",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 14,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: 1
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !1,
                                fontSize: 12,
                                fontColor: theme_color.text
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 0,
                                zeroLineColor: "#e9edf3"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontSize: 12,
                                fontColor: theme_color.text,
                                source: "auto"
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 20,
                                zeroLineColor: "#e9edf3"
                            }
                        }]
                    }
                }
            });
        t(".popup_metaverse").on("click", function (e) {
            // e.preventDefault();
            var a = t(this),
                r = t(this);

            t.get(r).done(t => {



                // tnx_data3
                var tnx_data2_metaverse = $("#metaverse_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_metaverse.length; i++) {
                    tnx_data3.push(tnx_data2_metaverse[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }



                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                // tnx_data
                var tnx_data2_metaverse_bull_1 = [1.21, 2.32, 4.26, 6.36, 8.24, 10.11, 12.70, 14.43, 16.72, 18.27, 20.44, 22.77];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_metaverse_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                // $("#high_risk_bull_gains_text span").html((tnx_data[11] - tnx_data3[11]).toFixed(6));
                $("#metaverse_bull_gains_text span").html("Enter an amount");
                // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")

                o_metaverse.data.datasets[0].data = tnx_data;
                o_metaverse.data.datasets[1].data = tnx_data3;

                o_metaverse.update();

            })
        })
        t("#token-number-metaverse-bull").on("input", function () {
            current_amount = $(this).val();

            createCookie("plan", "Metaverse Bull", 10);

            if ($(this).val() < 500) {
                current_amount = 500;
            }

            if ($(this).val() > 1000000) {
                current_amount = 1000000;
            }

            if ($("#slider-metaverse").val() == 1) {
                $("#metaverse_bull_gains_text span").html((parseFloat($("#token-number-metaverse-bull").val()) + (parseFloat($("#token-number-metaverse-bull").val()) * 22.77 / 100)).toFixed(6));
            }
            if ($("#slider-metaverse").val() == 2) {
                $("#metaverse_bull_gains_text span").html((parseFloat($("#token-number-metaverse-bull").val()) + (parseFloat($("#token-number-metaverse-bull").val()) * 49.22 / 100)).toFixed(6));
            }
            if ($("#slider-metaverse").val() == 3) {
                $("#metaverse_bull_gains_text span").html((parseFloat($("#token-number-metaverse-bull").val()) + (parseFloat($("#token-number-metaverse-bull").val()) * 122.11 / 100)).toFixed(6));
            }

            if ($(this).val() == '') {
                current_amount = 0;
                $("#metaverse_bull_gains_text span").html("Enter an amount");
            }

        });
        t(document).on('input', '#slider-metaverse', function () {

            tnx_labels = [];

            function chartdate(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 11;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            function chartdate2(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 6;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            if ($(this).val() == 1) {

                chartdate(90);

                var tnx_data2_metaverse = $("#metaverse_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_metaverse.length; i++) {
                    tnx_data3.push(tnx_data2_metaverse[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }



                var tnx_data2_metaverse_bull = $("#metaverse_bull_title").attr("bull").split(',');
                var tnx_data2_metaverse_bull_1 = [1.21, 2.32, 4.26, 6.36, 8.24, 10.11, 12.70, 14.43, 16.72, 18.27, 20.44, 22.77];


                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_metaverse_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#metaverse_bull_gains_text span").html((parseFloat($("#token-number-metaverse-bull").val()) + (parseFloat($("#token-number-metaverse-bull").val()) * 22.77 / 100)).toFixed(6));
                $(".metaverse_bull_roi").html("22%");

            }
            if ($(this).val() == 2) {

                chartdate2(180);

                var tnx_data2_metaverse = $("#metaverse_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_metaverse.length; i++) {
                    tnx_data3.push(tnx_data2_metaverse[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 6;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }




                var tnx_data2_metaverse_bull = $("#metaverse_bull_title").attr("bull2").split(',');
                //[1.21, 2.32, 4.26, 6.36, 8.24, 10.11, 12.70, 14.43, 16.72, 18.27, 20.44, 22.77];
                var tnx_data2_metaverse_bull_1 = [4.63, 16.37, 22.17, 30.19, 39.17, 49.35];


                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 6; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_metaverse_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 6;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }
                $("#metaverse_bull_gains_text span").html((parseFloat($("#token-number-metaverse-bull").val()) + (parseFloat($("#token-number-metaverse-bull").val()) * 49.22 / 100)).toFixed(6));
                $(".metaverse_bull_roi").html("49%");
            }
            if ($(this).val() == 3) {
                chartdate(366);

                var tnx_data2_metaverse = $("#metaverse_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_metaverse.length; i++) {
                    tnx_data3.push(tnx_data2_metaverse[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                var tnx_data2_metaverse_bull = $("#metaverse_bull_title").attr("bull2").split(',');
                //[4.63, 16.37, 22.17, 30.19, 39.17, 49.35];
                var tnx_data2_metaverse_bull_1 = [5.01, 12.31, 24.11, 29.42, 33.47, 44.74, 51.74, 64.62, 73.17, 88.53, 107.73, 122.11];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_metaverse_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }
                $("#metaverse_bull_gains_text span").html((parseFloat($("#token-number-metaverse-bull").val()) + (parseFloat($("#token-number-metaverse-bull").val()) * 122.11 / 100)).toFixed(6));
                $(".metaverse_bull_roi").html("122%");
            }

            if ($("#token-number-metaverse-bull").val() == '') {
                current_amount = 0;
                $("#metaverse_bull_gains_text span").html("Enter an amount");
            }

            // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")
            o_metaverse.data.labels = tnx_labels;
            o_metaverse.data.datasets[0].data = tnx_data;
            o_metaverse.data.datasets[1].data = tnx_data3;

            o_metaverse.update();

        });
    }
    
    if (t("#tknSale-commodities").length > 0) {

        var theme_color = "#2c80ff";
        //        var tnx_labels = ["14 Feb", "15 Feb", "16 Feb", "17 Feb", "18 Feb"];
        //        var tnx_data = [1, 2, 0, 100, 0];


        var e = document.getElementById("tknSale-commodities").getContext("2d"),
            o_commodities = new Chart(e, {
                type: "line",
                data: {
                    labels: tnx_labels_high_risk,
                    datasets: [{
                        label: "Commodities Bull",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: "#495463",
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }, {
                        label: "Average Commodities",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: theme_color.base,
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }]
                },
                options: {
                    legend: {
                        display: 1
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        enabled: true,
                        mode: 'label',
                        backgroundColor: "#f2f4f7",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 14,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: 1
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !1,
                                fontSize: 12,
                                fontColor: theme_color.text
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 0,
                                zeroLineColor: "#e9edf3"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontSize: 12,
                                fontColor: theme_color.text,
                                source: "auto"
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 20,
                                zeroLineColor: "#e9edf3"
                            }
                        }]
                    }
                }
            });
        t(".popup_commodities").on("click", function (e) {
            // e.preventDefault();
            var a = t(this),
                r = t(this);

            t.get(r).done(t => {



                // tnx_data3
                var tnx_data2_commodities = $("#commodities_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_commodities.length; i++) {
                    tnx_data3.push(tnx_data2_commodities[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }



                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                // tnx_data
                var tnx_data2_commodities_bull_1 = [1.47, 4.34, 6.47, 8.48, 11.82, 13.48, 16.02, 19.5, 22.21, 25.15, 28.31, 31.31];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_commodities_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                // $("#high_risk_bull_gains_text span").html((tnx_data[11] - tnx_data3[11]).toFixed(6));
                $("#commodities_bull_gains_text span").html("Enter an amount");
                // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")

                o_commodities.data.datasets[0].data = tnx_data;
                o_commodities.data.datasets[1].data = tnx_data3;

                o_commodities.update();

            })
        })
        t("#token-number-commodities-bull").on("input", function () {
            current_amount = $(this).val();

            createCookie("plan", "Commodities Bull", 10);

            if ($(this).val() < 500) {
                current_amount = 500;
            }

            if ($(this).val() > 1000000) {
                current_amount = 1000000;
            }

            if ($("#slider-commodities").val() == 1) {
                $("#commodities_bull_gains_text span").html((parseFloat($("#token-number-commodities-bull").val()) + (parseFloat($("#token-number-commodities-bull").val()) * 31.31 / 100)).toFixed(6));
            }
            if ($("#slider-commodities").val() == 2) {
                $("#commodities_bull_gains_text span").html((parseFloat($("#token-number-commodities-bull").val()) + (parseFloat($("#token-number-commodities-bull").val()) * 72.21 / 100)).toFixed(6));
            }
            if ($("#slider-commodities").val() == 3) {
                $("#commodities_bull_gains_text span").html((parseFloat($("#token-number-commodities-bull").val()) + (parseFloat($("#token-number-commodities-bull").val()) * 185.87 / 100)).toFixed(6));
            }

            if ($(this).val() == '') {
                current_amount = 0;
                $("#commodities_bull_gains_text span").html("Enter an amount");
            }

        });
        t(document).on('input', '#slider-commodities', function () {

            tnx_labels = [];

            function chartdate(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 11;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            function chartdate2(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 6;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            if ($(this).val() == 1) {

                chartdate(90);

                var tnx_data2_commodities = $("#commodities_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_commodities.length; i++) {
                    tnx_data3.push(tnx_data2_commodities[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }



                var tnx_data2_commodities_bull = $("#commodities_bull_title").attr("bull").split(',');
                var tnx_data2_commodities_bull_1 = [1.47, 4.34, 6.47, 8.48, 11.82, 13.48, 16.02, 19.5, 22.21, 25.15, 28.31, 31.31];


                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_commodities_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#commodities_bull_gains_text span").html((parseFloat($("#token-number-commodities-bull").val()) + (parseFloat($("#token-number-commodities-bull").val()) * 31.31 / 100)).toFixed(6));
                $(".commodities_bull_roi").html("31%");

            }
            if ($(this).val() == 2) {

                chartdate2(180);

                var tnx_data2_commodities = $("#commodities_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_commodities.length; i++) {
                    tnx_data3.push(tnx_data2_commodities[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 6;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }




                var tnx_data2_commodities_bull = $("#commodities_bull_title").attr("bull2").split(',');
                // [1.47, 4.34, 6.47, 8.48, 11.82, 13.48, 16.02, 19.5, 22.21, 25.15, 28.31, 31.31];
                var tnx_data2_commodities_bull_1 = [5.31, 21.35, 31.15, 48.61, 61.14, 72.21];


                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 6; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_commodities_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 6;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }
                $("#commodities_bull_gains_text span").html((parseFloat($("#token-number-commodities-bull").val()) + (parseFloat($("#token-number-commodities-bull").val()) * 72.21 / 100)).toFixed(6));
                $(".commodities_bull_roi").html("72%");
            }
            if ($(this).val() == 3) {
                chartdate(366);

                var tnx_data2_commodities = $("#commodities_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_commodities.length; i++) {
                    tnx_data3.push(tnx_data2_commodities[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                var tnx_data2_commodities_bull = $("#commodities_bull_title").attr("bull2").split(',');
                // [5.31, 21.35, 31.15, 48.61, 61.14, 72.21];
                var tnx_data2_commodities_bull_1 = [6.47, 16.63, 24.47, 33.36, 57.82, 72.53, 88.43, 102.13, 125.19, 146.08, 164.30, 185.87];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_commodities_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }
                $("#commodities_bull_gains_text span").html((parseFloat($("#token-number-commodities-bull").val()) + (parseFloat($("#token-number-commodities-bull").val()) * 185.87 / 100)).toFixed(6));
                $(".commodities_bull_roi").html("185%");
            }

            if ($("#token-number-commodities-bull").val() == '') {
                current_amount = 0;
                $("#commodities_bull_gains_text span").html("Enter an amount");
            }

            // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")
            o_commodities.data.labels = tnx_labels;
            o_commodities.data.datasets[0].data = tnx_data;
            o_commodities.data.datasets[1].data = tnx_data3;

            o_commodities.update();

        });
    }
    if (t("#tknSale-forex").length > 0) {

        var theme_color = "#2c80ff";
        //        var tnx_labels = ["14 Feb", "15 Feb", "16 Feb", "17 Feb", "18 Feb"];
        //        var tnx_data = [1, 2, 0, 100, 0];


        var e = document.getElementById("tknSale-forex").getContext("2d"),
            o_forex = new Chart(e, {
                type: "line",
                data: {
                    labels: tnx_labels_high_risk,
                    datasets: [{
                        label: "Forex Bull",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: "#ff6868",
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }, {
                        label: "EUR/USD",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: theme_color.base,
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: []
                    }]
                },
                options: {
                    legend: {
                        display: 1
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        enabled: true,
                        mode: 'label',
                        backgroundColor: "#f2f4f7",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 14,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: 1
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !1,
                                fontSize: 12,
                                fontColor: theme_color.text
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 0,
                                zeroLineColor: "#e9edf3"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontSize: 12,
                                fontColor: theme_color.text,
                                source: "auto"
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 20,
                                zeroLineColor: "#e9edf3"
                            }
                        }]
                    }
                }
            });
        t(".popup_forex").on("click", function (e) {
            // e.preventDefault();
            var a = t(this),
                r = t(this);

            t.get(r).done(t => {



                // tnx_data3
                var tnx_data2_forex = $("#forex_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_forex.length; i++) {
                    tnx_data3.push(tnx_data2_forex[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }



                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                // tnx_data
                var tnx_data2_forex_bull_1 = [1.66, 4.52, 7.42, 11.47, 15.90, 18.41, 21.64, 25.69, 28.30, 30.49, 32.30, 34.51];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_forex_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                // $("#high_risk_bull_gains_text span").html((tnx_data[11] - tnx_data3[11]).toFixed(6));
                $("#forex_bull_gains_text span").html("Enter an amount");
                // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")

                o_forex.data.datasets[0].data = tnx_data;
                o_forex.data.datasets[1].data = tnx_data3;

                o_forex.update();

            })
        })
        t("#token-number-forex-bull").on("input", function () {
            current_amount = $(this).val();

            createCookie("plan", "Forex Bull", 10);

            if ($(this).val() < 500) {
                current_amount = 500;
            }

            if ($(this).val() > 1000000) {
                current_amount = 1000000;
            }

            if ($("#slider-forex").val() == 1) {
                $("#forex_bull_gains_text span").html((parseFloat($("#token-number-forex-bull").val()) + (parseFloat($("#token-number-forex-bull").val()) * 34.51 / 100)).toFixed(6));
            }
            if ($("#slider-forex").val() == 2) {
                $("#forex_bull_gains_text span").html((parseFloat($("#token-number-forex-bull").val()) + (parseFloat($("#token-number-forex-bull").val()) * 79.96 / 100)).toFixed(6));
            }
            if ($("#slider-forex").val() == 3) {
                $("#forex_bull_gains_text span").html((parseFloat($("#token-number-forex-bull").val()) + (parseFloat($("#token-number-forex-bull").val()) * 201.31 / 100)).toFixed(6));
            }

            if ($(this).val() == '') {
                current_amount = 0;
                $("#forex_bull_gains_text span").html("Enter an amount");
            }

        });
        t(document).on('input', '#slider-forex', function () {

            tnx_labels = [];

            function chartdate(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 11;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            function chartdate2(timespan) {


                let timespan_days = timespan;

                let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                        ];

                for (i = 0; i < timespan_days; i++) {
                    let today_m = new Date(new Date().setDate(new Date().getDate() + (i - timespan_days + 1)));
                    tnx_labels.push(monthNames[today_m.getMonth()] + " " + today_m.getDate());
                }

                let tnx_labels_old = tnx_labels;
                tnx_labels = [];
                let maxVal = 6;
                let delta = Math.floor(tnx_labels_old.length / maxVal);

                for (i = 0; i < tnx_labels_old.length; i = i + delta) {
                    tnx_labels.push(tnx_labels_old[i]);
                }
                $("#tknSale").attr('test', tnx_labels);

            }

            if ($(this).val() == 1) {

                chartdate(90);

                var tnx_data2_forex = $("#forex_bull_title").attr("data").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_forex.length; i++) {
                    tnx_data3.push(tnx_data2_forex[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }



                var tnx_data2_forex_bull = $("#forex_bull_title").attr("bull").split(',');
                var tnx_data2_forex_bull_1 = [1.66, 4.52, 7.42, 11.47, 15.90, 18.41, 21.64, 25.69, 28.30, 30.49, 32.30, 34.51];


                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_forex_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }

                $("#forex_bull_gains_text span").html((parseFloat($("#token-number-forex-bull").val()) + (parseFloat($("#token-number-forex-bull").val()) * 34.51 / 100)).toFixed(6));
                $(".forex_bull_roi").html("34%");

            }
            if ($(this).val() == 2) {

                chartdate2(180);

                var tnx_data2_forex = $("#forex_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_forex.length; i++) {
                    tnx_data3.push(tnx_data2_forex[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 6;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }




                var tnx_data2_forex_bull = $("#forex_bull_title").attr("bull2").split(',');
                //[1.66, 4.52, 7.42, 11.47, 15.90, 18.41, 21.64, 25.69, 28.30, 30.49, 32.30, 34.51];
                var tnx_data2_forex_bull_1 = [7.36, 19.36, 34.26, 46.37, 63.23, 79.96];


                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 6; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_forex_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 6;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }
                $("#forex_bull_gains_text span").html((parseFloat($("#token-number-forex-bull").val()) + (parseFloat($("#token-number-forex-bull").val()) * 79.96 / 100)).toFixed(6));
                $(".forex_bull_roi").html("79%");
            }
            if ($(this).val() == 3) {
                chartdate(366);

                var tnx_data2_forex = $("#forex_bull_title").attr("data2").split(',');

                var tnx_data3 = [];

                for (i = 0; i < tnx_data2_forex.length; i++) {
                    tnx_data3.push(tnx_data2_forex[i]);

                    if (tnx_data3[i] == 0) {
                        tnx_data3[i] = null;
                    }
                    if (tnx_data3[i] != null) {
                        tnx_data3[i] = parseFloat(tnx_data3[i]).toFixed(6);
                    }
                }

                var tnx_data3_old = tnx_data3;
                tnx_data3 = [];

                let maxVal_data = 11;
                let delta_data = Math.floor(tnx_data3_old.length / maxVal_data);

                for (i = 0; i < tnx_data3_old.length; i = i + delta_data) {
                    tnx_data3.push(tnx_data3_old[i]);
                }

                var tnx_data2_forex_bull = $("#forex_bull_title").attr("bull2").split(',');
                // [7.36, 19.36, 34.26, 46.37, 63.23, 79.96];
                var tnx_data2_forex_bull_1 = [5.72, 23.18, 41.51, 52.26, 64.41, 79.42, 89.43, 102.41, 123.62, 153.32, 178.44, 201.47];

                var tnx_data = [];

                tnx_data[0] = tnx_data3[0];

                for (i = 1; i < 12; i++) {

                    tnx_data.push((parseFloat(tnx_data3[i]) + parseFloat(tnx_data3[i]) * parseFloat(tnx_data2_forex_bull_1[i]) / 100));

                    if (tnx_data[i] == 0) {
                        tnx_data[i] = null;
                    }
                    if (tnx_data[i] != null) {
                        tnx_data[i] = tnx_data[i].toFixed(6);
                    }
                }

                var tnx_data_old = tnx_data;
                tnx_data = [];

                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(tnx_data_old.length / maxVal_data_bull);

                for (i = 0; i < tnx_data_old.length; i = i + delta_data_bull) {
                    tnx_data.push(tnx_data_old[i]);
                }
                $("#forex_bull_gains_text span").html((parseFloat($("#token-number-forex-bull").val()) + (parseFloat($("#token-number-forex-bull").val()) * 201.31 / 100)).toFixed(6));
                $(".forex_bull_roi").html("201%");
            }

            if ($("#token-number-forex-bull").val() == '') {
                current_amount = 0;
                $("#forex_bull_gains_text span").html("Enter an amount");
            }

            // o.data.labels = tnx_labels, o.data.datasets[0].data = tnx_data, o.data.datasets[1].data = tnx_data3, o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")
            o_forex.data.labels = tnx_labels;
            o_forex.data.datasets[0].data = tnx_data;
            o_forex.data.datasets[1].data = tnx_data3;

            o_forex.update();

        });
    }

    
    if (t("#portfolio").length > 0) {

        var theme_color = "#2c80ff";
        //        var tnx_labels = ["14 Feb", "15 Feb", "16 Feb", "17 Feb", "18 Feb"];
        //        var tnx_data = [1, 2, 0, 100, 0];


        var e = document.getElementById("portfolio").getContext("2d"),
            o = new Chart(e, {
                type: "pie",
                data: {
                    labels: portfolio_labels,
                    datasets: [{
                        label: "",
                        tension: .4,
                        backgroundColor: portfolio_colors,
                        borderColor: theme_color.base,
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#ffffff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#ffffff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: portfolio_data
                    }]
                },
                options: {
                    legend: {
                        display: 1,
                        position: "right",
                        align: "middle",
                        labels: {
                            fontColor: '#ffffff'
                        }
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        callbacks: {
                            title: function (t, e) {
                                return e.labels[t[0].index]
                            },
                            label: function (t, e) {
                                return e.datasets[0].data[t.index]
                            }
                        },
                        backgroundColor: "#f5f5f5",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 14,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: !1
                    }
                }
            });
    }

    window.pieColors = {
        pieColor1: "#1c65c9",
        pieColor2: "#00d285",
        pieColor2: "#02a2fe"
    };
    if (t("#mobileDesktop").length > 0) {
        theme_color = "red";

        var n = document.getElementById("mobileDesktop").getContext("2d");
        new Chart(n, {
            type: "doughnut",
            data: {
                labels: ["Mobile", "Desktop"],
                datasets: [{
                    lineTension: 0,
                    backgroundColor: [window.pieColors.pieColor1, window.pieColors.pieColor2],
                    borderColor: "#fff",
                    borderWidth: 2,
                    hoverBorderColor: "#fff",
                    data: phase_data
                }]
            },
            options: {
                legend: {
                    display: !1,
                    labels: {
                        boxWidth: 10,
                        fontColor: "#000"
                    }
                },
                rotation: -1.6,
                cutoutPercentage: 80,
                maintainAspectRatio: !1,
                tooltips: {
                    callbacks: {
                        title: function (t, e) {
                            return e.labels[t[0].index]
                        },
                        label: function (t, e) {
                            return e.datasets[0].data[t.index] + " "
                        }
                    },
                    backgroundColor: "#f2f4f7",
                    titleFontSize: 13,
                    titleFontColor: theme_color.heading,
                    titleMarginBottom: 10,
                    bodyFontColor: theme_color.text,
                    bodyFontSize: 14,
                    bodySpacing: 4,
                    yPadding: 15,
                    xPadding: 15,
                    footerMarginTop: 5,
                    displayColors: !1
                }
            }
        })
    }
    if (t("#phaseStatus").length > 0) {
        var theme_color = "#2c80ff";
        var phase_data1 = 1234;
        var precision = 1000000; // 2 decimals
        var randomnum = Math.floor(Math.random() * (equity * 10 / 100 * precision - 1 * precision) + 1 * precision) / (1 * precision);
        var end_num = randomnum - 200;
        phase_data1 = end_num;

        var equity = t("#active_robot_title").attr("equity");
        var robot_activated_at = t("#active_robot_title").attr("date_activated");
        var robot_activated = t("#active_robot_title").attr("robot");

        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;


        if (robot_activated == "1" && robot_activated_at != today) {

            var max_stocks = 100 * 70 / 100;
            var min_stocks = 100 * 60 / 100;
            var stocks = parseFloat(Math.random() * (max_stocks - min_stocks) + min_stocks).toFixed(2);

            var max_gb = 100 * 12 / 100;
            var min_gb = 100 * 5 / 100;
            var gb = parseFloat(Math.random() * (max_gb - min_gb) + min_gb).toFixed(2);

            var crypto = parseFloat(100 - stocks - gb).toFixed(2);

            //            var min_crypto = equity*5/100;
            //            var min_crypto = equity*5/100;

            var phase_data = [stocks, gb, crypto];

            var n = document.getElementById("phaseStatus").getContext("2d");
            new Chart(n, {
                type: "doughnut",
                data: {
                    labels: ["Stocks", "Green Bonds", "Crypto"],
                    datasets: [{
                        lineTension: 10,
                        backgroundColor: [window.pieColors.pieColor1, window.pieColors.pieColor2],
                        borderColor: "#fff",
                        borderWidth: 2,
                        hoverBorderColor: "#fff",
                        data: phase_data
                }]
                },
                options: {
                    legend: {
                        display: !1,
                        labels: {
                            boxWidth: 8,
                            fontColor: "#000"
                        }
                    },
                    rotation: -1.6,
                    cutoutPercentage: 80,
                    maintainAspectRatio: 1,
                    tooltips: {
                        callbacks: {
                            title: function (t, e) {
                                return e.labels[t[0].index]
                            },
                            label: function (t, e) {
                                return e.datasets[0].data[t.index] + " %"
                                //                                return phase_data1
                            }
                        },
                        backgroundColor: "#f2f4f7",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 16,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: !1
                    }
                }
            })

        } else {

            var max_stocks = equity * 90 / 100;
            var min_stocks = equity * 70 / 100;
            //            var stocks = (Math.random() * (max_stocks - min_stocks) + min_stocks).toFixed(1);
            var stocks = 100;

            var max_gb = equity * 12 / 100;
            var min_gb = equity * 5 / 100;
            var gb = (Math.random() * (max_gb - min_gb) + min_gb).toFixed(1);

            var crypto = 100 - stocks - gb;
            //            var min_crypto = equity*5/100;
            //            var min_crypto = equity*5/100;

            var phase_data = [stocks];

            var n = document.getElementById("phaseStatus").getContext("2d");
            new Chart(n, {
                type: "doughnut",
                data: {
                    labels: ["Stoks", "Green Bonds", "Crypto"],
                    datasets: [{
                        lineTension: 10,
                        backgroundColor: [window.pieColors.pieColor1, window.pieColors.pieColor2],
                        borderColor: "#fff",
                        borderWidth: 2,
                        hoverBorderColor: "#fff",
                        data: phase_data
                }]
                },
                options: {
                    legend: {
                        display: !1,
                        labels: {
                            boxWidth: 8,
                            fontColor: "#000"
                        }
                    },
                    rotation: -1.6,
                    cutoutPercentage: 80,
                    maintainAspectRatio: 1,
                    tooltips: {
                        callbacks: {
                            title: function (t, e) {
                                return e.labels[t[0].index]
                            },
                            label: function (t, e) {
                                return e.datasets[0].data[t.index] + " %"
                                //                                return phase_data1
                            }
                        },
                        backgroundColor: "#f2f4f7",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 16,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: !1
                    }
                }
            })

        }






    }
    if (t("#predictions").length > 0) {

        var equity = t("#active_robot_title").attr("equity");
        var robot_activated_at = t("#active_robot_title").attr("date_activated");
        var robot_activated = t("#active_robot_title").attr("robot");

        var robot_activated_at_new = new Date(robot_activated_at);
        robot_activated_at_new.setDate(robot_activated_at_new.getDate());

        var robot_activated_at_d = robot_activated_at_new.getDate();
        var robot_activated_at_m = robot_activated_at_new.getMonth();
        var robot_activated_at_y = robot_activated_at_new.getFullYear().toString().substr(-2);

        var robot_activated_at_dateformat = dd + '/' + mm + '/' + yyyy;
        var i;
        var test
        var robot_activated_at_1_dateformat;
        var robot_activated_at_2_dateformat;
        var robot_activated_at_3_dateformat;
        var robot_activated_at_4_dateformat;

        var list = [robot_activated_at_1_dateformat, robot_activated_at_2_dateformat, robot_activated_at_3_dateformat, robot_activated_at_4_dateformat]

        for (i = 0; i < 3; i++) {
            let robot_activated_at_new1 = new Date(robot_activated_at_dateformat);
            robot_activated_at_new1.setDate(robot_activated_at_new1.getDate());

            let robot_activated_at_d1 = robot_activated_at_new1.getDate();
            let robot_activated_at_m1 = robot_activated_at_new1.getMonth();
            let robot_activated_at_y1 = robot_activated_at_new1.getFullYear().toString().substr(-2);

            list[i] = robot_activated_at_d1 + '/' + robot_activated_at_m1 + '/' + robot_activated_at_y1;

            test = robot_activated_at_d1 + '/' + robot_activated_at_m1 + '/' + robot_activated_at_y1;

        }

        //        var tnx_labels = [test, robot_activated_at_2_dateformat, robot_activated_at_3_dateformat, robot_activated_at_4_dateformat];
        //        var tnx_data = [equity, equity*120/100, equity*130/100, equity*140/100];



        var e = document.getElementById("predictions").getContext("2d"),
            o = new Chart(e, {
                type: "line",
                data: {
                    labels: tnx_labels,
                    datasets: [{
                        label: "3 Month Prediction",
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: theme_color.base,
                        pointBorderColor: theme_color.base,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: theme_color.base,
                        pointHoverBorderWidth: 2,
                        pointRadius: 5,
                        pointHitRadius: 6,
                        data: tnx_data
                    }]
                },
                options: {
                    legend: {
                        display: !1
                    },
                    maintainAspectRatio: 1,
                    tooltips: {
                        callbacks: {
                            title: function (t, e) {
                                return "Date : " + e.labels[t[0].index]
                            },
                            label: function (t, e) {
                                return e.datasets[0].data[t.index] + " Equity"
                            }
                        },
                        backgroundColor: "#f2f4f7",
                        titleFontSize: 13,
                        titleFontColor: theme_color.heading,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color.text,
                        bodyFontSize: 14,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: !1
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !1,
                                fontSize: 12,
                                fontColor: theme_color.text
                            },
                            gridLines: {
                                color: "#e9edf3",
                                tickMarkLength: 0,
                                zeroLineColor: "#e9edf3"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontSize: 12,
                                fontColor: theme_color.text,
                                source: "auto"
                            },
                            gridLines: {
                                color: "transparent",
                                tickMarkLength: 20,
                                zeroLineColor: "#e9edf3"
                            }
                        }]
                    }
                }
            });
        t(".token-sale-graph li a").on("click", function (e) {
            e.preventDefault();
            var a = t(this),
                r = t(this).attr("href");
            t.get(r).done(t => {
                o.data.labels = Object.values(t.chart.days_alt), o.data.datasets[0].data = Object.values(t.chart.data_alt), o.update(), a.parents(".token-sale-graph").find("a.toggle-tigger").text(a.text()), a.closest(".toggle-class").toggleClass("active")
            })
        })
    }
    if (t("#regStatistics2").length > 0) {

        var theme_color = "red";

        var a = document.getElementById("regStatistics2").getContext("2d"),
            r = new Chart(a, {
                type: "bar",
                data: {
                    labels: user_labels,
                    datasets: [{
                        label: "",
                        lineTension: 0,
                        backgroundColor: theme_color.base,
                        borderColor: theme_color.base,
                        barThickness: .4,
                        data: user_data
                    }]
                },
                options: {
                    legend: {
                        display: !1
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        callbacks: {
                            title: function (t, e) {
                                return !1
                            },
                            label: function (t, e) {
                                return e.datasets[0].data[t.index] + " "
                            }
                        },
                        backgroundColor: "#f2f4f7",
                        bodyFontColor: theme_color.base,
                        bodyFontSize: 14,
                        bodySpacing: 5,
                        yPadding: 3,
                        xPadding: 10,
                        footerMarginTop: 10,
                        displayColors: !1
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !0,
                                fontSize: 10,
                                fontColor: theme_color.text
                            },
                            gridLines: {
                                color: "transparent",
                                tickMarkLength: 0,
                                zeroLineColor: "transparent"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontSize: 9,
                                fontColor: theme_color.text,
                                source: "auto"
                            },
                            gridLines: {
                                color: "transparent",
                                tickMarkLength: 7,
                                zeroLineColor: "transparent"
                            }
                        }]
                    }
                }
            });
        t(".reg-statistic-graph li a").on("click", function (e) {
            e.preventDefault();
            var o = t(this),
                a = t(this).attr("href");
            t.get(a).done(t => {
                r.data.labels = Object.values(t.chart.days_alt), r.data.datasets[0].data = Object.values(t.chart.data_alt), r.update(), o.parents(".reg-statistic-graph").find("a.toggle-tigger").text(o.text()), o.closest(".toggle-class").toggleClass("active")
            })
        })
    }

    window.pieColors = {
        pieColor1: "#00d285",
        pieColor2: "#ffc100"
    };
}(jQuery);
