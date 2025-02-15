@include('templates.principalheader')

<div id="main">
    <div class="w3-teal">
        <button id="openNav" class="w3-button w3-teal w3-xlarge" onclick="w3_open()">&#9776;</button>
        <div class="w3-container">
            <h1>Sectioning</h1>
        </div>
    </div>

    <div class="container" style="width: 80%; height: auto; border: 1px solid #ccc; padding: 20px;">
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f0f4f8;
                margin: 0;
                padding: 20px;
            }

            .container {
                max-width: 1300px;
                margin: auto;
                background: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }

            h1 {
                text-align: center;
                color: #2c3e50;
                margin-bottom: 20px;
                font-size: 24px;
                font-weight: 600;
            }

            .alert {
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 5px;
                color: #fff;
            }

            .alert-success {
                background-color: #28a745;
                /* Green */
            }

            .alert-danger {
                background-color: #dc3545;
                /* Red */
            }

            .info-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 15px;
                padding: 10px 0;
                border-bottom: 1px solid #e0e0e0;
            }

            .label {
                font-weight: bold;
                color: #2980b9;
            }

            input[type="text"] {
                width: 100%;
                padding: 10px;
                margin-top: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 14px;
            }

            input[type="text"]:focus {
                border-color: #2980b9;
                outline: none;
                box-shadow: 0 0 5px rgba(41, 128, 185, 0.5);
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                overflow-x: auto;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 12px;
                text-align: left;
            }

            th {
                background-color: #2980b9;
                color: white;
                font-weight: bold;
            }

            tr:hover {
                background-color: #f2f2f2;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            tr:nth-child(odd) {
                background-color: #ffffff;
            }

            input[type="checkbox"] {
                margin-right: 10px;
            }

            @media (max-width: 600px) {
                .info-row {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .info-row div {
                    margin-bottom: 10px;
                    width: 100%;
                }

                table {
                    display: block;
                    overflow-x: auto;
                    white-space: nowrap;
                }

                th,
                td {
                    min-width: 120px;
                    padding: 10px 5px;
                }

                h1 {
                    font-size: 20px;
                }

                input[type="text"] {
                    font-size: 16px;
                }

                button {
                    width: 100%;
                    padding: 12px;
                }
            }
        </style>

        <div class="container">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @elseif (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="/assigning" method="POST">
                @csrf
                <input type="hidden" name="grade" value="{{ $proof->level }}">
                <input type="hidden" name="payment_id" value="{{ $proof->id }}">
                <div class="info-row">
                    <div>
                        <span class="label">Name:</span> {{ $students->firstname }} {{ $students->middlename }}
                        {{ $students->lastname }} {{ $students->suffix }}
                    </div>
                    <div><span class="label">1st Semester S.Y. 2024 - 2025</span></div>
                </div>
                <div class="info-row">
                    <div><span class="label">Year Level:</span> {{ $proof->level }}</div>
                </div>

                <h2>Assign Classes</h2>
                <input type="text" id="searchInput" onkeyup="searchClasses()" placeholder="Search for classes..."
                    aria-label="Search for classes">

                <table>
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Year Level</th>
                            <th>Adviser</th>
                            <th>Section</th>
                            <th>EDP Code</th>
                            <th>Subject</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Units</th>
                            <th>Days</th>
                            <th>Time</th>
                            <th>Room</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="classTableBody">
                        @foreach ($classes as $class)
                            @if ($class->grade === $proof->level)
                                <tr>
                                    <td><input type="checkbox" name="selected_classes[]" value="{{ $class->edpcode }}">
                                    </td>
                                    <td>{{ $class->grade }}</td>
                                    <td>{{ $class->adviser }}</td>
                                    <td>{{ $class->section }}</td>
                                    <td>{{ $class->edpcode }}</td>
                                    <td>{{ $class->subject }}</td>
                                    <td>{{ $class->description }}</td>
                                    <td>{{ $class->type }}</td>
                                    <td>{{ $class->unit }}</td>
                                    <td>{{ $class->days }}</td>
                                    <td>{{ $class->time }}</td>
                                    <td>{{ $class->room }}</td>
                                    <td>Active</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="button">Assign Selected Classes</button>
            </form>
        </div>

        <script>
            function searchClasses() {
                const input = document.getElementById('searchInput');
                const filter = input.value.toLowerCase();
                const table = document.getElementById('classTableBody');
                const rows = table.getElementsByTagName('tr');

                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    let rowContainsSearchTerm = false;

                    for (let j = 1; j < cells.length; j++) { // Start from 1 to skip the checkbox column
                        if (cells[j]) {
                            const cellText = cells[j].textContent || cells[j].innerText;
                            if (cellText.toLowerCase().includes(filter)) {
                                rowContainsSearchTerm = true;
                                break;
                            }
                        }
                    }

                    rows[i].style.display = rowContainsSearchTerm ? "" : "none";
                }
            }
        </script>
    </div>
</div>

@include('templates.principalfooter')
