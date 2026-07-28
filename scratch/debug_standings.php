<?php
$content = file_get_contents('c:/Users/luism/gemini-work/sistemaTorneos/resources/views/tournaments/standings.blade.php');
$pos = strpos($content, 'Fase Eliminatoria');
if ($pos !== false) {
    echo "Position: $pos\n";
    $snippet = substr($content, $pos - 200, 400);
    echo "Hex snippet:\n" . bin2hex($snippet) . "\n\n";
    echo "Text snippet:\n" . $snippet . "\n";
} else {
    echo "Not found!\n";
}
