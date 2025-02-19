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

                <form id="loanForm" method="POST" action="{{ route('loans.store')}}">
                @csrf

                    <label for="id">Book ID</label>
                    <select id="id" name="book_id">
                        <option value="">Choose a book</option>

                        @foreach ($books as $book)
                            <option value="{{ $book->id }}">{{ $book->title }}</option>
                        @endforeach

                    </select>
                    @error('book_id')
                        <div class="text-danger" style="color: red;">{{$message}}</div>
                    @enderror

                    <label for="member">Member ID</label>
                    <select id="member" name="member_id">
                        <option value="">Choose one option</option>
                        <option value="5">5</option>
                    </select>
                    @error('member_id')
                        <div class="text-danger" style="color: red;">{{$message}}</div>
                    @enderror

                    <label for="pret">Loan Date</label>
                    <input id="pret" name="loan_date" type="date" value="{{ date('Y-m-d') }}" />
                    @error('loan_date')
                        <div class="text-danger" style="color: red;">{{$message}}</div>
                    @enderror

                    <label for="return">Return Date</label>
                    <input id="return" name="return_date" type="date">
                    @error('return_date')
                        <div class="text-danger" style="color: red;">{{$message}}</div>
                    @enderror

                    <input name="status" type="hidden">

                    <button type="submit">Save loan</button>
                </form>
            </fieldset> 
        </div> 
    </main>

    
</body>
</html>

</x-app-layout>