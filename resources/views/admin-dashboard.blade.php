<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
</head>
<body>

  <h1>ERES EL ADMIN</h1>

  <!-- LOGOUT -->
  <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit">
          Cerrar sesión
      </button>
  </form>

</body>
</html>
