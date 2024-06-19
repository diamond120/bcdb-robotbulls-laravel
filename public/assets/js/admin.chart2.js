! function (t) {
    "use strict";
    if (t("#regStatistics2").length > 0) {
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
