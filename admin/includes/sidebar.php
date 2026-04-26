 <aside id="sidebar" class="sidebar">

     <ul class="sidebar-nav" id="sidebar-nav">

         <li class="nav-item">
             <a class="nav-link <?php echo htmlspecialchars($page_name === "dashboard" ? "" : "collapsed"); ?>" href="home.php?page=dashboard">
                 <i class="bi bi-grid"></i>
                 <span> Dashboard </span>
             </a>
         </li>

         <li class="nav-item">
             <a class="nav-link <?php echo htmlspecialchars($page_name === "officials-accounts" ? "" : "collapsed"); ?>" href="home.php?page=officials-accounts">
                 <i class="bi bi-person-check"></i>
                 <span> Officials Accounts </span>
             </a>
         </li>

         <li class="nav-item">
             <a class="nav-link <?php echo htmlspecialchars($page_name === "files-and-folders" || $page_name === "folder-contents" || $page_name === "officials-folder-contents" ? "" : "collapsed"); ?>" href="home.php?page=files-and-folders">
                 <i class="bi bi-folder"></i>
                 <span> Files and Folders </span>
             </a>
         </li>

         <li class="nav-item">
             <a class="nav-link <?php echo htmlspecialchars($page_name === "user-uploaded-files" ? "" : "collapsed"); ?>" href="home.php?page=user-uploaded-files">
                 <i class="bi bi-file-earmark-check"></i>
                 <span> User Uploaded Files </span>
             </a>
         </li>

         <li class="nav-item">
             <a class="nav-link <?php echo htmlspecialchars($page_name === "file-sharing-logs" ? "" : "collapsed"); ?>" href="home.php?page=file-sharing-logs">
                 <i class="bi bi-share"></i>
                 <span> File Sharing Logs </span>
             </a>
         </li>

         <li class="nav-item">
             <a class="nav-link <?php echo htmlspecialchars($page_name === "archive" ? "" : "collapsed"); ?>" href="home.php?page=archive">
                 <i class="bi bi-archive"></i>
                 <span> Archive </span>
             </a>
         </li>
   
         <li class="nav-heading"> Profile </li>

         <li class="nav-item">
             <a class="nav-link <?php echo htmlspecialchars($page_name === "user-profile" ? "" : "collapsed"); ?>" href="home.php?page=user-profile">
                 <i class="bi bi-person"></i>
                 <span> My Profile </span>
             </a>
         </li>

         <li class="nav-item">
             <a class="nav-link collapsed" href="logout.php">
                 <i class="bi bi-box-arrow-left"></i>
                 <span> Logout </span>
             </a>
         </li>

     </ul>

 </aside>