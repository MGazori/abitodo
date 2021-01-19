<?php defined('BASE_PATH') or die("Permision Denied"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= SITE_TITLE ?></title>
  <link rel="shortcut icon" href="<?= BASE_URL ?>assets/img/favicon-64.png">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<body>
  <div class="page">
    <div class="pageHeader">
      <div class="title"><a href="http://localhost/abitodo" style="color: #ffffff;"><?= SITE_TITLE ?></a></div>
      <div class="userPanel">
        <a class="logOut" href="<?= site_url('?logout=1') ?>" title="log out"><i class="fa fa-sign-out"></i></a>
        <span class="username"><?= $userInfo->name ?? "unknown" ?></span><img src="<?= $userInfo->profileImage ?? "assets/img/user.jpeg" ?>" width="40" height="40" />
      </div>
    </div>
    <div class="main">
      <div class="nav">
        <div class="searchbox">
          <div><i class="fa fa-search"></i>
            <input id="taskSeachInput" type="search" placeholder="Search" />
          </div>
        </div>
        <div class="menu">
          <div class="title">Folders</div>
          <ul id="foldersList">
            <li class="folderRow active" data-folder-id="all"><span class="folderTitle">All Tasks</span></li>
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
          <div class="addTask">
            <form action="process/ajaxHandler.php" method="POST" id="addTaskForm">
              <input type="text" id="addTaskInput" placeholder="add task here" autocomplete="off">
              <button class="addtaskbtn" title="add task">Add Task</button>
            </form>
          </div>
          <div class="filterBox">
            <button id="sort-undone" class="sortBtn" data-sort-mode="just-undone" title="sort task by undone"></button>
            <button id="sort-done" class="sortBtn" data-sort-mode="just-done" title="sort task by done"></button>
            <button id="sort-new" class="sortBtn" data-sort-mode="new-first" title="sort task by new first"></button>
            <button id="sort-old" class="sortBtn active" data-sort-mode="old-first" title="sort task by older first"></button>
          </div>
        </div>
        <div class="content">
          <div class="list">
            <div class="taskInfoTitle">
              <span class="taskInfoTitle">Title</span>
              <span class="taskInfoCreatedAt">Create Time</span>
            </div>
            <ul id="taskList">
              <?= $emptyTask ?? null ?>
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
  <div class="project-info-container">
    <div class="project-info">
      <h4>This Open Source Project Developed In Beta Version By <a href="https://mgazori.com/">Mohammad Gazori</a>. Go To <a href="https://github.com/MGazori/abitodo">GitHub</a></h4>
    </div>
  </div>
  <audio id='task-done-audio' src='assets/audio/taskRingtone.mp3'></audio>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="assets/js/script.js"></script>
</body>

</html>