<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Usuario</title>
</head>
<body>
    <h1>User: {{ $user->name }}</h1>
    <p>Email: {{ $user->email }}</p>

    <h3>Attempts:</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>IP</th>
                <th>Device</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $user->login_attempts as  $attempt)
                <tr>
                    <td>{{ $attempt->id }}</td>
                    <td>{{ $attempt->ip_address }}</td>
                    <td>{{ $attempt->user_agent }}</td>
                    <td>{{ ucfirst($attempt->status) }}</td>
                    <td>{{ $attempt->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
