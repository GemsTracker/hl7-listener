# create databases
CREATE DATABASE IF NOT EXISTS gems;
CREATE DATABASE IF NOT EXISTS gems_data;
CREATE DATABASE IF NOT EXISTS gems_ls;

CREATE USER 'gems'@'%' IDENTIFIED BY 'test123';
GRANT ALL ON `gems`.* TO 'gems'@'%';
GRANT ALL ON `gems_data`.* TO 'gems'@'%';

CREATE USER 'gems_ls'@'%' IDENTIFIED BY 'test123';
GRANT ALL ON `gems_ls`.* TO 'gems_ls'@'%';
