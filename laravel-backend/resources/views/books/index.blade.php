<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Books') }}
        </h2>
    </x-slot>
    <head>
        <style>
            .form-inline {
                margin-bottom: 20px;
            }

            .form-inline input[type="text"] {
                width: 50%;
                padding: 10px;
                font-size: 16px;
            }

            .form-inline button[type="submit"] {
                padding: 10px 20px;
                font-size: 16px;
                background-color: #337ab7;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .form-inline button[type="submit"]:hover {
                background-color: #23527c;
            }
        </style>
         {{-- /*Style pour les grids*/ --}}
        <style>
            .custom-card {
                border: 1px solid #ddd;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                height: 400px; /* Fixed height */
                width: 100%; /* Fixed width */
                display: flex;
                flex-direction: column;
                position: relative;
                overflow: hidden;
            }

            .custom-card .card-title {
                font-size: 1.25rem;
                font-weight: bold;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 1; /* Number of lines to show */
                line-clamp: 1;
                overflow: hidden;
            }

            .custom-card .card-text {
                font-size: 1rem;
                color: #555;
                max-height: 80px; /* Initial max height */
                overflow: hidden;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 2; /* Number of lines to show */
                line-clamp: 2;
            }

            .custom-card img {
                max-width: 100%;
                height: 60%; /* Fixed height */
                border-bottom: 1px solid #ddd;
                border-top-left-radius: 10px;
                border-top-right-radius: 10px;
                object-fit: cover;
                transition: height 0.7s, transform 0.7s;
            }
            .custom-card:hover img {
                height: auto; /* Fixed height on hover */
                width: 100%;
                transform: scale(0.9);
                
            }

            .card-body {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .card-actions {
                position: absolute;
                top: 10px;
                right: 10px;
                display: none;
            }

            .custom-card:hover .card-actions {
                display: block;
            }

            .card-actions a, button {
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }
            .card-author {
                bottom: 10px;
                right: 10px;
                font-weight: bold;
                color: #007bff; /* Primary color */
            }
        </style>
    </head>
    
    <div class="container mt-4">
        @if (Auth::user()->role == 'admin')
            <a href="{{ route('books.create') }}" class="btn btn-success mb-3">Add book</a>
        @endif
        
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

        <div class="row">
            @foreach($books as $book)
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card custom-card">
                        @if($book->image != null)
                            <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}">
                        @else
                            <img src="{{ asset('build/images/default-book-cover.png') }}" alt="Default Image">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $book->title }}</h5>
                            <p class="card-text">{{ $book->description }}</p>
                            <p class="card-author mt-2 font-bold text-primary text-right">{{ $book->author }}</p>
                            <div class="card-actions">
                                @if (Auth::user()->role == 'admin')
                                    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"> <i class="fa "></i>Delete</button>
                                    </form>
                                @else
                                    @if ($book->status == 'Borrowed')
                                        <a href="#" class="btn btn-secondary btn-sm disabled">Borrowed</a>
                                    @else
                                        <a href="#" onclick="checkMembership({{ Auth::user()->member ? 'true' : 'false' }}, {{ $book->id }})" class="btn btn-primary btn-sm">Borrow</a>
                                        <a href="#" class="btn btn-secondary btn-sm">Return</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 h3 font-weight-bold text-primary">Books list</h3>
            </div>
            <div class="row w-60">
                <div class="col-md-12">
                    <form class="form-inline" action="{{ route('books.index') }}" method="GET">
                        <input type="text" id="search-input" class="form-control" name="search" placeholder="Rechercher un livre...">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>ISBN</th>
                                <th>Publication Year</th>
                                <th>Status</th>
                                <th class="bg-transparent border-none"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($books as $book)
                                <tr>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->author }}</td>
                                    <td>{{ $book->isbn }}</td>
                                    <td>{{ $book->published_year }}</td>
                                    <td>{{ $book->status }}</td>
                                    @if (Auth::user()->role == 'admin')
                                        <td class="items-center ">
                                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                        </td>
                                    @else
                                        @if ($book->status == 'Borrowed')
                                            <td>
                                                <a href="#" class="btn btn-secondary btn-sm disabled">Borrowed</a>
                                            </td>
                                            
                                        @else
                                            <td>
                                            <a href="#" onclick="checkMembership({{ Auth::user()->member ? 'true' : 'false' }}, {{ $book->id }})" class="btn btn-primary btn-sm">Borrow</a>
                                            </td>
                                        @endif
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-bg-info text-white" colspan="6">No books to show</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">Showing 21 to 30 of 57 entries</div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                            <ul class="pagination">
                                <li class="paginate_button page-item previous" id="dataTable_previous">
                                    <a href="#" aria-controls="dataTable" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                                </li>
                                <li class="paginate_button page-item ">
                                    <a href="#" aria-controls="dataTable" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                                </li>
                                <li class="paginate_button page-item ">
                                    <a href="#" aria-controls="dataTable" data-dt-idx="2" tabindex="0" class="page-link">2</a>
                                </li>
                                <li class="paginate_button page-item active">
                                    <a href="#" aria-controls="dataTable" data-dt-idx="3" tabindex="0" class="page-link">3</a>
                                </li>
                                <li class="paginate_button page-item ">
                                    <a href="#" aria-controls="dataTable" data-dt-idx="4" tabindex="0" class="page-link">4</a>
                                </li>
                                <li class="paginate_button page-item ">
                                    <a href="#" aria-controls="dataTable" data-dt-idx="5" tabindex="0" class="page-link">5</a>
                                </li>
                                <li class="paginate_button page-item ">
                                    <a href="#" aria-controls="dataTable" data-dt-idx="6" tabindex="0" class="page-link">6</a>
                                </li>
                                <li class="paginate_button page-item next" id="dataTable_next">
                                    <a href="#" aria-controls="dataTable" data-dt-idx="7" tabindex="0" class="page-link">Next</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    
    

    <!-- Add this modal dialog in the body of index.blade.php -->
<div id="membershipModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Become a Member</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You need to be a member to borrow a book. Would you like to become a member?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="{{ route('members.create') }}" class="btn btn-primary">Become a Member</a>
            </div>
        </div>
    </div>
</div>

<script>
    function checkMembership(isMember, bookId) {
        if (isMember) {
            window.location.href = '/loans/create/' + bookId;
        } else {
            $('#membershipModal').modal('show');
        }
    }

    $(document).ready(function() {
    var timeout;
    $('#search-input').on('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            var search = $('#search-input').val();
            $.ajax({
                type: 'GET',
                url: '/books/search',
                data: {search: search},
                success: function(data) {
                    $('#books-table').html(data);
                }
            });
        }, 500);
    });
});

    
</script>
</x-app-layout>