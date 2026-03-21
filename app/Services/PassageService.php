<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * Passage Service
 * 
 * This follows the Single Responsibility Principle (SRP) -
 * This class is only responsible for managing passage/text files.
 */
class PassageService
{
    /**
     * Path to passages directory
     */
    protected string $passagesPath;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->passagesPath = resource_path('passages');
    }

    /**
     * Get all available passages
     * 
     * @return array Array of passages with filename as key and content as value
     */
    public function getAllPassages(): array
    {
        $passages = [];
        
        if (!is_dir($this->passagesPath)) {
            Log::warning("Passages directory not found: {$this->passagesPath}");
            return $passages;
        }

        $files = File::files($this->passagesPath);
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'txt') {
                $filename = $file->getFilenameWithoutExtension();
                $content = File::get($file->getPathname());
                $passages[$filename] = [
                    'filename' => $filename,
                    'name' => $this->formatName($filename),
                    'content' => $content,
                    'path' => $file->getPathname()
                ];
            }
        }

        return $passages;
    }

    /**
     * Get a specific passage by filename
     * 
     * @param string|int $filename The filename or key of the passage
     * @return string|null
     */
    public function getPassage($filename): ?string
    {
        // Cast to string to handle integer keys from dropdowns
        $stringFilename = (string) $filename;
        $path = "{$this->passagesPath}/{$stringFilename}.txt";
        
        if (!file_exists($path)) {
            Log::warning("Passage not found: {$path}");
            return null;
        }
        
        return File::get($path);
    }

    /**
     * Get passage names for dropdown selection
     * 
     * @return array
     */
    public function getPassageNames(): array
    {
        $passages = $this->getAllPassages();
        $names = [];
        
        foreach ($passages as $key => $passage) {
            // Ensure key is always a string
            $stringKey = (string) $key;
            $names[$stringKey] = $passage['name'];
        }
        
        return $names;
    }

    /**
     * Get a specific passage name by key (supports both string and integer keys)
     * 
     * This method handles the conversion of integer keys from dropdown selections
     * to string keys used in the internal array.
     * 
     * @param string|int $key The passage key from request
     * @param string|null $default Default value if key not found
     * @return string|null
     */
    public function getPassageName($key, ?string $default = null): ?string
    {
        logger("PassageService: getPassageName called with key: " . var_export($key, true) . " (type: " . gettype($key) . ")");
        
        if ($key === null || $key === '') {
            logger("PassageService: key is null or empty, returning default: {$default}");
            return $default;
        }
        
        // Cast to string to handle integer keys from dropdowns
        $stringKey = (string) $key;
        logger("PassageService: converted key to string: '{$stringKey}'");
        
        $passageNames = $this->getPassageNames();
        logger("PassageService: available passage names: " . json_encode($passageNames));
        
        // Check if key exists
        if (!array_key_exists($stringKey, $passageNames)) {
            logger("PassageService: key not found, available keys: " . json_encode(array_keys($passageNames)));
            return $default;
        }
        
        $result = $passageNames[$stringKey];
        logger("PassageService: result: {$result}");
        
        return $result;
    }

    /**
     * Format filename to readable name
     * 
     * @param string $filename
     * @return string
     */
    protected function formatName(string $filename): string
    {
        // Convert underscores and hyphens to spaces
        $name = str_replace(['_', '-'], ' ', $filename);
        
        // Capitalize each word
        $name = ucwords($name);
        
        return $name;
    }

    /**
     * Check if passages directory exists
     * 
     * @return bool
     */
    public function hasPassages(): bool
    {
        return is_dir($this->passagesPath) && count(File::files($this->passagesPath)) > 0;
    }

    /**
     * Get passage count
     * 
     * @return int
     */
    public function count(): int
    {
        if (!is_dir($this->passagesPath)) {
            return 0;
        }

        return count(File::files($this->passagesPath));
    }
}
