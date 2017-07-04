@if ($errors)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors as $error)
                @foreach ($error as $message)
                    <li>{{ $message }}</li>
                @endforeach
            @endforeach
        </ul>
    </div>
@endif
