<form method="POST" action="{{ route('avatar.upload') }}" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="avatar">Avatar:</label>
        <input type="file" name="avatar" id="avatar">
    </div>
    <div>
        <button type="submit">Upload Avatar</button>
    </div>
</form>
