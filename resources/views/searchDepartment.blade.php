<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Policy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
</head>

<style>

    .container.mt-5
    {
        /* border: 2px solid black; */
        border-top:none;
        margin-top:0 !important;
    }

</style>
<body>
    @include('UsersView.header')

    <div class="container mt-5">
        <br>
        <h2>Search for Department / Office / Sub-Office</h2>
        <br>
        <div class="row justify-content-center">
            <form method="POST" action="{{ route('department.show') }}">
                @csrf
                <label for="search">Enter Character to Search:</label>
                <input type="text" id="search" name="search" class="form-control" value="{{ old('search', $search ?? '') }}" >
                <button type="submit" style="display: block; margin: 20px auto;" class="btn btn-primary btn-lg">Search</button>
            </form>

            @if(!empty($search))
            <h3>Search Results for: "{{ $search }}"</h3>
                @if($departments->isEmpty())
                    <p>No departments found.</p>
                @else
                @if(!empty($hierarchy))
                    <h3 class="mt-5">Detailed Hierarchy</h3>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Department</th>
                                    <th>Office</th>
                                    <th>Sub-Office</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hierarchy as $entry)
                                    <tr>
                                        <td>{{ $entry['dept_details']->department }}</td>
                                        <td>{{ $entry['office_details']->office ?? '-' }}</td>
                                        <td>{{ $entry['suboffice_details']->suboffice ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endif
            @endif
        </div>
        <br>
    </div>

    @include('UsersView.footer')
</body>
</html>






