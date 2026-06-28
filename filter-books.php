<?php
include "db_conn.php";
include "php/func-book.php";

// Collect filters from POST
$filters = [
    'author_id'   => $_POST['author_id'] ?? null,
    'category_id' => $_POST['category_id'] ?? null,
    'year_min'    => $_POST['year_min'] ?? null,
    'year_max'    => $_POST['year_max'] ?? null,
    'search'      => $_POST['search'] ?? null,
    'sort'        => $_POST['sort'] ?? null
];

// Get filtered books using the updated function
$filteredBooks = get_filtered_books($conn, $filters);

// Return as JSON
header('Content-Type: application/json');
echo json_encode($filteredBooks);
