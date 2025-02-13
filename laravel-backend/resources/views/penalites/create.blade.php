<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penalite Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #1b0ab0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    
    .container {
        background: white;
        padding: 20px;
        border-radius: 8px ;
        width: 300px;
        text-align: center;
    }
    
    h2 {
        margin-bottom: 20px;
    }
    
    form {
        display: flex;
        flex-direction: column;
    }
    
    label {
        text-align: center;
        margin: 5px 0;
        
    
    }
    
    input, select {
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    
    button {
        background-color: #0c0c90;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        border-radius: 4px;
    }
    
    button:hover {
        background-color: #2d0c87;
    }
    
</style>
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
