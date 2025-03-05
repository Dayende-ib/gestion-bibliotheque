<x-app-layout>
    <div class="container">
        <h2>Edit Member</h2>
        <form action="{{ route('members.update', $member) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label>Phone:</label>
                <input type="text" name="phone" class="form-control" value="{{ $member->phone }}" required>
                
            </div>
            <div class="mb-3">
                <label>Address:</label>
                <input type="text" name="address" class="form-control" value="{{ $member->address }}" required>
            </div>
            <div class="mb-3">
                <label>Join Date:</label>
                <input type="date" name="join_date" class="form-control" value="{{ $member->join_date }}" required>
            </div>
            <div class="mb-3">
                <label>Expiry Date:</label>
                <input type="date" name="expiry_date" class="form-control" value="{{ $member->expiry_date }}" required>
            </div>
            <div class="mb-3">
                <label>Status:</label>
                <select name="status" class="form-control" required>
                    <option value="Active" {{ $member->status == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ $member->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="Banned" {{ $member->status == 'Banned' ? 'selected' : '' }}>Banned</option>
                </select>
            </div>

            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-warning w-25">Update</button>
            </div>
            
        </form>
    </div>
</x-app-layout>