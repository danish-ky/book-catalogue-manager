<?php 

# Get all Authors function
function get_all_author($con){
    $sql  = "SELECT * FROM authors";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

# Get Author by ID function
function get_author($con, $id){
    $sql  = "SELECT * FROM authors WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

# Alias to match view-book.php calls
function get_author_by_id($con, $id){
    return get_author($con, $id);
}
