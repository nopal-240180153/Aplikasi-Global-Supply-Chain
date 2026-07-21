@echo off
echo ========================================
echo   Database Export untuk Deployment
echo ========================================
echo.

REM Set path MySQL (sesuaikan dengan instalasi Anda)
set MYSQL_PATH=C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin
set DB_NAME=global_supply_chain
set DB_USER=root
set DB_PASS=
set OUTPUT_FILE=deployment-package\database.sql

echo Membuat folder deployment-package...
if not exist "deployment-package" mkdir deployment-package

echo.
echo Exporting database: %DB_NAME%
echo Output: %OUTPUT_FILE%
echo.

if "%DB_PASS%"=="" (
    "%MYSQL_PATH%\mysqldump" -u %DB_USER% %DB_NAME% > %OUTPUT_FILE%
) else (
    "%MYSQL_PATH%\mysqldump" -u %DB_USER% -p%DB_PASS% %DB_NAME% > %OUTPUT_FILE%
)

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo   Export Berhasil!
    echo ========================================
    echo.
    echo File tersimpan di: %OUTPUT_FILE%
    echo.
    echo Langkah selanjutnya:
    echo 1. Buat ZIP file project ^(tanpa vendor/, node_modules/, .env^)
    echo 2. Upload ke InfinityFree
    echo 3. Import database.sql via phpMyAdmin
    echo.
    echo Baca panduan lengkap: DEPLOYMENT_GUIDE.md
    echo.
) else (
    echo.
    echo ========================================
    echo   Export Gagal!
    echo ========================================
    echo.
    echo Kemungkinan penyebab:
    echo 1. Path MySQL salah - Edit file ini dan sesuaikan MYSQL_PATH
    echo 2. Database tidak ditemukan - Pastikan database '%DB_NAME%' ada
    echo 3. Credentials salah - Cek DB_USER dan DB_PASS
    echo.
    echo Atau export manual via phpMyAdmin:
    echo 1. Buka http://localhost/phpmyadmin
    echo 2. Pilih database '%DB_NAME%'
    echo 3. Klik Export -^> Go
    echo 4. Save ke deployment-package\database.sql
    echo.
)

pause
