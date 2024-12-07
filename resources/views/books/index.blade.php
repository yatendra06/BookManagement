<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .table-responsive {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4;mt-4">Books Management</h1>
        <!-- Success Message -->
        @if (session('success'))
            <h5 style="color: green;"class="ml-3">{{ session('success') }}</h5>
        @endif

        <!-- Search Form -->
        <div class="col-lg-4 text-end">

            <div style="margin-left: 20px">
                <a href="{{ route('books.index') }}"class="btn btn-success">Back</a>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')"
                    onclick="event.preventDefault();
                            this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
            <form method="GET" action="{{ route('books.index') }}" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search By Author Name Or Title" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
                <span style="color:red;font-size:10px"><strong>&nbsp;Note:</strong>&nbsp; Search By Author Name And
                    Title</span>
            </form>
        </div>
        <!-- Books -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Rating</th>
                        <th>Description</th>
                        <th>Actions</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $key=> $book)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->rating }} / 5</td>
                            <td>
                                <textarea id="comments" name="comments" cols="40" rows="3">{{ $book->description }}</textarea><br>
                            </td>

                            <td style="">
                                <!-- Review Form -->
                                <form action="{{ route('books.rate', $book) }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <select name="rating" id="rating-{{ $book->id }}" class="form-select">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}"
                                                    {{ $book->rating == $i ? 'selected' : '' }}>{{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                        <button type="submit" class="btn btn-outline-primary"
                                            style="margin-top:10px">Submit</button>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <!-- Comments Section -->
                                <h5>Comments</h5>
                                @foreach ($book->comments as $comment)
                                    <div class="comment mb-2">
                                        <strong>{{ $comment->user ? $comment->user->name : 'Anonymous' }}:</strong>
                                        {{ $comment->comment }}</>
                                    </div>
                                @endforeach
                                @if ($book->comments->isEmpty())
                                    <p>No comments yet.</p>
                                @endif
                                <!-- Add Comment Form -->
                                @auth
                                    <form action="{{ route('comments.store', $book) }}" method="POST" class="mt-2">
                                        @csrf
                                        <textarea name="comment" class="form-control" placeholder="Write a comment" required></textarea>
                                        <button type="submit" class="btn btn-primary mt-2">Add Comment</button>
                                    </form>
                                @else
                                    <p>Please <a href="{{ route('login') }}">log in</a> to add a comment.</p>
                                @endauth
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No Result found!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $books->links('pagination::bootstrap-4') }}
        </div>
    </div>
</body>

</html>
