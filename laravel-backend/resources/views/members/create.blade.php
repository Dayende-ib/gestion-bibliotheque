<x-app-layout>
    <div class="container mt-5">
        @if (Auth::user()->role == 'admin')
            <h2 class="mb-4 h2 text-center">ADD NEW MEMBER</h2>
        @else
            <h2 class="mb-4 h2 text-center">BECOME A MEMBER</h2>
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

        <form action="{{ route('members.store') }}" method="POST" class="bg-light-subtle p-5 rounded shadow-sm">
            @csrf
            <div class="row mb-3">
                @if (Auth::user()->role == 'admin')
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id" class="form-label">User</label>
                            <select class="form-control" id="user_id" name="user_id" required>
                                <option value="">Select a user</option>
                                <!-- list of users -->
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->lastname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @else
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id" class="form-label">User</label>
                            <input type="text" class="form-control" id="user_id" name="user_id" value="{{ Auth::user()->id }}" readonly hidden>
                            <input type="text" class="form-control" value="{{ Auth::user()->lastname }} {{ Auth::user()->firstname }}" readonly>
                        </div>
                    </div>
                @endif
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Fill here your phone" required>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Fill here your address" required>
            </div>
            <div class="form-group mb-3">
                <label for="membership_number" class="form-label">Membership Number</label>
                <input type="text" class="form-control" id="membership_number" name="membership_number" value="{{ $membershipNumber }}" readonly>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="join_date" class="form-label">Join Date</label>
                        <input type="datetime-local" class="form-control @error('join_date') is-invalid @enderror" id="join_date" name="join_date" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" id="expiry_date" value="{{ old('expiry_date') }}" name="expiry_date">
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <select class="form-control" id="status" name="status" required hidden>
                    <option value="Active">Active</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var now = new Date();
            var year = now.getFullYear();
            var month = ('0' + (now.getMonth() + 1)).slice(-2);
            var day = ('0' + now.getDate()).slice(-2);
            var hours = ('0' + now.getHours()).slice(-2);
            var minutes = ('0' + now.getMinutes()).slice(-2);
            var formattedDateTime = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
            document.getElementById('join_date').value = formattedDateTime;
        });
    </script>
</x-app-layout>