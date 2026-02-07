-- Migration: Create Permission Tables
-- Date: 2026-02-08

START TRANSACTION;

-- Permissions table
CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    permission_key VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Role-Permission mapping table
CREATE TABLE IF NOT EXISTS peran_izin (
    peran_jenis_id INT NOT NULL,
    permission_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (peran_jenis_id, permission_id),
    FOREIGN KEY (peran_jenis_id) REFERENCES peran_jenis(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed initial permissions
INSERT INTO permissions (permission_key, description) VALUES
('manage_cooperative', 'Manage cooperative details and settings'),
('manage_members', 'Manage cooperative members'),
('view_reports', 'View financial reports'),
('approve_loans', 'Approve loan applications');

-- Assign permissions to admin role (ID 2)
INSERT INTO peran_izin (peran_jenis_id, permission_id) VALUES
(2, 1), -- manage_cooperative
(2, 2), -- manage_members
(2, 3); -- view_reports

-- Assign permissions to super_admin role (ID 1)
INSERT INTO peran_izin (peran_jenis_id, permission_id) VALUES
(1, 1), (1, 2), (1, 3), (1, 4);

COMMIT;
