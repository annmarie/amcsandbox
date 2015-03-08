CREATE DATABASE recibes;

USE recibes;

CREATE TABLE recipe (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  rcp_headline VARCHAR(255) NOT NULL,
  rcp_body MEDIUMTEXT,
  rcp_notes MEDIUMTEXT,
  rcp_img_id INT(6),
  created TIMESTAMP DEFAULT NOW()
);

CREATE TABLE ingredient (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ingr_name VARCHAR(255) UNIQUE NOT NULL,
  ingr_desc VARCHAR(255),
  ingr_img_id INT(6)
);

CREATE TABLE recipe_ingredient (
  rcp_id INT(6) NOT NULL,
  ingr_id INT(6) NOT NULL,
  ingr_amount VARCHAR(100) NOT NULL,
  ingr_notes MEDIUMTEXT,
  ingr_order INT(6) DEFAULT 0,
  created TIMESTAMP DEFAULT NOW()
);

CREATE TABLE tag (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tag_name VARCHAR(255) UNIQUE NOT NULL,
  tag_img_id INT(6)
);

CREATE TABLE tags (
  tag_id INT(6) NOT NULL,
  rcp_id INT(6),
  tag_order INT(6) DEFAULT 0,
  created TIMESTAMP DEFAULT NOW()
);

CREATE TABLE image (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  img_filename VARCHAR(255) NOT NULL
);

