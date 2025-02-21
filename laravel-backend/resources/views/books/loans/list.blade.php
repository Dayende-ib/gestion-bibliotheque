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
                padding: 10px;
                margin: 20px 20px 20px 30px;
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

            .btn-danger {
                background-color: rgba(187, 0, 0, 0.849);
            }

            /* Tableau */
            table {
                width: 80%;
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
    @if(Session::get('success'))
            <div class="alert alert-success" style="background-color: green; color: white;">
                {{ Session::get('success') }}
            </div>
        @endif
        @if(Session::get('error'))
            <div class="alert alert-danger" style="background-color: rgb(114, 13, 13); color: white;">
                {{ Session::get('error') }}
            </div>
        @endif
    <body class="container-fluid">

            <h1>📜 Loan List</h1>

            <a href="{{ route('loans.create') }}">
                <button class="btn btn-primary"> Add new loan</button>
            </a>

            <table>
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Book name</th>
                        <th>Loan Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="loanTableBody">
                    @forelse ($loans as $loan)
                    <tr>
                        <td>{{ $loan->member_id }}</td>
                        <td>{{ $loan->book->title }}</td>
                        <td>{{ $loan->borrowed_at}}</td>
                        <td>{{ $loan->due_date }}</td>
                        <td>{{ $loan->status }}</td>
                        <td>
                            <form action="{{ route('loans.destroy', $loan->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                         <tr>
                            <td class="cell text-center" colspan="6">Aucun emprunt ajouté</td>
                        </tr>
                @endforelse
                </tbody>
            </table>
            

    </body>
</x-app-layout>