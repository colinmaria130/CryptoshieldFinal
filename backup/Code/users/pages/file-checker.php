<?php
// File type restriction function
function isRestrictedFileType($filename, $mimeType)
{
    $blockedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'mp4', 'mov', 'avi', 'mkv', 'flv', 'webm'];
    $blockedMimes = ['image/', 'video/'];

    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (in_array($extension, $blockedExtensions)) return true;

    foreach ($blockedMimes as $mime) {
        if (strpos($mimeType, $mime) === 0) return true;
    }

    return false;
}

// Handle file check
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['file-to-check']) && $_FILES['file-to-check']['error'] === UPLOAD_ERR_OK) {

    $user_id = isset($_SESSION["user-id"]) ? htmlspecialchars(trim($_SESSION["user-id"])) : null;
    $file_name = basename($_FILES["file-to-check"]["name"]);
    $mime_type = mime_content_type($_FILES["file-to-check"]["tmp_name"]);

    if (isRestrictedFileType($file_name, $mime_type)) {
        $result = [
            "status" => "error",
            "text-color" => "danger",
            "message" => "File type not allowed! Please try again."
        ];
    } else {
        $file_content = file_get_contents($_FILES["file-to-check"]["tmp_name"]);

        $suspicious_patterns = [
            '/<script.*?>.*?<\/script>/is' => 'HTML <script> tag',
            '/<\s*iframe/i'               => 'HTML <iframe> tag',
            '/<\s*style/i'                => 'HTML <style> tag',
            '/<\s*(form|input|button|select)/i' => 'HTML form element',
            '/eval\s*\(/i'                => 'PHP eval() usage',
            '/base64_decode\s*\(/i'       => 'base64_decode() usage',
            '/shell_exec\s*\(/i'          => 'shell_exec() usage',
            '/system\s*\(/i'              => 'system() call',
            '/passthru\s*\(/i'            => 'passthru() call',
            '/exec\s*\(/i'                => 'exec() call',
            '/mysqli?_.*?\(/i'            => 'MySQLi or MySQL function',
            '/\bSELECT\b/i'               => 'SQL SELECT statement',
            '/\bINSERT\b/i'               => 'SQL INSERT statement',
            '/\bUPDATE\b/i'               => 'SQL UPDATE statement',
            '/\bDELETE\b/i'               => 'SQL DELETE statement',
            '/\bDROP\b/i'                 => 'SQL DROP statement',
        ];

        $suspicious_matches = [];

        foreach ($suspicious_patterns as $pattern => $description) {
            if (preg_match($pattern, $file_content)) {
                $suspicious_matches[] = $description;
            }
        }

        if (empty($suspicious_matches)) {
            $result = [
                "status" => "clean",
                "text-color" => "success",
                "message" => "No malicious codes/patterns found."
            ];
        } else {
            $result = [
                "status" => "suspicious",
                "text-color" => "danger",
                "message" => "Suspicious codes/patterns found!",
                "matches" => $suspicious_matches
            ];
        }
    }
}
?>

<main class="main" id="main">
    <?php include_once "includes/pagetitle.php"; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-dark text-white"> File Checker </div>
                <div class="card-body">

                    <!-- Form -->
                    <form method="POST" enctype="multipart/form-data" class="mt-2">
                        <div class="mb-3">
                            <label class="form-label"> Select File to Check: </label>
                            <input type="file" name="file-to-check" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-dark"> Check File </button>
                    </form>

                    <!-- Result -->
                    <?php if (!empty($result)): ?>
                        <div class="mt-4" id="result-div">
                            <div class="alert alert-<?php echo htmlspecialchars($result['text-color']); ?>">
                                <h5 class="alert-heading">Scan Result: <?php echo ucfirst($result['status']); ?></h5>
                                <p><?php echo htmlspecialchars($result['message']); ?></p>

                                <?php if (!empty($result['matches'])): ?>
                                    <hr>
                                    <p><strong>Detected Patterns:</strong></p>
                                    <ul class="mb-0">
                                        <?php foreach ($result['matches'] as $match): ?>
                                            <li><?php echo htmlspecialchars($match); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</main>
