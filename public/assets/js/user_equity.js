function getEquity() {

    const url = 'https://app.robotbulls.com/user/ajax/get_equity';
    const requestOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    };

    
    fetch(url, requestOptions)
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return Promise.reject('Network response was not ok');
        }
    })
    .then(data => {
        console.log("data: ");
        console.log(data);
        console.log(data['equity']);
        let equity = data['equity'];
        document.getElementById("equity").innerHTML = equity.toFixed(2);
        document.getElementById("equity").style.background = "#888888";
        setTimeout(function () {
            document.getElementById("equity").style.background = "none";
        }, 500);
    })
    .catch(error => {
        console.error('Fetch error: ', error);
    });
}

if (number_of_transactions > 0) {
    getEquity();
    setInterval(function () {
        getEquity();
    }, 10000 + Math.random() * 10000 - 2000);
}
