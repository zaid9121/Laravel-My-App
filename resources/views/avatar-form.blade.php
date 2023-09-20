<x-layout doctitle="Manage your avatar">
    <div class="container container--narrow py-md-5">
        <h2 class="text-center mb-3">Upload a new Avatar</h2>
        <form action="/manage-avatar" method="POST" enctype="multipart/form-data"> <!-- without --enctype="multipart/form-data-- laravel does not see atached file -->
            @csrf
            <div class="mb-3">
                <input type="file" name="avatar">
                @error('avatar')
                    <p class="alert small alert-danger shadow-sm">{{ $message }}</p>
                @enderror
            </div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
</x-layout>