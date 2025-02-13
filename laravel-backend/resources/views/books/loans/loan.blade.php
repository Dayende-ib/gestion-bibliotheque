<x-app-layout>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Borrowing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    text-align: center;
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

button {
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

    </style>

</head>
<body>
    <main>
        <h1>📚 Book Borrowing</h1>
        <div class="place">
            <fieldset>
                <legend>Loan Form</legend>
                <form id="loanForm">
                    <label for="id">Book ID</label>
                    <select id="id">
                        <option value="">Choose one option</option>
                        <option value="HTML">HTML</option>
                        <option value="CSS">CSS</option>
                        <option value="JavaScript">JavaScript</option>
                    </select>

                    <label for="member">Member ID</label>
                    <select id="member">
                        <option value="">Choose one option</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>

                    <label for="pret">Loan Date</label>
                    <input id="pret" type="date">

                    <label for="return">Return Date</label>
                    <input id="return" type="date">

                    <label for="st">Status</label>
                    <select id="st">
                        <option value="">Choose one option</option>
                        <option value="Handed Over">Handed Over</option>
                        <option value="Still on Loan">Still on Loan</option>
                    </select>

                    <button type="submit">Save</button>
                </form>
            </fieldset> 
        </div> 
    </main>

    
</body>
</html>

</x-app-layout>