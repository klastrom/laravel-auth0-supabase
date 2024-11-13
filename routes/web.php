<?php

use Auth0\Laravel\Facade\Auth0;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Services\SupabaseService;

Route::get('/private', function () {
  if (! auth()->check()) {
    return redirect('/public');
  }
  return response('Welcome! You are logged in.');
})->middleware('auth');

Route::get('/scope', function () {
  if (! auth()->check()) {
    return redirect('/public');
  }
  return response('You have `read:messages` permission, and can therefore access this resource.');
})->middleware('auth')->can('read:messages');

Route::get('/', function () {
  if (! auth()->check()) {
    return redirect('/public');
  }

  $user = auth()->user();
  $name = $user->name ?? 'User';
  $email = $user->email ?? '';

  return response("Hello {$name}! Your email address is {$email}.");
});

Route::get('/colors', function () {
  if (! auth()->check()) {
    return redirect('/public');
  }

  $endpoint = Auth0::management()->users();

  $colors = ['red', 'blue', 'green', 'black', 'white', 'yellow', 'purple', 'orange', 'pink', 'brown'];

  $endpoint->update(
    id: auth()->id(),
    body: [
        'user_metadata' => [
            'color' => $colors[random_int(0, count($colors) - 1)]
        ]
    ]
  );

  $metadata = $endpoint->get(auth()->id()); // Retrieve the user's metadata.
  $metadata = Auth0::json($metadata); // Convert the JSON to a PHP array.

  $color = $metadata['user_metadata']['color'] ?? 'unknown';
  $name = auth()->user()->name;

  return response("Hello {$name}! Your favorite color is {$color}.");
})->middleware('auth');

Route::get('/public', function () {
    return view('public');
});


Route::get('/db-test', function (SupabaseService $supabaseService) {
    // Use SupabaseService to get data
    $response = $supabaseService->getData('web_links');
    
    if ($response->successful()) {
        $data = $response->json();
        // Process data
        var_dump($data);
    }

});