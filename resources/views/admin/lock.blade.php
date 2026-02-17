<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Screen Locked</title>
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        .lock-screen {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 450px;
            width: 90%;
            text-align: center;
            animation: slideIn 0.5s ease;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .lock-icon {
            font-size: 80px;
            color: #764ba2;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
        .user-info h2 {
            color: #333;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .user-info p {
            color: #666;
            margin-bottom: 30px;
        }
        .password-group {
            position: relative;
            margin-bottom: 20px;
        }
        .password-group input {
            padding-left: 45px;
            height: 50px;
            border-radius: 25px;
            border: 2px solid #e0e0e0;
            font-size: 16px;
            transition: all 0.3s;
        }
        .password-group input:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.1);
        }
        .password-group .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
        }
        .btn-unlock {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 40px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            width: 100%;
        }
        .btn-unlock:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .logout-link {
            display: block;
            margin-top: 20px;
            color: #999;
            text-decoration: none;
            transition: color 0.3s;
        }
        .logout-link:hover {
            color: #764ba2;
        }
        .alert-danger {
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="lock-screen">
        <div class="lock-icon">
            <i class="fa fa-lock"></i>
        </div>
        <div class="user-info">
            <h2>Screen Locked</h2>
            <p>@auth('web'){{ auth()->user()->name }}@endauth</p>
        </div>

        <form method="POST" action="{{ route('unlock') }}">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first('password') }}
                </div>
            @endif

            <div class="password-group">
                <span class="input-icon">
                    <i class="fa fa-key"></i>
                </span>
                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="Enter your password to unlock"
                       required
                       autocomplete="current-password"
                       autofocus>
            </div>

            <button type="submit" class="btn btn-unlock">
                <i class="fa fa-unlock-alt"></i> Unlock
            </button>
        </form>

        <a href="{{ route('logout') }}" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-sign-out"></i> Not you? Log out
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <script>
        // Prevent going back after locking
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };

        // Auto-focus on password input
        $(document).ready(function() {
            $('input[name="password"]').focus();
        });
    </script>
</body>
</html>
