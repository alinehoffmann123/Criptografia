<?php
putenv('OPENSSL_CONF=C:\Program Files\Git\usr\ssl\openssl.cnf');

// -------------- SIMÉTRICO (AES) -----------------
function simetrico($texto, $chave) {
    $iv = random_bytes(16);
    $cifrado = openssl_encrypt($texto, 'AES-128-CBC', $chave, 0, $iv);

    return [$cifrado, base64_encode($iv)];
}

function simetrico_decifra($cifrado, $chave, $iv) {
    return openssl_decrypt($cifrado, 'AES-128-CBC', $chave, 0, base64_decode($iv));
}

// -------------- ASSIMÉTRICO (RSA) -----------------
function gerar_par_chaves(): array {
    $config = [
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
        'private_key_bits' => 2048,
    ];

    $res = openssl_pkey_new($config);
    if ($res === false) {
        $err = [];
        while ($e = openssl_error_string()) { $err[] = $e; }
        throw new RuntimeException(
            "Falha ao gerar par RSA. "
            . "Verifique se a extensão 'openssl' está habilitada e se o OPENSSL_CONF aponta para um openssl.cnf válido. "
            . ($err ? "Erros OpenSSL: " . implode(" | ", $err) : "")
        );
    }

    // Exporta a chave privada (PEM)
    $privPem = '';
    if (!openssl_pkey_export($res, $privPem)) {
        throw new RuntimeException("Falha ao exportar a chave privada.");
    }

    // Extrai a chave pública (PEM)
    $det = openssl_pkey_get_details($res);
    if ($det === false || empty($det['key'])) {
        throw new RuntimeException("Falha ao obter a chave pública.");
    }

    $pubPem = $det['key'];
    return [$privPem, $pubPem];
}

function assimetrico($texto, $chavePublica) {
    openssl_public_encrypt($texto, $cifrado, $chavePublica);
    return base64_encode($cifrado);
}

function assimetrico_decifra($cifrado, $chavePrivada) {
    openssl_private_decrypt(base64_decode($cifrado), $decifrado, $chavePrivada);
    return $decifrado;
}

// ---------------- PROGRAMA PRINCIPAL ----------------
echo "Digite uma frase: ";
$frase = trim(fgets(STDIN));

// --- Simétrico ---
$chave = "minhaChave123456";
list($cifrado, $iv) = simetrico($frase, $chave);
echo "\n[SIMÉTRICO] Texto cifrado: $cifrado\n";
echo "[SIMÉTRICO] Texto decifrado: " . simetrico_decifra($cifrado, $chave, $iv) . "\n";

// --- Assimétrico ---
$privada = file_get_contents(__DIR__ . '/rsa_priv.pem');
$publica = file_get_contents(__DIR__ . '/rsa_pub.pem');
$cifradoRSA = assimetrico($frase, $publica);
echo "\n[ASSIMÉTRICO] Texto cifrado: $cifradoRSA\n";
echo "[ASSIMÉTRICO] Texto decifrado: " . assimetrico_decifra($cifradoRSA, $privada) . "\n";
