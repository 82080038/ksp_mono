-- Migration to fix Badan Hukum field inconsistencies

-- Step 1: Update existing data to align status fields
UPDATE koperasi_tenant 
SET status_badan_hukum = 'terdaftar'
WHERE badan_hukum = 'terdaftar' AND status_badan_hukum = 'belum_terdaftar';

-- Step 2: Clean up the badan_hukum text field (deprecate in favor of enum)
UPDATE koperasi_tenant
SET badan_hukum = NULL;

-- Step 3: Add validation constraint for nomor_badan_hukum
ALTER TABLE koperasi_tenant
ADD CONSTRAINT chk_nomor_badan_hukum 
CHECK (nomor_badan_hukum IS NULL OR 
      (LENGTH(nomor_badan_hukum) = 12 AND nomor_badan_hukum REGEXP '^[0-9]+$'));
