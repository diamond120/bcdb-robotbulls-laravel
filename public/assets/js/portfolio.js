! function (t) {

    function getDate(n) {
        var date = new Date();
        date.setDate(date.getDate() - n);
        return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
    }
    
    if ($("#portfolio").length > 0) {

        var theme_color = "#2c80ff";

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
