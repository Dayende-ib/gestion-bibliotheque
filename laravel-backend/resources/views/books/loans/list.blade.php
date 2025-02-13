<x-app-layout>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Loan List</title>
        <style>


            label {
                font-weight: bold;
            }

            input, select {
                width: 100%;
                padding: 10px;
                margin: 5px 0;
                border: 1px solid #bdc3c7;
                border-radius: 5px;
            }

            button{
                width: 100%;
                padding: 10px;
                margin-top: 10px;
                font-size: 1rem;
                font-weight: bold;
                color: white;
                background-color: #2980b9;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            button:hover {
                background-color: #1a5276;
            }

            /* Tableau */
            table {
                width: 90%;
                margin: auto;
                border-collapse: collapse;
                background: white;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            }

            th, td {
                padding: 10px;
                border: 1px solid #ddd;
            }

            th {
                background: #2980b9;
                color: white;
            }

            td button {
                margin: 5px;
            }

        </style>
    </head>
    <body class="container-fluid">
            <h1>📜 Loan List</h1>
            <table>
                <thead>
                    <tr>
                        <th>Book ID</th>
                        <th>Member ID</th>
                        <th>Loan Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="loanTableBody">
                </tbody>
            </table>
            <a href="{{ route('loans.create') }}"> Back</a>

    </body>
</x-app-layout>