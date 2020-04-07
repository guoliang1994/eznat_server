<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Base extends Model
{
    protected $fillable  = [];
    public function __construct(array $attributes = [])
    {
        if (php_sapi_name() != 'cli') {
            $field = DB::select("SHOW COLUMNS FROM {$this->table}");
            unset($field['id']); // 删除主键
            $this->fillable = array_column($field, "Field");
        }
        parent::__construct($attributes);
    }
    public function patchDelete($id)
    {
        $ids = explode(',', $id);
        $this::destroy($ids);
        return ['code' => 20000, 'msg' => '删除成功'];
    }
    public function updateOrCreate($input)
    {
        $id = $input['id'];
        $obj =  $this::find($id) ?: new $this;
        $obj->fill($input)->save();
    }
    public function scopeOfUser($query, $uid)
    {
        return $query->where('user.id', $uid);
    }
    public function scopeOfUserNameOrAccount($query, $searchText){
        return $query
            ->where('account', 'like', "%$searchText%")
            ->orWhere('user.name', 'like', "%$searchText%");
    }
}
