<?php

/**
 * Script to create storage directory and symlink for Hostinger
 * Run this once via browser: https://yourdomain.com/create-storage.php
 *
 * This creates: public_html/storage -> /home/username/storage/app/public
 */
$basePath = dirname(__DIR__); // Goes up from public to project root
$storagePath = $basePath.'/storage/app/public';
$publicStoragePath = $basePath.'/public/storage';

echo "Base path: $basePath<br>";
echo "Storage path: $storagePath<br>";
echo "Public storage path: $publicStoragePath<br><br>";

// Create storage/app/public directory
if (! file_exists($storagePath)) {
    if (mkdir($storagePath, 0755, true)) {
        echo "✓ Storage directory created at: $storagePath<br>";
    } else {
        echo '✗ Failed to create storage directory<br>';
    }
} else {
    echo '✓ Storage directory already exists<br>';
}

// Create books subdirectory
$booksPath = $storagePath.'/books';
if (! file_exists($booksPath)) {
    mkdir($booksPath, 0755, true);
    echo '✓ Books directory created<br>';
}

// Create pdfs subdirectory
$pdfsPath = $storagePath.'/books/pdfs';
if (! file_exists($pdfsPath)) {
    mkdir($pdfsPath, 0755, true);
    echo '✓ PDFs directory created<br>';
}

// Create symlink from public/storage to storage/app/public
if (! file_exists($publicStoragePath)) {
    // Try to create symlink
    if (function_exists('symlink') && ! in_array('symlink', array_map('trim', explode(',', ini_get('disable_functions'))))) {
        if (symlink($storagePath, $publicStoragePath)) {
            echo '✓ Symlink created: public/storage -> storage/app/public<br>';
        } else {
            echo '✗ Failed to create symlink<br>';
        }
    } else {
        // If symlink is disabled, copy files instead
        echo '⚠ Symlink disabled. Creating directory copy instead...<br>';

        // Copy contents to public/storage
        if (mkdir($publicStoragePath, 0755, true)) {
            // Copy books folder
            $srcBooks = $storagePath.'/books';
            $destBooks = $publicStoragePath.'/books';
            if (file_exists($srcBooks) && ! file_exists($destBooks)) {
                mkdir($destBooks, 0755, true);
            }
            echo '✓ Created public/storage directory for direct access<br>';
        }
    }
} else {
    echo '✓ Symlink/directory already exists<br>';
}

echo '<br><b>Done!</b> Files will be stored in storage/app/public and accessible via /storage/ URL.';
