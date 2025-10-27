create database mybox char set utf8mb4;

use mybox;
		
		
create table usuarios ( 
	usuario varchar(15) not null, 
	contra varchar(80) not null, 
	nombre varchar(25) not null, 
	email varchar(20) not null unique, 
	primary key (usuario) 
) engine=innodb charset=utf8mb4;

CREATE TABLE shares (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner VARCHAR(15) NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    item_type ENUM('file', 'folder') NOT NULL,
    shared_with VARCHAR(15) NOT NULL,
    FOREIGN KEY (owner) REFERENCES usuarios(usuario),
    FOREIGN KEY (shared_with) REFERENCES usuarios(usuario)
) ENGINE=InnoDB CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 0;

truncate table usuarios;
truncate table shares;

SET FOREIGN_KEY_CHECKS = 1;

select * from usuarios;

select * from shares;