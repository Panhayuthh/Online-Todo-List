<?php

    require_once('config.php');

    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    $searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : '';
    $params = [$userId];

    $countSql = "SELECT COUNT(*) as total FROM to_do_list WHERE user_id = ?";

    if ($searchQuery) {
        $countSql .= " AND title LIKE ?";
        $params[] = '%' . $searchQuery . '%';
    }

    $countStmt = $conn->prepare($countSql);
    $countStmt->execute($params);
    $totalLists = $countStmt->fetchColumn();
    $totalPages = ceil($totalLists / $limit);

    $sql = "SELECT id, title FROM to_do_list WHERE user_id = ?";

    $params = [$userId];

    if ($searchQuery) {
        $sql .= " AND title LIKE ?";
        $params[] = '%' . $searchQuery . '%';
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $lists = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="card mb-4 shadow-sm" id="tableCard">
    <div class="card-header">
        <h4 class="my-0 font-weight-normal">Lists Overview</h4>
    </div>
    <div class="card-body">
        <form method="get">
            <div class="row justify-content-md-between row-cols-1 row-cols-sm-2">
                <div class="mb-3 col-xl-6">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#listCreateModal">
                        Create A List
                    </button>
                </div>
                <div class="mb-3 col-sm-8 col-xl-4">
                    <input type="text" name="search_query" class="form-control w-100" placeholder="Search tasks..." value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
                </div>
                <div class="mb-3 col-sm-4 col-xl-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Apply</button>
                </div>
            </div>
        </form>

        <?php require 'createList.php'; ?>

        <div class="table-responsive">
            <table class="table table-bordered" id="taskTable">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lists as $list) { ?>
                        <tr>
                            <td><?= htmlspecialchars($list['title']) ?></td>
                            <td>
                                <div class="dropdown">
                                    <a class='link' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                        <i class='fas fa-ellipsis-v'></i>
                                    </a>
                                    <ul class='dropdown-menu'>
                                        <li><a class='dropdown-item' data-bs-toggle='modal' data-bs-target='#editListModal<?= $list['id'] ?>'>Edit List</a></li>
                                        <li><a class='dropdown-item' href='deleteList.php?id=<?= $list['id'] ?>' onclick='return confirm("Are you sure you want to delete this list?");'>Delete List</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>