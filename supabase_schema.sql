-- MSWDO Portal Tubungan, Iloilo - Supabase (PostgreSQL) Database Schema Migration
-- Run this in your Supabase SQL Editor

-- 1. Create tb_admin table
CREATE TABLE IF NOT EXISTS tb_admin (
    id SERIAL PRIMARY KEY,
    first_name TEXT NOT NULL,
    middle_name TEXT,
    last_name TEXT NOT NULL,
    position TEXT DEFAULT 'Admin',
    gender TEXT,
    birthdate DATE,
    email TEXT UNIQUE NOT NULL,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL, -- PHP password_hash()
    created_at TIMESTAMP DEFAULT NOW()
);

-- 2. Create tb_focal table
CREATE TABLE IF NOT EXISTS tb_focal (
    id SERIAL PRIMARY KEY,
    first_name TEXT NOT NULL,
    middle_name TEXT,
    last_name TEXT NOT NULL,
    gender TEXT,
    username TEXT UNIQUE NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL, -- PHP password_hash()
    created_at TIMESTAMP DEFAULT NOW()
);

-- 3. Create tb_clients table
CREATE TABLE IF NOT EXISTS tb_clients (
    id SERIAL PRIMARY KEY,
    first_name TEXT NOT NULL,
    middle_name TEXT,
    last_name TEXT NOT NULL,
    extension TEXT,
    age INT,
    sex TEXT,
    religion TEXT,
    address TEXT NOT NULL,
    contact_number TEXT,
    civil_status TEXT,
    date_of_birth DATE,
    place_of_birth TEXT,
    occupation TEXT,
    educational_attainment TEXT,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL, -- PHP password_hash()
    created_at TIMESTAMP DEFAULT NOW()
);

-- 4. Create tb_program table
CREATE TABLE IF NOT EXISTS tb_program (
    id SERIAL PRIMARY KEY,
    program_name TEXT NOT NULL,
    description TEXT,
    status TEXT DEFAULT 'Active',
    budget NUMERIC DEFAULT 0
);

-- 5. Create tb_beneficiaries table
CREATE TABLE IF NOT EXISTS tb_beneficiaries (
    id SERIAL PRIMARY KEY,
    id_number TEXT UNIQUE NOT NULL,
    first_name TEXT NOT NULL,
    middle_name TEXT,
    last_name TEXT NOT NULL,
    program_id INT REFERENCES tb_program(id) ON DELETE SET NULL,
    age INT,
    gender TEXT,
    address TEXT,
    contact_number TEXT,
    birthdate DATE
);

