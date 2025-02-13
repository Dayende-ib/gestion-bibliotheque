<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penalite Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Penalite Management</h2>
        <form action="#" method="POST">
            <label for="loan_id">Loan_ID  :</label>
            <input type="text" id="loan_id" name="loan_id" required>

            <label for="amount">Amount :</label>
            <input type="number" id="amount" name="amount" required>

            <label for="status">Status :</label>
            <select id="status" name="status" required>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
                <option value="overdue">Overdue</option>
            </select>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
