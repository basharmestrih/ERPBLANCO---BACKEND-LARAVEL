<h1>Add New Song</h1>

<form action="/songs/store" method="POST">
    @csrf
    <label>Title:</label>
    <input type="text" name="title" required><br><br>

    <label>Author:</label>
    <input type="text" name="author" required><br><br>

    <button type="submit">Save</button>
</form>

<a href="/songs">Back</a>
