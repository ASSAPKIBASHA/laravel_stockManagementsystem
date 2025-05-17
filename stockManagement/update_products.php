<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();

if ($user) {
    $updated = App\Models\Product::whereNull('user_id')->update(['user_id' => $user->id]);
    echo "Updated {$updated} products.\n";
} else {
    echo "No users found.\n";
}
