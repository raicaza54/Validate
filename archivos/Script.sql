SELECT * FROM validate.track__seguimiento WHERE user_identifier NOT IN(1,2) ORDER BY id DESC;
SELECT * FROM validate.track__seguimiento WHERE 1 ORDER BY id DESC;

SELECT * FROM auth__users;
SELECT * FROM auth__users_groups WHERE group_id = 2;
SELECT * FROM clie__archivos WHERE id = 1634380766923482;
SELECT * FROM clie__carpetas WHERE archivos_id = 1634380766923482;
SELECT * FROM clie__archivos_detalle WHERE fk_archivos = 1634380766923482;

SELECT * FROM clie__archivos WHERE id = 376180633122689;
SELECT * FROM clie__archivos_detalle WHERE fk_archivos = 376180633122689;
