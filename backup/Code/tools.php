<main id="main" class="main">

    <?php
    include_once "includes/pagetitle.php";
    ?>

    <div class="container" id="form">

        <div class="row">

            <!-- Text Encryption (AES/RSA) -->
            <div class="col-md-12">

                <div class="card shadow mb-2">

                    <div class="card-header bg-dark text-white"> Encryption Tool </div>

                    <div class="card-body">

                        <form id="encryptionForm" class="mt-2">

                            <input type="hidden" name="user-id" value="<?php echo htmlspecialchars(base64_encode($user_id)); ?>">

                            <div class="mb-2">

                                <label class="form-label">Select Encryption Type:</label>

                                <select name="encryption_type" id="encryption_type" class="form-select">
                                    <option value="aes"> AES (AES-256-CBC) </option>
                                    <option value="rsa"> RSA (2048-bit) </option>
                                </select>

                            </div>

                            <div class="mb-3">
                                <label class="form-label"> Enter Text: </label>
                                <textarea name="text" class="form-control" rows="4" id="textField"></textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-dark" id="encryptBtn">Encrypt</button>
                                <button type="button" class="btn btn-outline-dark" id="decryptBtn">Decrypt</button>
                            </div>

                        </form>

                        <div class='result mt-3'>

                            <div class="d-flex gap-2">

                                <strong id="resultLabel"> </strong>

                                <span title="Copy Text" style="cursor: pointer; display: none;" onclick="copyToClipboard('resultText')" id="copyButton">
                                    <i class="bi bi-clipboard-fill"></i>
                                </span>

                                <p id="copyStatus" class="mb-0 text-success small"></p>

                            </div>

                            <span id="resultText"></span>
                        </div>

                    </div>

                </div>

            </div>

            <!-- File Encryption -->
            <div class="col-md-12">

                <div class="card shadow mb-4 mt-4">
                    <div class="card-header bg-dark text-white"> File Encryption </div>

                    <div class="card-body">

                        <!-- Encryption Form -->
                        <form action="../process/users/user-file-encryption.php" method="POST" enctype="multipart/form-data" class="mt-2">

                            <input type="hidden" name="action" value="Encrypt">

                            <div class="mb-3">

                                <label class="form-label"> Encrypt File: </label>
                                <input type="file" name="uploaded-file" class="form-control" required
                                    accept=".txt,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.csv,.json,.xml,.zip,.rar,.7z,.html,.php,.js,.css">

                            </div>

                            <button type="submit" class="btn btn-dark"> Upload & Encrypt </button>

                        </form>

                        <hr>

                        <!-- Decryption Form -->
                        <form action="../process/users/user-file-encryption.php" method="POST" enctype="multipart/form-data">

                            <input type="hidden" name="action" value="Decrypt">

                            <div class="mb-3">

                                <label class="form-label">Decrypt File:</label>
                                <input type="file" name="uploaded-file" class="form-control" required
                                    accept=".txt,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.csv,.json,.xml,.zip,.rar,.7z,.html,.php,.js,.css">
                            </div>

                            <button type="submit" class="btn btn-outline-dark"> Upload & Decrypt </button>

                        </form>

                    </div>
                </div>

            </div>

        </div>

    </div>

</main>