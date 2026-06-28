<?php
// php/edit-book.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit;
}

include_once "../db_conn.php";
include_once "../php/func-book.php";
include_once "../php/func-category.php";
include_once "../php/func-author.php";

function back_to_edit($book_id, $type, $msg) {
    $qs = http_build_query([$type => $msg]);
    header("Location: ../edit-book.php?id=" . urlencode($book_id) . "&" . $qs);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin.php");
    exit;
}

// REQUIRED: book_id
$book_id = $_POST['book_id'] ?? '';
if (!$book_id) {
    back_to_edit('', 'error', 'Invalid Book ID.');
}

// Fetch book
$book = get_book_by_id($conn, $book_id);
if (!$book) {
    back_to_edit($book_id, 'error', 'Book not found.');
}

// Inputs
$title          = trim($_POST['book_title'] ?? '');
$description    = trim($_POST['book_description'] ?? '');
$pages          = (int)($_POST['book_pages'] ?? 0);
$published_year = (int)($_POST['published_year'] ?? 0);
$publisher      = trim($_POST['book_publisher'] ?? '');
$author_id      = (int)($_POST['book_author'] ?? 0);
$category_id    = (int)($_POST['book_category'] ?? 0);
$isbn           = trim($_POST['book_isbn'] ?? '');
$current_cover  = $_POST['current_cover'] ?? '';
$current_file   = $_POST['current_file'] ?? '';

if ($title === '' || $description === '' || $author_id === 0 || $category_id === 0) {
    back_to_edit($book_id, 'error', 'Please fill all required fields.');
}

// Upload handling
$cover = $current_cover;
$file  = $current_file;
$maxImageBytes = 3*1024*1024;
$maxFileBytes  = 30*1024*1024;
$allowedImageExt = ['jpg','jpeg','png','gif','webp'];
$allowedDocExt   = ['pdf','epub','mobi'];

// Cover upload
if (!empty($_FILES['book_cover']['name']) && $_FILES['book_cover']['error'] === UPLOAD_ERR_OK) {
    if ($_FILES['book_cover']['size'] > $maxImageBytes) {
        back_to_edit($book_id,'error','Cover too large (max 3MB).');
    }
    $ext = strtolower(pathinfo($_FILES['book_cover']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedImageExt, true)) {
        back_to_edit($book_id,'error','Invalid cover type.');
    }
    $newCover = uniqid('cover_', true) . "." . $ext;
    $dest = "../uploads/cover/".$newCover;
    if (!move_uploaded_file($_FILES['book_cover']['tmp_name'], $dest)) {
        back_to_edit($book_id,'error','Failed to upload cover.');
    }
    if ($current_cover && file_exists("../uploads/cover/".$current_cover)) {
        @unlink("../uploads/cover/".$current_cover);
    }
    $cover = $newCover;
}

// File upload
if (!empty($_FILES['file']['name']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    if ($_FILES['file']['size'] > $maxFileBytes) {
        back_to_edit($book_id,'error','File too large (max 30MB).');
    }
    $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedDocExt, true)) {
        back_to_edit($book_id,'error','Invalid file type.');
    }
    $newFile = uniqid('book_', true).".".$ext;
    $dest = "../uploads/files/".$newFile;
    if (!move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
        back_to_edit($book_id,'error','Failed to upload book file.');
    }
    if ($current_file && file_exists("../uploads/files/".$current_file)) {
        @unlink("../uploads/files/".$current_file);
    }
    $file = $newFile;
}

// Update
$updateData = [
    'title'=>$title,
    'description'=>$description,
    'pages'=>$pages,
    'published_year'=>$published_year,
    'publisher'=>$publisher,
    'author_id'=>$author_id,
    'category_id'=>$category_id,
    'isbn'=>$isbn,
    'cover'=>$cover,
    'file'=>$file
];

$updated = update_book($conn,$book_id,$updateData);
if ($updated) {
    back_to_edit($book_id,'success','Book updated successfully.');
} else {
    back_to_edit($book_id,'error','Failed to update book.');
}
