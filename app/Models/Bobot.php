<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Bobot extends Model
{
    use HasFactory;
    protected $table = 'bobots';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Ambil semua kolom kecuali id_penilaian
        $columns = Schema::getColumnListing($this->table);
        $this->fillable = array_diff($columns, [$this->primaryKey]);
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id','id');
    }
}
