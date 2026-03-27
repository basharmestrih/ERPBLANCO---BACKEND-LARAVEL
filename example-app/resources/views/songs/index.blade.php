<h1>Songs List</h1>

<a href="/songs/create">Add Song</a>



<table cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Actions</th>
    </tr>

    @foreach($songs as $song)
    <tr>
        <td>{{ $song->id }}</td>
        <td>{{ $song->title }}</td>
        <td>{{ $song->author }}</td>
        <td>
            <a href="">Edit</a> |
            <a href="" style="color:red;">Delete</a>
        </td>
    </tr>
    @endforeach
</table>
