<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Racing+Sans+One&display=swap" />
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <link rel="stylesheet" href="css/fitur.css" />
    <link rel="stylesheet" href="css/account.css" />

    <title>E-bike Monitoring!</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

</head>

<body>
    <div class="hamburger" onclick="toggleSidebar()">
        <i data-feather="menu"></i>
    </div>
    <!-- Sidebar start -->
    @include('partials.userside')
    <div class="account-container">
        <h1>Account Information</h1>

        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.updateEmail') }}" method="POST">
            @csrf
            <div class="account-info">
                <!-- Motor ID -->
                <p>Motor ID: <span id="motor-id">{{ $motorsId ?? '______' }}</span></p>
                <!-- Username -->
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username"
                        value="{{ Auth::guard('admin')->user()->username }}" readonly>
                </div>
                <!-- Email -->
                <div class="form-group">
                    <label for="current_email">Current Email:</label>
                    <input type="email" id="current_email" name="current_email"
                        value="{{ Auth::guard('admin')->user()->email }}" readonly>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your new email" required>
                </div>

                <!-- <div class="form-group">
                <input type="email" id="email" name="email" value="{{ Auth::guard('admin')->user()->email }}" readonly>
            </div> -->
                <!-- Enter Password -->
                <div class="form-group">
                    <label for="password">Enter Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <!-- <div class="form-group">
              <label for="password">Enter New Password:</label>
              <input type="password" id="password" name="password" placeholder="Enter your new password" required>
            </div> -->
                <!-- Reset Password -->
                <!-- <div class="form-group">
              <button type="button" id="reset-password-btn" class="reset-btn">Reset Password</button>
            </div> -->
                <!-- Save Changes Button -->
                <div class="form-group">
                    <button type="submit" id="save-changes-btn" class="save-btn">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        feather.replace();
    </script>

    <!-- Java script -->
    <script src="js/fiturjs.js"></script>
</body>

</html>