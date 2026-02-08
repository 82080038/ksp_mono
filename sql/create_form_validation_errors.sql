CREATE TABLE IF NOT EXISTS form_validation_errors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    input_value VARCHAR(255) NOT NULL,
    field_type ENUM('username', 'phone') NOT NULL,
    error_type VARCHAR(50) NOT NULL,
    user_ip VARCHAR(45)
);

CREATE INDEX idx_field_type ON form_validation_errors (field_type);
CREATE INDEX idx_error_type ON form_validation_errors (error_type);
