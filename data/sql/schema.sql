CREATE TABLE currency (code VARCHAR(3), number VARCHAR(3) NOT NULL, name VARCHAR(255) NOT NULL, digits TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(code)) ENGINE = INNODB;
CREATE TABLE currency_country (id BIGINT AUTO_INCREMENT, code VARCHAR(3) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX code_idx (code), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE currency_rate (from_code VARCHAR(3), to_code VARCHAR(3), rate DOUBLE(18, 20) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(from_code, to_code)) ENGINE = INNODB;
ALTER TABLE currency_country ADD CONSTRAINT currency_country_code_currency_code FOREIGN KEY (code) REFERENCES currency(code);
