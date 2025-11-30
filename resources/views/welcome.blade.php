<!doctype html>
<html lang="es" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    @vite('resources/css/app.css')
</head>

<body class="flex items-center justify-center h-screen bg-blue-400">
    <div class="card w-96 shadow-xl bg-blue-100">
        <!-- Login card -->
        <div class="card-body ml-5 mr-5 mb-3 mt-3">
            <h2 class="card-title text-center text-3xl font-bold flex justify-center items-center">Welcome</h2>
            <p class="text-center mb-10">Log into your account</p>
            <form class="flex flex-col gap-4">
                <!-- Username -->
                <div class="form-control">
                    <input type="text" placeholder="Username" class="input input-md rounded-3xl w-full p-5 mb-1"
                        required>
                </div>
                <!-- Password -->
                <div class="form-control">
                    <input type="password" placeholder="Password" class="input input-md rounded-3xl w-full p-5"
                        required>
                </div>
                <div class="flex items-center justify-between mt-2">
                    <label class="label cursor-pointer flex gap-2">
                        <input type="checkbox" checked="checked" class="toggle toggle-sm toggle-primary" />
                        <span class="label-text text-gray-500">Remember me</span>
                    </label>

                    <a href="#" class="link link-hover text-sm text-gray-500">Forgot password?</a>
                </div>

                <!-- Button -->
                <div class="form-control mt-7 flex justify-center items-center">
                    <button type="submit" class="btn btn-primary p-5 rounded-4xl w-full">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>