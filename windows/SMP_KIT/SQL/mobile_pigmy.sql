CREATE TABLE
    mobile_pigmy (
        id INT AUTO_INCREMENT PRIMARY KEY,
        licence_key VARCHAR(12) NOT NULL,
        branch_code VARCHAR(20) NOT NULL
    );

-- Insert one row with a generated 12-character key
INSERT INTO
    mobile_pigmy (licence_key, branch_code)
VALUES
    ('HD13G0K736NQ', 'SKT777');