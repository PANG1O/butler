<?php
namespace App\Models;

class UserModel extends Base {
    protected static string $table = 'users';
    protected static array $fields = [
        'id',
        'username',
        'email',
        'password',
        'active',
        'deleted',
        'created',
        'updated'
    ];
}
