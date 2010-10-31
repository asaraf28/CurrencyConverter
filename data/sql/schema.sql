CREATE TABLE currency (code VARCHAR(3), number VARCHAR(3) NOT NULL, name VARCHAR(255) NOT NULL, locations VARCHAR(255) NOT NULL, digits TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(code)) ENGINE = INNODB;
CREATE TABLE currency_rate (from_code VARCHAR(3), to_code VARCHAR(3), rate DOUBLE(18, 5) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(from_code, to_code)) ENGINE = INNODB;
