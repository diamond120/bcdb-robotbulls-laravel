function getIndexOfK(arr, k) {
    for (var i = 0; i < arr.length; i++) {
        var index = arr[i].indexOf(k);
        if (index > -1) {
            return [i, index];
        }
    }
}

data = graph.map(function (elem) {
    return elem.map(function (elem2) {
        return parseFloat(elem2);
    });
});

data = data.reverse();

var jsondata = JSON.stringify(data);


// create the chart
Highcharts.stockChart('coingraph', {
    rangeSelector: {
        selected: 0
    },

    series: [{
        type: 'candlestick',
        name: 'RobotBulls Coin',
        data: data,
        dataGrouping: {
            units: [
                    [
                        'week', // unit name
                        [1] // allowed multiples
                    ], [
                        'month',
                        [1, 2, 3, 4, 6]
                    ]
                ]
        }
        }]
});
