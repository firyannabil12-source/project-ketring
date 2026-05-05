<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'adminbaru@gmail.com')->first();
if ($user) {
    $user->password = Hash::make('mamaiksan123');
    $user->save();
    echo "Password untuk adminbaru@gmail.com berhasil direset menjadi: mamaiksan123" . PHP_EOL;
} else {
    echo "User adminbaru@gmail.com tidak ditemukan." . PHP_EOL;
}
