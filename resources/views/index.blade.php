<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página DaisyUI</title>
  @vite('resources/js/app.js')
</head>
<body class="bg-base-200">

  <!-- Navbar -->
  <div class="navbar bg-base-100 shadow-lg">
    <div class="flex-1 px-4">
      <a class="text-2xl font-bold text-primary">MiProyecto</a>
    </div>
    <div class="flex-none gap-2">
      <a class="btn btn-ghost">Inicio</a>
      <a class="btn btn-ghost">Servicios</a>
      <a class="btn btn-ghost">Contacto</a>
    </div>
  </div>

  <!-- Hero -->
  <div class="hero min-h-screen bg-gradient-to-r from-primary to-secondary text-white">
    <div class="hero-content text-center">
      <div class="max-w-md">
        <h1 class="text-5xl font-bold mb-5">Bienvenido a DaisyUI</h1>
        <p class="mb-5">Este es un ejemplo completo usando solo DaisyUI para que compruebes que funciona localmente.</p>
        <button class="btn btn-accent btn-lg">Comenzar</button>
      </div>
    </div>
  </div>

  <!-- Características -->
  <section class="py-16">
    <div class="text-center mb-12">
      <h2 class="text-4xl font-bold text-primary">Características</h2>
      <p class="text-gray-500">DaisyUI hace que Tailwind sea mucho más fácil de usar</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-8">
      <div class="card bg-base-100 shadow-xl hover:scale-105 transition-transform duration-300">
        <div class="card-body">
          <h3 class="card-title">Fácil de usar</h3>
          <p>Clases predefinidas para componentes comunes.</p>
        </div>
      </div>
      <div class="card bg-base-100 shadow-xl hover:scale-105 transition-transform duration-300">
        <div class="card-body">
          <h3 class="card-title">Responsive</h3>
          <p>Se adapta a cualquier tamaño de pantalla de forma automática.</p>
        </div>
      </div>
      <div class="card bg-base-100 shadow-xl hover:scale-105 transition-transform duration-300">
        <div class="card-body">
          <h3 class="card-title">Personalizable</h3>
          <p>Cambia colores, botones, inputs y mucho más fácilmente.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Formulario de contacto -->
  <section class="py-16 bg-base-100">
    <div class="max-w-lg mx-auto">
      <h2 class="text-3xl font-bold text-center text-primary mb-6">Contáctanos</h2>
      <form class="space-y-4">
        <div class="form-control">
          <label class="label">
            <span class="label-text">Nombre</span>
          </label>
          <input type="text" placeholder="Tu nombre" class="input input-bordered w-full" />
        </div>
        <div class="form-control">
          <label class="label">
            <span class="label-text">Correo</span>
          </label>
          <input type="email" placeholder="correo@ejemplo.com" class="input input-bordered w-full" />
        </div>
        <div class="form-control">
          <label class="label">
            <span class="label-text">Mensaje</span>
          </label>
          <textarea placeholder="Escribe tu mensaje" class="textarea textarea-bordered w-full"></textarea>
        </div>
        <button class="btn btn-primary w-full">Enviar</button>
      </form>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer footer-center p-10 bg-base-200 text-base-content rounded-t-xl">
    <div>
      <p>© 2025 MiProyecto. Todos los derechos reservados.</p>
    </div>
  </footer>

</body>
</html>
