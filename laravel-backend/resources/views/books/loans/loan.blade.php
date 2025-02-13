<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Borrowing</title>
    <link rel="stylesheet" href="style.css">
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

    <script>
        // Sauvegarde des données et redirection vers la liste
document.getElementById("loanForm")?.addEventListener("submit", function (e) {
    e.preventDefault();

    // Récupération des valeurs
    let bookID = document.getElementById("id").value;
    let memberID = document.getElementById("member").value;
    let loanDate = document.getElementById("pret").value;
    let returnDate = document.getElementById("return").value;
    let status = document.getElementById("st").value;

    if (!bookID || !memberID || !loanDate || !returnDate || !status) {
        alert("Please fill all fields.");
        return;
    }

    // Récupérer les anciens prêts
    let loans = JSON.parse(localStorage.getItem("loans")) || [];
    loans.push({ bookID, memberID, loanDate, returnDate, status });

    // Sauvegarde des prêts dans le localStorage
    localStorage.setItem("loans", JSON.stringify(loans));

    // Redirection vers la liste
    window.location.href = "list.html";
});

// Affichage des données dans la liste
function loadLoans() {
    let loans = JSON.parse(localStorage.getItem("loans")) || [];
    let tableBody = document.getElementById("loanTableBody");

    if (tableBody) {
        tableBody.innerHTML = "";
        loans.forEach((loan, index) => {
            let row = `<tr>
                <td>${loan.bookID}</td>
                <td>${loan.memberID}</td>
                <td>${loan.loanDate}</td>
                <td>${loan.returnDate}</td>
                <td>${loan.status}</td>
                <td>
                    <button onclick="editLoan(${index})">Edit</button>
                    <button onclick="deleteLoan(${index})">Delete</button>
                </td>
            </tr>`;
            tableBody.innerHTML += row;
        });
    }
}

// Suppression d'un prêt
function deleteLoan(index) {
    let loans = JSON.parse(localStorage.getItem("loans")) || [];
    loans.splice(index, 1);
    localStorage.setItem("loans", JSON.stringify(loans));
    loadLoans();
}

// Fonction de retour
function goBack() {
    window.location.href = "loan.html";
}

// Charger les prêts automatiquement si on est sur list.html
if (window.location.pathname.includes("list.html")) {
    loadLoans();
}

    </script>
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
