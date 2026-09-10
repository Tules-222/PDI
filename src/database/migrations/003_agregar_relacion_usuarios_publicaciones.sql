create TABLE relacion_user_post(
    id_user int(10) AUTO_INCREMENT,
    id_post int(10) AUTO_INCREMENT,
    FOREIGN KEY id_user REFERENCES user(id);
    FOREIGN KEY id_post REFERENCES posts(id);
);