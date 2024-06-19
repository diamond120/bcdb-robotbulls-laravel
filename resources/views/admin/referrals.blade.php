@extends('layouts.admin')
@section('title', ucfirst($is_page).' Ambassadors')
@section('content')

<div class="page-content">
    <div class="container">
        @include('layouts.messages')
        @include('vendor.notice')
        <div class="card content-area content-area-mh">
            <div class="card-innr">
                <div class="card-head has-aside">
                    <h4 class="card-title">{{ ucfirst($is_page) }} Ambassadors</h4>

                    <div class="card-opt data-action-list d-none d-md-inline-flex">
                        <ul class="btn-grp btn-grp-block guttar-20px">
                            <li>
                                <a href="#" class="btn btn-auto btn-sm btn-primary" data-toggle="modal" data-target="#addBonuses">
                                    <em class="fas fa-plus-circle"> </em><span>Add <span class="d-none d-md-inline-block">Bonuses</span></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    
                </div>

                @if (auth()->user()->id == '1')
                    <div class="page-nav-wrap">
                        <div class="page-nav-bar justify-content-between bg-lighter">
                            <div class="page-nav w-100 w-lg-auto">
                                <ul class="nav">
                                    <li class="nav-item{{ (is_page('referrals') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.referrals') }}">All</a></li>
                                    <li class="nav-item{{ (is_page('referrals.ambassadors') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.referrals', 'ambassadors') }}">Ambassadors</a></li>
                                </ul>
                            </div>
                            <div class="search flex-grow-1 pl-lg-4 w-100 w-sm-auto">
                                <form action="{{ route('admin.referrals') }}" method="GET" autocomplete="off">
                                    <div class="input-wrap">
                                        <span class="input-icon input-icon-left"><em class="ti ti-search"></em></span>
                                        <input type="search" class="input-solid input-transparent" placeholder="Quick search with name/email/id/wallet address" value="{{ request()->get('s', '') }}" name="s">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                
                <table class="data-table user-list">
                    <thead>
                        <tr class="data-item data-head">
                            <th class="data-col data-col-wd-md filter-data dt-user">User</th>
                            <th class="data-col dt-token">Amount of people</th>
                            <th class="data-col dt-token">Amount Referred</th>
                            <th class="data-col dt-token">Equity Referred</th>
