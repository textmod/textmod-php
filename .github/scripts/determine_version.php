<?php
$tags = explode("\n", shell_exec("git tag"));
rsort($tags, SORT_NATURAL);

$nextVersion = "";

$inputVersion = $argv[1];

if (empty($tags)) {
    // No existing tags, set a default starting version
    $nextVersion = "1.0.0";
} else {
    // Find the highest version
    foreach ($tags as $tag) {
        if (preg_match('/(\d+\.\d+\.\d+)/', $tag, $matches)) {
            $currentVersion = $matches[1];
            $nextVersion = $currentVersion;
            break;
        }
    }

    if (empty($nextVersion)) {
        // No existing tag found, set a default starting version
        $nextVersion = "1.0.0";
    }
}

switch ($inputVersion) {
    case "patch":
        $versionParts = explode(".", $nextVersion);
        $patchVersion = isset($versionParts[2]) ? (int)$versionParts[2] : 0;
        $nextVersion = $versionParts[0] . "." . $versionParts[1] . "." . ($patchVersion + 1);
        break;
    case "minor":
        $versionParts = explode(".", $nextVersion);
        $minorVersion = isset($versionParts[1]) ? (int)$versionParts[1] : 0;
        $nextVersion = $versionParts[0] . "." . ($minorVersion + 1) . ".0";
        break;
    case "major":
        $versionParts = explode(".", $nextVersion);
        $majorVersion = isset($versionParts[0]) ? (int)$versionParts[0] : 0;
        $nextVersion = ($majorVersion + 1) . ".0.0";
        break;
}

echo $nextVersion;

return $nextVersion;
