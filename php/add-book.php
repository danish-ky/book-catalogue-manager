<?php  
session_start();

# Check if the admin is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {

    # Database Connection
    include "../db_conn.php";

    # Helper functions
    include "func-validation.php";
    include "func-file-upload.php";
    include "func-book.php"; // contains generateBookID()

    /** 
     * If all required Input fields are filled
     **/
    if (isset($_POST['book_title']) &&
        isset($_POST['book_description']) &&
        isset($_POST['book_author']) &&
        isset($_POST['book_category']) &&
        isset($_FILES['book_cover']) &&
        isset($_FILES['file'])) {

        // Generate unique Book ID
        $book_id = generateBookID($conn);

        // Get data from POST
        $title          = $_POST['book_title'];
        $description    = $_POST['book_description'];
        $author         = $_POST['book_author'];
        $category       = $_POST['book_category'];
        $publisher      = $_POST['book_publisher'] ?? '';
        $isbn           = $_POST['book_isbn'] ?? '';
        $pages          = $_POST['book_pages'] ?? null;
        $published_year = $_POST['published_year'] ?? null;

        // URL format for redirect with user input
        $user_input = 'title='.$title.'&category_id='.$category.'&desc='.$description.'&author_id='.$author.'&publisher='.$publisher.'&isbn='.$isbn.'&pages='.$pages.'&published_year='.$published_year;

        # Simple form Validation
        $fields = [
            "Book title"       => $title,
            "Book description" => $description,
            "Book author"      => $author,
            "Book category"    => $category
        ];

        foreach ($fields as $text => $value) {
            is_empty($value, $text, "../add-book.php", "error", $user_input);
        }

        # Upload Book Cover
        $allowed_image_exs = ["jpg", "jpeg", "png"];
        $cover_path = "cover";
        $book_cover = upload_file($_FILES['book_cover'], $allowed_image_exs, $cover_path);

        if ($book_cover['status'] == "error") {
            $em = $book_cover['data'];
            header("Location: ../add-book.php?error=$em&$user_input");
            exit;
        }

        # Upload Book File
        $allowed_file_exs = ["pdf", "docx", "pptx"];
        $file_path = "files";
        $file = upload_file($_FILES['file'], $allowed_file_exs, $file_path);

        if ($file['status'] == "error") {
            $em = $file['data'];
            header("Location: ../add-book.php?error=$em&$user_input");
            exit;
        }

        # Get uploaded file paths
        $book_cover_URL = $book_cover['data'];
        $file_URL       = $file['data'];

        # Insert data into database
        $sql  = "INSERT INTO books (
                    book_id,
                    title,
                    author_id,
                    description,
                    category_id,
                    publisher,
                    isbn,
                    pages,
                    published_year,
                    cover,
                    file
                 ) VALUES (?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $conn->prepare($sql);
        $res  = $stmt->execute([
            $book_id,
            $title,
            $author,
            $description,
            $category,
            $publisher,
            $isbn,
            $pages,
            $published_year,
            $book_cover_URL,
            $file_URL
        ]);

        if ($res) {
            $sm = "The book successfully created!";
            header("Location: ../add-book.php?success=$sm");
            exit;
        } else {
            $em = "Unknown Error Occurred!";
            header("Location: ../add-book.php?error=$em&$user_input");
            exit;
        }

    } else {
        header("Location: ../admin.php");
        exit;
    }

} else {
    header("Location: ../login.php");
    exit;
}
?>
