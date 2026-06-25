<?php

require_once '../src/Auth.php';
require_once '../src/DosenRepository.php';
require_once '../src/Helper.php';

Auth::check();
Auth::requireAdmin();

$id = (int) $_GET['id'];

$repo = new DosenRepository();

$repo->softDelete($id);

Helper::redirect('index.php');