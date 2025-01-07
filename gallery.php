<?php
include "koneksi.php";

// Handle Add, Edit, and Delete Requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Add Gallery
        if ($action == 'add') {
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $image = $_FILES['image']['name'];
            $target = "image/" . basename($image);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $query = "INSERT INTO gallery (title, image) VALUES ('$title', '$image')";
                mysqli_query($conn, $query);
            }
        }

        // Edit Gallery
        if ($action == 'edit') {
            $id = intval($_POST['id']);
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $image = $_FILES['image']['name'];
            $target = "image/" . basename($image);

            if (!empty($image)) {
                move_uploaded_file($_FILES['image']['tmp_name'], $target);
                $query = "UPDATE gallery SET title='$title', image='$image' WHERE id=$id";
            } else {
                $query = "UPDATE gallery SET title='$title' WHERE id=$id";
            }
            mysqli_query($conn, $query);
        }
    }
}

// Handle Delete Request
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $query = "DELETE FROM gallery WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: admin.php?page=gallery");
    exit();
}

// Paginasi
$items_per_page = 4;
$current_page = isset($_GET['page_number']) ? intval($_GET['page_number']) : 1;
$offset = ($current_page - 1) * $items_per_page;

// Fetch Data with Pagination
$query = "SELECT * FROM gallery LIMIT $offset, $items_per_page";
$result = mysqli_query($conn, $query);

// Fetch Total Items
$total_items_query = "SELECT COUNT(*) AS total FROM gallery";
$total_items_result = mysqli_query($conn, $total_items_query);
$total_items = mysqli_fetch_assoc($total_items_result)['total'];
$total_pages = ceil($total_items / $items_per_page);
?>

<div class="container mt-4">
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addGalleryModal">+ Gallery</button>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = $offset + 1; ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><img src="image/<?= htmlspecialchars($row['image']) ?>" width="100" alt=""></td>
                    <td>
                        <button
                            class="btn btn-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#editGalleryModal<?= $row['id'] ?>">
                            Edit
                        </button>
                        <a
                            href="?page=gallery&delete_id=<?= $row['id'] ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure?')">
                            Delete
                        </a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editGalleryModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Gallery</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Judul</label>
                                        <input type="text" name="title" class="form-control" value="<?= $row['title'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Gambar</label>
                                        <input type="file" name="image" class="form-control">
                                        <small>Leave blank to keep the current image</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Paginasi -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=gallery&page_number=<?= $current_page - 1 ?>">Previous</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=gallery&page_number=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=gallery&page_number=<?= $current_page + 1 ?>">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addGalleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Gallery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Tambah Gallery</button>
                </div>
            </form>
        </div>
    </div>
</div>
