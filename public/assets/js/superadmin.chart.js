/*! CoreApp v1.4.0 | Copyright by Softnio. */
(function (a) {
    'use strict';
    if (0 < a("#tknSale").length) {
        var b = document.getElementById("tknSale").getContext("2d"),
            c = new Chart(b, {
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
                            title: function (a, b) {
                                return "Date : " + b.labels[a[0].index]
                            },
                            label: function (a, b) {
                                return b.datasets[0].data[a.index] + " Tokens"
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
                                beginAtZero: !0,
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
        a(".token-sale-graph li a").on("click", function (b) {
            b.preventDefault();
            var d = a(this),
                e = a(this).attr("href");
            a.get(e).done(a => {
                c.data.labels = Object.values(a.chart.days_alt), c.data.datasets[0].data = Object.values(a.chart.data_alt), c.update(), d.parents(".token-sale-graph").find("a.toggle-tigger").text(d.text()), d.closest(".toggle-class").toggleClass("active")
            })
        })
    }
    if (0 < a("#regStatistics").length) {
        var d = document.getElementById("regStatistics").getContext("2d"),
            e = new Chart(d, {
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
                            title: function () {
                                return !1
                            },
                            label: function (a, b) {
                                return b.datasets[0].data[a.index] + " "
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
        a(".reg-statistic-graph li a").on("click", function (b) {
            b.preventDefault();
            var c = a(this),
                d = a(this).attr("href");
            a.get(d).done(a => {
                e.data.labels = Object.values(a.chart.days_alt), e.data.datasets[0].data = Object.values(a.chart.data_alt), e.update(), c.parents(".reg-statistic-graph").find("a.toggle-tigger").text(c.text()), c.closest(".toggle-class").toggleClass("active")
            })
        })
    }
    if (0 < a("#phaseStatus").length) var f = document.getElementById("phaseStatus").getContext("2d"),
        g = new Chart(f, {
            type: "doughnut",
            data: {
                labels: phase_labels,
                datasets: [{
                    lineTension: 0,
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
                        title: function (a, b) {
                            return b.labels[a[0].index]
                        },
                        label: function (a, b) {
                            return b.datasets[0].data[a.index] + " "
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
    if (0 < a("#userCountries").length) var f = document.getElementById("userCountries").getContext("2d"),
        g = new Chart(f, {
            type: "doughnut",
            data: {
                labels: usercountries_labels,
                datasets: [{
                    lineTension: 0,
                    borderColor: "#fff",
                    borderWidth: 2,
                    hoverBorderColor: "#fff",
                    data: usercountries_data
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
                        title: function (a, b) {
                            return b.labels[a[0].index]
                        },
                        label: function (a, b) {
                            return b.datasets[0].data[a.index] + " "
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
})(jQuery);
