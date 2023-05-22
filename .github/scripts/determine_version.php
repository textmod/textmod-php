<?php
$tags = explode("\n", shell_exec("git tag"));
rsort($tags, SORT_NATURAL);

$nextVersion = "";

$inputVersion = $argv[1];
$branch = $argv[2];

// Validate the branch input
if ($branch !== "php7" && $branch !== "php8") {
    echo "Invalid branch. Please provide either 'php7' or 'php8' as the branch.";
    return;
}

if (empty($tags)) {
    // No existing tags, set a default starting version based on the branch
    $nextVersion = match ($branch) {
        "php7" => "7.0.0",
        default => "8.0.0",
    };
} else {
    // Find the highest version without the branch suffix
    foreach ($tags as $tag) {
        if (preg_match('/^(\d+\.\d+\.\d+)$/', $tag, $matches)) {
            $currentVersion = $matches[1];
            $nextVersion = $currentVersion;
            break;
        }
    }

    if (empty($nextVersion)) {
        // No existing tag found without the branch suffix, set a default starting version based on the branch
        $nextVersion = match ($branch) {
            "php7" => "7.0.0",
            default => "8.0.0",
        };
    }
}

switch ($inputVersion) {
    case "patch":
        $versionParts = explode(".", $nextVersion);
        $patchVersion = isset($versionParts[2]) ? (int)$versionParts[2] : 0;

        // Increment the patch version appropriately based on the branch
        switch ($branch) {
            case "php7":
                if ($versionParts[0] == 7 && $versionParts[1] == 0 && $versionParts[2] == 0) {
                    $nextVersion = $versionParts[0] . "." . $versionParts[1] . "." . ($patchVersion + 1);
                } else {
                    $nextVersion = $versionParts[0] . "." . $versionParts[1] . "." . $versionParts[2];
                }
                break;
            case "php8":
            default:
                $nextVersion = $versionParts[0] . "." . $versionParts[1] . "." . ($patchVersion + 1);
                break;
        }
        break;
    case "minor":
        $versionParts = explode(".", $nextVersion);
        $minorVersion = isset($versionParts[1]) ? (int)$versionParts[1] : 0;

        // Increment the minor version appropriately based on the branch
        switch ($branch) {
            case "php7":
                if ($versionParts[0] == 7 && $versionParts[1] == 0 && $versionParts[2] == 0) {
                    $nextVersion = $versionParts[0] . "." . ($minorVersion + 1) . ".0";
                } else {
                    $nextVersion = $versionParts[0] . "." . $versionParts[1] . ".0";
                }
                break;
            case "php8":
            default:
                $nextVersion = $versionParts[0] . "." . ($minorVersion + 1) . ".0";
                break;
        }
        break;
    case "major":
        $versionParts = explode(".", $nextVersion);
        $majorVersion = isset($versionParts[0]) ? (int)$versionParts[0] : 0;

        // Increment the major version appropriately based on the branch
        switch ($branch) {
            case "php7":
                if ($versionParts[0] == 7 && $versionParts[1] == 0 && $versionParts[2] == 0) {
                    $nextVersion = ($majorVersion + 1) . ".0.0";
                } else {
                    $nextVersion = $versionParts[0] . ".0.0";
                }
                break;
            case "php8":
            default:
                $nextVersion = ($majorVersion + 1) . ".0.0";
                break;
        }
        break;
}

echo $nextVersion;

return $nextVersion;
