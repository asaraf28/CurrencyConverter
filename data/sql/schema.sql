CREATE TABLE country (id BIGINT AUTO_INCREMENT, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE currency (code VARCHAR(3), number VARCHAR(3) NOT NULL, name VARCHAR(255) NOT NULL, digits TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(code)) ENGINE = INNODB;
CREATE TABLE currency_country (currency_code VARCHAR(3), country_id BIGINT, PRIMARY KEY(currency_code, country_id)) ENGINE = INNODB;
CREATE TABLE currency_rate (from_code VARCHAR(3), to_code VARCHAR(3), rate DOUBLE(40, 20) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(from_code, to_code)) ENGINE = INNODB;
ALTER TABLE currency_country ADD CONSTRAINT currency_country_currency_code_currency_code FOREIGN KEY (currency_code) REFERENCES currency(code);
ALTER TABLE currency_country ADD CONSTRAINT currency_country_country_id_country_id FOREIGN KEY (country_id) REFERENCES country(id);
