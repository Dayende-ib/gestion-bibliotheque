<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan List</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    text-align: center;
    margin: 20px;
}

h1 {
    color: #2c3e50;
}

.place {
    display: flex;
    justify-content: center;
}

fieldset {
    width: 90%;
    max-width: 400px;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

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

button, a {
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
<body>
    <main>
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
                <!-- Les données seront insérées ici via JavaScript -->
            </tbody>
        </table>
        <a href="{{ route('books.create') }}"> Back</a>
    </main>

</body>
</html>
