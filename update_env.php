<?php

// Caminho para o arquivo onde a saída do Passport está armazenada
$outputPath = __DIR__ . '/passport_output.txt';

// Verifica se o arquivo existe e lê o conteúdo
if (!file_exists($outputPath)) {
    echo "Erro: O arquivo passport_output.txt não foi encontrado.\n";
    exit(1);
}

$output = file_get_contents($outputPath);

// Verifica se a saída está vazia
if (!$output) {
    echo "Erro: Saída de passport:install está vazia.\n";
    exit(1);
}

// Extrai os valores do primeiro Client ID e Client Secret encontrados
preg_match('/Client ID: (\d+)/', $output, $clientIdMatches);
preg_match('/Client Secret: ([a-zA-Z0-9]+)/', $output, $clientSecretMatches);

if (!empty($clientIdMatches) && !empty($clientSecretMatches)) {
    $clientId = $clientIdMatches[1];
    $clientSecret = $clientSecretMatches[1];

    // Atualiza o arquivo .env
    $envPath = __DIR__ . '/.env';
    if (file_exists($envPath)) {
        $envContent = file_get_contents($envPath);
        $envContent = preg_replace('/PASSPORT_CLIENT_ID=.*/', "PASSPORT_CLIENT_ID=$clientId", $envContent);
        $envContent = preg_replace('/PASSPORT_CLIENT_SECRET=.*/', "PASSPORT_CLIENT_SECRET=$clientSecret", $envContent);
        file_put_contents($envPath, $envContent);
        echo "Variáveis PASSPORT_CLIENT_ID e PASSPORT_CLIENT_SECRET foram atualizadas com sucesso no arquivo .env!\n";
    } else {
        echo "Erro: O arquivo .env não foi encontrado.\n";
        exit(1);
    }
} else {
    echo "Erro: Não foi possível encontrar os valores de Client ID ou Client Secret na saída.\n";
    exit(1);
}