<!--                            <th class="data-col dt-login">Last/First Login</th>-->
                            <th class="data-col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($referedUsers as $referrant)
                        <tr class="data-item">
                            <td class="data-col data-col-wd-md dt-user">
                                <center>
                                <div class="d-flex align-items-center">
                                    <div class="fake-class">
                                        <span class="lead user-name text-wrap">
                                            @if (auth()->user()->id == '1')
                                            <a href="{{ route('admin.users.view', [$referrant['id'], 'details'] ) }}" target="_blank">{{ $referrant['name'] }}</a>
                                            @else
                                            <span href="{{ route('admin.users.view', [$referrant['id'], 'details'] ) }}" target="_blank">{{ $referrant['name'] }}</span>
                                            @endif
                                        </span>
                                        <span class="sub user-id">{{ set_id($referrant['id'], 'user') }}</span>
                                    </div>
                                </div>
                                    </center>
                            </td>
                            <td class="data-col dt-token">
                                <center>
                                    <span class="lead lead-btoken">{{ number_format($referrant['count']) }}</span>
                                </center>
                            </td>
                            <td class="data-col dt-verify">
                                <center>
                                    <span class="lead lead-btoken">{{ number_format($referrant['tokenBalance']) }}</span>
                                </center>
                            </td>
                            <td class="data-col dt-verify">
                                <center>
                                    <span class="lead lead-btoken">{{ number_format($referrant['equity']) }}</span>
                                </center>
                            </td>
                            <td class="data-col text-right">
                                <div class="relative d-inline-block">
                                    <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                                    <div class="toggle-class dropdown-content dropdown-content-top-left">
                                        <ul id="more-menu" class="dropdown-list">
                                            <li><a href="javascript:void(0)" data-uid="{{ $referrant['id'] }}" data-type="referrals" class="user-form-action user-action"><em class="fas fa-users"></em>Referrals</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>

                        </tr>
                        {{-- .data-item --}}
                        @endforeach
                    </tbody>
                </table>

            </div>
            {{-- .card-innr --}}
        </div>{{-- .card --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}

@endsection


@section('modals')

<div class="modal fade" id="addBonuses">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title">Add Bonuses</h3>

                    @csrf
                    
                    <select id="bonus_user" name="user" required="required" class="select-block select-bordered" data-dd-class="search-on">
                            @forelse($ambassadors as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @empty
                            <option value="">No user found</option>
                            @endif
                        </select>

                    <!-- File input for CSV -->
                    <div class="input-item input-with-label">
                        <label class="input-item-label">Upload CSV</label>
                        <div class="input-wrap">
                            <input type="file" name="csv" accept=".csv" required="required">
                        </div>
                    </div>
                    
                    <!-- Button to process the CSV -->
                    <div class="gaps-1x"></div>

                    <a class="btn btn-primary btn-auto btn-info" style="color: white;" onclick="processCsv()">Process CSV</a>
                    
                    <table id="new_bonuses_table" class="d-none mt-4">
                        <thead>
                            <tr>
                                <th><center>User</center></th>
                                <th><center>Referrant</center></th>
                                <th><center>TNX</center></th>
                                <th><center>Amount</center></th>
                                <th><center>Bonus</center></th>
                                <th><center>Index</center></th>
                                <th><center>B_Amount</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                    
                    <div id="total_bonuses"></div>

                    <button id="submit_bonuses" class="btn btn-md btn-primary mt-4 d-none" type="submit" onclick="addBonuses()">Approve</button>
                    <button id="bonuses_download_csv" class="btn btn-md btn-primary mt-4 d-none" onclick="exportTableToCSV()">Export to CSV</button>
                    
            </div>
        </div>
        {{-- .modal-content --}}
    </div>
    {{-- .modal-dialog --}}
</div>



<script>   
    
function processCsv() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = "/admin/ajax/getbonusestransactions";

    // Get the file input by its name or ID
    const fileInput = document.querySelector('input[name="csv"]');
    const file = fileInput.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(event) {
            const csv = event.target.result;
            const lines = csv.split("\n");
            const phoneNumbers = [];

            const user_bonus = document.getElementById('bonus_user').value;
            let phoneIndex; // Declare outside of for loop so we can access it later

            function splitCSVLine(line) {
                let insideQuote = false;
                let fieldStart = 0;
                const result = [];

                for (let i = 0; i <= line.length; i++) {
                    if (i === line.length || (line[i] === ',' && !insideQuote)) {
                        result.push(line.substring(fieldStart, i).replace(/^"|"$/g, ''));
                        fieldStart = i + 1;
                    } else if (line[i] === '"') {
                        insideQuote = !insideQuote;
                    }
                }

                return result;
            }

            for (let i = 0; i < lines.length; i++) {
                const cells = splitCSVLine(lines[i]);

                // Assuming the "phone" column is somewhere in the header
                if (i === 0) {
                    phoneIndex = cells.findIndex(cell => cell.trim().toLowerCase() === 'phone');
                    if (phoneIndex === -1) {
                        alert('Phone column not found in CSV.');
                        return;
                    }
                } else {
                    phoneNumbers.push(cells[phoneIndex]);
                }
            }

            // Now, you can send this data to your server
            const data = {
                user_bonus: user_bonus,
                phones: phoneNumbers
            };

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Get the table body element
                const tableBody = document.getElementById('new_bonuses_table').querySelector('tbody');

                // Clear existing rows
                tableBody.innerHTML = '';

                // Add new rows from the response data
                data.data.forEach(entry => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td><center>${entry.phone}</center> <center><span class="small">${entry.user}</span></center></td>
                        <td><center>${entry.referred_by || 'N/A'}</center></td>
                        <td><center>${entry.transaction_number}</center> <center><span class="small">${entry.transaction_date}</span></center></td>
                        <td><center>${entry.amount}</center> <center><span class="small">${entry.currency}</span></center></td>
                        <td><center>${entry.referral_bonus_received}</center></td>
                        <td><center>${entry.transaction_index}</center></td>
                        <td><center>${entry.bonus_amount}</center> <center><span class="small">${entry.bonus_percentage}</span></center></td>
                    `;

                    tableBody.appendChild(row);
                    
                    //show button and columns
                                    });
                document.getElementById("new_bonuses_table").classList.remove("d-none");
                document.getElementById("submit_bonuses").classList.remove("d-none");
                document.getElementById("bonuses_download_csv").classList.remove("d-none");
                document.getElementById("total_bonuses").innerText = data.total_bonuses;
            })
            .catch(error => {
                console.log('There was a problem with the fetch operation:', error.message);
            });
        };

        reader.onerror = function() {
            console.error("Could not read the file.");
        };

        reader.readAsText(file);
    } else {
        alert("No file selected.");
    }
}
    
function addBonuses() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = "/admin/ajax/addbonuses";

    const fileInput = document.querySelector('input[name="csv"]');
    const file = fileInput.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(event) {
            const csv = event.target.result;
            const lines = csv.split("\n");
            const phoneNumbers = [];
            const user_bonus = document.getElementById('bonus_user').value;
            let phoneIndex;

            function splitCSVLine(line) {
                let insideQuote = false;
                let fieldStart = 0;
                const result = [];

                for (let i = 0; i <= line.length; i++) {
                    if (i === line.length || (line[i] === ',' && !insideQuote)) {
                        result.push(line.substring(fieldStart, i).replace(/^"|"$/g, ''));
                        fieldStart = i + 1;
                    } else if (line[i] === '"') {
                        insideQuote = !insideQuote;
                    }
                }

                return result;
            }

            for (let i = 0; i < lines.length; i++) {
                const cells = splitCSVLine(lines[i]);
                if (i === 0) {
                    phoneIndex = cells.findIndex(cell => cell.trim().toLowerCase() === 'phone');
                    if (phoneIndex === -1) {
                        alert('Phone column not found in CSV.');
                        return;
                    }
                } else {
                    phoneNumbers.push(cells[phoneIndex]);
                }
            }

            const data = {
                user_bonus: user_bonus,
                phones: phoneNumbers
            };

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);
                alert("Success!");  // For user feedback
            })
            .catch(error => {
                console.error("There was an error:", error);
                alert("An error occurred!");  // For user feedback
            });
        };
        
        reader.readAsText(file);  // This line was missing
    }
}
   
function exportTableToCSV() {
    var table = document.getElementById('new_bonuses_table');
    var rows = table.querySelectorAll('tr');
    var csv = [];

    // Custom headers
    csv.push("User (Phone);User (Name);Referrant;TNX (ID);TNX (Date-Time);Amount (Value);Amount (Currency);Bonus;Index;B_Amount (Value);B_Amount (Percentage)");

    // Start at 1 to skip the original header row
    for (var i = 1; i < rows.length; i++) {
        var cols = rows[i].querySelectorAll('td');
        var rowData = [];
        
        for (var j = 0; j < cols.length; j++) {
            var centers = cols[j].querySelectorAll('center');
            if (centers.length > 1) {
                // For the columns with multiple center tags, capture each one separately
                centers.forEach(function(center) {
                    rowData.push(center.innerText);
                });
            } else {
                rowData.push(cols[j].innerText);
            }
        }
        csv.push(rowData.join(';')); // Using semicolon ';' as the delimiter for European CSVs
    }

    var csvString = csv.join('\r\n');
    var blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });

    var link = document.createElement('a');
    var url = URL.createObjectURL(blob);
    
    var date = new Date();
    var filename = "real - " + document.getElementById("select2-bonus_user-container").innerText + "-" + date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear() + ".csv"; // Replace "Username" with actual username

    link.href = url;
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
  
</script>


    
@endsection
