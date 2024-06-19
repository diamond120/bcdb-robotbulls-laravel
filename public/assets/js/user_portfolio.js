! function (t) {
    if ($("#portfolio").length > 0) {

        var theme_color = "#2c80ff";
        /*----------------Portfolio----------------*/

        var portfolio_labels = name_of_asset_classes.split(",");
        for (var i = 0; i < portfolio_labels.length; i++) {

            if (portfolio_labels[i].length > 16) {
                portfolio_labels[i] = portfolio_labels[i].substring(0, 13);
                portfolio_labels[i] = portfolio_labels[i] + "...";
            }
        }
        var portfolio_data = equity_of_asset_classes.split(",");
        //add percentages
        for (var i = 0; i < portfolio_data.length; i++) {

            if (portfolio_labels[i].includes("Bull")) {
                portfolio_data[i] = parseFloat(portfolio_data[i]);
                portfolio_data[i] = portfolio_data[i].toFixed(2);

                //            document.getElementsByClassName("amount-equity")[i].innerHTML = portfolio_data[i];
            }
        }



        var portfolio_colors = [];

        for (var i = 0; i < portfolio_labels.length; i++) {
            if (portfolio_labels[i].includes("General Bull")) {
                portfolio_colors.push("#758698")
            } else if (portfolio_labels[i].includes("Stocks Bull")) {
                portfolio_colors.push("#1babfe")
            } else if (portfolio_labels[i].includes("Crypto Bull")) {
                portfolio_colors.push("#ffc100")
            } else if (portfolio_labels[i].includes("Ecological Bull")) {
                portfolio_colors.push("#00d285")
            } else if (portfolio_labels[i].includes("NFT Bull")) {
                portfolio_colors.push("#e6effb")
            } else if (portfolio_labels[i].includes("Metaverse Bull")) {
                portfolio_colors.push("#bc69fb")
            } else if (portfolio_labels[i].includes("Commodities Bull")) {
                portfolio_colors.push("#495463")
            } else if (portfolio_labels[i].includes("AI Bull")) {
                portfolio_colors.push("#ff6868")
            } else if (portfolio_labels[i].includes("Bonus")) {
                portfolio_colors.push("#FFFF00")
            } else if (portfolio_labels[i].includes("RBC")) {
                portfolio_colors.push("#253992")
            } else if (portfolio_labels[i].includes("BTC")) {
                portfolio_colors.push("#f2a900")
            } else if (portfolio_labels[i].includes("ETH")) {
                portfolio_colors.push("#215CAF")
            } else if (portfolio_labels[i].includes("ADA")) {
                portfolio_colors.push("#2a71d0")
            } else if (portfolio_labels[i].includes("DOGE")) {
                portfolio_colors.push("#C3A634")
            } else if (portfolio_labels[i].includes("Withdraw")) {
                portfolio_colors.push("#ffc100")
            } else {
                portfolio_colors.push("#ffffff")
            }
        }


        /*----------------End Portfolio----------------*/

        var e = document.getElementById("portfolio").getContext("2d"),
            o = new Chart(e, {
                type: "pie",
                data: {
                    labels: portfolio_labels,
                    datasets: [{
                        label: "",
                        tension: .4,
                        backgroundColor: portfolio_colors,
                        borderColor: theme_color,
                        pointBorderColor: theme_color,
                        pointBackgroundColor: "#ffffff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "#ffffff",
                        pointHoverBorderColor: theme_color,
                        pointHoverBorderWidth: 2,
                        pointRadius: 6,
                        pointHitRadius: 6,
                        data: portfolio_data
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                        labels: {
                            generateLabels: function (chart) {
                                return chart.data.labels.map(function (label, index) {
                                    return {
                                        text: label,
                                        fillStyle: chart.data.datasets[0].backgroundColor[index],
                                    }
                                });
                            }
                        }
                    },
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
                        titleFontColor: theme_color,
                        titleMarginBottom: 10,
                        bodyFontColor: theme_color,
                        bodyFontSize: 14,
                        bodySpacing: 4,
                        yPadding: 15,
                        xPadding: 15,
                        footerMarginTop: 5,
                        displayColors: false
                    }
                }
            });

        // Generate custom HTML legend
        document.getElementById('chart-legend').innerHTML = o.generateLegend();

        // Add click event to legend items
        var legendItems = document.getElementById('chart-legend').getElementsByTagName('li');
        for (var i = 0; i < legendItems.length; i += 1) {
            legendItems[i].addEventListener("click", legendClickCallback, false);
        }
    }

    function legendClickCallback(event) {
        event = event || window.event;

        var target = event.target || event.srcElement;
        while (target.nodeName !== 'LI') {
            target = target.parentElement;
        }

        var parent = target.parentElement;
        var itemIndex = Array.prototype.slice.call(parent.children).indexOf(target);

        var meta = o.getDatasetMeta(0).data[itemIndex]; // Use the myChart variable here
        meta.hidden = !meta.hidden;

        // Strike through the legend text if the segment is hidden
        if (meta.hidden) {
            target.style.textDecoration = 'line-through';
        } else {
            target.style.textDecoration = 'none';
        }

        o.update(); // And here
    }
}(jQuery);
