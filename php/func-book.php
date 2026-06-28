<?php
// func-book.php
// Safe version: prevents redeclaration errors

if (!function_exists('get_all_books')) {
    function get_all_books($conn) {
        $sql = "SELECT b.*, a.name AS author_name, c.name AS category_name
                FROM books b
                LEFT JOIN authors a ON b.author_id = a.id
                LEFT JOIN categories c ON b.category_id = c.id
                ORDER BY b.id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $books ? $books : [];
    }
}

if (!function_exists('get_book_by_id')) {
    function get_book_by_id($conn, $id) {
        $sql = "SELECT * FROM books WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('update_book')) {
    function update_book($conn, $id, $data) {
        $sql = "UPDATE books SET 
                title = :title,
                author_id = :author_id,
                description = :description,
                category_id = :category_id,
                publisher = :publisher,
                isbn = :isbn,
                pages = :pages,
                published_year = :published_year,
                cover = :cover,
                file = :file
                WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':title' => $data['title'],
            ':author_id' => $data['author_id'],
            ':description' => $data['description'],
            ':category_id' => $data['category_id'],
            ':publisher' => $data['publisher'],
            ':isbn' => $data['isbn'],
            ':pages' => $data['pages'],
            ':published_year' => $data['published_year'],
            ':cover' => $data['cover'],
            ':file' => $data['file'],
            ':id' => $id
        ]);
        return $stmt->rowCount() > 0;
    }
}

if (!function_exists('delete_book')) {
    function delete_book($conn, $id) {
        $sql = "DELETE FROM books WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}

if (!function_exists('generateBookID')) {
    function generateBookID($conn) {
        do {
            $datePart = date("Ymd");
            $randomPart = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $bookID = "BK" . $datePart . $randomPart;

            $stmt = $conn->prepare("SELECT COUNT(*) FROM books WHERE book_id = ?");
            $stmt->execute([$bookID]);
            $exists = $stmt->fetchColumn();
        } while ($exists > 0);

        return $bookID;
    }
}

if (!function_exists('get_books_by_author')) {
    function get_books_by_author($conn, $author_id) {
        $sql = "SELECT b.*, a.name AS author_name, c.name AS category_name
                FROM books b
                LEFT JOIN authors a ON b.author_id = a.id
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.author_id = ?
                ORDER BY b.id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$author_id]);
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $books ? $books : [];
    }
}

if (!function_exists('get_books_by_category')) {
    function get_books_by_category($conn, $category_id) {
        $sql = "SELECT b.*, a.name AS author_name, c.name AS category_name
                FROM books b
                LEFT JOIN authors a ON b.author_id = a.id
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.category_id = ?
                ORDER BY b.id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$category_id]);
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $books ? $books : [];
    }
}

// ==============================
// Fixed search_books function
// ==============================
if (!function_exists('search_books')) {
    /**
     * Search books by title, author name, or category name
     * @param PDO $conn
     * @param string $keyword
     * @return array
     */
    function search_books($conn, $keyword) {
        $likeKey = "%$keyword%";
        $sql = "SELECT b.*, a.name AS author_name, c.name AS category_name
                FROM books b
                LEFT JOIN authors a ON b.author_id = a.id
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.title LIKE :title
                   OR b.description LIKE :description
                   OR a.name LIKE :author
                   OR c.name LIKE :category
                ORDER BY b.id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':title' => $likeKey,
            ':description' => $likeKey,
            ':author' => $likeKey,
            ':category' => $likeKey
        ]);
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $books ?: [];
    }
}
?>
