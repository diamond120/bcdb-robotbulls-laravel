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
//        console.log(chart_graph);
        var date1 = new Date(2021, 9, 31);
        var date2 = getnewDate(0);
        var diffTime = Math.abs(date2 - date1);
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
//        console.log(diffDays);

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
//            console.log(a);
//            console.log(r);
            t.get(r).done(t => {

                var url = window.location.href;
                url.hash = "";

                chart.options.data[0].dataPoints = [];


                if (r == url + "?price=7") {
//                    console.log('7');

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
//                    console.log('30');
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

    
    
    var bulls = [["general", "#758698", "General Bull", "S&P 500"], ["crypto", "#ffc100", "Crypto Bull", "CMC 200"], ["crypto2", "#ff8100", "Crypto Bull", "CMC 200"], ["ecological", "#00d285", "Ecological Bull", "Federal Global Green Bonds"], ["nft", "#e6effb", "NFT Bull", "International NFTs"], ["metaverse", "#bc69fb", "Metaverse Bull", "Metaverse NFTs"], ["crypto", "#ffc100", "Crypto Bull", "CMC 200"], ["commodities", "#495463", "Commodities Bull", "Average Commodities"]];
    //var tnx_data2_general = $("#general_bull_title").attr("data").split(',');
    
    
    for (let h = 0; h < bulls.length; h++) { 
        
    if (t("#tknSale-"+bulls[h][0]).length > 0) {

        var theme_color = "#2c80ff";
        tnx_labels = [0,0,0,0,0,0,0,0,0,0,0,0];

        let e = document.getElementById("tknSale-"+bulls[h][0]).getContext("2d"),
            o_general = new Chart(e, {
                type: "line",
                data: {
                    labels: tnx_labels,
                    datasets: [{
                        label: bulls[h][2],
                        tension: .4,
                        backgroundColor: "transparent",
                        borderColor: bulls[h][1],
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
                        label: bulls[h][3],
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
        t(".popup_"+bulls[h][0]).on("click", function (e) {
            // e.preventDefault();
            var a = t(this),
                r = t(this);

            t.get(r).done(t => {

                //set new labels
                tnx_labels = $("#"+bulls[h][0]+"_bull_title").attr("dates3m").split(',');
                
                //open asset data
                var asset_data = $("#"+bulls[h][0]+"_bull_title").attr("data3m_s").split(',');
                
                //round to 2 decimals
//                var tnx_data3 = [];
                var asset_data_rounded = [];
                for (i = 0; i < 12; i++) {
                    asset_data_rounded.push(asset_data[i]);
                    if (asset_data_rounded[i] == 0) {
                        asset_data_rounded[i] = null;
                    }
                    if (asset_data_rounded[i] != null) {
                        asset_data_rounded[i] = parseFloat(asset_data_rounded[i]).toFixed(2);
                    }
                }

                //get delta
                var asset_data_rounded_old = asset_data_rounded;
                asset_data_rounded = [];
                let maxVal_data = 11;
                let delta_data = Math.floor(asset_data_rounded_old.length / maxVal_data);
                for (i = 0; i < asset_data_rounded_old.length; i = i + delta_data) {
                    asset_data_rounded.push(asset_data_rounded_old[i]);
                }
                
                
                //Bull data
                // tnx_data
//                var tnx_data2_general_bull_1 = $("#"+bulls[h][0]+"_bull_title").attr("bull").split(',');
                var bull_data = $("#"+bulls[h][0]+"_bull_title").attr("data3m_b").split(',');
                var bull_data_new = [];
                bull_data_new[0] = asset_data_rounded[0];

                //add asset value to percentage
                for (i = 1; i < 12; i++) {
                    if (bull_data_new[i-1] != null && bull_data_new[i-1] != undefined) {
                        bull_data_new.push( parseFloat(asset_data_rounded[i])+ (bull_data_new[i-1]*((100 + parseFloat(bull_data[i-1])) /100)-bull_data_new[i-1]) * ((100 + parseFloat(bull_data[i])) /100) );
                    } else {
//                        console.log("first element");
                        //the first element
                        bull_data_new.push( (parseFloat(asset_data_rounded[i]) + parseFloat(asset_data_rounded[i]) * parseFloat(bull_data[i]) / 100) );
                    }
                    
                    //prevent bug
                    if (bull_data_new[i] == 0) {
                        bull_data_new[i] = null;
                    }
                    //round array
                    if (bull_data_new[i] != null) {
                         try {
                            bull_data_new[i] = bull_data_new[i].toFixed(2);
                         } catch(err) { 
                             continue;
                         }
                    }
                }
                
                //get delta
                var bull_data_new_old = bull_data_new;
                bull_data_new = [];
                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(bull_data_new_old.length / maxVal_data_bull);
                for (i = 0; i < bull_data_new_old.length; i = i + delta_data_bull) {
                    bull_data_new.push(bull_data_new_old[i]);
                }

                $("#"+bulls[h][0]+"_bull_gains_text span").html("Enter an amount");
                
                //save data
                o_general.data.labels = tnx_labels;
                o_general.data.datasets[0].data = bull_data_new;
                o_general.data.datasets[1].data = asset_data_rounded;
                o_general.update();
            })
        })
        t("#token-number-"+bulls[h][0]+"-bull").on("input", function () {
            current_amount = $(this).val();

            createCookie("plan", bulls[h][2], 10);

            if ($(this).val() < 100) {
                current_amount = 100;
            }
            if ($(this).val() > 1000000) {
                current_amount = 1000000;
            }
            
            // tnx_data
            if ($("#slider-"+bulls[h][0]).val() == 1) {
                $("#"+bulls[h][0]+"_bull_gains_text span").html((parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) + (parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) * $("#"+bulls[h][0]+"_bull_title").attr("data3m_b").split(',')[11] / 100)).toFixed(2));
            }
            if ($("#slider-"+bulls[h][0]).val() == 2) {
                $("#"+bulls[h][0]+"_bull_gains_text span").html((parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) + (parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) * $("#"+bulls[h][0]+"_bull_title").attr("data1y_b").split(',')[5] / 100)).toFixed(2));
            }
            if ($("#slider-"+bulls[h][0]).val() == 3) {
                $("#"+bulls[h][0]+"_bull_gains_text span").html((parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) + (parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) * $("#"+bulls[h][0]+"_bull_title").attr("data1y_b").split(',')[11] / 100)).toFixed(2));
            }
            
            if ($(this).val() == '') {
                current_amount = 0;
                $("#"+bulls[h][0]+"_bull_gains_text span").html("Enter an amount");
            }

        });
        t(document).on('input', '#slider-'+bulls[h][0], function () {

            tnx_labels = [];

            if ($(this).val() == 1) {
                
                //set new labels
                //                chartdate(90);
                tnx_labels = $("#"+bulls[h][0]+"_bull_title").attr("dates3m").split(',');

                //open asset data
                var asset_data = $("#"+bulls[h][0]+"_bull_title").attr("data3m_s").split(',');

                //round to 2 decimals
                var asset_data_rounded = [];
                for (i = 0; i < 12; i++) {
                    asset_data_rounded.push(asset_data[i]);
                    if (asset_data_rounded[i] == 0) {
                        asset_data_rounded[i] = null;
                    }
                    if (asset_data_rounded[i] != null) {
                        asset_data_rounded[i] = parseFloat(asset_data_rounded[i]).toFixed(2);
                    }
                }

                //get delta
                var asset_data_rounded_old = asset_data_rounded;
                asset_data_rounded = [];
                let maxVal_data = 11;
                let delta_data = Math.floor(asset_data_rounded_old.length / maxVal_data);
                for (i = 0; i < asset_data_rounded_old.length; i = i + delta_data) {
                    asset_data_rounded.push(asset_data_rounded_old[i]);
                }


                //Bull data
                var bull_data = $("#"+bulls[h][0]+"_bull_title").attr("data3m_b").split(',');
                var bull_data_new = [];
                bull_data_new[0] = asset_data_rounded[0];

                //add asset value to percentage
                for (i = 1; i < 12; i++) {
                    bull_data_new.push((parseFloat(asset_data_rounded[i]) + parseFloat(asset_data_rounded[i]) * parseFloat(bull_data[i]) / 100));
                    //prevent bug
                    if (bull_data_new[i] == 0) {
                        bull_data_new[i] = null;
                    }
                    //round array
                    if (bull_data_new[i] != null) {
                        bull_data_new[i] = bull_data_new[i].toFixed(2);
                    }
                }

                //get delta
                var bull_data_new_old = bull_data_new;
                bull_data_new = [];
                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(bull_data_new_old.length / maxVal_data_bull);
                for (i = 0; i < bull_data_new_old.length; i = i + delta_data_bull) {
                    bull_data_new.push(bull_data_new_old[i]);
                }

                $("#"+bulls[h][0]+"_bull_gains_text span").html((parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) + (parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) * $("#"+bulls[h][0]+"_bull_title").attr("data3m_b").split(',')[11] / 100)).toFixed(2));
                $("."+bulls[h][0]+"_bull_roi").html(Math.round($("#"+bulls[h][0]+"_bull_title").attr("data3m_b").split(',')[11])+"%");
            }
            if ($(this).val() == 2) {

//                chartdate2(180);
                //set new labels
                tnx_labels = $("#"+bulls[h][0]+"_bull_title").attr("dates1y").split(',');
                tnx_labels.shift();
                tnx_labels.shift();
                tnx_labels.shift();
                tnx_labels.shift();
                tnx_labels.shift();
                tnx_labels.shift();
                
                //open asset data
                var asset_data = $("#"+bulls[h][0]+"_bull_title").attr("data1y_s").split(',');
                asset_data.shift();
                asset_data.shift();
                asset_data.shift();
                asset_data.shift();
                asset_data.shift();
                asset_data.shift();
                
                //round to 2 decimals
                var asset_data_rounded = [];
                for (i = 0; i < 6; i++) {
                    asset_data_rounded.push(asset_data[i]);
                    if (asset_data_rounded[i] == 0) {
                        asset_data_rounded[i] = null;
                    }
                    if (asset_data_rounded[i] != null) {
                        asset_data_rounded[i] = parseFloat(asset_data_rounded[i]).toFixed(2);
                    }
                }

                //get delta
                var asset_data_rounded_old = asset_data_rounded;
                asset_data_rounded = [];
                let maxVal_data = 6;
                let delta_data = Math.floor(asset_data_rounded_old.length / maxVal_data);
                for (i = 0; i < asset_data_rounded_old.length; i = i + delta_data) {
                    asset_data_rounded.push(asset_data_rounded_old[i]);
                }

                
                //Bull data
                var bull_data = $("#"+bulls[h][0]+"_bull_title").attr("data1y_b").split(',');
                var bull_data_new = [];
                bull_data_new[0] = asset_data_rounded[0];

                //add asset value to percentage
                for (i = 1; i < 6; i++) {
                    bull_data_new.push((parseFloat(asset_data_rounded[i]) + parseFloat(asset_data_rounded[i]) * parseFloat(bull_data[i]) / 100));
                    //prevent bug
                    if (bull_data_new[i] == 0) {
                        bull_data_new[i] = null;
                    }
                    //round array
                    if (bull_data_new[i] != null) {
                        bull_data_new[i] = bull_data_new[i].toFixed(2);
                    }
                }

                //get delta
                var bull_data_new_old = bull_data_new;
                bull_data_new = [];
                let maxVal_data_bull = 6;
                let delta_data_bull = Math.floor(bull_data_new_old.length / maxVal_data_bull);
                for (i = 0; i < bull_data_new_old.length; i = i + delta_data_bull) {
                    bull_data_new.push(bull_data_new_old[i]);
                }

                $("#"+bulls[h][0]+"_bull_gains_text span").html((parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) + (parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) * $("#"+bulls[h][0]+"_bull_title").attr("data1y_b").split(',')[5] / 100)).toFixed(2));
                $("."+bulls[h][0]+"_bull_roi").html(Math.round($("#"+bulls[h][0]+"_bull_title").attr("data1y_b").split(',')[5])+"%");

            }
            if ($(this).val() == 3) {
//                chartdate(366);
                //set new labels
                tnx_labels = $("#"+bulls[h][0]+"_bull_title").attr("dates1y").split(',');

                //open asset data
                var asset_data = $("#"+bulls[h][0]+"_bull_title").attr("data1y_s").split(',');

                //round to 2 decimals
                var asset_data_rounded = [];
                for (i = 0; i < 12; i++) {
                    asset_data_rounded.push(asset_data[i]);
                    if (asset_data_rounded[i] == 0) {
                        asset_data_rounded[i] = null;
                    }
                    if (asset_data_rounded[i] != null) {
                        asset_data_rounded[i] = parseFloat(asset_data_rounded[i]).toFixed(2);
                    }
                }

                //get delta
                var asset_data_rounded_old = asset_data_rounded;
                asset_data_rounded = [];
                let maxVal_data = 11;
                let delta_data = Math.floor(asset_data_rounded_old.length / maxVal_data);
                for (i = 0; i < asset_data_rounded_old.length; i = i + delta_data) {
                    asset_data_rounded.push(asset_data_rounded_old[i]);
                }

                
                //Bull data
                var bull_data = $("#"+bulls[h][0]+"_bull_title").attr("data1y_b").split(',');
                var bull_data_new = [];
                bull_data_new[0] = asset_data_rounded[0];

                //add asset value to percentage
                for (i = 1; i < 12; i++) {
                    bull_data_new.push((parseFloat(asset_data_rounded[i]) + parseFloat(asset_data_rounded[i]) * parseFloat(bull_data[i]) / 100));
                    //prevent bug
                    if (bull_data_new[i] == 0) {
                        bull_data_new[i] = null;
                    }
                    //round array
                    if (bull_data_new[i] != null) {
                        bull_data_new[i] = bull_data_new[i].toFixed(2);
                    }
                }

                //get delta
                var bull_data_new_old = bull_data_new;
                bull_data_new = [];
                let maxVal_data_bull = 11;
                let delta_data_bull = Math.floor(bull_data_new_old.length / maxVal_data_bull);
                for (i = 0; i < bull_data_new_old.length; i = i + delta_data_bull) {
                    bull_data_new.push(bull_data_new_old[i]);
                }

                $("#"+bulls[h][0]+"_bull_gains_text span").html((parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) + (parseFloat($("#token-number-"+bulls[h][0]+"-bull").val()) * $("#"+bulls[h][0]+"_bull_title").attr("data1y_b").split(',')[11] / 100)).toFixed(2));
                $("."+bulls[h][0]+"_bull_roi").html(Math.round($("#"+bulls[h][0]+"_bull_title").attr("data1y_b").split(',')[11])+"%");

            }

            if ($("#token-number-"+bulls[h][0]+"-bull").val() == '') {
                current_amount = 0;
                $("#"+bulls[h][0]+"_bull_gains_text span").html("Enter an amount");
            }

            o_general.data.labels = tnx_labels;
            o_general.data.datasets[0].data = bull_data_new;
            o_general.data.datasets[1].data = asset_data_rounded;

            o_general.update();

        });
    }
   
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
