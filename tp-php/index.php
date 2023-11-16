<?php
require __DIR__.'/vendor/autoload.php';

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Exemple de l'utilisation des variables du .env
$cloudfrontUrl = $_ENV['AWS_CLOUDFRONT_URL'];

// Local Vars
$uploadDir = 'storage/';
$uploadedFiles = [];

// Créer le dossier 'storage' s'il n'existe pas
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Gérer l'upload de fichier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $filePath = $uploadDir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        echo "Le fichier a été uploadé.";
    } else {
        echo "Erreur lors de l'upload du fichier.";
    }
}

// Lister les fichiers dans 'storage'
if ($handle = opendir($uploadDir)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry !== "." && $entry !== "..") {
            $uploadedFiles[] = $entry;
        }
    }
    closedir($handle);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload de Fichiers</title>
</head>
<body>
<h1>Upload de Fichiers</h1>
<form action="index.php" method="post" enctype="multipart/form-data">
    <input type="file" name="file" />
    <button type="submit">Upload</button>
</form>
<h2>Fichiers Uploadés</h2>
<?php foreach ($uploadedFiles as $file): ?>
    <a href="storage/<?php echo htmlspecialchars($file); ?>" target="_blank">
        <?php echo $file; ?>
    </a>
<?php endforeach; ?>
</body>
</html>
