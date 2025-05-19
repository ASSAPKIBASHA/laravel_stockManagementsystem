<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
@vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
  <title>Login</title>
</head>
<body>



<div class="flex items-center justify-center min-h-screen bg-gray-100">
  <form action="{{url('/login')}}" method="post">
              @csrf
              <h2 class="text-2xl  font-bold mb-4 text-center">Stock Manangement System </h2>
              <h2 class="text-2xl  font-bold mb-4 text-center">Login</h2>
                  @if(session('success'))
        <div class="mb-4 text-green-600">
            {{ session('success') }}
        </div>
    @endif
                  @if($errors->any())
        <div class="mb-4 text-red-600">
            {{ $errors->first() }}
        </div>
    @endif
              <div class="mb-4">
                            <label class="block mb-1"  for="username">Username:</label>
                            <input type="text" name="name" class="input-style" required>
              </div>
               <div class="mb-4">
                            <label class="block mb-1"  for="email">Email:</label>
                            <input type="email" name="email" class="input-style" required>
              </div>
              <div class="mb-4">
                            <label class="block mb-1"  for="password">Password:</label>
                            <input type="password" name="password" class="input-style" required>
              </div>
              <button type="submit" class="btn-style w-full">Login</button>
              <div class="mt-4"><p>don't have an account you can.
                            <a href="{{url('/register')}}" class="text-blue-500 hover:underline">Register</a></p>
              </div>
  </form>
</div>

</body>
</html>