CREATE TABLE relacion_user_post (
    id_user INTEGER NOT NULL,
    id_post INTEGER NOT NULL,
    PRIMARY KEY (id_user, id_post),
    FOREIGN KEY (id_user) REFERENCES usuarios(id),
    FOREIGN KEY (id_post) REFERENCES posts(id)
);