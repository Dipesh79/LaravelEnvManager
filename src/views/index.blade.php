<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LaravelENVManager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .card-custom {
            background-color: #f8f9fa;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
        }
        .form-control:focus, .form-control-sm:focus {
            border-color: #0056b3;
            box-shadow: 0 0 0 0.2rem rgba(0,86,179,.25);
        }
        .form-label {
            color: #333;
        }
        .btn-custom, .btn-sm {
            background-color: #0056b3;
            border: none;
        }
        .mb-3, .form-group {
            margin-bottom: 0.5rem;
        }
        .form-control {
            height: calc(1.5em + 0.75rem + 2px);
        }
        .form-control-sm {
            height: calc(1.25em + 0.5rem + 2px);
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="https://khanaldipesh.com.np/package/laravel-env-manager" target="_blank">LaravelENVManager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{config('envManager.returnUrl')}}">Return Back</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <form action="{{ route('laravel-env-manager.backup') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Backup .env</button>
    </form>
    <div class="card card-custom p-4">

        <h1 class="mb-4">Environment Variables Editor Form</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('laravel-env-manager.update') }}" method="POST">
            @csrf
            <div class="form-row">
                @foreach ($envVariables as $key => $value)
                    <div class="col-md-12 mb-3" id="variable-{{ $key }}">
                        <label for="{{ $key }}" class="form-label">{{ $key }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="{{ $key }}" name="variables[{{ $key }}]" value="{{ $value }}">
                            <button type="button" class="btn btn-danger" onclick="removeVariable('{{ $key }}')">Remove</button>
                        </div>
                    </div>
                @endforeach
                <div id="newVariablesContainer"></div>
                <button type="button" class="btn btn-success" onclick="addVariable()">Add New Variable</button>
            </div>
            <button type="submit" class="btn btn-custom mt-3"><i class="fas fa-save"></i> Save Changes</button>
        </form>
    </div>
</div>
<script>
    function addVariable() {
        const variableName = prompt("Enter the name of the new variable:");
        if (variableName === null || variableName.trim() === "") {
            alert("Variable name is required.");
            return; // Exit if no name is provided
        }

        const variableValue = prompt("Enter the value of the new variable:");
        if (variableValue === null || variableValue.trim() === "") {
            alert("Variable value is required.");
            return; // Exit if no value is provided
        }

        const uniqueId = Date.now(); // Use timestamp for a unique ID
        let html = `<div class="input-group mb-3" id="newVariable-${uniqueId}">
                    <input type="text" class="form-control" name="newVariables[${variableName}]" placeholder="${variableName}=VALUE" value="${variableName}=${variableValue}">
                    <button type="button" class="btn btn-danger" onclick="removeNewVariable('newVariable-${uniqueId}')">Remove</button>
                </div>`;

        // Insert the new variable input above the "Add Variable" button
        const addVariableButton = document.querySelector("#newVariablesContainer");
        addVariableButton.insertAdjacentHTML('beforebegin', html);
    }

    function removeVariable(key) {
        document.getElementById('variable-' + key).remove();
    }

    function removeNewVariable(id) {
        document.getElementById(id).remove();
    }
</script>
</body>
</html>
