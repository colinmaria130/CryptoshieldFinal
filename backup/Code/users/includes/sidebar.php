 <aside id="sidebar" class="sidebar">

     <ul class="sidebar-nav" id="sidebar-nav">

         <li class="nav-item">
             <a class="nav-link <?php echo htmlspecialchars($page_name === "home" ? "" : "collapsed"); ?>" href="home.php?page=home">
                 <i class="bi bi-grid"></i>
                 <span> Home </span>
             </a>
         </li>

         <li class="nav-item">
             <a class="nav-link <?php echo htmlspecialchars($page_name === "crypto-logs" ? "" : "collapsed"); ?>" href="home.php?page=crypto-logs">
                 <i class="bi bi-clock-history"></i>
                 <span> Crypto Logs </span>
             </a>
         </li>

         <li class="nav-item">
             <a class="nav-link <?php echo htmlspecialchars($page_name === "uploaded-files" ? "" : "collapsed"); ?>" href="home.php?page=uploaded-files">
                 <i class="bi bi-file-earmark-check"></i>
                 <span> Uploaded Files </span>
             </a>
         </li>

         <li class="nav-item">
             <a class="nav-link <?php echo htmlspecialchars($page_name === "file-checker" ? "" : "collapsed"); ?>" href="home.php?page=file-checker">
                 <i class="bi bi-shield-exclamation"></i>
                 <span> File Checker </span>
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