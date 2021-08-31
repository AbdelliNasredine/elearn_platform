<?php


$app->get("/api/faculty/{id}/departments", function ($request, \Slim\Http\Response $response, $args) {

	$faculty_id = $args["id"];
	$faculty = \App\Models\Faculty::find($faculty_id);

	if (!$faculty) return $response->withStatus(404)->withJson(["err" => "Not found"]);

	return $response->withStatus(200)->withJson($faculty->departments);

});