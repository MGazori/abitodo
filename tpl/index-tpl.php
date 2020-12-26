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
            <!-- show users folder -->
            <?php foreach ($folders as $folder) : ?>
              <li>
                <a href="?folder_id=<?= $folder->id ?>"><i class="fa fa-folder"></i><?= $folder->name ?></a>
                <a href="?delete_folder=<?= $folder->id ?>"><i class="fa fa-times removeFolderBtn"></i></a>
              </li>
            <?php endforeach; ?>
            <li class="active"> <i class="fa fa-folder-open"></i>Messages</li>
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
            <ul>
              <li class="checked"><i class="fa fa-check-square-o"></i><span>Update team page</span>
                <div class="info">
                  <div class="button green">In progress</div><span>Complete by 25/04/2014</span>
                </div>
              </li>
              <li><i class="fa fa-square-o"></i><span>Design a new logo</span>
                <div class="info">
                  <div class="button">Pending</div><span>Complete by 10/04/2014</span>
                </div>
              </li>
              <li><i class="fa fa-square-o"></i><span>Find a front end developer</span>
                <div class="info"></div>
              </li>
            </ul>
          </div>
          <!-- <div class="list">
            <div class="title">Tomorrow</div>
            <ul>
              <li><i class="fa fa-square-o"></i><span>Find front end developer</span>
                <div class="info"></div>
              </li>
            </ul> -->
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