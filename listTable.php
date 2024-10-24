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
        <div class="row d-flex justify-content-md-between row-cols-1 row-cols-sm-2">
            <div class="col">
                <h4 class="my-sm-0 font-weight-normal">Lists Overview</h4>
            </div>
            <div class="col d-flex justify-content-sm-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#listCreateModal">
                    Create A List
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php require 'createList.php'; ?>

        <div class="accordion" id="accordionList">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        My Lists
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionList">
                    <div class="accordion-body">
                        <li class="list-group-item">
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
                        </li>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>