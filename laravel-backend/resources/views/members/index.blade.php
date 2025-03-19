<x-app-layout>
    <div class="container">
        @if (Auth::user()->role == 'admin')
            <a href="{{ route('members.create') }}" class="btn btn-success w-25 mb-3">Add member</a>
        @endif
    
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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

        <!-- Search Form -->
        <form method="GET" action="{{ route('members.index') }}" class="mb-3" id="search-form">
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control" placeholder="Rechercher un membre" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
                <button type="button" id="clear-search" class="btn btn-secondary ms-2"><i class="fa fa-times"></i>Cancel</button>
            </div>
        </form>
    
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Join Date</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="members-list">
                @include('members.partials.members-list', ['members' => $members])
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $members->links() }}
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('clear-search').addEventListener('click', function() {
                document.getElementById('search').value = '';
                document.getElementById('search-form').submit();
            });
        });
    </script>
</x-app-layout>