-- 6. Create tb_aics_applications table
CREATE TABLE IF NOT EXISTS tb_aics_applications (
    id SERIAL PRIMARY KEY,
    client_id INT REFERENCES tb_clients(id) ON DELETE CASCADE,
    service_id INT,
    application_date DATE DEFAULT NOW(),
    recommendation TEXT,
    findings TEXT,
    prepared_by TEXT,
    status TEXT DEFAULT 'Pending',
    others TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- 7. Create tb_aics_famcom table (family composition for AICS)
CREATE TABLE IF NOT EXISTS tb_aics_famcom (
    id SERIAL PRIMARY KEY,
    application_id INT REFERENCES tb_aics_applications(id) ON DELETE CASCADE,
    first_name TEXT,
    middle_name TEXT,
    last_name TEXT,
    extension TEXT,
    age INT,
    sex TEXT,
    civil_status TEXT,
    educational_attainment TEXT,
    occupation TEXT,
    income TEXT
);

-- 8. Create clientele_category table
CREATE TABLE IF NOT EXISTS clientele_category (
    id SERIAL PRIMARY KEY,
    category TEXT NOT NULL,
    client_id INT REFERENCES tb_clients(id) ON DELETE CASCADE,
    application_id INT REFERENCES tb_aics_applications(id) ON DELETE CASCADE,
    others TEXT
);

-- 9. Create tb_services table
CREATE TABLE IF NOT EXISTS tb_services (
    id SERIAL PRIMARY KEY,
    service_name TEXT NOT NULL,
    program_id INT REFERENCES tb_program(id) ON DELETE CASCADE,
    description TEXT
);

-- 10. Create tb_allocation_history table
CREATE TABLE IF NOT EXISTS tb_allocation_history (
    id SERIAL PRIMARY KEY,
    date DATE DEFAULT NOW(),
    amount NUMERIC NOT NULL,
    remarks TEXT,
    admin_id INT REFERENCES tb_admin(id) ON DELETE SET NULL,
    program_id INT REFERENCES tb_program(id) ON DELETE CASCADE
);

-- 11. Create program_focal (junction) table
CREATE TABLE IF NOT EXISTS program_focal (
    program_id INT REFERENCES tb_program(id) ON DELETE CASCADE,
    focal_id INT REFERENCES tb_focal(id) ON DELETE CASCADE,
    PRIMARY KEY (program_id, focal_id)
);


-- ====================================================================
-- SEED DATA
-- ====================================================================

-- Seed standard tb_program
INSERT INTO tb_program (id, program_name, description, status, budget) VALUES
(1, 'Assistance to Individuals in Crisis Situations (AICS)', 'Immediate financial, medical, burial, and transportation assistance to individuals in crisis.', 'Active', 500000),
(2, 'Solo Parent Program', 'Comprehensive package of social development services for solo parents and their children.', 'Active', 250000),
(3, 'Senior Citizen Program', 'Welfare, socialization, and financial assistance programs for senior residents aged 60 and above.', 'Active', 350000),
(4, 'Persons with Disability (PWD) Program', 'Rehabilitation, self-enhancement, and economic sufficiency programs for PWDs.', 'Active', 200000)
ON CONFLICT (id) DO UPDATE 
SET program_name = EXCLUDED.program_name, 
    description = EXCLUDED.description, 
    status = EXCLUDED.status, 
    budget = EXCLUDED.budget;

-- Seed services for AICS
INSERT INTO tb_services (id, service_name, program_id, description) VALUES
(1, 'Medical Assistance', 1, 'Assistance for medicines, hospitalization, laboratory fees, and other medical procedures.'),
(2, 'Burial Assistance', 1, 'Assistance for casket, funeral services, burial lot, and other funeral-related expenses.'),
(3, 'Educational Assistance', 1, 'Assistance for school fees, books, supplies, and school projects of students from poor families.'),
(4, 'Food Assistance', 1, 'Immediate food packs or hot meals during disasters or extreme financial distress.'),
(5, 'Transportation Assistance', 1, 'Bus, boat, or plane fare for individuals stranded or needing to go back to their home provinces.')
ON CONFLICT (id) DO UPDATE
SET service_name = EXCLUDED.service_name,
    program_id = EXCLUDED.program_id,
    description = EXCLUDED.description;

-- Seed default Administrator account
-- Email: admin@tubungan.gov.ph
-- Username: admin
-- Password: password123 (bcrypt: $2y$10$8M4W8hTzGkZfG1sXmS1EKeUuC6I3YhW8C2S0T09J5IeU8T2S0D1eG)
INSERT INTO tb_admin (id, first_name, middle_name, last_name, position, gender, birthdate, email, username, password) VALUES
(1, 'Maria Lourdes', 'G', 'Tacordon', 'MSWD Officer', 'Female', '1980-05-15', 'admin@tubungan.gov.ph', 'admin', '$2y$10$8M4W8hTzGkZfG1sXmS1EKeUuC6I3YhW8C2S0T09J5IeU8T2S0D1eG')
ON CONFLICT (id) DO NOTHING;

-- Seed default Focal Person account
-- Email: focal.aics@tubungan.gov.ph
-- Username: focalaics
-- Password: password123 (bcrypt: $2y$10$8M4W8hTzGkZfG1sXmS1EKeUuC6I3YhW8C2S0T09J5IeU8T2S0D1eG)
INSERT INTO tb_focal (id, first_name, middle_name, last_name, gender, username, email, password) VALUES
(1, 'Juan', 'D', 'Cruz', 'Male', 'focalaics', 'focal.aics@tubungan.gov.ph', '$2y$10$8M4W8hTzGkZfG1sXmS1EKeUuC6I3YhW8C2S0T09J5IeU8T2S0D1eG')
ON CONFLICT (id) DO NOTHING;

-- Assign focal person to AICS program
INSERT INTO program_focal (program_id, focal_id) VALUES
(1, 1)
ON CONFLICT DO NOTHING;
