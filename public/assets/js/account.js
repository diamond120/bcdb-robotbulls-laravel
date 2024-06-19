    // Global flags to track loading state
    let isLoadingModal = false;
    let isLoadingModalMessages = false;

    function show_whitelisting() {
        console.log("Whitelisting test");

        let existingModal = document.getElementById('whitelisting-modal');

        if (existingModal) {
            console.log("existingModal");
            $('#whitelisting-modal').modal('show');
        } else if (!isLoadingModal) {
            isLoadingModal = true;
            const url = 'https://app.robotbulls.com/user/ajax/account/whitelisting-form';
            const requestOptions = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            };

            fetch(url, requestOptions)
                .then(response => response.ok ? response.text() : Promise.reject('Network response was not ok'))
                .then(modalHTML => {
                    document.getElementById('ajax-modal').innerHTML += modalHTML;
                    $('#whitelisting-modal').modal('show');
                    isLoadingModal = false;
                })
                .catch(error => {
                    console.error('Fetch error: ', error);
                    isLoadingModal = false;
                });
        }
    }
    
    function get_key() {
        
        let existingModal = document.getElementById('get-key-modal');
        
        if (existingModal) {
            console.log("existingModal");
            $('#get-key-modal').modal('show');
        } else if (!isLoadingModal) {
            isLoadingModal = true;
            const url = 'https://app.robotbulls.com/user/ajax/account/get-key-form';
            const requestOptions = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            };

            fetch(url, requestOptions)
                .then(response => response.ok ? response.text() : Promise.reject('Network response was not ok'))
                .then(modalHTML => {
                    document.getElementById('ajax-modal').innerHTML += modalHTML;
                    $('#get-key-modal').modal('show');
                    isLoadingModal = false;
                })
                .catch(error => {
                    console.error('Fetch error: ', error);
                    isLoadingModal = false;
                });
        }
    }

    function startWhitelistingProcess() {
        event.preventDefault();
        document.getElementById('loading-animation').style.display = 'inline';
        setTimeout(() => document.getElementById('whitelisting-form').submit(), 1000);
    }

    function get_key_textarea() {
        console.log("click")
        event.preventDefault();
        
        var myInput = document.getElementById('agree-terms').checked;
        var myInput2 = document.getElementById('agree-note1').checked;
        var myInput3 = document.getElementById('agree-note2').checked;
        if (myInput && myInput2 && myInput3) {
            document.getElementById("privatekey_field").style.display = "block";
            document.getElementById("get_private_key_button").style.display = "none";
            document.getElementById("checkboxes").style.display = "none";
        } else {
            alert('Please accept the conditions before proceeding.');
        }
        
    }

    function show_messages() {
        console.log("Messages test");

        let existingModalMessages = document.getElementById('messages-modal');

        if (existingModalMessages) {
            console.log("existingModalMessages");
            $('#messages-modal').modal('show');
        } else if (!isLoadingModalMessages) {
            isLoadingModalMessages = true;
            const url = 'https://app.robotbulls.com/user/ajax/account/messages-form';
            const requestOptions = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            };

            fetch(url, requestOptions)
                .then(response => response.ok ? response.text() : Promise.reject('Network response was not ok'))
                .then(modalHTML => {
                    document.getElementById('ajax-modal').innerHTML += modalHTML;
                    $('#messages-modal').modal('show');
                    isLoadingModalMessages = false;
                })
                .catch(error => {
                    console.error('Fetch error: ', error);
                    isLoadingModalMessages = false;
                });
        }
    }

    function fetchMessagesForUser(userId) {
        console.log("Click past messages");
        fetch(`/admin/fetch-messages/${userId}`)
            .then(response => response.json())
            .then(data => {
                let messagesList = document.getElementById('messagesList');
                messagesList.innerHTML = '';
                data.reverse().forEach(message => {
                    let messageItem = `
                        <li class="text-dark row no-gutters justify-content-between">
                            <div class="col-md col-sm-3"><strong class="text-dark">${message.from}</strong></div>
                            <div class="col-md col-sm-3"><span class="text-dark">${message.message}</span></div>
                            <div class="col-md col-sm-3"><span class="text-dark">${message.created_at}</span></div>
                            <div class="col-md col-sm-3"><span class="text-dark">${message.channel}</span></div>
                            <div class="col-md col-sm-3"><span class="text-dark">${message.status}</span></div>
                        </li>
                    `;
                    messagesList.innerHTML += messageItem;
                });
            });
    }
