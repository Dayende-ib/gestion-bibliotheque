<tbody>
    @foreach($members as $member)
        <tr>
            <td>{{ $member->user->lastname }} {{ $member->user->firstname }}</td>
            <td>{{ $member->phone }}</td>
            <td>{{ $member->address }}</td>
            <td>{{ $member->user->email }}</td>
            <td>{{ $member->join_date }}</td>
            <td>{{ $member->expiry_date }}</td>
            <td>{{ $member->status }}</td>
            <td>
                <a href="{{ route('members.edit', $member) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('members.destroy', $member) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this member ?')">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>