SELECT * FROM validate.track__seguimiento WHERE user_identifier NOT IN(1,2) ORDER BY id DESC;
SELECT * FROM validate.track__seguimiento WHERE 1 ORDER BY id DESC;

DESCRIBE clie__analisis;
SELECT * FROM clie__analisis ORDER BY created_at DESC;

SELECT * FROM clie__consecutivos ORDER BY id DESC;

SELECT * FROM auth__users;
SELECT * FROM auth__users_groups WHERE group_id = 2;
SELECT * FROM clie__archivos WHERE id = 1634380766923482;
SELECT * FROM clie__carpetas WHERE archivos_id = 1634380766923482;
SELECT * FROM clie__archivos_detalle WHERE fk_archivos = 1634380766923482;

DESCRIBE sist__listas;

SELECT * FROM (
SELECT sl.lista,
sl.nombre,
sl.aka,
sl.identificacion,
sl.otros, 
sl.deleted_at,
levenshtein_ratio('5897611', sl.identificacion) AS ratio FROM sist__listas AS sl WHERE deleted_at = 0) AS li WHERE li.ratio > 85;





SELECT * FROM clie__archivos WHERE id = 376180633122689;
SELECT * FROM clie__archivos_detalle WHERE fk_archivos = 376180633122689;

SELECT * FROM clie__consecutivos;

#ALTER TABLE `clie__consecutivos`
#ADD UNIQUE `fk_clientes_correlativo` (`fk_clientes`, `correlativo`);