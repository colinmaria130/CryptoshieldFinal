<div class="pagetitle">

    <?php if (isset($_SESSION["query-status"]) && isset($_SESSION["query-status"]) !== "") { ?>
        <div id="notification" class="alert alert-<?php echo htmlspecialchars($_SESSION["query-status"]["status"]); ?> text-center" role="alert">
            <?php echo htmlspecialchars($_SESSION["query-status"]["message"]); ?>
        </div>

        <?php unset($_SESSION["query-status"]); ?>
    <?php } ?>

    <h1>
        <?php echo htmlspecialchars($page_titles[$page_name]); ?>
    </h1>

    <nav>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php?page=dashboard"> Home </a></li>
            <li class="breadcrumb-item active">
                <?php echo htmlspecialchars($page_titles[$page_name]); ?>
            </li>
        </ol>

    </nav>

</div>