PRAGMA foreign_keys = off;
BEGIN TRANSACTION;
-- Stores all the users for the application
CREATE TABLE IF NOT EXISTS user (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    first_name VARCHAR(120) NULL,
    last_name VARCHAR(120) NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(34) NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL
);
-- To speed up logins
CREATE INDEX IF NOT EXISTS idx_user_credentials ON user(email, username, password);
-- To ensure that user details are unique
CREATE UNIQUE INDEX IF NOT EXISTS idx_uniq_user ON user(first_name, last_name, email, phone);
CREATE UNIQUE INDEX IF NOT EXISTS idx_uniq_user_password ON user(password);
CREATE TABLE IF NOT EXISTS password_resets (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    user_identity VARCHAR(255) NOT NULL,
    created_on TEXT NOT NULL DEFAULT CURRENT_DATE
);
CREATE INDEX IF NOT EXISTS idx_resetpassword_user_identity ON password_resets(user_identity);
COMMIT;
PRAGMA foreign_keys = on;