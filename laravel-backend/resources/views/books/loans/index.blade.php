<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Loans') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <h1 class="text-center text-primary">List of Loans</h1>
        <a class="btn btn-primary" href="{{ route('loans.history') }}">View loans history</a>
        
        @if(Session::get('success'))
            <div class="alert alert-success" style="background-color: green; color: white;">
                {{ Session::get('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <table class="table table-striped table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    @if (Auth::user()->role == 'admin')
                        <th class="bg-primary">Borrower Name</th>
                        <th class="bg-primary">Borrower Phone</th>
                        <th class="bg-primary">Borrower Status</th>
                    @endif
                    <th>Book</th>
                    <th>Borrowed Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                    <tr>
                        @if (Auth::user()->role == 'admin')
                            <td>{{ $loan->member->user->lastname }} {{ $loan->member->user->firstname }}</td>
                            <td>{{ $loan->member->phone }}</td>
                            <td>{{ $loan->member->status }}</td>    
                        @endif
                        <td>{{ $loan->book->title }}</td>
                        <td>{{ $loan->borrowed_at }}</td>
                        <td>{{ $loan->due_date }}</td>
                        <td>{{ $loan->status }}</td>
                        <td>
                            
                            @if (Auth::user()->role == 'admin')
                                <form action="{{ route('loans.destroy', $loan->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            @else
                                @if ($loan->status == 'returned')
                                    <button class="btn btn-success" disabled>Returned</button>
                                @endif     
                                @if ($loan->status == 'Borrowed')
                                    <form action="{{ route('loans.return', $loan->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Return</button>
                                    </form>
                                @endif     
                            @endif
                            
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-bg-info text-white" colspan="8">No loans</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $loans->links() }}
    </div>
</x-app-layout>
