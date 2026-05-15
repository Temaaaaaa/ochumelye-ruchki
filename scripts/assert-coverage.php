<?php

if ($argc < 3) {
    fwrite(STDERR, "Usage: php scripts/assert-coverage.php <minimum-percent> <clover-file>\n");
    exit(2);
}

$minimumPercent = (float) $argv[1];
$coverageFile = $argv[2];

if (! file_exists($coverageFile)) {
    fwrite(STDERR, "Coverage file not found: {$coverageFile}\n");
    exit(2);
}

$coverage = simplexml_load_file($coverageFile);

if ($coverage === false || ! isset($coverage->project->metrics)) {
    fwrite(STDERR, "Unable to parse Clover coverage file: {$coverageFile}\n");
    exit(2);
}

$metrics = $coverage->project->metrics;
$statements = (int) $metrics['statements'];
$coveredStatements = (int) $metrics['coveredstatements'];
$coveragePercent = $statements === 0
    ? 100.0
    : ($coveredStatements / $statements) * 100;

printf(
    "Line coverage: %.2f%% (%d/%d)\n",
    $coveragePercent,
    $coveredStatements,
    $statements,
);

if ($coveragePercent < $minimumPercent) {
    fwrite(
        STDERR,
        sprintf(
            "Coverage gate failed: %.2f%% is below required %.2f%%.\n",
            $coveragePercent,
            $minimumPercent,
        ),
    );
    exit(1);
}
