<?php  

# Get all Categories function
function get_all_categories($con) {
    $sql  = "SELECT * FROM categories";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

# Get category by ID
function get_category($con, $id) {
    $sql  = "SELECT * FROM categories WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

# Alias to match view-book.php calls
function get_category_by_id($con, $id){
    return get_category($con, $id);
}
