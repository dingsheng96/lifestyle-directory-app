<?php

namespace App\Models;

use App\Models\Project;
use App\Models\ProjectService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $table = 'services';

    protected $fillable = ['name', 'description'];

    // Relationships
    public function projects()
    {
        return $this->belongsToMany(Project::class, ProjectService::class, 'service_id', 'project_id', 'id', 'id');
    }

    // Attributes
    public function getNameWithProjectCountAttribute()
    {
        if ($this->projects_count > 0) {

            return $this->name . ' (' . $this->projects_count . ')';
        }

        return $this->name;
    }
}
