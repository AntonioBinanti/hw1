CREATE TABLE users (
    id integer primary key auto_increment,
    username varchar(16) not null unique,
    password varchar(255) not null,
    email varchar(255) not null unique,
    name varchar(255) not null,
    surname varchar(255) not null,
    n_car_saved integer default 0,
    since timestamp not null default current_timestamp
) Engine = InnoDB;

CREATE TABLE car (
    user integer not null,
    marca varchar(255) not null,
    modello varchar(255) not null,
    anno integer not null,
    img varchar(255),
    primary key(user,marca,modello,anno),
    foreign key(user) references users(id) on delete cascade on update cascade
) Engine = InnoDB;

DELIMITER //
CREATE TRIGGER car_saved_trigger
AFTER INSERT ON car
FOR EACH ROW
BEGIN
UPDATE users 
SET n_car_saved = n_car_saved + 1
WHERE id = new.user;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER car_unsaved_trigger
AFTER DELETE ON car
FOR EACH ROW
BEGIN
UPDATE users 
SET n_car_saved = n_car_saved - 1 
WHERE id = old.user;
END //
DELIMITER ;