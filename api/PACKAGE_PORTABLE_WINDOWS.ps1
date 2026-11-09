param([string]$Destination = "$PSScriptRoot\BookSwap-Portable-Windows")
$ErrorActionPreference = 'Stop'
$phpExe = (Get-Command php).Source
$phpRoot = Split-Path $phpExe
if (-not (Test-Path "$PSScriptRoot\vendor\autoload.php")) { throw 'Exécutez composer install avant le packaging.' }
if (Test-Path $Destination) { throw "Le dossier existe déjà : $Destination" }
New-Item -ItemType Directory -Path $Destination | Out-Null
Copy-Item "$PSScriptRoot\*" $Destination -Recurse -Force -Exclude '.git','node_modules','BookSwap-Portable-Windows','_laravel_skeleton'
Copy-Item $phpRoot "$Destination\runtime\php" -Recurse
$portableIni = "$Destination\runtime\php\php.ini"
if (-not (Test-Path $portableIni)) { Copy-Item "$Destination\runtime\php\php.ini-development" $portableIni }
$ini = Get-Content $portableIni -Raw
if ($ini -notmatch '(?m)^extension\s*=\s*pdo_sqlite') { Add-Content $portableIni "`nextension_dir=ext`nextension=pdo_sqlite`nextension=sqlite3" }
Write-Host 'Distribution créée. Vérifiez php.ini, l’architecture x64/x86 et testez-la sur une machine Windows propre.'
