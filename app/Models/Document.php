<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
	protected $table = "documents";
	protected $fillable = [
		"id",
		"title",
		"file_url",
		"like_count",
		"view_count",
		"is_solution",
		"is_video",
		"document_type_id",
		"module_name",
		"university_name",
		"academic_year",
		"academic_level",
		"faculty_name",
		"speciality_name",
		"user_id",
		"parent_id"
	];

	public function type(){
		return $this->belongsTo(DocumentType::class, "document_type_id");
	}

	public function parent(){
		return $this->belongsTo(Document::class , "parent_id");
	}
}