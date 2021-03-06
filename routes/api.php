<?php
$app->get("/api/faculty/{id}/departments", function ($request, \Slim\Http\Response $response, $args) {

	$faculty_id = $args["id"];
	$faculty = \App\Models\Faculty::find($faculty_id);

	if (!$faculty) return $response->withStatus(404)->withJson(["err" => "Not found"]);

	return $response->withStatus(200)->withJson($faculty->departments);

});

$app->post("/api/course/{course_id:[0-9]+}/chapters", function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
	$course_id = $args["course_id"];
	$course = \App\Models\Course::find($course_id);
	if (!$course) return $response->withStatus(404);
	\App\Models\Chapter::create([
		"name" => $request->getParam("chapter_name"),
		"course_id" => $course_id,
	]);
	return $response->withStatus(200);
});

$app->post("/api/course/{course_id:[0-9]+}/chapters/{chapter_id:[0-9]+}/lectures", function ($request, $response, $args) {
	$chapterId = $args["chapter_id"];
	\App\Models\Lecture::create([
		"name" => $request->getParam("name"),
		"content" => $request->getParam("content"),
		"video_url" => $request->getParam("video_url"),
		"lecture_format_id" => $request->getParam("format"),
		"chapter_id" => $chapterId,
	]);
	return $response->withStatus(200);
});