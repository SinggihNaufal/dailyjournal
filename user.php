<?php
include "koneksi.php";

// Handle Add, Edit, and Delete Requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Add User
        if ($action == 'add') {
            $username = trim($_POST['username']);
            $password = md5($_POST['password']);
            $role = trim($_POST['role']);
            $photo = $_FILES['photo']['name'];
            $target = __DIR__ . "/img/" . basename($photo);

            if (empty($username) || empty($password) || empty($role)) {
                die("All fields are required.");
            }

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $file_type = mime_content_type($_FILES['photo']['tmp_name']);

                if (in_array($file_type, $allowed_types)) {
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
                        $stmt = $conn->prepare("INSERT INTO users (username, password, role, photo) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("ssss", $username, $password, $role, $photo);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        die("Failed to upload the file.");
                    }
                } else {
                    die("Invalid file type. Only JPG, PNG, and GIF are allowed.");
                }
            } else {
                die("No file uploaded or upload error.");
            }
        }

        // Edit User
        if ($action == 'edit') {
            $id = intval($_POST['id']);
            $username = trim($_POST['username']);
            $role = trim($_POST['role']);
            $photo = $_FILES['photo']['name'];
            $target = __DIR__ . "/img/" . basename($photo);

            if (empty($username) || empty($role)) {
                die("All fields are required.");
            }

            if (!empty($photo)) {
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
                    $stmt = $conn->prepare("UPDATE users SET username=?, role=?, photo=? WHERE id=?");
                    $stmt->bind_param("sssi", $username, $role, $photo, $id);
                } else {
                    die("Failed to upload the file.");
                }
            } else {
                $stmt = $conn->prepare("UPDATE users SET username=?, role=? WHERE id=?");
                $stmt->bind_param("ssi", $username, $role, $id);
            }
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin.php?page=user");
    exit();
}

// Pagination Logic
$items_per_page = 4;
$current_page = isset($_GET['page_number']) ? max(1, intval($_GET['page_number'])) : 1;
$offset = ($current_page - 1) * $items_per_page;

$stmt = $conn->prepare("SELECT * FROM users LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $items_per_page);
$stmt->execute();
$result = $stmt->get_result();

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
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td>
                        <?php if ($row['photo']): ?>
                            <img src="img/<?= htmlspecialchars($row['photo']) ?>" width="50" height="50" onerror="this.src='img/default.png'">
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
                                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($row['username']) ?>" required>
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
