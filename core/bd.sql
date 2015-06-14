CREATE SCHEMA registro
  AUTHORIZATION chat;

GRANT ALL ON SCHEMA registro TO postgres;
GRANT ALL ON SCHEMA registro TO chat;
COMMENT ON SCHEMA registro
  IS 'Datos del Registro';

CREATE TABLE registro.usuario(
	pk_usu SERIAL NOT NULL,
	ch_nomb CHARACTER VARYING(50) NOT NULL,
	ch_ape CHARACTER VARYING(50) NOT NULL,
	ch_use CHARACTER VARYING (20) NOT NULL,
	ch_pass CHARACTER VARYING(32) NOT NULL,
	CONSTRAINT pk_usuario PRIMARY KEY (pk_usu),
	CONSTRAINT usuario_user UNIQUE (ch_use)
);
ALTER TABLE registro.usuario OWNER to chat;

INSERT INTO registro.usuario
	(ch_nomb, ch_ape, ch_use, ch_pass)
		VALUES ('Administrador', 'Master', 'admin', '');

