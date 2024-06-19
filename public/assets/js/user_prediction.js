! function (t) {


    /*----------------Prediction----------------*/

    var tnx_labels = [];
    var tnx_data = [];

    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];

    var today = new Date().getTime();
    var n = new Date(today);

    var user_labels = [monthNames[n.getMonth()], monthNames[n.getMonth() + 1], monthNames[n.getMonth() + 2], monthNames[n.getMonth() + 3], monthNames[n.getMonth() + 4], monthNames[n.getMonth() + 5], monthNames[n.getMonth() + 6], monthNames[n.getMonth() + 7], monthNames[n.getMonth() + 8], monthNames[n.getMonth() + 9], monthNames[n.getMonth() + 10], monthNames[n.getMonth() + 11]];
    var user_labels3 = [monthNames[n.getMonth()], monthNames[n.getMonth() + 1], monthNames[n.getMonth() + 2], monthNames[n.getMonth() + 3], monthNames[n.getMonth() + 4], monthNames[n.getMonth() + 5]];
    var user_labels6 = [monthNames[n.getMonth()], monthNames[n.getMonth() + 1], monthNames[n.getMonth() + 2], monthNames[n.getMonth() + 3], monthNames[n.getMonth() + 4], monthNames[n.getMonth() + 5]];
    var user_labels12 = user_labels;

    var user_data = [];
    var user_data3 = [];
    var user_data6 = [];
    var user_data12 = [];

    if (number_of_transactions > 0) {

        equity_array = equity_array.split(",");
        var equity_prediction = 0;
        if (user_equity == 0 || user_equity == "0") {
            equity_prediction = 0;
        } else {
            equity_prediction = user_equity;
        }
        user_data.push(equity_prediction);
        for (var i = 0; i < 11; i++) {
            equity_prediction = ((102.45 + parseFloat(equity_array[n.getMonth() + i]) * 1.5) * equity_prediction / 100);
            user_data.push(equity_prediction);
        }

    } else {
        for (var i = 0; i < 12; i++) {
            user_data.push(0);
        }
    }

    for (var i = 0; i < 3; i++) {
        user_data3.push(user_data[i]);
    }
    for (var i = 0; i < 6; i++) {
        user_data6.push(user_data[i]);
    }
    for (var i = 0; i < 12; i++) {
        user_data12.push(user_data[i]);
    }

    console.log("user_data" + user_data);

    /*----------------End Prediction----------------*/


    if (t("#prediction").length > 0) {

        var theme_color = "red";

        var a = document.getElementById("prediction").getContext("2d"),
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

}(jQuery);
