<?php
include "koneksi.php";

// Handle Add, Edit, and Delete Requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Add User
        if ($action == 'add') {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $password = md5($_POST['password']);
            $role = mysqli_real_escape_string($conn, $_POST['role']);
            $photo = $_FILES['photo']['name'];
            $target = "image/" . basename($photo);

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
                $query = "INSERT INTO users (username, password, role, photo) VALUES ('$username', '$password', '$role', '$photo')";
                mysqli_query($conn, $query);
            }
        }

        // Edit User
        if ($action == 'edit') {
            $id = intval($_POST['id']);
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $role = mysqli_real_escape_string($conn, $_POST['role']);
            $photo = $_FILES['photo']['name'];
            $target = "image/" . basename($photo);

            if (!empty($photo)) {
                move_uploaded_file($_FILES['photo']['tmp_name'], $target);
                $query = "UPDATE users SET username='$username', role='$role', photo='$photo' WHERE id=$id";
            } else {
                $query = "UPDATE users SET username='$username', role='$role' WHERE id=$id";
            }
            mysqli_query($conn, $query);
        }
    }
}

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $query = "DELETE FROM users WHERE id=$id";
    mysqli_query($conn, $query);
    header("Location: admin.php?page=user");
    exit();
}

// Pagination Logic
$items_per_page = 4;
$current_page = isset($_GET['page_number']) ? intval($_GET['page_number']) : 1;
$offset = ($current_page - 1) * $items_per_page;

$query = "SELECT * FROM users LIMIT $offset, $items_per_page";
$result = mysqli_query($conn, $query);

$total_query = "SELECT COUNT(*) AS total FROM users";
$total_result = mysqli_query($conn, $total_query);
$total_items = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_items / $items_per_page);
?>

<!-- Konten Manajemen User -->
<div class="container mt-4">
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Add User</button>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Role</th>
                <th>Photo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = $offset + 1; ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td>
                        <?php if ($row['photo']): ?>
                            <img src="image/<?= htmlspecialchars($row['photo']) ?>" width="50" height="50">
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $row['id'] ?>">Edit</button>
                        <a href="?page=user&delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="editUserModal<?= $row['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5>Edit User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <div class="mb-3">
                                        <label>Username</label>
                                        <input type="text" name="username" class="form-control" value="<?= $row['username'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Role</label>
                                        <select name="role" class="form-control" required>
                                            <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                            <option value="user" <?= $row['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Photo</label>
                                        <input type="file" name="photo" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=user&page_number=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<!-- Modal Add User -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5>Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Photo</label>
                        <input type="file" name="photo" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

