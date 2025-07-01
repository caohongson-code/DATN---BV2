<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageOption extends Model
{
    use HasFactory;

    protected $fillable = ['value'];

    protected $table = 'storages'; // Giữ nguyên bảng cũ
}
