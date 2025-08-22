# 🔐 Atividade de Criptografia — Chave Simétrica e Assimétrica

Este projeto implementa um **algoritmo de criptografia** que permite digitar uma frase e vê-la sendo **cifrada e decifrada** usando dois métodos principais:

- 🔑 **Criptografia Simétrica (AES)** → a mesma chave é usada para cifrar e decifrar.  
- 🔑 **Criptografia Assimétrica (RSA)** → usa um par de chaves (pública e privada).  

---

## 🚀 Como executar

### Pré-requisitos
- PHP **7.4+** ou **8.x** instalado na máquina.  
- Extensão `openssl` habilitada no `php.ini`.  
- (Opcional) OpenSSL instalado no sistema para gerar chaves manualmente.  

### Rodando o programa
1. Clone ou copie este repositório para sua máquina.  
2. Abra o terminal na pasta do projeto.  
3. Execute:  
   ```bash
   php index.php
   ```  
4. Digite uma frase quando solicitado.  

---

## 🔧 Gerar chaves RSA (copie e cole)

> **Atenção:** **NÃO** faça commit da **chave privada** no Git. Mantenha `rsa_priv.pem` apenas na sua máquina.  
> A chave **pública** (`rsa_pub.pem`) pode ser compartilhada/commitada se desejar.

### Linux / macOS (Bash)
```bash
openssl genpkey -algorithm RSA -pkeyopt rsa_keygen_bits:2048 -out rsa_priv.pem
openssl rsa -in rsa_priv.pem -pubout -out rsa_pub.pem
```

### Windows (PowerShell)
```powershell
openssl genpkey -algorithm RSA -pkeyopt rsa_keygen_bits:2048 -out rsa_priv.pem
openssl rsa -in rsa_priv.pem -pubout -out rsa_pub.pem
```

> Se o Windows reclamar do `openssl.cnf`, aponte o caminho antes de rodar (ajuste conforme seu ambiente):
>
> **PowerShell**
> ```powershell
> $env:OPENSSL_CONF="C:\Program Files\Git\usr\ssl\openssl.cnf"
> ```
> **Git Bash**
> ```bash
> export OPENSSL_CONF="/c/Program Files/Git/usr/ssl/openssl.cnf"
> ```

Depois de gerar, o `index.php` pode carregar os arquivos assim:
```php
$chavePrivada = file_get_contents(__DIR__ . '/rsa_priv.pem');
$chavePublica = file_get_contents(__DIR__ . '/rsa_pub.pem');
```

---

## 🛠️ O que o código faz

1. 📥 **Lê uma frase digitada pelo usuário**.  
2. 🔒 **Cifra e decifra com AES (simétrica)**:  
   - Gera um *IV* (vetor de inicialização).  
   - Mostra o texto cifrado em Base64.  
   - Recupera o texto original.  
3. 🔑 **Cifra e decifra com RSA (assimétrica)**:  
   - Gera (ou carrega) um par de chaves (pública e privada).  
   - Cifra a frase com a **chave pública**.  
   - Decifra com a **chave privada**.  

---

## 📌 Exemplo de uso

```bash
$ php index.php
Digite uma frase: oi

[SIMÉTRICO] Texto cifrado: Lo8ffIpDKturJWlefM7vPQ==
[SIMÉTRICO] Texto decifrado: oi

[ASSIMÉTRICO] Texto cifrado: G4sdUmbiTX8KmixNmtFn2xX0z3WkralnEw5eOo...
[ASSIMÉTRICO] Texto decifrado: oi
```

> ⚡ Cada execução gera textos cifrados diferentes devido ao uso de valores aleatórios (IV no AES, padding no RSA). Isso é esperado e aumenta a segurança.

---

## 📚 Conceitos básicos

### 🔑 Criptografia Simétrica
- Usa **uma única chave** para cifrar e decifrar.  
- É **rápida** e eficiente.  
- Exige um canal seguro para compartilhar a chave.  

### 🔑 Criptografia Assimétrica
- Usa um **par de chaves**:  
  - **Pública** → para cifrar.  
  - **Privada** → para decifrar.  
- Resolve o problema de distribuir chaves secretas.  
- É **mais lenta**, porém muito útil para troca de chaves e autenticação.  

---

## 🔄 Esquema híbrido (curiosidade)

Na prática, sistemas reais (como HTTPS) combinam os dois mundos:

```
[Mensagem] --AES--> [Texto Cifrado] 
     ^                           |
     |                           v
 [Chave AES] --RSA--> [Chave Cifrada]
```

- A mensagem é cifrada com **AES**.  
- A chave AES é cifrada com **RSA**.  
- O destinatário usa a chave privada para recuperar a chave AES e decifrar a mensagem.  

---

## 📦 .gitignore recomendado

O repositório inclui um `.gitignore` que evita a inclusão de chaves privadas e arquivos temporários.  
Se quiser ignorar também a **pública**, descomente a linha sugerida no arquivo.

---

## 👨‍💻 Autor
Atividade desenvolvida como parte da disciplina **Auditoria e Segurança de Sistemas**.
