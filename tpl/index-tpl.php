<?php defined('BASE_PATH') or die("Permision Denied"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= SITE_TITLE ?></title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />


</head>

<body>
  <div class="page">
    <div class="pageHeader">
      <div class="title"><a href="http://localhost/abitodo" style="color: #ffffff;"><?= SITE_TITLE ?></a></div>
      <div class="userPanel"><i class="fa fa-chevron-down"></i><span class="username">M.Gazori </span><img src="assets/img/user.jpeg" width="40" height="40" /></div>
    </div>
    <div class="main">
      <div class="nav">
        <div class="searchbox">
          <div><i class="fa fa-search"></i>
            <input type="search" placeholder="Search" />
          </div>
        </div>
        <div class="menu">
          <div class="title">Navigation</div>
          <ul id="foldersList">
            <li class="folderRow active" data-folder-id="all"><span class="folderTitle">All Tasks</span></li>
            <!-- show user folder -->
            <?php foreach ($folders as $folder) : ?>
              <li class="folderRow" data-folder-id="<?= $folder->id ?>">
                <span class="folderTitle" data-folder-id="<?= $folder->id ?>"><?= $folder->name ?></span>
                <button class="removeFolderBtn" data-folder-id="<?= $folder->id ?>"></button>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="addFolderBox">
          <form action="process/ajaxHandler.php" method="POST" id="addFolderForm">
            <input type="text" id="addFolderInput" placeholder="add folder here" autocomplete="off">
            <button class="addFolderbtn" title="add folder">
              <i class="fa fa-plus"></i>
            </button>
          </form>
        </div>
      </div>
      <div class="view">
        <div class="viewHeader">
          <div class="title">Manage Tasks</div>
          <div class="functions">
            <div class="button active">Add New Task</div>
            <div class="button">Completed</div>
            <div class="button inverz"><i class="fa fa-trash-o"></i></div>
          </div>
        </div>
        <div class="content">
          <div class="list">
            <div class="title">Today</div>
            <ul id="taskList">
              <!-- show user tasks -->
              <?php foreach ($tasks as $task) : ?>
                <li data-task-id="<?= $task->id ?>" <?= ($task->is_done) ? "class='checked taskRow'" : "class='taskRow'" ?>><i class="fa <?= ($task->is_done) ? "fa-check-square-o" : 'fa-square-o' ?>"></i><span><?= $task->title ?></span>
                  <div class="info">
                    <span class="task-created-at"><?= $task->created_at ?></span>
                    <button class="removeTaskBtn" data-task-id="<?= $task->id ?>"></button>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="assets/js/script.js"></script>
</body>

</html>